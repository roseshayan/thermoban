<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<section class="auth-page-section">
    <div class="site-container">
        <div class="auth-card-container">
            
            <div class="auth-card-header">
                <div class="auth-logo-box">
                    <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان" class="auth-logo">
                </div>
                <h1 class="auth-title">تعیین کلمه عبور جدید</h1>
                <p class="auth-subtitle">کد تأیید ۶ رقمی پیامک شده و کلمه عبور جدید خود را وارد نمایید</p>
            </div>

            <?php if (!empty($demoCode)): ?>
                <div class="form-alert-msg alert-info" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    <span><strong>حالت شبیه‌ساز پیامک:</strong> کد پیامکی شما جهت ورود مستقیم: <strong style="letter-spacing: 3px;"><?= e($demoCode) ?></strong></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="form-alert-msg alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="form-alert-msg alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= e($success) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= url('reset-password') ?>" method="POST" class="auth-form js-validate-form" id="resetForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="token" value="<?= e($token ?? '') ?>">

                <div class="form-group">
                    <label for="code">کد تأیید ۶ رقمی پیامک شده <span class="required">*</span></label>
                    <input type="text" id="code" name="code" class="form-control" placeholder="مثال: ۱۲۳۴۵۶" dir="ltr" maxlength="10" required data-msg="کد ۶ رقمی پیامک شده را وارد فرمایید" style="text-align: center; letter-spacing: 4px; font-weight: 800; font-size: 1.1rem;" autofocus>
                </div>

                <div class="form-group">
                    <label for="new_password">کلمه عبور جدید <span class="required">*</span></label>
                    <div class="password-field-wrapper">
                        <input type="password" id="new_password" name="password" class="form-control" placeholder="حداقل ۶ کاراکتر..." required data-msg="کلمه عبور جدید الزامی است">
                        <button type="button" class="btn-toggle-password" aria-label="نمایش کلمه عبور" title="نمایش کلمه عبور" tabindex="-1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">تکرار کلمه عبور جدید <span class="required">*</span></label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="تکرار کلمه عبور جدید..." required data-msg="تکرار کلمه عبور الزامی است">
                        <button type="button" class="btn-toggle-password" aria-label="نمایش کلمه عبور" title="نمایش کلمه عبور" tabindex="-1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-auth-submit w-100">
                    <span>ثبت کلمه عبور جدید و ورود</span>
                    <i class="fa-solid fa-key"></i>
                </button>
            </form>

            <div class="auth-card-footer">
                <a href="<?= url('forgot-password') ?>" class="text-navy">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>درخواست مجدد کد تأیید</span>
                </a>
            </div>

        </div>
    </div>
</section>
