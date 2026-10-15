<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<section class="auth-page-section">
    <div class="site-container">
        <div class="auth-card-container">
            
            <div class="auth-card-header">
                <div class="auth-logo-box">
                    <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان" class="auth-logo">
                </div>
                <h1 class="auth-title">بازیابی کلمه عبور</h1>
                <p class="auth-subtitle">شماره تلفن همراه خود را وارد کنید تا کد تأیید برای شما ارسال شود</p>
            </div>

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

            <form action="<?= url('forgot-password') ?>" method="POST" class="auth-form js-validate-form" id="forgotForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <div class="form-group">
                    <label for="phone">شماره تلفن همراه <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="۰۹۱۲۳۴۵۶۷۸۹" dir="ltr" required data-type="phone" data-msg="شماره تلفن همراه معتبر الزامی است" autofocus>
                </div>

                <?= \App\Services\CaptchaService::renderHtml() ?>

                <button type="submit" class="btn btn-primary btn-auth-submit w-100">
                    <span>ارسال کد بازیابی پیامکی</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            <div class="auth-card-footer" style="display: flex; justify-content: space-between; align-items: center;">
                <a href="<?= url('login') ?>" class="text-navy font-bold">
                    <i class="fa-solid fa-arrow-right"></i>
                    <span>بازگشت به صفحه ورود</span>
                </a>
                <a href="<?= url('register') ?>" class="text-orange font-bold">ثبت‌نام در ترموبان</a>
            </div>

        </div>
    </div>
</section>
