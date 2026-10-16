<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<section class="auth-page-section">
    <div class="site-container">
        <div class="auth-card-container">
            
            <div class="auth-card-header">
                <div class="auth-logo-box">
                    <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان" class="auth-logo">
                </div>
                <h1 class="auth-title">ورود به حساب کاربری</h1>
                <p class="auth-subtitle">برای دسترسی به سوابق سفارش، فاکتورها، آدرس‌ها و تیکت‌های پشتیبانی وارد شوید</p>
            </div>

            <?php if (\App\Core\Auth::check()): ?>
                <div class="form-alert-msg" style="background: #fff8e1; color: #b78103; border: 1px solid #ffe082; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 0.88rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>شما هم‌اکنون با حساب <strong><?= e(\App\Core\Auth::user()['name']) ?></strong> وارد شده‌اید. برای دسترسی به پنل مدیریت با مشخصات مدیر وارد شوید، یا <a href="<?= url('logout') ?>" style="text-decoration: underline; font-weight: bold; color: #b78103;">از حساب جاری خارج شوید</a>.</span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="form-alert-msg alert-error">
                    <?= icon('alert', '', 18) ?>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="form-alert-msg alert-success">
                    <?= icon('check', '', 18) ?>
                    <span><?= e($success) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= url('login') ?>" method="POST" class="auth-form js-validate-form" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <div class="form-group">
                    <label for="identifier">شماره تلفن همراه یا ایمیل <span class="required">*</span></label>
                    <input type="text" id="identifier" name="identifier" class="form-control" placeholder="۰۹۱۲۳۴۵۶۷۸۹ یا ایمیل..." dir="ltr" required data-msg="شماره موبایل یا ایمیل خود را وارد نمایید" autofocus>
                </div>

                <div class="form-group">
                    <div class="label-flex">
                        <label for="password">کلمه عبور <span class="required">*</span></label>
                        <a href="<?= url('forgot-password') ?>" class="forgot-link">فراموشی رمز عبور؟</a>
                    </div>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="کلمه عبور خود را وارد کنید..." required data-msg="کلمه عبور الزامی است">
                        <button type="button" class="btn-toggle-password" aria-label="نمایش کلمه عبور" title="نمایش کلمه عبور" tabindex="-1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <?= \App\Services\CaptchaService::renderHtml() ?>

                <button type="submit" class="btn btn-primary btn-auth-submit w-100">
                    <span>ورود به سامانه</span>
                    <?= icon('arrow-left', '', 18) ?>
                </button>
            </form>

            <div class="auth-card-footer">
                <span>هنوز حساب کاربری ندارید؟</span>
                <a href="<?= url('register') ?>" class="text-orange font-bold">ثبت‌نام سریع در ترموبان</a>
            </div>

        </div>
    </div>
</section>
