<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/app/Config/config.php';
require_once ROOT_PATH . '/app/Core/Database.php';

use App\Core\Database;

try {
    $pdo = Database::getInstance();

    // 1. Create service_tariffs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `service_tariffs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `service_key` VARCHAR(50) UNIQUE NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `subtitle` VARCHAR(255) NULL,
        `price` VARCHAR(150) NOT NULL,
        `guarantee` VARCHAR(150) NOT NULL,
        `features` TEXT NOT NULL,
        `sort_order` INT DEFAULT 0,
        `is_active` TINYINT(1) DEFAULT 1,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Seed default tariffs if empty
    $tariffCount = (int) $pdo->query("SELECT COUNT(*) FROM `service_tariffs`")->fetchColumn();
    if ($tariffCount === 0) {
        $stmt = $pdo->prepare("INSERT INTO `service_tariffs` (`service_key`, `title`, `subtitle`, `price`, `guarantee`, `features`, `sort_order`, `is_active`) VALUES (:key, :title, :sub, :price, :guar, :features, :sort, 1)");
        
        $defaultTariffs = [
            [
                'key' => 'repair',
                'title' => 'تعمیر تخصصی پکیج شوفاژ دیواری',
                'sub' => 'عیب‌یابی پیشرفته و رفع ارورها در محل در کمتر از ۱ ساعت',
                'price' => 'شروع اجرت از ۳۵۰,۰۰۰ تومان (طبق نرخنامه اتحادیه)',
                'guar' => '۶ ماه ضمانت کتبی شرکتی',
                'features' => json_encode([
                    'تعمیر یا تعویض پمپ سیرکولاتور گراندفوس و ویلو',
                    'رفع نشتی آب و افت فشار بار دستگاه',
                    'تعمیر تخصصی برد الکترونیک با تجهیزات روز',
                    'رفع عیب انفجاری روشن شدن و خاموش شدن مشعل',
                    'تنظیم شعله ماژولیشن و تست شیر گاز'
                ], JSON_UNESCAPED_UNICODE),
                'sort' => 1
            ],
            [
                'key' => 'maintenance',
                'title' => 'سرویس دوره‌ای و اسیدشویی مبدل‌ها',
                'sub' => 'رسوب‌زدایی کامل با پمپ مخصوص اسید و مواد دیسکلر بدون آسیب به مس',
                'price' => 'سرویس کامل دوره ای از ۴۵۰,۰۰۰ تومان',
                'guar' => 'تضمین داغی آب و گرمایش یکنواخت',
                'features' => json_encode([
                    'اسیدشویی مبدل اصلی و مبدل ثانویه صفحه‌ای',
                    'تخلیه لجن و رسوبات رادیاتورها با دستگاه شستشو',
                    'شارژ باد منبع انبساط با گیج دیجیتال دقیق',
                    'تنظیم الکترودهای جرقه و سنسور یون',
                    'نظافت کامل محفظه احتراق و شیپوره فن'
                ], JSON_UNESCAPED_UNICODE),
                'sort' => 2
            ],
            [
                'key' => 'install',
                'title' => 'نصب اصولی و استاندارد پکیج دیواری',
                'sub' => 'دارای کد رسمی نصب ایران رادیاتور و فعال‌سازی فوری گارانتی',
                'price' => 'نصب دستگاه‌های خریداری شده رایگان',
                'guar' => 'فعال سازی گارانتی ۲۴ ماهه رسمی',
                'features' => json_encode([
                    'نصب شابلون و اتصالات استاندارد ۵ تایی',
                    'نصب دودکش استاندارد دوجداره با شیب مجاز',
                    'نصب رسوب‌گیر مغناطیسی و فیلتر پلی‌فسفات',
                    'هواگیری و تست فشار اولیه مدار گرمایش',
                    'مهر کردن دفترچه گارانتی رسمی شرکت'
                ], JSON_UNESCAPED_UNICODE),
                'sort' => 3
            ],
            [
                'key' => 'setup',
                'title' => 'راه‌اندازی فصلی و تنظیمات هوشمند',
                'sub' => 'تبدیل حالت تابستانه به زمستانه و بالعکس همراه با بهینه‌سازی مصرف',
                'price' => 'از ۲۵۰,۰۰۰ تومان',
                'guar' => 'تست کامل عملکرد در حضور مشتری',
                'features' => json_encode([
                    'تنظیم درجه حرارت رادیاتورها و آب مصرفی',
                    'هواگیری کلیه رادیاتورهای واحد مسکونی',
                    'تنظیم سنسورهای اتاقی جهت کاهش قبض گاز',
                    'تست نهایی عملکرد بدون ایجاد سر و صدا'
                ], JSON_UNESCAPED_UNICODE),
                'sort' => 4
            ],
            [
                'key' => 'sale',
                'title' => 'فروش بی‌واسطه پکیج و رادیاتور',
                'sub' => 'عرضه مستقیم به قیمت کارخانه با ارسال فوری به سراسر تهران',
                'price' => 'قیمت مصوب کارخانه با تخفیف نقدی',
                'guar' => 'گارانتی رسمی و ضمانت اصالت فابریک',
                'features' => json_encode([
                    'ارائه پیش‌فاکتور رسمی برای پروژه‌ها',
                    'مشاوره رایگان تلفنی جهت انتخاب بهترین ظرفیت',
                    'ارسال اکسپرس و تحویل درب منزل در تهران',
                    'نصب رایگان در صورت خرید دستگاه پکیج کامل'
                ], JSON_UNESCAPED_UNICODE),
                'sort' => 5
            ]
        ];

        foreach ($defaultTariffs as $dt) {
            $stmt->execute($dt);
        }
    }

    // 2. Create service_requests table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `service_requests` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `tracking_code` VARCHAR(50) UNIQUE NOT NULL,
        `user_id` INT NULL,
        `customer_name` VARCHAR(150) NOT NULL,
        `customer_phone` VARCHAR(20) NOT NULL,
        `service_type` VARCHAR(100) NOT NULL,
        `brand` VARCHAR(100) NOT NULL,
        `model` VARCHAR(100) NULL,
        `district` VARCHAR(50) NOT NULL,
        `address` TEXT NOT NULL,
        `preferred_date` VARCHAR(50) NOT NULL,
        `time_slot` VARCHAR(50) NOT NULL,
        `urgency` ENUM('urgent', 'normal', 'scheduled') DEFAULT 'normal',
        `problem_type` VARCHAR(150) NULL,
        `description` TEXT NULL,
        `photos` JSON NULL,
        `video_path` VARCHAR(255) NULL,
        `technician_id` INT NULL,
        `technician_name` VARCHAR(150) NULL,
        `status` ENUM('pending', 'assigned', 'in_progress', 'completed', 'canceled') DEFAULT 'pending',
        `estimated_cost` INT NULL,
        `final_cost` INT NULL,
        `admin_notes` TEXT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
        FOREIGN KEY (`technician_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 3. Ensure Chat Settings exist (Default Disabled)
    $chatSettings = [
        'module_chat' => '0',
        'chat_provider' => 'goftino',
        'chat_goftino_key' => '',
        'chat_raychat_key' => ''
    ];
    $stmtSet = $pdo->prepare("INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES (:k, :v)");
    foreach ($chatSettings as $k => $v) {
        $stmtSet->execute(['k' => $k, 'v' => $v]);
    }

    // 4. Ensure upload directories exist
    $dirs = [
        ROOT_PATH . '/public/uploads/service_requests/photos',
        ROOT_PATH . '/public/uploads/service_requests/videos',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    echo "MIGRATION_V4_SUCCESS\n";
} catch (\Throwable $e) {
    echo "MIGRATION_ERROR: " . $e->getMessage() . "\n";
}
