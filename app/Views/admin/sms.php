<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-comment-sms" style="color: #EF5C16; margin-left: 8px;"></i>
                تنظیمات وب‌سرویس پیامکی (سامانه ملی‌پیامک MeliPayamak REST)
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                پیکربندی خطوط اختصاصی، پترن‌های خدماتی بدون بلک‌لیست (کد OTP، صدور فاکتور و رزرو نصاب) و ارسال تست زنده
            </p>
        </div>
        <div style="background: #fef2f2; color: #991b1b; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; border: 1px solid #fecaca;">
            <i class="fa-solid fa-bolt" style="margin-left: 4px;"></i>
            وب‌سرویس رسمی RestService.asmx ملی‌پیامک
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 20px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-circle-check" style="margin-left: 8px;"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="form-alert-msg alert-error" style="margin-bottom: 20px; background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-triangle-exclamation" style="margin-left: 8px;"></i>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    
    <!-- Settings Form -->
    <form action="<?= url('admin/sms') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save_settings">

        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">اطلاعات احراز هویت پنل ملی‌پیامک</h3>
                    <span style="font-size: 0.78rem; color: #64748b;">حساب کاربری وب‌سرویس melipayamak.com</span>
                </div>
                <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #011C39; font-size: 0.88rem;">
                    <input type="checkbox" name="sms_enabled" value="1" <?= ($settings['sms_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                    فعال بودن ارسال پیامک
                </label>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">نام کاربری پنل ملی‌پیامک</label>
                    <input type="text" name="sms_username" class="form-control" dir="ltr" value="<?= e($settings['sms_username'] ?? '') ?>" placeholder="username">
                </div>
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">کلمه عبور / کلید وب‌سرویس</label>
                    <input type="password" name="sms_password" class="form-control" dir="ltr" value="<?= e($settings['sms_password'] ?? '') ?>" placeholder="password / API key">
                </div>
            </div>

            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">شماره خط فرستنده اختصاصی یا اشتراکی</label>
                <input type="text" name="sms_from_number" class="form-control" dir="ltr" value="<?= e($settings['sms_from_number'] ?? '5000...') ?>" placeholder="50004000...">
            </div>
        </div>

        <!-- Patterns Box -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 24px;">
            <h3 style="font-size: 1.15rem; color: #011C39; font-weight: 800; margin: 0 0 16px 0;">
                شناسه الگوهای خدماتی صوتی/متنی (BaseServiceNumber Patterns)
            </h3>
            <p style="font-size: 0.82rem; color: #64748b; margin-top: -8px; margin-bottom: 16px;">
                پیامک‌های پترن با سرعت زیر ۵ ثانیه و حتی به شماره‌های مسدود تبلیغاتی (بلک‌لیست) ارسال می‌گردند.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.82rem; color: #011C39; margin-bottom: 6px; display: block;">کد پترن OTP ورود/بازیابی</label>
                    <input type="text" name="sms_pattern_otp" class="form-control" dir="ltr" value="<?= e($settings['sms_pattern_otp'] ?? '14520') ?>" placeholder="مثال: 14520">
                </div>
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.82rem; color: #011C39; margin-bottom: 6px; display: block;">کد پترن ثبت سفارش</label>
                    <input type="text" name="sms_pattern_order" class="form-control" dir="ltr" value="<?= e($settings['sms_pattern_order'] ?? '14521') ?>" placeholder="مثال: 14521">
                </div>
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.82rem; color: #011C39; margin-bottom: 6px; display: block;">کد پترن پاسخ تیکت</label>
                    <input type="text" name="sms_pattern_ticket" class="form-control" dir="ltr" value="<?= e($settings['sms_pattern_ticket'] ?? '14522') ?>" placeholder="مثال: 14522">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 0.95rem; font-weight: 800; border-radius: 8px;">
            <i class="fa-solid fa-floppy-disk" style="margin-left: 8px;"></i>
            ذخیره تنظیمات پنل پیامک
        </button>
    </form>

    <!-- Test SMS Box -->
    <div>
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <h3 style="font-size: 1.1rem; color: #011C39; font-weight: 800; margin: 0 0 14px 0;">
                <i class="fa-solid fa-paper-plane" style="color: #EF5C16; margin-left: 6px;"></i>
                ارسال پیامک آزمایشی (Live Test)
            </h3>
            <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 16px;">
                جهت تست اتصال به وب‌سرویس و بررسی صحت اعتبار حساب ملی‌پیامک
            </p>

            <form action="<?= url('admin/sms') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="test_sms">

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">شماره موبایل گیرنده تست</label>
                    <input type="tel" name="test_phone" class="form-control" dir="ltr" placeholder="۰۹۱۲۳۴۵۶۷۸۹" required>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">متن پیامک تست</label>
                    <textarea name="test_message" class="form-control" rows="3">تست ارتباط وب‌سرویس پیامکی نمایندگی مجاز ترموبان کد ۷۲۷۳</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="padding: 10px; font-size: 0.9rem; font-weight: 700;">
                    <i class="fa-solid fa-paper-plane" style="margin-left: 6px;"></i>
                    ارسال پیامک تستی
                </button>
            </form>
        </div>
    </div>

</div>
