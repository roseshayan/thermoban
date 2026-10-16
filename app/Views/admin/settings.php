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
