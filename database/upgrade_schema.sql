-- Upgrade schema for Super Admin, Modules, SEO, Gateways, SMS, Password Resets

-- 1. Modify users role and status
ALTER TABLE users 
    MODIFY COLUMN role ENUM('user', 'technician', 'admin', 'super_admin') DEFAULT 'user',
    ADD COLUMN status ENUM('active', 'blocked') DEFAULT 'active' AFTER role;

-- Update existing admin to super_admin
UPDATE users SET role = 'super_admin', name = 'شایان نماینده (سوپر ادمین)' WHERE phone = '09121111111';

-- 2. Create password_resets table
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) NOT NULL,
    token VARCHAR(100) NOT NULL,
    code VARCHAR(10) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(phone),
    INDEX(token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Insert default settings for Modules, Maintenance, SEO, Gateways, SMS
INSERT INTO settings (setting_key, setting_value) VALUES
('module_shop', '1'),
('module_blog', '1'),
('module_tickets', '1'),
('module_gateways', '1'),
('module_sms', '1'),
('module_registration', '1'),
('maintenance_mode', '0'),
('maintenance_title', 'سامانه ترموبان در حال ارتقا و به‌روزرسانی است'),
('maintenance_message', 'نمایندگی رسمی کد  برای بهبود کیفیت خدمات به صورت موقت در دست به‌روزرسانی است. در صورت نیاز به خدمات اضطراری یا تعمیر فوری با پشتیبانی ما تماس بگیرید.'),
('maintenance_phone', '۰۲۱-۷۷۴۲۸۵۹۶'),
('seo_meta_title_template', '%title% | %sitename%'),
('seo_meta_description', 'مرکز تخصصی فروش، نصب و تعمیرات پکیج دیواری ایران رادیاتور و بوتان در ۲۲ منطقه تهران با کد مجاز  و ضمانت طلایی ۲۴ ماهه'),
('seo_meta_keywords', 'تعمیر پکیج, نصب پکیج, ایران رادیاتور, بوتان, نمایندگی پکیج تهران'),
('seo_canonical_url', 'http://thermoban.test'),
('seo_og_image', 'images/hero-technician.jpg'),
('seo_robots_txt', 'User-agent: *\nDisallow: /admin/\nDisallow: /profile/\nDisallow: /cart/\nDisallow: /checkout/\nAllow: /\nSitemap: http://thermoban.test/sitemap.xml'),
('seo_google_analytics', 'G-XXXXXXXXXX'),
('seo_search_console', 'google-site-verification=XXXXXXXXXXXXXXXXXXXX'),
('seo_schema_type', 'HVACBusiness'),
('gateway_default', 'zarinpal'),
('gateway_zarinpal_merchant', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'),
('gateway_zarinpal_sandbox', '1'),
('gateway_zibal_merchant', 'zibal'),
('gateway_zibal_sandbox', '1'),
('gateway_cod_enabled', '1'),
('sms_username', 'thermoban_sms'),
('sms_password', 'sms_secret_password'),
('sms_from_number', '500040001234'),
('sms_pattern_otp', '14520'),
('sms_pattern_order', '14521'),
('sms_pattern_ticket', '14522'),
('sms_enabled', '0'),
('module_chat', '0'),
('chat_provider', 'goftino'),
('chat_goftino_key', ''),
('chat_raychat_key', '')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- 4. Create service_tariffs table
CREATE TABLE IF NOT EXISTS service_tariffs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_key VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NULL,
    price VARCHAR(150) NOT NULL,
    guarantee VARCHAR(150) NOT NULL,
    features TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create service_requests table
CREATE TABLE IF NOT EXISTS service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_code VARCHAR(50) UNIQUE NOT NULL,
    user_id INT NULL,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NULL,
    district VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    preferred_date VARCHAR(50) NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    urgency ENUM('urgent', 'normal', 'scheduled') DEFAULT 'normal',
    problem_type VARCHAR(150) NULL,
    description TEXT NULL,
    photos JSON NULL,
    video_path VARCHAR(255) NULL,
    technician_id INT NULL,
    technician_name VARCHAR(150) NULL,
    status ENUM('pending', 'assigned', 'in_progress', 'completed', 'canceled') DEFAULT 'pending',
    estimated_cost INT NULL,
    final_cost INT NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (technician_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

