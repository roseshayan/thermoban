<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header">
    <div class="admin-header-title">
        <h1><i class="fa-solid fa-id-badge text-orange"></i> ویرایش پروفایل و حساب مدیریت</h1>
        <p>تنظیم مشخصات فردی، اطلاعات تماس، تصویر آواتار و تغییر کلمه عبور مدیر سامانه</p>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="admin-alert admin-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="admin-alert admin-alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<?php
$avatarUrl = !empty($user['avatar']) ? (str_starts_with($user['avatar'], 'http') ? $user['avatar'] : url($user['avatar'])) : asset('images/hero-technician.jpg');
?>

<form method="POST" action="<?= url('admin/profile') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 24px; align-items: start;">
        
        <!-- Right Column: Avatar Card -->
        <div class="admin-card" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px 20px; text-align: center;">
            <div style="position: relative; width: 140px; height: 140px; margin: 0 auto 16px auto;">
                <img id="avatarPreview" src="<?= $avatarUrl ?>" alt="<?= e($user['name']) ?>" style="width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.12);">
                <label for="avatarFileInput" style="position: absolute; bottom: 4px; right: 4px; width: 38px; height: 38px; border-radius: 50%; background: #EF5C16; color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.2);" title="انتخاب تصویر جدید">
                    <i class="fa-solid fa-camera"></i>
                    <input type="file" name="avatar_file" id="avatarFileInput" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
                </label>
            </div>

            <h3 style="margin: 0 0 4px 0; font-size: 1.15rem; color: #011C39; font-weight: 800;"><?= e($user['name']) ?></h3>
            <div style="margin-bottom: 12px;">
                <?php if (($user['role'] ?? '') === 'super_admin'): ?>
                    <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-weight: 800; padding: 4px 12px; border-radius: 20px;">
                        <i class="fa-solid fa-crown" style="margin-left: 4px;"></i>سوپر ادمین (برنامه‌نویس)
                    </span>
                <?php else: ?>
                    <span class="badge badge-primary" style="padding: 4px 12px; border-radius: 20px;">
                        <i class="fa-solid fa-shield-halved" style="margin-left: 4px;"></i>مدیر سامانه
                    </span>
                <?php endif; ?>
            </div>

            <p style="font-size: 0.82rem; color: #64748b; line-height: 1.6; margin: 0;">
                تصویر پروفایل در بالای نوار ناوبری پنل و در پاسخ‌های رسمی تیکت‌ها نمایش داده می‌شود.
            </p>
        </div>

        <!-- Left Column: Information & Password -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Personal Info Card -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px;">
                <h2 style="font-size: 1.1rem; color: #011C39; margin: 0 0 20px 0; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-user-pen text-orange"></i>
                    اطلاعات کاربری و ارتباطی
                </h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="admin-form-group">
                        <label class="admin-label">نام و نام خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="admin-input" value="<?= e($user['name']) ?>" required>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">شماره تلفن همراه (نام کاربری) <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="admin-input" value="<?= e($user['phone']) ?>" required style="direction: ltr; text-align: right;">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">آدرس پست الکترونیکی (ایمیل)</label>
                        <input type="email" name="email" class="admin-input" value="<?= e($user['email'] ?? '') ?>" placeholder="name@domain.ir" style="direction: ltr; text-align: right;">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">کد ملی</label>
                        <input type="text" name="national_id" class="admin-input" value="<?= e($user['national_id'] ?? '') ?>" placeholder="کد ملی ۱۰ رقمی" style="direction: ltr; text-align: right;">
                    </div>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px;">
                <h2 style="font-size: 1.1rem; color: #011C39; margin: 0 0 10px 0; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-key text-orange"></i>
                    تغییر کلمه عبور
                </h2>
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 20px;">
                    در صورتی که مایل به تغییر رمز عبور خود نیستید، فیلدهای زیر را خالی بگذارید.
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div class="admin-form-group">
                        <label class="admin-label">رمز عبور فعلی</label>
                        <input type="password" name="current_password" class="admin-input" placeholder="••••••••" style="direction: ltr;">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">رمز عبور جدید</label>
                        <input type="password" name="new_password" class="admin-input" placeholder="حداقل ۶ کاراکتر" style="direction: ltr;">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">تکرار رمز عبور جدید</label>
                        <input type="password" name="confirm_password" class="admin-input" placeholder="تکرار رمز عبور جدید" style="direction: ltr;">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 32px; font-size: 0.95rem; font-weight: 800;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    ذخیره تغییرات پروفایل
                </button>
            </div>

        </div>

    </div>
</form>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
