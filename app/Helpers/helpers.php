<?php
/**
 * Thermoban Global Helper Functions
 * 
 * High-performance, zero-CDN, fully defensive helpers for templating, routing, and system settings.
 */

defined('ROOT_PATH') or exit('No direct script access allowed');

if (!defined('BASE_URL')) {
    if (!function_exists('get_base_url')) {
        function get_base_url(): string {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $script = $_SERVER['SCRIPT_NAME'] ?? '';
            $dir = trim(str_replace('\\', '/', dirname($script)), '/');
            
            if ($dir === '' || $dir === '.') {
                return $protocol . $host . '/';
            }
            
            if (basename($dir) === 'public') {
                $parent = dirname($dir);
                if ($parent === '' || $parent === '.') {
                    return $protocol . $host . '/' . $dir . '/';
                }
                return $protocol . $host . '/' . $parent . '/public/';
            }
            
            return $protocol . $host . '/' . $dir . '/';
        }
    }
    define('BASE_URL', rtrim(get_base_url(), '/') . '/');
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'uploads/') || str_starts_with($cleanPath, 'public/uploads/')) {
            return upload_url($cleanPath);
        }
        return BASE_URL . $cleanPath;
    }
}

if (!function_exists('upload_url')) {
    function upload_url(?string $path = ''): string {
        if (empty($path)) return '';
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }
        if (strpos(BASE_URL, '/public/') !== false) {
            return BASE_URL . $cleanPath;
        }
        return BASE_URL . 'public/' . $cleanPath;
    }
}

if (!function_exists('avatar_url')) {
    function avatar_url(?string $avatar = ''): string {
        if (empty($avatar)) {
            return asset('images/hero-technician.jpg');
        }
        if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
            return $avatar;
        }
        $clean = ltrim($avatar, '/');
        if (str_starts_with($clean, 'uploads/') || str_starts_with($clean, 'public/uploads/')) {
            return upload_url($clean);
        }
        return asset('images/' . $clean);
    }
}

if (!function_exists('site_logo')) {
    function site_logo(): string {
        try {
            if (class_exists('\App\Models\Setting')) {
                $logo = \App\Models\Setting::get('site_logo');
                if (!empty($logo)) {
                    return upload_url($logo);
                }
            }
        } catch (\Throwable $e) {}
        return asset('images/logo-thermoban.png');
    }
}

if (!function_exists('site_favicon')) {
    function site_favicon(): string {
        try {
            if (class_exists('\App\Models\Setting')) {
                $fav = \App\Models\Setting::get('site_favicon');
                if (!empty($fav)) {
                    return upload_url($fav);
                }
            }
        } catch (\Throwable $e) {}
        return asset('images/logo-icon.png');
    }
}

if (!function_exists('to_shamsi')) {
    function to_shamsi($datetime = null, string $format = 'Y/m/d', bool $persianDigits = true): string {
        if (class_exists('\App\Core\Jalali')) {
            return \App\Core\Jalali::format($format, $datetime, $persianDigits);
        }
        return is_string($datetime) ? $datetime : date($format);
    }
}

if (!function_exists('fa_num')) {
    function fa_num($str): string {
        if (class_exists('\App\Core\Jalali')) {
            return \App\Core\Jalali::toPersianNumbers((string)$str);
        }
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($english, $persian, (string)$str);
    }
}

if (!function_exists('en_num')) {
    function en_num($str): string {
        if (class_exists('\App\Core\Jalali')) {
            return \App\Core\Jalali::toEnglishNumbers((string)$str);
        }
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($arabic, $english, str_replace($persian, $english, (string)$str));
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime): string {
        if (class_exists('\App\Core\Jalali')) {
            return \App\Core\Jalali::timeAgo($datetime);
        }
        return 'به تازگی';
    }
}

if (!function_exists('asset')) {
    function asset(string $path = ''): string {
        $cleanPath = ltrim($path, '/');
        if (strpos(BASE_URL, '/public/') !== false) {
            return BASE_URL . 'assets/' . $cleanPath;
        }
        return BASE_URL . 'public/assets/' . $cleanPath;
    }
}

if (!function_exists('e')) {
    function e(?string $string): string {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('icon')) {
    function icon(string $name, string $class = '', int $size = 0): string {
        static $map = [
            'phone' => 'fa-solid fa-phone',
            'mobile' => 'fa-solid fa-mobile-screen-button',
            'calendar' => 'fa-solid fa-calendar-days',
            'clock' => 'fa-solid fa-clock',
            'eye' => 'fa-solid fa-eye',
            'user' => 'fa-solid fa-user',
            'technician' => 'fa-solid fa-user-gear',
            'search' => 'fa-solid fa-magnifying-glass',
            'shield' => 'fa-solid fa-shield-halved',
            'truck' => 'fa-solid fa-truck-fast',
            'wrench' => 'fa-solid fa-wrench',
            'credit-card' => 'fa-solid fa-credit-card',
            'check' => 'fa-solid fa-check',
            'alert' => 'fa-solid fa-triangle-exclamation',
            'info' => 'fa-solid fa-circle-info',
            'map-pin' => 'fa-solid fa-location-dot',
            'building' => 'fa-solid fa-building',
            'mail' => 'fa-solid fa-envelope',
            'star' => 'fa-solid fa-star',
            'star-empty' => 'fa-regular fa-star',
            'cart' => 'fa-solid fa-cart-shopping',
            'heart' => 'fa-solid fa-heart',
            'ticket' => 'fa-solid fa-ticket',
            'invoice' => 'fa-solid fa-file-invoice-dollar',
            'edit' => 'fa-solid fa-pen-to-square',
            'trash' => 'fa-solid fa-trash-can',
            'flame' => 'fa-solid fa-fire-flame-curved',
            'ruler' => 'fa-solid fa-ruler-combined',
            'gear' => 'fa-solid fa-gear',
            'plus' => 'fa-solid fa-plus',
            'minus' => 'fa-solid fa-minus',
            'arrow-left' => 'fa-solid fa-arrow-left',
            'arrow-right' => 'fa-solid fa-arrow-right',
            'copy' => 'fa-solid fa-copy',
            'map' => 'fa-solid fa-map-location-dot',
            'print' => 'fa-solid fa-print',
            'medal' => 'fa-solid fa-award',
            'filter' => 'fa-solid fa-filter',
            'paper-plane' => 'fa-solid fa-paper-plane',
            'paperclip' => 'fa-solid fa-paperclip',
            'headset' => 'fa-solid fa-headset',
            'comments' => 'fa-solid fa-comments',
            'comment-dots' => 'fa-solid fa-comment-dots',
            'bell' => 'fa-solid fa-bell',
            'circle-check' => 'fa-solid fa-circle-check',
            'circle-xmark' => 'fa-solid fa-circle-xmark',
            'chart' => 'fa-solid fa-chart-line',
            'box' => 'fa-solid fa-box-open',
            'users' => 'fa-solid fa-users',
            'newspaper' => 'fa-solid fa-newspaper',
            'sliders' => 'fa-solid fa-sliders',
            'sign-out' => 'fa-solid fa-right-from-bracket',
            'lock' => 'fa-solid fa-lock',
            'times' => 'fa-solid fa-xmark',
            'close' => 'fa-solid fa-xmark',
            'download' => 'fa-solid fa-download',
            'share' => 'fa-solid fa-share-nodes',
            'bars' => 'fa-solid fa-bars',
            'chevron-left' => 'fa-solid fa-chevron-left',
            'chevron-right' => 'fa-solid fa-chevron-right',
            'chevron-down' => 'fa-solid fa-chevron-down',
            'chevron-up' => 'fa-solid fa-chevron-up',
        ];

        $faClass = $map[$name] ?? 'fa-solid fa-' . $name;
        $style = $size > 0 ? ' style="font-size: ' . $size . 'px;"' : '';
        $extraClass = $class !== '' ? ' ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') : '';

        return '<i class="' . $faClass . $extraClass . '"' . $style . ' aria-hidden="true"></i>';
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        return \App\Core\Security::generateCsrf();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('site_name')) {
    function site_name(): string {
        if (class_exists('\App\Models\Setting')) {
            $val = \App\Models\Setting::get('site_name');
            if ($val !== null && trim($val) !== '') {
                return (string)$val;
            }
        }
        return defined('SITE_NAME') ? (string)SITE_NAME : 'ترموبان';
    }
}

if (!function_exists('site_phone_mobile')) {
    function site_phone_mobile(): string {
        if (class_exists('\App\Models\Setting')) {
            $val = \App\Models\Setting::get('site_phone_1');
            if ($val !== null && trim($val) !== '') {
                return (string)$val;
            }
        }
        return defined('SITE_PHONE_1') ? (string)SITE_PHONE_1 : '';
    }
}

if (!function_exists('site_phone_landline')) {
    function site_phone_landline(): string {
        if (class_exists('\App\Models\Setting')) {
            $val = \App\Models\Setting::get('site_phone_2');
            if ($val !== null && trim($val) !== '') {
                return (string)$val;
            }
        }
        return defined('SITE_PHONE_2') ? (string)SITE_PHONE_2 : '';
    }
}

if (!function_exists('site_emergency_phone')) {
    function site_emergency_phone(): string {
        if (class_exists('\App\Models\Setting')) {
            $p = \App\Models\Setting::get('site_phone_emergency');
            if ($p !== null && trim($p) !== '') {
                return (string)$p;
            }
        }
        $p2 = site_phone_landline();
        if ($p2 !== '') {
            return $p2;
        }
        $p1 = site_phone_mobile();
        return $p1 !== '' ? $p1 : '';
    }
}

if (!function_exists('site_email')) {
    function site_email(): string {
        if (class_exists('\App\Models\Setting')) {
            $val = \App\Models\Setting::get('site_email');
            if ($val !== null && trim($val) !== '') {
                return (string)$val;
            }
        }
        return defined('SITE_EMAIL') ? (string)SITE_EMAIL : '';
    }
}

if (!function_exists('site_address')) {
    function site_address(): string {
        if (class_exists('\App\Models\Setting')) {
            $val = \App\Models\Setting::get('site_address');
            if ($val !== null && trim($val) !== '') {
                return (string)$val;
            }
        }
        return defined('SITE_ADDRESS') ? (string)SITE_ADDRESS : '';
    }
}

if (!function_exists('site_working_hours')) {
    function site_working_hours(): array {
        $weekdays = class_exists('\App\Models\Setting') ? \App\Models\Setting::get('site_working_hours_weekdays') : null;
        $weekend  = class_exists('\App\Models\Setting') ? \App\Models\Setting::get('site_working_hours_weekend') : null;
        return [
            'weekdays' => ($weekdays !== null && trim($weekdays) !== '') ? (string)$weekdays : 'شنبه تا چهارشنبه: ۸:۰۰ الی ۲۰:۰۰',
            'weekend'  => ($weekend !== null && trim($weekend) !== '') ? (string)$weekend : 'پنج‌شنبه‌ها: ۸:۰۰ الی ۱۶:۰۰ | جمعه و ایام تعطیل: اعزام اضطراری شبانه‌روزی'
        ];
    }
}

if (!function_exists('site_socials')) {
    function site_socials(): array {
        return [
            'instagram' => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('social_instagram', '') : ''),
            'telegram'  => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('social_telegram', '') : ''),
            'eitaa'     => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('social_eitaa', '') : ''),
            'rubika'    => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('social_rubika', '') : ''),
            'whatsapp'  => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('social_whatsapp', '') : ''),
            'bale'      => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('bale_support_url', 'https://ble.ir/thermoban_support') : 'https://ble.ir/thermoban_support')
        ];
    }
}

if (!function_exists('site_trust_badges')) {
    function site_trust_badges(): array {
        return [
            'active'        => class_exists('\App\Models\Setting') ? (\App\Models\Setting::get('trust_badges_active', '1') === '1') : true,
            'enamad_code'   => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('enamad_code', '') : ''),
            'samandehi_code'=> (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('samandehi_code', '') : ''),
            'union_license' => (string)(class_exists('\App\Models\Setting') ? \App\Models\Setting::get('union_license_text', 'پروانه کسب رسمی اتحادیه شوفاژکاران تهران - کد جواز مجاز') : 'پروانه کسب رسمی اتحادیه شوفاژکاران تهران - کد جواز مجاز')
        ];
    }
}

if (!function_exists('clean_phone_for_tel')) {
    function clean_phone_for_tel(?string $phone = ''): string {
        if (empty($phone)) return '';
        $en = en_num((string)$phone);
        return preg_replace('/[^0-9+]/', '', $en);
    }
}
