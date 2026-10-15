-- =========================================================
-- Thermoban Database Update v3.0
-- Media Library, Brands Management, Product Gallery, Article SEO
-- =========================================================

-- 1. Update Brands Table
ALTER TABLE `brands` 
    ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) DEFAULT 1,
    ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- 2. Update Products Table with Gallery
ALTER TABLE `products`
    ADD COLUMN IF NOT EXISTS `gallery` JSON NULL AFTER `image`;

-- 3. Update Articles Table with SEO Fields
ALTER TABLE `articles`
    ADD COLUMN IF NOT EXISTS `meta_title` VARCHAR(255) NULL AFTER `reading_time`,
    ADD COLUMN IF NOT EXISTS `meta_description` TEXT NULL AFTER `meta_title`,
    ADD COLUMN IF NOT EXISTS `focus_keyword` VARCHAR(150) NULL AFTER `meta_description`,
    ADD COLUMN IF NOT EXISTS `canonical_url` VARCHAR(255) NULL AFTER `focus_keyword`;

-- 4. Create Media Library Table
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

-- 5. Seed default brands if empty
INSERT IGNORE INTO `brands` (`id`, `name`, `slug`, `logo`, `badge`, `description`, `is_active`) VALUES
(1, 'ایران رادیاتور', 'iran-radiator', 'brand-iranradiator-logo.png', 'کد مجاز ۷۲۷۳', 'بزرگترین تولیدکننده پکیج شوفاژ دیواری و رادیاتور آلومینیومی در خاورمیانه با نشان استاندارد ملی', 1),
(2, 'بوتان', 'butane', 'brand-butane.png', 'نمایندگی رسمی', 'پیشگام صنعت تأسیسات حرارتی در ایران با بیش از ۷۰ سال سابقه درخشان در تولید پکیج‌های دیواری', 1),
(3, 'لورچ', 'lorch', 'brand-lorch.png', NULL, 'تولیدکننده انواع پکیج شوفاژ دیواری کم‌مصرف و سازگار با سیستم‌های گرمایش از کف', 1),
(4, 'ایساتیس', 'isatis', 'brand-isatis.png', NULL, 'محصول مشترک تکنولوژی روز اروپا و قطعات مرغوب ایتالیایی با ضریب ایمنی فوق‌العاده', 1),
(5, 'بوش', 'bosch', 'brand-bosch.png', NULL, 'تکنولوژی آلمان با حداکثر بهره‌وری انرژی، دوام قطعات و کمترین میزان آلایندگی', 1),
(6, 'آریستون', 'ariston', 'brand-ariston.png', NULL, 'برند معتبر ایتالیایی با طراحی منحصربه‌فرد، راندمان بالا و قطعات تمام استیل مقاوم', 1);
