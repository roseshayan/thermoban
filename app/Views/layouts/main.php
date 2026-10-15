<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? SITE_NAME) ?></title>
    <meta name="description" content="ترموبان، مرکز تخصصی نصب، سرویس و تعمیرات انواع پکیج دیواری در ۲۲ منطقه تهران با کد مجاز نصب ایران رادیاتور و ضمانت معتبر قطعات">
    <meta name="keywords" content="تعمیر پکیج, نصب پکیج, فروش پکیج, سرویس دوره‌ای پکیج, ایران رادیاتور, بوتان, لورچ, بوش">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('images/logo-icon.png') ?>">
    
    <!-- Local Fonts (Installed via npm, 100% Offline / No CDN) -->
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

    <!-- Site Header -->
    <?php require VIEW_PATH . '/layouts/header.php'; ?>

    <!-- Main Content -->
    <main class="site-main">
        <?= $content ?>
    </main>

    <!-- Site Footer & CTA -->
    <?php require VIEW_PATH . '/layouts/footer.php'; ?>

    <!-- Floating Bale Messenger Support Button (Bottom Right) -->
    <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener noreferrer" class="bale-float-btn" aria-label="پشتیبانی پیام‌رسان بله">
        <span class="bale-tooltip">پشتیبانی آنلاین در پیام‌رسان بله</span>
        <div class="bale-pulse-ring"></div>
        <div class="bale-icon-box">
            <svg class="bale-svg" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="32" fill="#00A693"/>
                <path d="M44.5 24.2C43.8 20.8 41.2 18.2 37.8 17.5C32.1 16.3 26.2 18.2 22.3 22.4C18.4 26.6 17 32.6 18.7 38C20 42.1 23.4 45.3 27.6 46.4C33.8 48 40.3 45.4 43.8 40C44.7 38.6 43.9 36.7 42.2 36.6C40.8 36.5 39.6 37.4 39 38.6C36.6 42.6 31.7 44.5 27.1 43.3C23.9 42.5 21.3 40 20.3 36.8C18.9 32.5 20.1 27.8 23.2 24.5C26.3 21.2 31 19.8 35.5 20.7C38.2 21.2 40.3 23.2 40.9 25.9C41.7 29.8 39.4 33.6 35.6 34.7C33.3 35.4 30.8 34.6 29.3 32.8C27.9 31 27.8 28.5 29 26.6C29.6 25.7 29.3 24.5 28.4 23.9C27.5 23.3 26.3 23.6 25.7 24.5C23.7 27.5 23.9 31.5 26.1 34.3C28.4 37.2 32.4 38.4 36 37.3C41.6 35.6 45.4 29.9 44.5 24.2Z" fill="white"/>
            </svg>
        </div>
    </a>

    <!-- Interactive Scripts -->
    <script>
        window.THERMOBAN_CONFIG = {
            baseUrl: "<?= url() ?>",
            csrfToken: "<?= e($csrfToken ?? '') ?>"
        };
    </script>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
