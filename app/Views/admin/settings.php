<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="margin-bottom: 20px;">
    <div>
        <h2 class="panel-main-title">تنظیمات اصلی نمایندگی و سامانه</h2>
        <p class="panel-subtitle">مدیریت شماره‌های تماس، ساعات کاری، شبکه‌های اجتماعی، نمادهای اعتماد و آدرس دفتر مرکزی</p>
    </div>
</div>

<div class="dashboard-block" style="background: #fff; max-width: 900px;">
    <form action="<?= url('admin/settings') ?>" method="POST" class="js-validate-form">
        
        <!-- Section: Basic Info -->
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 0 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-gear" style="color: #EF5C16;"></i>
            مشخصات کلی و اطلاعات تماس
        </h3>

        <div class="form-group">
            <label>عنوان اصلی وب‌سایت <span class="required">*</span></label>
            <input type="text" name="settings[site_name]" class="form-control" value="<?= e($settings['site_name'] ?? 'ترموبان | نمایندگی رسمی ایران رادیاتور و بوتان') ?>" required>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>شماره تلفن همراه پشتیبانی <span class="required">*</span></label>
                <input type="tel" name="settings[site_phone_1]" class="form-control" value="<?= e($settings['site_phone_1'] ?? '') ?>" placeholder="مثلاً: ۰۹۱۲۳۴۵۶۷۸۹" dir="ltr" required>
                <small class="text-muted" style="font-size: 0.78rem;">در بخش‌های تماس با شماره همراه و هماهنگی سریع نمایش داده می‌شود.</small>
            </div>
            <div class="form-group">
                <label>شماره تلفن ثابت دفتر مرکزی <span class="text-muted" style="font-weight: normal;">(اختیاری)</span></label>
                <input type="text" name="settings[site_phone_2]" class="form-control" value="<?= e($settings['site_phone_2'] ?? '') ?>" placeholder="مثلاً: ۰۲۱۷۷۴۲۸۵۹۶ (در صورت خالی بودن مخفی می‌شود)" dir="ltr">
                <small class="text-muted" style="font-size: 0.78rem;">اگر خالی باشد، هیچ شماره ثابت تستی در سایت نمایش داده نمی‌شود.</small>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>شماره تماس اضطراری و شبانه‌روزی <span class="text-muted" style="font-weight: normal;">(اختیاری)</span></label>
                <input type="tel" name="settings[site_phone_emergency]" class="form-control" value="<?= e($settings['site_phone_emergency'] ?? '') ?>" placeholder="در صورت خالی بودن از شماره موبایل استفاده می‌شود" dir="ltr">
                <small class="text-muted" style="font-size: 0.78rem;">مخصوص دکمه شناور تماس فوری در گوشه صفحه سایت.</small>
            </div>
            <div class="form-group">
                <label>آدرس ایمیل رسمی سایت</label>
                <input type="email" name="settings[site_email]" class="form-control" value="<?= e($settings['site_email'] ?? '') ?>" placeholder="مثلاً: info@thermoban.ir" dir="ltr">
            </div>
        </div>

        <div class="form-group">
            <label>آدرس پستی دفتر مرکزی و انبار قطعات <span class="required">*</span></label>
            <textarea name="settings[site_address]" rows="2" class="form-control" required><?= e($settings['site_address'] ?? 'تهران، خیابان دماوند، بعد از پل خاقانی، پلاک ۳۴') ?></textarea>
        </div>

        <!-- Section: Working Hours -->
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 30px 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-clock" style="color: #3b82f6;"></i>
            ساعات کاری و پذیرش
        </h3>

        <div class="form-row-2">
            <div class="form-group">
                <label>ساعات کاری روزهای عادی (شنبه تا چهارشنبه)</label>
                <input type="text" name="settings[site_working_hours_weekdays]" class="form-control" value="<?= e($settings['site_working_hours_weekdays'] ?? 'شنبه تا چهارشنبه: ۸:۰۰ الی ۲۰:۰۰') ?>">
            </div>
            <div class="form-group">
                <label>ساعات کاری پنج‌شنبه‌ها و روزهای تعطیل</label>
                <input type="text" name="settings[site_working_hours_weekend]" class="form-control" value="<?= e($settings['site_working_hours_weekend'] ?? 'پنج‌شنبه‌ها: ۸:۰۰ الی ۱۶:۰۰ | جمعه و ایام تعطیل: اعزام اضطراری شبانه‌روزی') ?>">
            </div>
        </div>

        <!-- Section: Social Media -->
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 30px 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-share-nodes" style="color: #10b981;"></i>
            شبکه‌های اجتماعی و پیام‌رسان‌ها
        </h3>
        <p style="font-size: 0.84rem; color: #64748b; margin-top: -8px; margin-bottom: 16px;">
            هر فیلدی که خالی بماند، آیکون آن به صورت خودکار در هدر و فوتر مخفی خواهد شد.
        </p>

        <div class="form-row-2">
            <div class="form-group">
                <label><i class="fa-brands fa-instagram" style="color: #E1306C; margin-left: 4px;"></i> پیج اینستاگرام</label>
                <input type="url" name="settings[social_instagram]" class="form-control" value="<?= e($settings['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/thermoban" dir="ltr">
            </div>
            <div class="form-group">
                <label><i class="fa-brands fa-telegram" style="color: #229ED9; margin-left: 4px;"></i> کانال / پشتیبانی تلگرام</label>
                <input type="url" name="settings[social_telegram]" class="form-control" value="<?= e($settings['social_telegram'] ?? '') ?>" placeholder="https://t.me/thermoban" dir="ltr">
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label><i class="fa-solid fa-comments" style="color: #ff6f00; margin-left: 4px;"></i> کانال ایتا (Eitaa)</label>
                <input type="url" name="settings[social_eitaa]" class="form-control" value="<?= e($settings['social_eitaa'] ?? '') ?>" placeholder="https://eitaa.com/thermoban" dir="ltr">
            </div>
            <div class="form-group">
                <label><i class="fa-solid fa-message" style="color: #7b1fa2; margin-left: 4px;"></i> کانال روبیکا (Rubika)</label>
                <input type="url" name="settings[social_rubika]" class="form-control" value="<?= e($settings['social_rubika'] ?? '') ?>" placeholder="https://rubika.ir/thermoban" dir="ltr">
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label><i class="fa-brands fa-whatsapp" style="color: #25D366; margin-left: 4px;"></i> شماره یا لینک واتساپ</label>
                <input type="url" name="settings[social_whatsapp]" class="form-control" value="<?= e($settings['social_whatsapp'] ?? '') ?>" placeholder="https://wa.me/989123456789" dir="ltr">
            </div>
            <div class="form-group">
                <label><i class="fa-solid fa-shield-cat" style="color: #00A693; margin-left: 4px;"></i> لینک کانال یا پشتیبانی پیام‌رسان بله</label>
                <input type="url" name="settings[bale_support_url]" class="form-control" value="<?= e($settings['bale_support_url'] ?? 'https://ble.ir/thermoban_support') ?>" placeholder="https://ble.ir/..." dir="ltr">
            </div>
        </div>

        <!-- Section: Trust Badges -->
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin: 30px 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-shield-halved" style="color: #f59e0b;"></i>
            نمادهای اعتماد و مجوزهای رسمی
        </h3>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="settings[trust_badges_active]" value="1" <?= ($settings['trust_badges_active'] ?? '1') === '1' ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <span style="font-weight: 700; color: #1e293b;">نمایش نمادهای اعتماد و مجوزها در فوتر سایت</span>
            </label>
        </div>

        <div class="form-group">
            <label>کد یا اسکریپت اینماد (Enamad)</label>
            <textarea name="settings[enamad_code]" rows="2" class="form-control" placeholder="کد html یا اسکریپت ارائه شده توسط اینماد" dir="ltr"><?= e($settings['enamad_code'] ?? '') ?></textarea>
            <small class="text-muted" style="font-size: 0.78rem;">در صورت خالی بودن، نماد پیش‌فرض رسمی با نشان اعتبار نمایش داده خواهد شد.</small>
        </div>

        <div class="form-group">
            <label>کد یا اسکریپت ساماندهی (نشان ملی ثبت رسانه‌های دیجیتال)</label>
            <textarea name="settings[samandehi_code]" rows="2" class="form-control" placeholder="کد html یا شناسه نشان ساماندهی" dir="ltr"><?= e($settings['samandehi_code'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>متن پروانه و مجوز رسمی اتحادیه</label>
            <input type="text" name="settings[union_license_text]" class="form-control" value="<?= e($settings['union_license_text'] ?? 'پروانه کسب رسمی اتحادیه شوفاژکاران تهران - کد جواز مجاز') ?>">
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700;">
                <?= icon('check', '', 18) ?>
                <span>ذخیره تمامی تنظیمات سامانه</span>
            </button>
        </div>
    </form>
</div>

<?php if (\App\Core\Auth::isSuperAdmin()): ?>
<div class="dashboard-block" style="background: #fff; max-width: 900px; margin-top: 25px; border-right: 4px solid #16a34a;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
        <div>
            <h3 style="font-size: 1rem; font-weight:700; color:#1e293b; margin:0 0 5px 0;">
                <i class="fa-solid fa-database" style="color:#16a34a; margin-left:6px;"></i>
                ارتقا و همگام‌سازی ساختار پایگاه داده
            </h3>
            <p style="font-size:0.85rem; color:#64748b; margin:0;">
                در صورت استقرار کدهای جدید (جداول منوها، سوالات متداول، نظرات مشتریان، شعب نمایندگی، تقویم و نرخ‌های مصوب)، با این دکمه ساختار دیتابیس را بدون نیاز به phpMyAdmin فوراً ارتقا دهید.
            </p>
        </div>
        <a href="<?= url('admin/migrate-db') ?>" class="btn btn-outline-success" style="border-radius:10px; font-weight:600; padding:10px 18px; border:1.5px solid #16a34a; color:#16a34a; display:inline-flex; align-items:center; gap:8px;" onclick="return confirm('آیا از اجرای همگام‌سازی ساختار دیتابیس اطمینان دارید؟');">
            <i class="fa-solid fa-rotate"></i>
            <span>همگام‌سازی ساختار دیتابیس</span>
        </a>
    </div>
</div>
<?php endif; ?>
