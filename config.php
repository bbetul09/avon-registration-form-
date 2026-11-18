<?php
// Site genelinde kullanılan iletişim bilgileri — değiştirmek için yalnızca burayı düzenleyin.

if (!defined('CONTACT_EMAIL')) {
    // Formlardan gelen bildirimlerin gönderileceği ve sitede gösterilen e-posta adresi
    define('CONTACT_EMAIL', 'dilekacar462@gmail.com');

    // Telefon: görünen biçim ve tel: linki için uluslararası biçim
    define('CONTACT_PHONE_DISPLAY', '0 (543) 590 09 21');
    define('CONTACT_PHONE_E164', '+905435900921');

    // Bot koruması: form tokenlarını imzalamak için gizli anahtar (paylaşmayın)
    define('BOT_GUARD_SECRET', '35f57c6cfefbab75d08c73d8b17c5c342971d0e79c5c7b17be374930e3b3a812');
}
