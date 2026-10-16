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
        <p class="panel-subtitle">مدیریت شماره‌های تماس، آدرس دفتر مرکزی، کد نمایندگی و لینک پشتیبانی پیام‌رسان بله</p>
    </div>
</div>

<div class="dashboard-block" style="background: #fff; max-width: 800px;">
    <form action="<?= url('admin/settings') ?>" method="POST" class="js-validate-form">
        <div class="form-group">
            <label>عنوان اصلی وب‌سایت <span class="required">*</span></label>
            <input type="text" name="settings[site_name]" class="form-control" value="<?= e($settings['site_name'] ?? 'ترموبان | نمایندگی کد  ایران رادیاتور و بوتان') ?>" required>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>شماره تلفن همراه پشتیبانی ۲۴ ساعته <span class="required">*</span></label>
                <input type="tel" name="settings[site_phone_1]" class="form-control" value="<?= e($settings['site_phone_1'] ?? '۰۹۱۲-۳۴۵-۶۷۸۹') ?>" dir="ltr" required>
            </div>
            <div class="form-group">
                <label>شماره تلفن ثابت دفتر مرکزی <span class="required">*</span></label>
                <input type="text" name="settings[site_phone_2]" class="form-control" value="<?= e($settings['site_phone_2'] ?? '۰۲۱-۷۷۴۲۸۵۹۶') ?>" dir="ltr" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>آدرس ایمیل پشتیبانی</label>
                <input type="email" name="settings[site_email]" class="form-control" value="<?= e($settings['site_email'] ?? 'info@devshayan.ir') ?>" dir="ltr">
            </div>
            <div class="form-group">
                <label>لینک پشتیبانی آنلاین پیام‌رسان بله <span class="required">*</span></label>
                <input type="url" name="settings[bale_support_url]" class="form-control" value="<?= e($settings['bale_support_url'] ?? 'https://ble.ir/thermoban_support') ?>" dir="ltr" required>
            </div>
        </div>

        <div class="form-group">
            <label>آدرس پستی دفتر مرکزی و هماهنگی شعب <span class="required">*</span></label>
            <textarea name="settings[site_address]" rows="2" class="form-control" required><?= e($settings['site_address'] ?? 'تهران، خیابان دماوند، بعد از پل خاقانی، پلاک ۳۴') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= icon('check', '', 18) ?>
            <span>ذخیره تنظیمات سامانه</span>
        </button>
    </form>
</div>

<?php if (\App\Core\Auth::isSuperAdmin()): ?>
<div class="dashboard-block" style="background: #fff; max-width: 800px; margin-top: 25px; border-right: 4px solid #16a34a;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
        <div>
            <h3 style="font-size: 1rem; font-weight:700; color:#1e293b; margin:0 0 5px 0;">
                <i class="fa-solid fa-database" style="color:#16a34a; margin-left:6px;"></i>
                ارتقا و همگام‌سازی ساختار پایگاه داده
            </h3>
            <p style="font-size:0.85rem; color:#64748b; margin:0;">
                در صورت استقرار کدهای جدید (جداول نرخ‌های مصوب، سفارشات خدمات، آدرس‌ها یا تنظیمات جدید)، با این دکمه ساختار دیتابیس را بدون نیاز به phpMyAdmin فوراً ارتقا دهید.
            </p>
        </div>
        <a href="<?= url('admin/migrate-db') ?>" class="btn btn-outline-success" style="border-radius:10px; font-weight:600; padding:10px 18px; border:1.5px solid #16a34a; color:#16a34a; display:inline-flex; align-items:center; gap:8px;" onclick="return confirm('آیا از اجرای همگام‌سازی ساختار دیتابیس اطمینان دارید؟');">
            <i class="fa-solid fa-rotate"></i>
            <span>همگام‌سازی ساختار دیتابیس</span>
        </a>
    </div>
</div>
<?php endif; ?>
