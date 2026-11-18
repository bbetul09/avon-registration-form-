<?php
// Ortak SEO yardımcıları (header.php ve sayfalar tarafından kullanılır)
require_once __DIR__ . '/config.php';

if (!function_exists('site_base_url')) {
    /** Sitenin kök URL'si, sonunda "/" ile. Örn: https://alanadi.com/ */
    function site_base_url() {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $dir    = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
        return $scheme . '://' . $host . $dir . '/';
    }

    /** Göreli yolu tam URL'ye çevirir. */
    function site_url($path = '') {
        return site_base_url() . ltrim($path, '/');
    }

    /** Ana Sayfa + verilen öğelerden BreadcrumbList şeması üretir. $items: [['name' => ..., 'url' => ...], ...] */
    function breadcrumb_schema(array $items, $id = null) {
        $list = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Ana Sayfa', 'item' => site_base_url()]];
        foreach ($items as $i => $item) {
            $list[] = ['@type' => 'ListItem', 'position' => $i + 2, 'name' => $item['name'], 'item' => $item['url']];
        }
        $schema = ['@type' => 'BreadcrumbList', 'itemListElement' => $list];
        if ($id) {
            $schema['@id'] = $id;
        }
        return $schema;
    }

    /** Başlık metninden URL/anchor için güvenli id üretir (Türkçe karakterler dönüştürülür). */
    function slugify_heading($text) {
        $text = mb_strtolower(strtr($text, ['İ' => 'i', 'I' => 'i', 'ı' => 'i', 'Ç' => 'c', 'Ş' => 's', 'Ğ' => 'g', 'Ö' => 'o', 'Ü' => 'u']), 'UTF-8');
        $text = strtr($text, ['ç' => 'c', 'ş' => 's', 'ğ' => 'g', 'ö' => 'o', 'ü' => 'u']);
        return trim(preg_replace('/[^a-z0-9]+/', '-', $text), '-');
    }

    /** Satır içi biçim: **kalın** ve [metin](site-içi-bağlantı). Girdi önceden HTML'e kaçırılmış olmalı. */
    function render_inline_markup($escaped) {
        $escaped = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $escaped);
        // Yalnızca site içi göreli adreslere izin verilir (javascript: / harici adres yok)
        return preg_replace('/\[([^\]]+)\]\(([A-Za-z0-9][A-Za-z0-9\-_.\/?=#]*)\)/u', '<a href="$2">$1</a>', $escaped);
    }

    /**
     * Blog içeriğini güvenli HTML'e çevirir.
     * Desteklenen: "## H2", "### H3", "- madde", "1. madde", **kalın**, [metin](sayfa.php), boş satırla paragraf.
     * Düz metin içerikler (eski yazılar) paragraf olarak görünmeye devam eder.
     * Dönüş: ['html' => string, 'toc' => [['id' => ..., 'text' => ...], ...]]
     */
    function render_blog_content($raw) {
        $e      = function ($v) { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); };
        $lines  = preg_split('/\r\n|\r|\n/', trim($raw));
        $html   = '';
        $toc    = [];
        $usedId = [];
        $para   = [];
        $list   = null; // 'ul' | 'ol'

        $flushPara = function () use (&$para, &$html, $e) {
            if ($para) {
                $html .= '<p>' . render_inline_markup($e(implode("\n", $para))) . "</p>\n";
                $para = [];
            }
        };
        $closeList = function () use (&$list, &$html) {
            if ($list) { $html .= '</' . $list . ">\n"; $list = null; }
        };

        foreach ($lines as $line) {
            $line = rtrim($line);

            if (preg_match('/^(#{2,3})\s+(.+)$/u', $line, $m)) {
                $flushPara(); $closeList();
                $level = strlen($m[1]);
                $id    = slugify_heading($m[2]) ?: 'bolum';
                $base  = $id; $n = 2;
                while (isset($usedId[$id])) { $id = $base . '-' . $n++; }
                $usedId[$id] = true;
                if ($level === 2) { $toc[] = ['id' => $id, 'text' => $m[2]]; }
                $html .= '<h' . $level . ' id="' . $e($id) . '">' . render_inline_markup($e($m[2])) . '</h' . $level . ">\n";
            } elseif (preg_match('/^[-*]\s+(.+)$/u', $line, $m)) {
                $flushPara();
                if ($list !== 'ul') { $closeList(); $html .= "<ul>\n"; $list = 'ul'; }
                $html .= '<li>' . render_inline_markup($e($m[1])) . "</li>\n";
            } elseif (preg_match('/^\d+\.\s+(.+)$/u', $line, $m)) {
                $flushPara();
                if ($list !== 'ol') { $closeList(); $html .= "<ol>\n"; $list = 'ol'; }
                $html .= '<li>' . render_inline_markup($e($m[1])) . "</li>\n";
            } elseif ($line === '') {
                $flushPara(); $closeList();
            } else {
                $closeList();
                $para[] = $line;
            }
        }
        $flushPara(); $closeList();

        return ['html' => $html, 'toc' => $toc];
    }

    /** Tüm sayfalarda ortak WebSite düğümü. */
    function website_schema() {
        return [
            '@type'      => 'WebSite',
            '@id'        => site_base_url() . '#website',
            'url'        => site_base_url(),
            'name'       => 'Avon Temsilci Kayıt Portalı',
            'inLanguage' => 'tr-TR',
        ];
    }
}
