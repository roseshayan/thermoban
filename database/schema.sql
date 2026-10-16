-- =========================================================================
-- Thermoban Database Schema & Seed Data
-- Character Set: utf8mb4 / Collation: utf8mb4_unicode_ci
-- =========================================================================

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) UNIQUE NOT NULL,
    `national_id` VARCHAR(10) NULL,
    `email` VARCHAR(150) NULL,
    `role` ENUM('user', 'technician', 'admin', 'super_admin') DEFAULT 'user',
    `status` ENUM('active', 'blocked') DEFAULT 'active',
    `avatar` VARCHAR(255) DEFAULT 'hero-technician.jpg',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. User Addresses Table
CREATE TABLE IF NOT EXISTS `user_addresses` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Brands Table
CREATE TABLE IF NOT EXISTS `brands` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) UNIQUE NOT NULL,
    `logo` VARCHAR(255) NOT NULL,
    `badge` VARCHAR(100) NULL,
    `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) UNIQUE NOT NULL,
    `type` ENUM('product', 'article') DEFAULT 'product'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Products Table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `brand_id` INT NOT NULL,
    `category_id` INT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `model` VARCHAR(100) NOT NULL,
    `capacity` INT NOT NULL,
    `area` VARCHAR(100) NOT NULL,
    `heat_exchanger` VARCHAR(100) NOT NULL,
    `flue_type` VARCHAR(100) NOT NULL,
    `price` INT NOT NULL,
    `old_price` INT NULL,
    `warranty` VARCHAR(100) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `features` JSON NULL,
    `specs` JSON NULL,
    `rating` DECIMAL(3, 1) DEFAULT 5.0,
    `reviews_count` INT DEFAULT 18,
    `stock` INT DEFAULT 10,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(50) UNIQUE NOT NULL,
    `user_id` INT NOT NULL,
    `address_id` INT NULL,
    `total_amount` INT NOT NULL,
    `discount_amount` INT DEFAULT 0,
    `payable_amount` INT NOT NULL,
    `delivery_slot` VARCHAR(100) NOT NULL,
    `payment_method` ENUM('online', 'cod') DEFAULT 'online',
    `payment_status` ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    `order_status` ENUM('registered', 'processing', 'assigned', 'completed', 'canceled') DEFAULT 'registered',
    `technician_name` VARCHAR(150) NULL,
    `ref_id` VARCHAR(100) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `quantity` INT DEFAULT 1,
    `unit_price` INT NOT NULL,
    `total_price` INT NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Articles Table
CREATE TABLE IF NOT EXISTS `articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `category` VARCHAR(100) DEFAULT 'آموزش و عیب‌یابی',
    `category_slug` VARCHAR(100) DEFAULT 'troubleshooting',
    `excerpt` TEXT NOT NULL,
    `content` LONGTEXT NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `author_name` VARCHAR(100) DEFAULT 'تیم فنی ترموبان',
    `reading_time` VARCHAR(50) DEFAULT '۵ دقیقه',
    `views_count` INT DEFAULT 120,
    `is_published` TINYINT(1) DEFAULT 1,
    `focus_keyword` VARCHAR(150) NULL,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` TEXT NULL,
    `canonical_url` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Support Tickets Table
CREATE TABLE IF NOT EXISTS `tickets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_number` VARCHAR(50) UNIQUE NOT NULL,
    `user_id` INT NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `priority` ENUM('normal', 'high', 'emergency') DEFAULT 'normal',
    `status` ENUM('open', 'answered', 'closed') DEFAULT 'open',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Ticket Messages Thread Table
CREATE TABLE IF NOT EXISTS `ticket_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT NOT NULL,
    `sender_type` ENUM('user', 'admin') DEFAULT 'user',
    `sender_name` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Quick Order Submissions Table
CREATE TABLE IF NOT EXISTS `quick_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `service` VARCHAR(100) NOT NULL,
    `district` VARCHAR(50) NOT NULL,
    `status` ENUM('pending', 'called', 'assigned', 'closed') DEFAULT 'pending',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Contact Form Messages Table
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `district` VARCHAR(50) NOT NULL,
    `service` VARCHAR(100) NOT NULL,
    `message` TEXT NULL,
    `status` ENUM('unread', 'read', 'responded') DEFAULT 'unread',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. System Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Comments & Reviews Table
CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `product_id` INT NULL,
    `article_id` INT NULL,
    `author_name` VARCHAR(150) NOT NULL,
    `author_phone` VARCHAR(20) NULL,
    `rating` TINYINT DEFAULT 5,
    `content` TEXT NOT NULL,
    `reply` TEXT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_comments_status` (`status`),
    INDEX `idx_comments_product` (`product_id`),
    INDEX `idx_comments_article` (`article_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Media Library Table
CREATE TABLE IF NOT EXISTS `media` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_size` INT NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `uploader_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_media_filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================================
-- Seed Data Insertion
-- =========================================================================

-- Admin & Default Demo User (Password: admin123 and 123456)
INSERT INTO `users` (`id`, `name`, `phone`, `national_id`, `email`, `password_hash`, `role`) VALUES
(1, 'مدیریت ارشد ترموبان', '09121111111', '0012345678', 'admin@thermoban.ir', '$2y$12$Nq96eE4gq5uN9p1n8LfZfeSgqT7oM2L9Z7cT0d6kX8kS7f1L8hQ.G', 'super_admin'),
(2, 'شایان نماینده', '09123456789', '0082736451', 'namayandeshayan@gmail.com', '$2y$12$Nq96eE4gq5uN9p1n8LfZfeSgqT7oM2L9Z7cT0d6kX8kS7f1L8hQ.G', 'user')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- User Addresses
INSERT INTO `user_addresses` (`id`, `user_id`, `title`, `district`, `address`, `postal_code`, `phone`, `lat`, `lng`, `is_default`) VALUES
(1, 2, 'منزل مسکونی (سعادت‌آباد)', 'منطقه ۲', 'تهران، سعادت‌آباد، میدان کاج، خیابان سرو غربی، کوچه مروارید، پلاک ۱۲، واحد ۴', '۱۹۹۷۸۵۴۳۲۱', '۰۹۱۲۳۴۵۶۷۸۹', 35.782400, 51.371200, 1),
(2, 2, 'دفتر کار مهندسی (پاسداران)', 'منطقه ۴', 'تهران، پاسداران، خیابان شهید پایدارفرد، نبش بوستان پنجم، ساختمان آریا، طبقه ۳', '۱۹۶۵۴۳۲۱۸۹', '۰۲۱۷۷۴۲۸۵۹۶', 35.761000, 51.462000, 0)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- Brands
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `badge`, `description`) VALUES
(1, 'ایران رادیاتور', 'iran-radiator', 'brand-iranradiator.png', 'نمایندگی رسمی کد ', 'بزرگترین و معتبرترین تولیدکننده پکیج شوفاژ دیواری و رادیاتور آلومینیومی در خاورمیانه'),
(2, 'بوتان', 'butane', 'brand-butane.png', 'نمایندگی مجاز', 'انتخابی مطمئن با بیش از ۶ دهه سابقه درخشان در صنعت تأسیسات حرارتی ایران')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `type`) VALUES
(1, 'پکیج دیواری دو مبدل فن‌دار', 'dual-heat-exchanger', 'product'),
(2, 'پکیج دیواری دیجیتال', 'digital-package', 'product'),
(3, 'آموزش و عیب‌یابی', 'troubleshooting', 'article'),
(4, 'راهنمای خرید و نگهداری', 'buying-guide', 'article')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Products
INSERT INTO `products` (`id`, `brand_id`, `category_id`, `title`, `slug`, `model`, `capacity`, `area`, `heat_exchanger`, `flue_type`, `price`, `old_price`, `warranty`, `image`, `description`, `features`, `specs`) VALUES
(1, 1, 1, 'پکیج دیواری ایران رادیاتور مدل L24FF', 'iran-radiator-l24ff', 'مدل L24FF - دیجیتال دو مبدل فن‌دار', 24000, 'تا ۱۴۰ مترمربع', 'دو مبدل حرارتی مجزا', 'فن‌دار محفظه احتراق بسته', 24800000, 26500000, '۲۴ ماه ضمانت طلایی شرکتی + نصب رایگان', 'package-thumb.png', 'پکیج شوفاژ دیواری ایران رادیاتور مدل L24FF از پرفروش‌ترین و مطمئن‌ترین پکیج‌های گرمایشی بازار است که با نمایشگر دیجیتال، سیستم عیب‌یابی هوشمند، دو مبدل حرارتی مونوتریک و فن تخلیه دود با بازدهی حرارتی بالای ۹۳ درصد عرضه می‌شود.', 
'["نمایشگر دیجیتال پیشرفته با نمایش کدهای خطا و دمای دقیق آب", "مجهز به دو مبدل حرارتی مجزا (مبدل اصلی و صفحه‌ای ثانویه)", "سیستم محافظت در برابر یخ‌زدگی و گریپاژ پمپ سیرکولاتور", "پمپ سیرکولاتور پرقدرت ۳ سرعته اروپایی ساخت Wilo / Grundfos", "سیستم بای‌پس اتوماتیک و هواگیری خودکار"]',
'{"ظرفیت حرارتی خروجی": "۲۴,۰۰۰ کیلوکالری بر ساعت (۲۸ کیلووات)", "حداکثر متراژ تحت پوشش": "تا ۱۴۰ مترمربع", "نوع مبدل حرارتی": "دو مبدل حرارتی (Monothermic)", "نوع محفظه احتراق": "بسته (فن‌دار)", "راندمان حرارتی": "۹۳.۵ درصد", "حداکثر دمای آب مدار گرمایش": "۸۵ درجه سانتیگراد", "حداکثر دمای آب گرم مصرفی": "۶۰ درجه سانتیگراد", "حداقل فشار آب راه‌انداز": "۰.۵ بار", "قطر دودکش": "۶۰/۱۰۰ میلیمتر دوجداره", "وزن خالص": "۳۲ کیلوگرم", "ابعاد (عرض×عمق×ارتفاع)": "۴۴۰ × ۳۲۰ × ۷۴۰ میلیمتر"}'),

(2, 1, 1, 'پکیج شوفاژ دیواری ایران رادیاتور مدل ECO24FF', 'iran-radiator-eco24ff', 'سری اکو ECO - راندمان اقتصادی', 24000, 'تا ۱۲۰ مترمربع', 'دو مبدل هیدرولیکی', 'فن‌دار استاندارد', 22400000, 23900000, '۲۴ ماه گارانتی ایران رادیاتور', 'package-thumb.png', 'مدل اکو ۲۴ هزار ایران رادیاتور گزینه‌ای مقرون‌به‌صرفه، بسیار کم‌صدا و کم‌مصرف برای آپارتمان‌های مسکونی تا ۱۲۰ مترمربع می‌باشد.',
'["راندمان حرارتی بالا با کاهش چشمگیر مصرف گاز", "دو مبدل مجزا مقاوم در برابر رسوب‌گیری", "تجهیزات ایمنی ضد یخ‌زدگی و ضد انسداد دودکش"]',
'{"ظرفیت حرارتی خروجی": "۲۴,۰۰۰ کیلوکالری", "متراژ مناسب": "تا ۱۲۰ متر", "نوع مبدل": "دو مبدل", "نوع دودکش": "فن‌دار"}'),

(3, 2, 2, 'پکیج دیواری بوتان مدل پرلا پرو 24RSI', 'butane-perla-pro-24rsi', 'سری Perla Pro - ایتالیایی دیجیتال', 24000, 'تا ۱۴۰ مترمربع', 'دو مبدل صفحه‌ای استنلس استیل', 'فن‌دار هرمتیک', 26200000, 28000000, '۲۴ ماه ضمانت بوتان + نصب رایگان', 'package-thumb.png', 'پکیج بوتان پرلا پرو ۲۴ از برترین و مدرن‌ترین مدل‌های شرکت بوتان با طراحی ایتالیایی، مشعل اتمسفریک و قطعات باکیفیت اروپایی است.',
'["قطعات معتبر اروپایی با استاندارد CE اروپا", "صفحه نمایشگر دیجیتال هوشمند", "سیستم کنترل نوسان دمای آب مصرفی"]',
'{"ظرفیت حرارتی": "۲۴,۰۰۰ کیلوکالری", "متراژ مناسب": "تا ۱۴۰ متر", "نوع مبدل": "دو مبدل استیل", "دودکش": "فن‌دار"}')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- Articles
INSERT INTO `articles` (`id`, `title`, `slug`, `category`, `category_slug`, `excerpt`, `content`, `image`, `author_name`, `reading_time`, `views_count`, `is_published`, `focus_keyword`, `meta_title`, `meta_description`) VALUES
(1, 'راهنمای جامع رفع خطای E81 پکیج ایران رادیاتور', 'error-e81-iran-radiator-fix', 'کدهای خطای پکیج', 'error-codes', 'بررسی کامل دلایل بروز ارور E81، نحوه ریست کردن دستگاه، تست ترموستات حد و اقدامات ایمنی فوری.', '<p class="lead-text">کد خطای E81 در پکیج‌های دیجیتال ایران رادیاتور یکی از خطاهای شایع است که نشان‌دهنده فعال شدن کلید حرارتی ۱۰۵ درجه به دلیل دمای بیش از حد مجاز می‌باشد.</p>', 'technician-thumb.jpg', 'مهندس رضایی', '۶ دقیقه', 1420, 1, 'ارور E81 ایران رادیاتور', 'رفع ارور E81 پکیج ایران رادیاتور | راهنمای تخصصی', 'آموزش گام به گام عیب‌یابی و رفع ارور E81 در پکیج‌های ایران رادیاتور با راهکارهای فوری.'),
(2, 'علت روشن نشدن پکیج چیست؟ بررسی ۷ دلیل اصلی و راهکارهای فوری', 'package-not-turning-on-causes', 'راهنمای عیب‌یابی', 'troubleshooting', 'چرا پکیج روشن نمی‌شود؟ عیب‌یابی دقیق افت فشار بار، قطعی گاز و برق، خرابی برد الکترونیک و جرقه زن.', '<p class="lead-text">خاموش شدن ناگهانی پکیج دیواری و روشن نشدن آن، یکی از آزاردهنده‌ترین اتفاقاتی است که در فصول سرد رخ می‌دهد.</p>', 'technician-thumb.jpg', 'مهندس کاظمی', '۵ دقیقه', 980, 1, 'علت روشن نشدن پکیج', 'علت روشن نشدن پکیج دیواری | راهنمای عیب‌یابی', 'بررسی فشار بار آب، قطعی گاز، خرابی پرشر سوئیچ هوا، برد الکترونیک و راهکارهای حل سریع.'),
(3, 'بهترین زمان سرویس دوره‌ای پکیج و نکات افزایش طول عمر', 'best-time-for-package-service', 'نگهداری و رسوب‌زدایی', 'maintenance', 'سرویس سالیانه پکیج دیواری چگونه مصرف گاز را تا ۲۵ درصد کاهش می‌دهد و از هزینه‌های سنگین جلوگیری می‌کند؟', '<p class="lead-text">انجام سرویس سالیانه منظم نه تنها ایمنی خانواده شما را تضمین می‌کند، بلکه راندمان حرارتی دستگاه را به حداکثر می‌رساند.</p>', 'technician-thumb.jpg', 'شایان نماینده', '۷ دقیقه', 1150, 1, 'سرویس دوره ای پکیج', 'بهترین زمان سرویس دوره‌ای پکیج دیواری', 'راهنمای کامل زمان‌بندی سرویس سالیانه پکیج و چک‌لیست اقدامات اسیدشویی و تنظیم مشعل.'),
(4, 'راهنمای محاسبه ظرفیت و انتخاب بهترین پکیج دیواری بر اساس متراژ', 'how-to-choose-best-package-capacity', 'راهنمای خرید و ظرفیت', 'buying-guide', 'فرمول دقیق انتخاب پکیج ۲۲، ۲۴، ۲۸ و ۳۶ هزار برای آپارتمان‌ها با متراژهای ۶۰ تا ۳۰۰ متر در تهران.', '<p class="lead-text">انتخاب ظرفیت نادرست موجب سرد ماندن خانه یا مصرف گاز بیش از حد و استهلاک زودهنگام دستگاه خواهد شد.</p>', 'technician-thumb.jpg', 'مهندس رضایی', '۴ دقیقه', 840, 1, 'انتخاب ظرفیت پکیج بر اساس متراژ', 'راهنمای محاسبه ظرفیت پکیج دیواری بر اساس متراژ خانه', 'فرمول دقیق انتخاب ظرفیت پکیج شوفاژ دیواری ۲۴، ۲۸ و ۳۶ هزار برای انواع متراژها.')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- System Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'ترموبان | نمایندگی کد  ایران رادیاتور و بوتان'),
('site_phone_1', '۰۹۱۲-۳۴۵-۶۷۸۹'),
('site_phone_2', '۰۲۱-۷۷۴۲۸۵۹۶'),
('site_address', 'تهران، خیابان دماوند، بعد از پل خاقانی، پلاک ۳۴'),
('site_email', 'info@devshayan.ir'),
('bale_support_url', 'https://ble.ir/thermoban_support'),
('module_shop', '0'),
('module_blog', '1'),
('module_tickets', '1'),
('module_gateways', '0'),
('module_sms', '0'),
('module_chat', '0'),
('chat_provider', 'goftino'),
('chat_goftino_key', ''),
('chat_raychat_key', '')
ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);

-- 14. Service Tariffs Table (Official Approved Rates)
CREATE TABLE IF NOT EXISTS `service_tariffs` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Service Tariffs Seed
INSERT INTO `service_tariffs` (`id`, `service_key`, `title`, `subtitle`, `price`, `guarantee`, `features`, `sort_order`, `is_active`) VALUES
(1, 'repair', 'تعمیر تخصصی پکیج شوفاژ دیواری', 'عیب‌یابی پیشرفته و رفع ارورها در محل در کمتر از ۱ ساعت', 'شروع اجرت از ۳۵۰,۰۰۰ تومان (طبق نرخنامه اتحادیه)', '۶ ماه ضمانت کتبی شرکتی', '["تعمیر یا تعویض پمپ سیرکولاتور گراندفوس و ویلو","رفع نشتی آب و افت فشار بار دستگاه","تعمیر تخصصی برد الکترونیک با تجهیزات روز","رفع عیب انفجاری روشن شدن و خاموش شدن مشعل","تنظیم شعله ماژولیشن و تست شیر گاز"]', 1, 1),
(2, 'maintenance', 'سرویس دوره‌ای و اسیدشویی مبدل‌ها', 'رسوب‌زدایی کامل با پمپ مخصوص اسید و مواد دیسکلر بدون آسیب به مس', 'سرویس کامل دوره ای از ۴۵۰,۰۰۰ تومان', 'تضمین داغی آب و گرمایش یکنواخت', '["اسیدشویی مبدل اصلی و مبدل ثانویه صفحه‌ای","تخلیه لجن و رسوبات رادیاتورها با دستگاه شستشو","شارژ باد منبع انبساط با گیج دیجیتال دقیق","تنظیم الکترودهای جرقه و سنسور یون","نظافت کامل محفظه احتراق و شیپوره فن"]', 2, 1),
(3, 'install', 'نصب اصولی و استاندارد پکیج دیواری', 'دارای کد رسمی نصب ایران رادیاتور و فعال‌سازی فوری گارانتی', 'نصب دستگاه‌های خریداری شده رایگان', 'فعال سازی گارانتی ۲۴ ماهه رسمی', '["نصب شابلون و اتصالات استاندارد ۵ تایی","نصب دودکش استاندارد دوجداره با شیب مجاز","نصب رسوب‌گیر مغناطیسی و فیلتر پلی‌فسفات","هواگیری و تست فشار اولیه مدار گرمایش","مهر کردن دفترچه گارانتی رسمی شرکت"]', 3, 1),
(4, 'setup', 'راه‌اندازی فصلی و تنظیمات هوشمند', 'تبدیل حالت تابستانه به زمستانه و بالعکس همراه با بهینه‌سازی مصرف', 'از ۲۵۰,۰۰۰ تومان', 'تست کامل عملکرد در حضور مشتری', '["تنظیم درجه حرارت رادیاتورها و آب مصرفی","هواگیری کلیه رادیاتورهای واحد مسکونی","تنظیم سنسورهای اتاقی جهت کاهش قبض گاز","تست نهایی عملکرد بدون ایجاد سر و صدا"]', 4, 1),
(5, 'sale', 'فروش بی‌واسطه پکیج و رادیاتور', 'عرضه مستقیم به قیمت کارخانه با ارسال فوری به سراسر تهران', 'قیمت مصوب نمایندگی با تخفیف نقدی', 'گارانتی اصالت کالا و کد نمایندگی رسمی', '["ارائه پیش‌فاکتور رسمی برای پروژه‌ها","مشاوره رایگان تلفنی جهت انتخاب بهترین ظرفیت","ارسال اکسپرس و تحویل درب منزل در تهران","نصب رایگان در صورت خرید دستگاه کامل"]', 5, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 15. Service Requests (Comprehensive Booking System with Media Attachments)
CREATE TABLE IF NOT EXISTS `service_requests` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

