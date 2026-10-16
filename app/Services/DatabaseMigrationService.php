<?php
namespace App\Services;

use App\Core\Database;

class DatabaseMigrationService {
    /**
     * Run all incremental migrations safely (idempotent with IF NOT EXISTS)
     */
    public static function run(): array {
        $pdo = Database::getInstance();
        $executed = [];

        // 1. service_tariffs table
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
        $executed[] = 'service_tariffs table verified/created';

        // Seed default tariffs if empty
        $count = (int) $pdo->query("SELECT COUNT(*) FROM `service_tariffs`")->fetchColumn();
        if ($count === 0) {
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
            $executed[] = 'Default tariffs seeded';
        }

        // 2. service_requests table
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
        $executed[] = 'service_requests table verified/created';

        // 3. password_resets table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `password_resets` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `phone` VARCHAR(20) NOT NULL,
            `token` VARCHAR(100) NOT NULL,
            `code` VARCHAR(10) NOT NULL,
            `expires_at` DATETIME NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(`phone`),
            INDEX(`token`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $executed[] = 'password_resets table verified/created';

        // 4. user_addresses table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `user_addresses` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `title` VARCHAR(100) NOT NULL,
            `district` VARCHAR(50) NOT NULL,
            `address` TEXT NOT NULL,
            `postal_code` VARCHAR(20) NULL,
            `phone` VARCHAR(20) NOT NULL,
            `lat` DECIMAL(10, 6) DEFAULT 35.7219,
            `lng` DECIMAL(10, 6) DEFAULT 51.4056,
            `is_default` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $executed[] = 'user_addresses table verified/created';

        // 5. Ensure users role and status columns
        try {
            $pdo->exec("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('user', 'technician', 'admin', 'super_admin') DEFAULT 'user'");
        } catch (\Throwable $e) {}

        try {
            $pdo->exec("ALTER TABLE `users` ADD COLUMN `status` ENUM('active', 'blocked') DEFAULT 'active' AFTER `role`");
        } catch (\Throwable $e) {}

        // 6. Ensure service_requests lat & lng columns
        try {
            $pdo->exec("ALTER TABLE `service_requests` ADD COLUMN `lat` DECIMAL(10, 6) NULL AFTER `address`");
        } catch (\Throwable $e) {}
        try {
            $pdo->exec("ALTER TABLE `service_requests` ADD COLUMN `lng` DECIMAL(10, 6) NULL AFTER `lat`");
        } catch (\Throwable $e) {}

        // 7. menus table (Simple, Dropdown & Mega Menu)
        $pdo->exec("CREATE TABLE IF NOT EXISTS `menus` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `location` ENUM('header', 'footer') NOT NULL DEFAULT 'header',
            `parent_id` INT NULL,
            `title` VARCHAR(150) NOT NULL,
            `url` VARCHAR(255) NOT NULL,
            `type` ENUM('link', 'dropdown', 'megamenu') DEFAULT 'link',
            `icon` VARCHAR(100) NULL,
            `badge` VARCHAR(50) NULL,
            `sort_order` INT DEFAULT 0,
            `is_active` TINYINT(1) DEFAULT 1,
            `megamenu_data` JSON NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`parent_id`) REFERENCES `menus`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $executed[] = 'menus table verified/created';

        // Seed default menus if empty
        $menuCount = (int) $pdo->query("SELECT COUNT(*) FROM `menus`")->fetchColumn();
        if ($menuCount === 0) {
            $stmtM = $pdo->prepare("INSERT INTO `menus` (`location`, `parent_id`, `title`, `url`, `type`, `icon`, `badge`, `sort_order`, `is_active`, `megamenu_data`) VALUES (:loc, :pid, :title, :url, :type, :icon, :badge, :sort, 1, :mega)");
            
            // Header items
            $stmtM->execute(['loc' => 'header', 'pid' => null, 'title' => 'صفحه اصلی', 'url' => '/', 'type' => 'link', 'icon' => 'fa-solid fa-house', 'badge' => null, 'sort' => 1, 'mega' => null]);
            
            // Megamenu for Services
            $megaData = json_encode([
                'columns' => [
                    [
                        'title' => 'تعمیرات تخصصی پکیج',
                        'links' => [
                            ['title' => 'تعمیر پکیج ایران رادیاتور', 'url' => '/services#repair'],
                            ['title' => 'تعمیر پکیج دیواری بوتان', 'url' => '/services#repair'],
                            ['title' => 'رفع ارورها و عیب‌یابی برد', 'url' => '/services#repair'],
                            ['title' => 'تعمیر و تعویض پمپ سیرکولاتور', 'url' => '/services#repair']
                        ]
                    ],
                    [
                        'title' => 'سرویس و اسیدشویی',
                        'links' => [
                            ['title' => 'رسوب‌زدایی و اسیدشویی مبدل', 'url' => '/services#maintenance'],
                            ['title' => 'شستشوی مدار رادیاتورها', 'url' => '/services#maintenance'],
                            ['title' => 'شارژ باد منبع انبساط', 'url' => '/services#maintenance'],
                            ['title' => 'تنظیم شعله و سنسورها', 'url' => '/services#maintenance']
                        ]
                    ],
                    [
                        'title' => 'نصب و گارانتی رسمی',
                        'links' => [
                            ['title' => 'نصب اصولی پکیج با مهر گارانتی', 'url' => '/services#install'],
                            ['title' => 'راه‌اندازی فصلی تابستانه/زمستانه', 'url' => '/services#setup'],
                            ['title' => 'ثبت اینترنتی سفارش خدمات', 'url' => '/service-order'],
                            ['title' => 'پیگیری سفارش و اعزام تکنسین', 'url' => '/service-order/tracking']
                        ]
                    ]
                ]
            ], JSON_UNESCAPED_UNICODE);
            $stmtM->execute(['loc' => 'header', 'pid' => null, 'title' => 'خدمات ما', 'url' => '/services', 'type' => 'megamenu', 'icon' => 'fa-solid fa-wrench', 'badge' => 'کد رسمی', 'sort' => 2, 'mega' => $megaData]);

            // Shop dropdown
            $stmtM->execute(['loc' => 'header', 'pid' => null, 'title' => 'فروشگاه پکیج', 'url' => '/shop', 'type' => 'dropdown', 'icon' => 'fa-solid fa-fire-flame-curved', 'badge' => 'تخفیف', 'sort' => 3, 'mega' => null]);
            $shopParentId = (int)$pdo->lastInsertId();
            $stmtM->execute(['loc' => 'header', 'pid' => $shopParentId, 'title' => 'پکیج‌های ایران رادیاتور', 'url' => '/shop?brand=iran-radiator', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 1, 'mega' => null]);
            $stmtM->execute(['loc' => 'header', 'pid' => $shopParentId, 'title' => 'پکیج‌های دیواری بوتان', 'url' => '/shop?brand=butane', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 2, 'mega' => null]);
            $stmtM->execute(['loc' => 'header', 'pid' => $shopParentId, 'title' => 'مشاهده تمامی مدل‌ها', 'url' => '/shop', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 3, 'mega' => null]);

            $stmtM->execute(['loc' => 'header', 'pid' => null, 'title' => 'نرخ مصوب', 'url' => '/services', 'type' => 'link', 'icon' => 'fa-solid fa-file-invoice-dollar', 'badge' => 'اتحادیه', 'sort' => 4, 'mega' => null]);
            $stmtM->execute(['loc' => 'header', 'pid' => null, 'title' => 'مقالات آموزشی', 'url' => '/blog', 'type' => 'link', 'icon' => 'fa-solid fa-newspaper', 'badge' => null, 'sort' => 5, 'mega' => null]);
            $stmtM->execute(['loc' => 'header', 'pid' => null, 'title' => 'درباره ما', 'url' => '/about', 'type' => 'link', 'icon' => 'fa-solid fa-shield-halved', 'badge' => null, 'sort' => 6, 'mega' => null]);
            $stmtM->execute(['loc' => 'header', 'pid' => null, 'title' => 'تماس با ما', 'url' => '/contact', 'type' => 'link', 'icon' => 'fa-solid fa-phone', 'badge' => null, 'sort' => 7, 'mega' => null]);

            // Footer items
            $stmtM->execute(['loc' => 'footer', 'pid' => null, 'title' => 'نصب استاندارد پکیج', 'url' => '/services#install', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 1, 'mega' => null]);
            $stmtM->execute(['loc' => 'footer', 'pid' => null, 'title' => 'تعمیرات فوری در محل', 'url' => '/services#repair', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 2, 'mega' => null]);
            $stmtM->execute(['loc' => 'footer', 'pid' => null, 'title' => 'سرویس دوره‌ای و اسیدشویی', 'url' => '/services#maintenance', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 3, 'mega' => null]);
            $stmtM->execute(['loc' => 'footer', 'pid' => null, 'title' => 'نرخ‌های مصوب اتحادیه', 'url' => '/services', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 4, 'mega' => null]);
            $stmtM->execute(['loc' => 'footer', 'pid' => null, 'title' => 'پیگیری آنلاین سفارش', 'url' => '/service-order/tracking', 'type' => 'link', 'icon' => null, 'badge' => null, 'sort' => 5, 'mega' => null]);
            $executed[] = 'Default header & footer menus seeded';
        }

        // 8. faqs table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `faqs` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `section` VARCHAR(50) NOT NULL DEFAULT 'home',
            `target_id` INT NULL,
            `question` TEXT NOT NULL,
            `answer` TEXT NOT NULL,
            `sort_order` INT DEFAULT 0,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $executed[] = 'faqs table verified/created';

        $faqCount = (int) $pdo->query("SELECT COUNT(*) FROM `faqs`")->fetchColumn();
        if ($faqCount === 0) {
            $stmtF = $pdo->prepare("INSERT INTO `faqs` (`section`, `target_id`, `question`, `answer`, `sort_order`, `is_active`) VALUES (:sec, :tid, :q, :a, :sort, 1)");
            $defaultFaqs = [
                ['sec' => 'home', 'tid' => null, 'q' => 'آیا خدمات تعمیر و نصب ترموبان دارای ضمانت کتبی است؟', 'a' => 'بله، تمامی خدمات تعمیرات قطعات و سرویس دوره‌ای پکیج در ترموبان دارای ۶ ماه ضمانت کتبی شرکتی با فاکتور رسمی معتبر بوده و خدمات نصب پکیج‌های آکبند شامل فعال‌سازی گارانتی رسمی ۲۴ ماهه ایران رادیاتور و بوتان می‌باشد.', 'sort' => 1],
                ['sec' => 'home', 'tid' => null, 'q' => 'مدت زمان رسیدن سرویس‌کار و تکنسین به محل در تهران چقدر است؟', 'a' => 'به دلیل استقرار تکنسین‌های مجرب در شعب چهارگانه شمال، شرق، غرب و مرکز تهران، میانگین زمان اعزام و حضور سرویس‌کار در کمتر از ۶۰ دقیقه پس از ثبت سفارش خواهد بود.', 'sort' => 2],
                ['sec' => 'home', 'tid' => null, 'q' => 'آیا قطعات تعویض شده در پکیج اورجینال و شرکتی هستند؟', 'a' => 'بله، ترموبان به عنوان مرکز معتبر با کد رسمی نمایندگی، منحصراً از قطعات یدکی پلمپ و فابریک کارخانه (نظیر پمپ‌های گراندفوس، مبدل‌های مسی ضخیم، بردهای اورجینال و شیرهای گاز سیت ایتالیا) استفاده می‌کند.', 'sort' => 3],
                ['sec' => 'home', 'tid' => null, 'q' => 'هزینه تعمیرات و دستمزد چگونه محاسبه می‌شود؟', 'a' => 'کلیه اجرت‌ها و هزینه‌ها دقیقا بر اساس نرخنامه مصوب اتحادیه صنایع برودتی و تهویه مطبوع محاسبه می‌گردد و پیش از شروع به کار توسط تکنسین به مشتری اعلام و پس از رضایت کامل دریافت می‌شود.', 'sort' => 4],
                ['sec' => 'services', 'tid' => null, 'q' => 'هر چند وقت یک‌بار پکیج دیواری نیاز به اسیدشویی دارد؟', 'a' => 'با توجه به سختی آب در اکثر مناطق تهران، توصیه مهندسی این است که مبدل‌های ثانویه و اصلی هر ۱۲ الی ۱۸ ماه یک‌بار با پمپ مخصوص اسیدشویی و دیسکلر استاندارد رسوب‌زدایی شوند تا راندمان گرمایش افت نکند.', 'sort' => 1],
                ['sec' => 'services', 'tid' => null, 'q' => 'علت افت مکرر فشار بار (عقربه مانومتر) پکیج چیست؟', 'a' => 'افت فشار بار معمولاً ناشی از نشتی در اتصالات لوله‌کشی و رادیاتورها، خرابی شیر پرکن، یا کم‌باد بودن منبع انبساط است که توسط تکنسین ترموبان در کمتر از نیم ساعت عیب‌یابی و شارژ باد می‌شود.', 'sort' => 2]
            ];
            foreach ($defaultFaqs as $df) {
                $stmtF->execute($df);
            }
            $executed[] = 'Default FAQs seeded';
        }

        // 9. testimonials table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `customer_name` VARCHAR(150) NOT NULL,
            `customer_district` VARCHAR(100) NOT NULL,
            `service_type` VARCHAR(150) NOT NULL,
            `rating` TINYINT DEFAULT 5,
            `content` TEXT NOT NULL,
            `avatar` VARCHAR(255) NULL,
            `sort_order` INT DEFAULT 0,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $executed[] = 'testimonials table verified/created';

        $testCount = (int) $pdo->query("SELECT COUNT(*) FROM `testimonials`")->fetchColumn();
        if ($testCount === 0) {
            $stmtT = $pdo->prepare("INSERT INTO `testimonials` (`customer_name`, `customer_district`, `service_type`, `rating`, `content`, `sort_order`, `is_active`) VALUES (:name, :dist, :srv, :rating, :content, :sort, 1)");
            $defaultTestimonials = [
                ['name' => 'مهندس علیرضا سلیمانی', 'dist' => 'سعادت‌آباد (منطقه ۲)', 'srv' => 'تعمیر برد و پمپ پکیج L24FF', 'rating' => 5, 'content' => 'پکیج ما وسط سرمای دی‌ماه ارور داده بود و خاموش شد. با ترموبان تماس گرفتم، تکنسین ۴۵ دقیقه‌ای رسید، قطعه فابریک رو در حضور خودم تعویض کرد و گارانتی کتبی داد. بسیار وقت‌شناس و محترم بودند.', 'sort' => 1],
                ['name' => 'دکتر مریم رضایی', 'dist' => 'پاسداران (منطقه ۴)', 'srv' => 'اسیدشویی مبدل و سرویس دوره‌ای', 'rating' => 5, 'content' => 'آب گرم مصرفی مدام سرد و گرم می‌شد. اسیدشویی رو با پمپ اتوماتیک بدون کوچکترین کثیف‌کاری داخل آشپزخانه انجام دادند و فشار آب داغ عالی شد. دستمزدشون هم کاملا طبق نرخنامه بود.', 'sort' => 2],
                ['name' => 'حاج حسین کاظمی', 'dist' => 'تهرانپارس (منطقه ۴)', 'srv' => 'نصب پکیج بوتان مدل پرلاپرو', 'rating' => 5, 'content' => 'خرید و نصب دستگاه رو از ترموبان انجام دادم. دفترچه گارانتی رو همانجا مهر کردند و دودکش دوجداره رو با رعایت شیب ایمنی نصب کردند. از تمیزی کار و پاسخگویی تلفنی راضی‌ام.', 'sort' => 3],
                ['name' => 'سرکار خانم شریفی', 'dist' => 'یوسف‌آباد (منطقه ۶)', 'srv' => 'رفع نشتی و تنظیم فصلی زمستانه', 'rating' => 5, 'content' => 'رادیاتورها هوا داشتند و گرم نمی‌شدند. تکنسین تمام رادیاتورها رو بالانس کرد و شعله مشعل رو تنظیم کرد، الان گرمایش تمام اتاق‌ها یکنواخت و بدون صدا کار میکنه.', 'sort' => 4]
            ];
            foreach ($defaultTestimonials as $dt) {
                $stmtT->execute($dt);
            }
            $executed[] = 'Default customer testimonials seeded';
        }

        // 10. branches table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `branches` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `district` VARCHAR(100) NOT NULL,
            `address` TEXT NOT NULL,
            `phone_landline` VARCHAR(50) NULL,
            `phone_mobile` VARCHAR(50) NULL,
            `working_hours` VARCHAR(150) NULL,
            `map_lat` DECIMAL(10, 6) DEFAULT 35.7219,
            `map_lng` DECIMAL(10, 6) DEFAULT 51.4056,
            `is_main` TINYINT(1) DEFAULT 0,
            `sort_order` INT DEFAULT 0,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $executed[] = 'branches table verified/created';

        $branchCount = (int) $pdo->query("SELECT COUNT(*) FROM `branches`")->fetchColumn();
        if ($branchCount === 0) {
            $stmtB = $pdo->prepare("INSERT INTO `branches` (`name`, `district`, `address`, `phone_landline`, `phone_mobile`, `working_hours`, `map_lat`, `map_lng`, `is_main`, `sort_order`, `is_active`) VALUES (:name, :dist, :addr, :land, :mob, :hours, :lat, :lng, :main, :sort, 1)");
            $defaultBranches = [
                [
                    'name' => 'دفتر مرکزی و هماهنگی شعب تهران',
                    'dist' => 'منطقه ۱۳ و ۸ (مرکز - شرق)',
                    'addr' => 'تهران، خیابان دماوند، بعد از پل خاقانی، پلاک ۳۴',
                    'land' => '۰۲۱-۷۷۴۲۸۵۹۶',
                    'mob' => '۰۹۱۲-۳۴۵-۶۷۸۹',
                    'hours' => 'همه روزه ۸:۰۰ الی ۲۱:۰۰ (پشتیبانی شبانه‌روزی)',
                    'lat' => 35.7150,
                    'lng' => 51.5020,
                    'main' => 1,
                    'sort' => 1
                ],
                [
                    'name' => 'شعبه شمال و شمال‌غرب تهران',
                    'dist' => 'منطقه ۱ و ۲ (سعادت‌آباد، تجریش، شهرک غرب)',
                    'addr' => 'تهران، سعادت‌آباد، میدان کاج، بلوار سرو غربی، مجتمع مهندسی سرو',
                    'land' => '۰۲۱-۲۲۱۴۵۶۷۸',
                    'mob' => '۰۹۱۲-۱۱۱-۲۲۳۳',
                    'hours' => 'شنبه تا جمعه ۸:۰۰ الی ۲۲:۰۰',
                    'lat' => 35.7824,
                    'lng' => 51.3712,
                    'main' => 0,
                    'sort' => 2
                ],
                [
                    'name' => 'شعبه شرق و شمال‌شرق تهران',
                    'dist' => 'منطقه ۴ و ۸ (تهرانپارس، نارمک، رسالت، پاسداران)',
                    'addr' => 'تهران، فلکه اول تهرانپارس، خیابان امیری طائمه، پلاک ۸۲',
                    'land' => '۰۲۱-۷۷۸۸۹۹۰۰',
                    'mob' => '۰۹۱۲-۴۴۴-۵۵۶۶',
                    'hours' => 'همه روزه ۸:۰۰ الی ۲۰:۰۰',
                    'lat' => 35.7310,
                    'lng' => 51.5280,
                    'main' => 0,
                    'sort' => 3
                ],
                [
                    'name' => 'شعبه غرب تهران و چیتگر',
                    'dist' => 'منطقه ۵ و ۲۲ (پونک، صادقیه، چیتگر، دریاچه)',
                    'addr' => 'تهران، اشرفی اصفهانی، بالاتر از میدان پونک، نبش خیابان نهم',
                    'land' => '۰۲۱-۴۴۲۲۳۳۴۴',
                    'mob' => '۰۹۱۲-۷۷۷-۸۸۹۹',
                    'hours' => 'همه روزه ۸:۰۰ الی ۲۱:۰۰',
                    'lat' => 35.7620,
                    'lng' => 51.3320,
                    'main' => 0,
                    'sort' => 4
                ]
            ];
            foreach ($defaultBranches as $db) {
                $stmtB->execute($db);
            }
            $executed[] = 'Default branches seeded';
        }

        // 11. Ensure all settings exist
        $defaultSettings = [
            'module_shop' => '1',
            'module_blog' => '1',
            'module_tickets' => '1',
            'module_gateways' => '1',
            'module_sms' => '1',
            'module_registration' => '1',
            'module_chat' => '0',
            'chat_provider' => 'goftino',
            'chat_goftino_key' => '',
            'chat_raychat_key' => '',
            'site_phone_emergency' => '۰۹۱۲-۳۴۵-۶۷۸۹',
            'site_working_hours_weekdays' => 'شنبه تا چهارشنبه: ۸:۰۰ الی ۲۰:۰۰',
            'site_working_hours_weekend' => 'پنج‌شنبه‌ها: ۸:۰۰ الی ۱۶:۰۰ | جمعه و ایام تعطیل: اعزام اضطراری شبانه‌روزی',
            'social_instagram' => 'https://instagram.com/thermoban.ir',
            'social_telegram' => 'https://t.me/thermoban',
            'social_eitaa' => '',
            'social_rubika' => '',
            'social_whatsapp' => 'https://wa.me/989123456789',
            'bale_support_url' => 'https://ble.ir/thermoban_support',
            'trust_badges_active' => '1',
            'enamad_code' => '',
            'samandehi_code' => '',
            'union_license_text' => 'پروانه کسب معتبر از اتحادیه تعمیرکاران شوفاژ و تهویه مطبوع تهران (کد نمایندگی رسمی)'
        ];
        $stmtSet = $pdo->prepare("INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES (:k, :v)");
        foreach ($defaultSettings as $k => $v) {
            $stmtSet->execute(['k' => $k, 'v' => $v]);
        }
        $executed[] = 'extended settings keys synchronized';

        // 12. Ensure upload directories exist
        $dirs = [
            ROOT_PATH . '/public/uploads/service_requests/photos',
            ROOT_PATH . '/public/uploads/service_requests/videos',
            ROOT_PATH . '/public/uploads/testimonials',
        ];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }
        $executed[] = 'upload directories verified';

        return $executed;
    }

    /**
     * Ensure a specific table exists; if not, run migrations automatically
     */
    public static function ensureTable(string $tableName): void {
        try {
            $pdo = Database::getInstance();
            $check = $pdo->query("SHOW TABLES LIKE '{$tableName}'")->fetch();
            if (!$check) {
                self::run();
            }
        } catch (\Throwable $e) {
            // Run anyway to fix
            self::run();
        }
    }
}
