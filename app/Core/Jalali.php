<?php
namespace App\Core;

/**
 * Jalali (Solar Hijri / Shamsi) Date Engine for Thermoban
 * 100% Standalone, Zero-dependency, accurate Persian calendar calculation.
 */
class Jalali {
    /**
     * Persian month names
     */
    public const MONTHS = [
        1  => 'فروردین',
        2  => 'اردیبهشت',
        3  => 'خرداد',
        4  => 'تیر',
        5  => 'مرداد',
        6  => 'شهریور',
        7  => 'مهر',
        8  => 'آبان',
        9  => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند'
    ];

    /**
     * Persian weekday names (0 = Saturday to 6 = Friday)
     */
    public const WEEKDAYS = [
        0 => 'شنبه',
        1 => 'یکشنبه',
        2 => 'دوشنبه',
        3 => 'سه‌شنبه',
        4 => 'چهارشنبه',
        5 => 'پنج‌شنبه',
        6 => 'جمعه'
    ];

    /**
     * Convert Gregorian Year, Month, Day (or single timestamp) to Jalali [year, month, day]
     */
    public static function toJalali($gy = null, ?int $gm = null, ?int $gd = null): array {
        if ($gm === null || $gd === null) {
            $ts = ($gy === null) ? time() : (is_numeric($gy) ? (int)$gy : strtotime((string)$gy));
            $dt = new \DateTime('@' . ($ts ?: time()));
            $dt->setTimezone(new \DateTimeZone('Asia/Tehran'));
            $gy = (int)$dt->format('Y');
            $gm = (int)$dt->format('n');
            $gd = (int)$dt->format('j');
        }

        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $jy = ($gy <= 1600) ? 0 : 979;
        $gy -= ($gy <= 1600) ? 621 : 1600;
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = (365 * $gy) + (int)(($gy2 + 3) / 4) - (int)(($gy2 + 99) / 100) + (int)(($gy2 + 399) / 400) - 80 + $gd + $g_d_m[$gm - 1];
        $jy += 33 * (int)($days / 12053);
        $days %= 12053;
        $jy += 4 * (int)($days / 1461);
        $days %= 1461;
        $jy += (int)(($days - 1) / 365);
        if ($days > 0) {
            $days = ($days - 1) % 365;
        }
        $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
        $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
        return [
            0       => $jy,
            1       => $jm,
            2       => $jd,
            'y'     => $jy,
            'm'     => $jm,
            'd'     => $jd,
            'year'  => $jy,
            'month' => $jm,
            'day'   => $jd
        ];
    }

    /**
     * Convert Jalali Year, Month, Day to Gregorian [year, month, day]
     */
    public static function toGregorian(int $jy, int $jm, int $jd): array {
        $gy = ($jy <= 979) ? 621 : 1600;
        $jy -= ($jy <= 979) ? 0 : 979;
        $days = (365 * $jy) + ((int)($jy / 33) * 8) + (int)((($jy % 33) + 3) / 4) + 78 + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
        $gy += 400 * (int)($days / 146097);
        $days %= 146097;
        if ($days > 36524) {
            $gy += 100 * (int)(--$days / 36524);
            $days %= 36524;
            if ($days >= 365) $days++;
        }
        $gy += 4 * (int)($days / 1461);
        $days %= 1461;
        $gy += (int)(($days - 1) / 365);
        if ($days > 0) $days = ($days - 1) % 365;
        $gd = $days + 1;
        $sal_a = [0, 31, ((($gy % 4 === 0 && $gy % 100 !== 0) || ($gy % 400 === 0)) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        for ($gm = 0; $gm < 13 && $gd > $sal_a[$gm]; $gm++) {
            $gd -= $sal_a[$gm];
        }
        return [$gy, $gm, $gd];
    }

    /**
     * Format a timestamp or date string into Shamsi/Jalali string
     */
    public static function format(?string $format = 'Y/m/d', $timestamp = null, bool $toPersianDigits = true): string {
        $format = $format ?: 'Y/m/d';
        
        if ($timestamp === null || $timestamp === '') {
            $ts = time();
        } elseif (is_numeric($timestamp)) {
            $ts = (int)$timestamp;
        } else {
            $ts = strtotime($timestamp);
            if ($ts === false) {
                return (string)$timestamp;
            }
        }

        // Set timezone to Asia/Tehran
        $dt = new \DateTime('@' . $ts);
        $dt->setTimezone(new \DateTimeZone('Asia/Tehran'));

        $gy = (int)$dt->format('Y');
        $gm = (int)$dt->format('n');
        $gd = (int)$dt->format('j');
        $hour = $dt->format('H');
        $minute = $dt->format('i');
        $second = $dt->format('s');
        $gWeekday = (int)$dt->format('w'); // 0 (Sun) to 6 (Sat)

        // Convert to Persian weekday: 0 = Saturday ... 6 = Friday
        $persianWeekdayIdx = ($gWeekday + 1) % 7;
        $persianWeekdayName = self::WEEKDAYS[$persianWeekdayIdx] ?? '';

        [$jy, $jm, $jd] = self::toJalali($gy, $gm, $gd);
        $monthName = self::MONTHS[$jm] ?? '';

        $padJm = str_pad((string)$jm, 2, '0', STR_PAD_LEFT);
        $padJd = str_pad((string)$jd, 2, '0', STR_PAD_LEFT);

        $replacements = [
            'Y' => (string)$jy,
            'y' => substr((string)$jy, -2),
            'm' => $padJm,
            'n' => (string)$jm,
            'd' => $padJd,
            'j' => (string)$jd,
            'F' => $monthName,
            'M' => $monthName,
            'l' => $persianWeekdayName,
            'D' => $persianWeekdayName,
            'H' => $hour,
            'h' => date('h', $ts),
            'i' => $minute,
            's' => $second,
            'A' => ((int)$hour >= 12) ? 'بعد از ظهر' : 'قبل از ظهر',
            'a' => ((int)$hour >= 12) ? 'ب.ظ' : 'ق.ظ',
        ];

        $output = '';
        $len = strlen($format);
        for ($i = 0; $i < $len; $i++) {
            $char = $format[$i];
            if (isset($replacements[$char])) {
                $output .= $replacements[$char];
            } else {
                $output .= $char;
            }
        }

        if ($toPersianDigits) {
            $output = self::toPersianNumbers($output);
        }

        return $output;
    }

    /**
     * Relative human-readable Persian time (e.g. «۵ دقیقه پیش»)
     */
    public static function timeAgo($timestamp): string {
        if ($timestamp === null || $timestamp === '') return '';
        $ts = is_numeric($timestamp) ? (int)$timestamp : strtotime($timestamp);
        if (!$ts) return (string)$timestamp;

        $diff = time() - $ts;
        if ($diff < 60) {
            return 'همین الان';
        } elseif ($diff < 3600) {
            $m = (int)($diff / 60);
            return self::toPersianNumbers($m) . ' دقیقه پیش';
        } elseif ($diff < 86400) {
            $h = (int)($diff / 3600);
            return self::toPersianNumbers($h) . ' ساعت پیش';
        } elseif ($diff < 172800) {
            return 'دیروز';
        } elseif ($diff < 2592000) {
            $d = (int)($diff / 86400);
            return self::toPersianNumbers($d) . ' روز پیش';
        } else {
            return self::format('Y/m/d', $ts);
        }
    }

    /**
     * Convert English digits to Persian digits
     */
    public static function toPersianNumbers(string $str): string {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return str_replace($en, $fa, $str);
    }

    /**
     * Convert Persian digits to English digits
     */
    public static function toEnglishNumbers(string $str): string {
        $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($fa, $en, $str);
    }
}
