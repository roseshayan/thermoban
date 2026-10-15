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
            <img src="<?= asset('images/badge-shield.png') ?>" alt="پشتیبانی آنلاین بله" class="bale-img">
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
