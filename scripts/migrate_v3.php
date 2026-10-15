<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/app/Config/config.php';
require_once ROOT_PATH . '/app/Core/Database.php';

use App\Core\Database;

try {
    $pdo = Database::getInstance();

    // Helper to check if column exists
    $hasColumn = function(string $table, string $column) use ($pdo): bool {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tbl AND COLUMN_NAME = :col");
        $stmt->execute(['db' => DB_NAME, 'tbl' => $table, 'col' => $column]);
        return (int) $stmt->fetchColumn() > 0;
    };

    // 1. Brands Table
    if (!$hasColumn('brands', 'is_active')) {
        $pdo->exec("ALTER TABLE `brands` ADD COLUMN `is_active` TINYINT(1) DEFAULT 1");
    }
    if (!$hasColumn('brands', 'created_at')) {
        $pdo->exec("ALTER TABLE `brands` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }

    // 2. Products Table
    if (!$hasColumn('products', 'gallery')) {
        $pdo->exec("ALTER TABLE `products` ADD COLUMN `gallery` JSON NULL AFTER `image`");
    }

    // 3. Articles Table
    if (!$hasColumn('articles', 'meta_title')) {
        $pdo->exec("ALTER TABLE `articles` ADD COLUMN `meta_title` VARCHAR(255) NULL AFTER `reading_time`");
    }
    if (!$hasColumn('articles', 'meta_description')) {
        $pdo->exec("ALTER TABLE `articles` ADD COLUMN `meta_description` TEXT NULL AFTER `meta_title`");
    }
    if (!$hasColumn('articles', 'focus_keyword')) {
        $pdo->exec("ALTER TABLE `articles` ADD COLUMN `focus_keyword` VARCHAR(150) NULL AFTER `meta_description`");
    }
    if (!$hasColumn('articles', 'canonical_url')) {
        $pdo->exec("ALTER TABLE `articles` ADD COLUMN `canonical_url` VARCHAR(255) NULL AFTER `focus_keyword`");
    }

    // 4. Media Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `media` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `filename` VARCHAR(255) NOT NULL,
        `original_name` VARCHAR(255) NOT NULL,
        `file_path` VARCHAR(255) NOT NULL,
        `file_size` INT NOT NULL,
        `mime_type` VARCHAR(100) NOT NULL,
        `uploader_id` INT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_media_filename` (`filename`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 5. Seed default brands
    $pdo->exec("INSERT IGNORE INTO `brands` (`id`, `name`, `slug`, `logo`, `badge`, `description`, `is_active`) VALUES
    (1, 'ایران رادیاتور', 'iran-radiator', 'brand-iranradiator-logo.png', 'کد مجاز ۷۲۷۳', 'بزرگترین تولیدکننده پکیج شوفاژ دیواری و رادیاتور آلومینیومی در خاورمیانه با نشان استاندارد ملی', 1),
    (2, 'بوتان', 'butane', 'brand-butane.png', 'نمایندگی رسمی', 'پیشگام صنعت تأسیسات حرارتی در ایران با بیش از ۷۰ سال سابقه درخشان در تولید پکیج‌های دیواری', 1),
    (3, 'لورچ', 'lorch', 'brand-lorch.png', NULL, 'تولیدکننده انواع پکیج شوفاژ دیواری کم‌مصرف و سازگار با سیستم‌های گرمایش از کف', 1),
    (4, 'ایساتیس', 'isatis', 'brand-isatis.png', NULL, 'محصول مشترک تکنولوژی روز اروپا و قطعات مرغوب ایتالیایی با ضریب ایمنی فوق‌العاده', 1),
    (5, 'بوش', 'bosch', 'brand-bosch.png', NULL, 'تکنولوژی آلمان با حداکثر بهره‌وری انرژی، دوام قطعات و کمترین میزان آلایندگی', 1),
    (6, 'آریستون', 'ariston', 'brand-ariston.png', NULL, 'برند معتبر ایتالیایی با طراحی منحصربه‌فرد، راندمان بالا و قطعات تمام استیل مقاوم', 1)");

    // 6. Seed default articles if empty
    $artCount = (int) $pdo->query("SELECT COUNT(*) FROM `articles`")->fetchColumn();
    if ($artCount === 0) {
        $pdo->exec("INSERT INTO `articles` (`id`, `title`, `slug`, `excerpt`, `content`, `image`, `reading_time`, `views_count`, `is_published`, `meta_title`, `meta_description`, `focus_keyword`) VALUES
        (1, 'علت روشن نشدن پکیج چیست؟ راهنمای عیب‌یابی تخصصی', 'boiler-wont-turn-on-causes', 'بررسی رایج ترین دلایل روشن نشدن مشعل پکیج دیواری و روش‌های رفع عیب افت فشار و جرقه زن', '<p>روشن نشدن پکیج یکی از متداول‌ترین مشکلاتی است که در فصول سرد سال برای مصرف‌کنندگان رخ می‌دهد...</p>', 'technician-thumb.jpg', '۵ دقیقه', 420, 1, 'علت روشن نشدن پکیج چیست؟ بررسی ۷ دلیل اصلی و راه حل سریع', 'جامع ترین راهنمای عیب یابی روشن نشدن مشعل پکیج دیواری ایران رادیاتور و بوتان همراه با راهکارهای تخصصی تکنسین ترموبان', 'روشن نشدن پکیج'),
        (2, 'زمان مناسب سرویس دوره‌ای پکیج دیواری', 'boiler-regular-maintenance-time', 'هر چند وقت یک بار باید پکیج را اسیدشویی و سرویس دوره‌ای کنیم؟ بررسی نکات استاندارد کارخانه‌ای', '<p>سرویس منظم و به موقع پکیج علاوه بر افزایش طول عمر قطعات، مصرف سوخت گاز را تا ۲۵ درصد کاهش می‌دهد...</p>', 'technician-thumb.jpg', '۴ دقیقه', 315, 1, 'بهترین زمان سرویس دوره‌ای پکیج دیواری چه موقع است؟', 'راهنمای زمان بندی سرویس دوره ای، اسیدشویی مبدل و تنظیم شعله پکیج دیواری در ۲۲ منطقه تهران با کد مجاز ۷۲۷۳', 'سرویس دوره ای پکیج'),
        (3, 'راهنمای تنظیم دمای پکیج در زمستان و تابستان', 'boiler-temperature-adjustment-guide', 'نحوه صحیح تنظیم دمای آبگرم مصرفی و رادیاتورها برای صرفه‌جویی در مصرف گاز و جلوگیری از رسوب', '<p>تنظیم دمای آب گرم بهداشتی و مدار گرمایش رادیاتورها متناسب با وضعیت آب و هوا اهمیت بالایی دارد...</p>', 'technician-thumb.jpg', '۶ دقیقه', 280, 1, 'آموزش تنظیم دمای پکیج شوفاژ در فصول مختلف سال', 'راهنمای کامل تنظیم درجات پکیج ایران رادیاتور و بوتان در حالت زمستانه و تابستانه برای حداکثر راندمان', 'تنظیم دمای پکیج')");
    }

    echo "MIGRATION_V3_SUCCESS\n";
} catch (\Throwable $e) {
    echo "MIGRATION_ERROR: " . $e->getMessage() . "\n";
}
