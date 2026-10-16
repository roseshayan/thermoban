<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<section class="auth-page-section">
    <div class="site-container">
        <div class="auth-card-container">
            
            <div class="auth-card-header">
                <div class="auth-logo-box">
                    <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان" class="auth-logo">
                </div>
                <h1 class="auth-title">ایجاد حساب کاربری</h1>
                <p class="auth-subtitle">عضویت سریع جهت دریافت ۶ ماه گارانتی کتبی خدمات و ثبت سفارش آنلاین</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="form-alert-msg alert-error">
                    <?= icon('alert', '', 18) ?>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= url('register') ?>" method="POST" class="auth-form js-validate-form" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <div class="form-group">
                    <label for="reg_name">نام و نام خانوادگی <span class="required">*</span></label>
                    <input type="text" id="reg_name" name="name" class="form-control" placeholder="مثال: علیرضا حسینی" required data-msg="لطفاً نام کامل خود را بنویسید" autofocus>
                </div>

                <div class="form-group">
                    <label for="reg_phone">شماره تلفن همراه <span class="required">*</span></label>
                    <input type="tel" id="reg_phone" name="phone" class="form-control" placeholder="۰۹۱۲۳۴۵۶۷۸۹" dir="ltr" required data-type="phone" data-msg="شماره موبایل ۱۱ رقمی معتبر وارد نمایید">
                </div>

                <div class="form-group">
                    <label for="reg_password">کلمه عبور اختصاصی <span class="required">*</span></label>
                    <div class="password-field-wrapper">
                        <input type="password" id="reg_password" name="password" class="form-control" placeholder="حداقل ۶ کاراکتر..." required data-msg="کلمه عبور الزامی است">
                        <button type="button" class="btn-toggle-password" aria-label="نمایش کلمه عبور" title="نمایش کلمه عبور" tabindex="-1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="auth-terms-note">
                    با کلیک بر روی ثبت‌نام، کلیه قوانین و شرایط خدمات نمایندگی مجاز ترموبان را می‌پذیرید.
                </div>

                <?= \App\Services\CaptchaService::renderHtml() ?>

                <button type="submit" class="btn btn-primary btn-auth-submit w-100">
                    <span>ایجاد حساب و ورود</span>
                    <?= icon('check', '', 18) ?>
                </button>
            </form>

            <div class="auth-card-footer">
                <span>قبلاً در ترموبان عضو شده‌اید؟</span>
                <a href="<?= url('login') ?>" class="text-orange font-bold">ورود به حساب کاربری</a>
            </div>

        </div>
    </div>
</section>
