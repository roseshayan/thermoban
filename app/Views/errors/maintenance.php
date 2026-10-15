<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'حالت ارتقا و به‌روزرسانی سیستم | ترموبان') ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/logo-icon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        .maint-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #011C39 0%, #082d56 100%);
            color: #FFFFFF;
            padding: 40px 20px;
            text-align: center;
        }
        .maint-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 28px;
            padding: 60px 45px;
            max-width: 680px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        }
        .maint-icon-gear {
            font-size: 4rem;
            color: #EF5C16;
            margin-bottom: 24px;
            display: inline-block;
            animation: spinGear 12s linear infinite;
        }
        @keyframes spinGear {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .maint-title {
            font-size: 1.8rem;
            font-weight: 900;
            margin-bottom: 16px;
            color: #FFFFFF;
        }
        .maint-desc {
            font-size: 1rem;
            color: #cbd5e1;
            line-height: 1.85;
            margin-bottom: 35px;
        }
        .maint-emergency-box {
            background: rgba(239, 92, 22, 0.12);
            border: 1px solid rgba(239, 92, 22, 0.35);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .emergency-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #ffedd5;
            margin-bottom: 8px;
        }
        .emergency-phones {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .emergency-phone-link {
            color: #FFFFFF;
            font-size: 1.25rem;
            font-weight: 900;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            direction: ltr;
        }
        .emergency-phone-link:hover {
            color: #EF5C16;
        }
        .maint-actions {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="maint-page">
        <div class="maint-card">
            <div class="maint-icon-gear">
                <i class="fa-solid fa-gear"></i>
            </div>
            
            <h1 class="maint-title"><?= e($title ?? 'سامانه ترموبان در حال به‌روزرسانی است') ?></h1>
            
            <p class="maint-desc">
                <?= nl2br(e($message ?? 'نمایندگی رسمی کد ۷۲۷۳ برای بهبود کیفیت خدمات و سامانه‌های ثبت سفارش، به صورت موقت در دست به‌روزرسانی زیرساختی می‌باشد. به زودی با امکانات جدیدتر در خدمت شما خواهیم بود.')) ?>
            </p>

            <div class="maint-emergency-box">
                <div class="emergency-title">
                    <i class="fa-solid fa-phone-volume" style="color: #EF5C16;"></i>
                    <span>نیاز فوری به تعمیرات یا اعزام تکنسین پکیج در تهران دارید؟</span>
                </div>
                <div class="emergency-phones">
                    <a href="tel:<?= str_replace('-', '', $phone ?? '02177428596') ?>" class="emergency-phone-link">
                        <i class="fa-solid fa-phone"></i>
                        <span><?= e($phone ?? '۰۲۱-۷۷۴۲۸۵۹۶') ?></span>
                    </a>
                </div>
            </div>

            <div class="maint-actions">
                <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener" class="btn btn-primary" style="padding: 12px 28px; background: #00A693; border-color: #00A693;">
                    <img src="<?= asset('images/badge-shield.png') ?>" alt="بله" style="width: 22px; height: 22px; vertical-align: middle; margin-left: 6px;">
                    <span>ارتباط با پشتیبان در بله</span>
                </a>
                <a href="<?= url('login') ?>" class="btn btn-outline" style="padding: 12px 20px; color: #cbd5e1; border-color: rgba(255,255,255,0.25);">
                    <i class="fa-solid fa-lock"></i>
                    <span>ورود همکاران و تکنسین‌ها</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
