<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه مورد نظر یافت نشد (خطای ۴۰۴) | ترموبان</title>
    <link rel="icon" type="image/png" href="<?= asset('images/logo-icon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        .error-page-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #011C39 0%, #0d3b66 100%);
            color: #FFFFFF;
            padding: 30px 20px;
            text-align: center;
        }
        .error-card-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }
        .error-code-badge {
            font-size: 6rem;
            font-weight: 900;
            line-height: 1;
            color: #EF5C16;
            margin-bottom: 12px;
            font-family: 'Vazirmatn', sans-serif;
            text-shadow: 0 4px 20px rgba(239, 92, 22, 0.4);
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 14px;
        }
        .error-desc {
            font-size: 0.95rem;
            color: #cbd5e1;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="error-page-container">
        <div class="error-card-box">
            <div class="error-code-badge">۴۰۴</div>
            <h1 class="error-title">صفحه مورد نظر شما پیدا نشد!</h1>
            <p class="error-desc">
                آدرس اینترنتی وارد شده تغییر کرده، حذف شده یا موقتاً در دسترس نیست. برای دسترسی به خدمات و محصولات پکیج از دکمه‌های زیر استفاده فرمایید.
            </p>
            <div class="error-actions">
                <a href="<?= url() ?>" class="btn btn-primary" style="padding: 12px 28px;">
                    <i class="fa-solid fa-house"></i>
                    <span>صفحه اصلی ترموبان</span>
                </a>
                <a href="<?= url('services') ?>" class="btn btn-outline" style="padding: 12px 24px; color: #fff; border-color: rgba(255,255,255,0.3);">
                    <i class="fa-solid fa-wrench"></i>
                    <span>خدمات تخصصی پکیج</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
