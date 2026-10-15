-- Thermoban Database v2 Update: Comments, Visitor Analytics, Backup settings, and Relations

-- 1. Comments & Reviews Table with Foreign Key Relations
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
    INDEX(`status`),
    INDEX(`product_id`),
    INDEX(`article_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Visitor Analytics Table
CREATE TABLE IF NOT EXISTS `visitor_logs` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `page_url` VARCHAR(255) NOT NULL,
    `referer` VARCHAR(255) NULL,
    `user_agent` VARCHAR(255) NULL,
    `device_type` ENUM('mobile', 'desktop', 'tablet') DEFAULT 'desktop',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(`ip_address`),
    INDEX(`created_at`),
    INDEX(`device_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Configure Default Settings as requested by User:
-- "در حالت عادی، بخش فروشگاه، درگاه پرداخت، اس ام اس و ملی پیامک، بک آپ گیری در files.ir باید غیرفعال باشه."
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('module_shop', '0'),
('module_gateways', '0'),
('module_sms', '0'),
('module_files_ir', '0'),
('module_analytics', '1'),
('backup_schedule', 'daily'),
('files_ir_api_key', ''),
('files_ir_endpoint', 'https://api.files.ir/v1/upload')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- 4. Seed sample reviews for demo
INSERT INTO `comments` (`id`, `user_id`, `product_id`, `author_name`, `rating`, `content`, `reply`, `status`) VALUES
(1, 2, 1, 'مهندس رضایی', 5, 'پکیج بیتا بوتان برای آپارتمان ۱۱۰ متری نصب کردیم، فشار آب گرم عالیه و نصاب کد ۷۲۷۳ در کمتر از ۲ ساعت با مهر رسمی گارانتی را فعال کردند.', 'درود بر شما، رضایت شما افتخار نمایندگی رسمی ترموبان کد ۷۲۷۳ است.', 'approved'),
(2, 2, 2, 'حمید کریمی', 5, 'کیفیت هیدرولیک برنجی مدل L24FF ایران رادیاتور فوق‌العاده بی‌صداست و مصرف گاز هم کاهش پیدا کرد.', 'سپاس از اعتماد شما به قطعات اورجینال شرکتی ترموبان.', 'approved')
ON DUPLICATE KEY UPDATE `content` = VALUES(`content`);
