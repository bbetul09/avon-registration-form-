<?php
/**
 * Formlar için anahtar gerektirmeyen, görünmez bot koruması.
 *
 * Katmanlar:
 *  1. Honeypot  : İnsanların görmediği bir alan. Doluysa gönderen bottur.
 *  2. Zaman     : İmzalı token ile formun ne zaman açıldığı tutulur; çok hızlı (<3 sn)
 *                 veya çok eski (>2 saat) gönderimler reddedilir. Token sahte üretilemez.
 *  3. JS kontrolü: Gerçek tarayıcıda main.js'nin eklediği alan; doğrudan POST atan botlarda yoktur.
 *  4. Hız sınırı: Aynı IP'den 10 dakikada en fazla 5 gönderim.
 */
require_once __DIR__ . '/config.php';

const BOT_GUARD_MIN_SECONDS = 3;
const BOT_GUARD_MAX_SECONDS = 7200;
const BOT_GUARD_RATE_LIMIT  = 5;
const BOT_GUARD_RATE_WINDOW = 600;
const BOT_GUARD_HONEYPOT    = 'website';

function bot_guard_sign($payload) {
    return hash_hmac('sha256', $payload, BOT_GUARD_SECRET);
}

/** Forma eklenecek gizli alanlar. $formName: 'register' | 'contact' */
function bot_guard_fields($formName) {
    $payload = $formName . '|' . time();
    $token   = $payload . '|' . bot_guard_sign($payload);
    $esc     = function ($v) { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); };

    return
        '<input type="hidden" name="form_token" value="' . $esc($token) . '">' . "\n" .
        '<div class="hp-field" aria-hidden="true">' .
            '<label for="hp-' . $esc($formName) . '">Bu alanı boş bırakın</label>' .
            '<input type="text" id="hp-' . $esc($formName) . '" name="' . BOT_GUARD_HONEYPOT . '" tabindex="-1" autocomplete="off">' .
        '</div>' . "\n";
}

function bot_guard_client_ip() {
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

/** IP başına basit dosya tabanlı hız sınırı. true = izin verildi. */
function bot_guard_rate_ok() {
    $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'avon_form_rl_' . hash('sha256', bot_guard_client_ip() . BOT_GUARD_SECRET);
    $now  = time();
    $fp   = @fopen($file, 'c+');
    if (!$fp) {
        return true; // dosya açılamazsa formu engelleme
    }
    flock($fp, LOCK_EX);
    $hits = json_decode(stream_get_contents($fp), true);
    $hits = is_array($hits) ? array_filter($hits, function ($t) use ($now) { return $t > $now - BOT_GUARD_RATE_WINDOW; }) : [];
    $ok   = count($hits) < BOT_GUARD_RATE_LIMIT;
    if ($ok) {
        $hits[] = $now;
    }
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode(array_values($hits)));
    flock($fp, LOCK_UN);
    fclose($fp);
    return $ok;
}

/**
 * Gönderimi doğrular.
 * Dönüş: ['ok' => true] | ['ok' => false, 'silent' => bool, 'message' => string]
 * silent=true ise bota başarılıymış gibi cevap verilmeli (botu eğitmemek için).
 */
function bot_guard_check($formName) {
    // 1. Honeypot
    if (!empty($_POST[BOT_GUARD_HONEYPOT])) {
        return ['ok' => false, 'silent' => true, 'message' => ''];
    }

    // 3. JS kontrolü
    if (!isset($_POST['js_check']) || $_POST['js_check'] !== 'ok') {
        return ['ok' => false, 'silent' => false, 'message' => 'Güvenlik doğrulaması başarısız. Lütfen JavaScript\'i etkinleştirip sayfayı yenileyin.'];
    }

    // 2. İmzalı zaman tokenı
    $parts = isset($_POST['form_token']) ? explode('|', (string) $_POST['form_token']) : [];
    if (count($parts) !== 3 || $parts[0] !== $formName || !ctype_digit($parts[1])
        || !hash_equals(bot_guard_sign($parts[0] . '|' . $parts[1]), $parts[2])) {
        return ['ok' => false, 'silent' => false, 'message' => 'Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.'];
    }
    $age = time() - (int) $parts[1];
    if ($age < BOT_GUARD_MIN_SECONDS) {
        return ['ok' => false, 'silent' => true, 'message' => ''];
    }
    if ($age > BOT_GUARD_MAX_SECONDS) {
        return ['ok' => false, 'silent' => false, 'message' => 'Formun süresi doldu. Lütfen sayfayı yenileyip tekrar deneyin.'];
    }

    // 4. Hız sınırı
    if (!bot_guard_rate_ok()) {
        return ['ok' => false, 'silent' => false, 'message' => 'Çok fazla deneme yaptınız. Lütfen birkaç dakika sonra tekrar deneyin.'];
    }

    return ['ok' => true];
}
