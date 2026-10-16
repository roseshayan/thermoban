<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/logo-icon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        body.installer-body {
            background: linear-gradient(135deg, #011C39 0%, #082952 50%, #0d3b66 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
            color: #1e293b;
            font-family: 'Vazirmatn', sans-serif;
            margin: 0;
        }
        .installer-card {
            background: #FFFFFF;
            border-radius: 24px;
            width: 820px;
            max-width: 100%;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .installer-header {
            background: #011C39;
            color: #FFFFFF;
            padding: 30px 24px;
            text-align: center;
            border-bottom: 4px solid #EF5C16;
            position: relative;
        }
        .installer-header img {
            height: 48px;
            margin-bottom: 12px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        .installer-header h1 {
            font-size: 1.35rem;
            margin: 0 0 6px 0;
            font-weight: 800;
            letter-spacing: -0.3px;
        }
        .installer-header p {
            margin: 0;
            font-size: 0.86rem;
            color: #cbd5e1;
        }
        .installer-steps {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            overflow-x: auto;
        }
        .installer-step-item {
            flex: 1;
            min-width: 120px;
            padding: 14px 8px;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #94a3b8;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .installer-step-item.active {
            color: #EF5C16;
            border-bottom-color: #EF5C16;
            background: #FFFFFF;
        }
        .installer-step-item.done {
            color: #16a34a;
        }
        .installer-content {
            padding: 35px 32px;
        }
        .step-panel {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        .step-panel.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .section-subhead {
            font-size: 0.95rem;
            font-weight: 800;
            color: #011C39;
            margin: 22px 0 10px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .req-list {
            list-style: none;
            padding: 0;
            margin: 0 0 16px 0;
        }
        .req-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.86rem;
        }
        .req-item.pass {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }
        .req-item.fail {
            border-color: #fecaca;
            background: #fef2f2;
        }
        .req-item.warn {
            border-color: #fed7aa;
            background: #fffbeb;
        }
        .req-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .req-title {
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .req-desc {
            font-size: 0.76rem;
            color: #64748b;
        }
        .req-val {
            font-family: 'Vazirmatn', monospace;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }
        .req-item.pass .req-val {
            background: #dcfce7;
            color: #166534;
        }
        .req-item.fail .req-val {
            background: #fee2e2;
            color: #991b1b;
        }
        .req-item.warn .req-val {
            background: #fef3c7;
            color: #92400e;
        }
        .status-summary-card {
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .status-summary-card.success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }
        .status-summary-card.danger {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }
        .status-summary-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        /* Password Wrapper with Eye Hint */
        .password-field-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .password-field-wrapper .form-control {
            width: 100%;
            padding-left: 42px !important;
        }
        .password-field-wrapper .btn-toggle-password {
            position: absolute;
            left: 10px;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: color 0.15s ease;
            z-index: 2;
        }
        .password-field-wrapper .btn-toggle-password:hover {
            color: #EF5C16;
        }

        /* Dropzone Upload Box */
        .upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .upload-dropzone:hover {
            border-color: #EF5C16;
            background: #fff7ed;
        }
        .upload-dropzone input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .preview-img-box {
            display: inline-block;
            max-width: 180px;
            max-height: 70px;
            margin-top: 10px;
            background: #ffffff;
            padding: 6px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .preview-img-box img {
            max-width: 100%;
            max-height: 58px;
            object-fit: contain;
        }

        /* Strength meter */
        .pwd-strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            margin-top: 6px;
            overflow: hidden;
        }
        .pwd-strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background 0.3s ease;
        }
    </style>
</head>
<body class="installer-body">

    <div class="installer-card">
        
        <!-- Header -->
        <div class="installer-header">
            <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان">
            <h1>سامانه راه‌انداز هوشمند ترموبان (بسته نصب هاست)</h1>
            <p>نمایندگی رسمی کد ۷۲۷۳ ایران رادیاتور و بوتان | آماده‌سازی و تحویل به مشتری</p>
        </div>

        <!-- Progress Steps -->
        <div class="installer-steps">
            <div class="installer-step-item active" id="stepIndicator1">
                <i class="fa-solid fa-server"></i>
                <span>۱. بررسی هاست</span>
            </div>
            <div class="installer-step-item" id="stepIndicator2">
                <i class="fa-solid fa-database"></i>
                <span>۲. پایگاه داده</span>
            </div>
            <div class="installer-step-item" id="stepIndicator3">
                <i class="fa-solid fa-palette"></i>
                <span>۳. هویت و برندینگ</span>
            </div>
            <div class="installer-step-item" id="stepIndicator4">
                <i class="fa-solid fa-user-shield"></i>
                <span>۴. مدیر ارشد</span>
            </div>
            <div class="installer-step-item" id="stepIndicator5">
                <i class="fa-solid fa-circle-check"></i>
                <span>۵. تحویل به مشتری</span>
            </div>
        </div>

        <!-- Body Form -->
        <div class="installer-content">

            <!-- STEP 1: Requirements & Hosting Health Check -->
            <div class="step-panel active" id="panelStep1">
                <?php if ($reqData['allPassed']): ?>
                    <div class="status-summary-card success">
                        <i class="fa-solid fa-circle-check status-summary-icon"></i>
                        <div>
                            <strong style="font-size: 0.98rem; display: block; margin-bottom: 2px;">هاست شما ۱۰۰٪ با سامانه سازگار است!</strong>
                            <span style="font-size: 0.84rem; opacity: 0.9;">تمامی افزونه‌های ضروری PHP، دسترسی‌های نوشتن و معماری سرور تأیید شدند.</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="status-summary-card danger">
                        <i class="fa-solid fa-triangle-exclamation status-summary-icon"></i>
                        <div>
                            <strong style="font-size: 0.98rem; display: block; margin-bottom: 2px;">برخی پیش‌نیازهای هاست تأمین نشده است!</strong>
                            <span style="font-size: 0.84rem; opacity: 0.9;">لطفاً از طریق کنترل‌پنل هاست (cPanel -> Select PHP Version -> Extensions) موارد قرمز را فعال نمایید.</span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- PHP Engine -->
                <div class="section-subhead">
                    <i class="fa-brands fa-php" style="color: #6366f1;"></i>
                    <span>نسخه موتور PHP</span>
                </div>
                <ul class="req-list">
                    <li class="req-item <?= $reqData['php']['status'] ? 'pass' : 'fail' ?>">
                        <div class="req-info">
                            <span class="req-title">
                                <i class="fa-solid <?= $reqData['php']['status'] ? 'fa-check text-green' : 'fa-xmark text-red' ?>"></i>
                                <?= e($reqData['php']['name']) ?>
                            </span>
                            <span class="req-desc"><?= e($reqData['php']['description']) ?> (نیاز: <?= e($reqData['php']['required']) ?>)</span>
                        </div>
                        <span class="req-val"><?= e($reqData['php']['current']) ?></span>
                    </li>
                </ul>

                <!-- Extensions -->
                <div class="section-subhead">
                    <i class="fa-solid fa-puzzle-piece" style="color: #EF5C16;"></i>
                    <span>افزونه‌های ضروری و کتابخانه‌ها</span>
                </div>
                <ul class="req-list">
                    <?php foreach ($reqData['extensions'] as $key => $ext): ?>
                        <li class="req-item <?= $ext['status'] ? 'pass' : 'fail' ?>">
                            <div class="req-info">
                                <span class="req-title">
                                    <i class="fa-solid <?= $ext['status'] ? 'fa-check text-green' : 'fa-xmark text-red' ?>"></i>
                                    <?= e($ext['name']) ?>
                                </span>
                                <span class="req-desc"><?= e($ext['description']) ?></span>
                            </div>
                            <span class="req-val"><?= e($ext['current']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- PHP Configurations & Directives -->
                <div class="section-subhead">
                    <i class="fa-solid fa-sliders" style="color: #0284c7;"></i>
                    <span>پیکربندی و منابع هاست (php.ini)</span>
                </div>
                <ul class="req-list">
                    <?php foreach ($reqData['directives'] as $key => $dir): ?>
                        <li class="req-item <?= $dir['status'] ? 'pass' : 'warn' ?>">
                            <div class="req-info">
                                <span class="req-title">
                                    <i class="fa-solid <?= $dir['status'] ? 'fa-check text-green' : 'fa-circle-exclamation text-orange' ?>"></i>
                                    <?= e($dir['name']) ?>
                                </span>
                                <span class="req-desc"><?= e($dir['description']) ?> (پیشنهادی: <?= e($dir['required']) ?>)</span>
                            </div>
                            <span class="req-val"><?= e($dir['current']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Permissions -->
                <div class="section-subhead">
                    <i class="fa-solid fa-folder-open" style="color: #eab308;"></i>
                    <span>دسترسی‌های نوشتن دایرکتوری‌ها</span>
                </div>
                <ul class="req-list">
                    <?php foreach ($reqData['permissions'] as $key => $perm): ?>
                        <li class="req-item <?= $perm['status'] ? 'pass' : 'fail' ?>">
                            <div class="req-info">
                                <span class="req-title">
                                    <i class="fa-solid <?= $perm['status'] ? 'fa-check text-green' : 'fa-xmark text-red' ?>"></i>
                                    <?= e($perm['name']) ?>
                                </span>
                                <span class="req-desc"><?= e($perm['description']) ?></span>
                            </div>
                            <span class="req-val"><?= e($perm['current']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div style="margin-top: 25px; display: flex; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="padding: 12px 18px; font-weight: 700;" onclick="location.reload()">
                        <i class="fa-solid fa-rotate-right"></i>
                        <span>بررسی مجدد سرور</span>
                    </button>
                    <?php if ($allPassed): ?>
                        <button type="button" class="btn btn-primary" style="flex: 1; padding: 12px; font-weight: 700;" onclick="goToStep(2)">
                            <span>تأیید سرور و مرحله بعد: پایگاه داده</span>
                            <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary" style="flex: 1; padding: 12px; font-weight: 700;" disabled title="ابتدا خطاهای بحرانی را رفع کنید">
                            <span>پیش‌نیازهای ضروری رفع نشده است</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- STEP 2: Database Configuration -->
            <div class="step-panel" id="panelStep2">
                <h3 style="margin: 0 0 8px 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
                    پیکربندی مشخصات پایگاه داده MySQL
                </h3>
                <p style="color: #64748b; font-size: 0.86rem; margin-bottom: 20px;">
                    مشخصات دیتابیس ساخته‌شده در cPanel (بخش MySQL Databases) را وارد فرمایید:
                </p>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">میزبان پایگاه داده (Database Host)</label>
                        <input type="text" id="db_host" class="form-control" value="localhost" dir="ltr" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">پورت (Port)</label>
                        <input type="number" id="db_port" class="form-control" value="3306" dir="ltr" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نام دیتابیس (Database Name) <span class="required">*</span></label>
                    <input type="text" id="db_name" class="form-control" value="thermoban_db" dir="ltr" placeholder="مثال: cpaneluser_thermoban" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 22px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نام کاربری دیتابیس (User) <span class="required">*</span></label>
                        <input type="text" id="db_user" class="form-control" value="root" dir="ltr" placeholder="مثال: cpaneluser_dbuser" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">کلمه عبور دیتابیس (Password)</label>
                        <div class="password-field-wrapper">
                            <input type="password" id="db_pass" class="form-control" dir="ltr" placeholder="رمز دیتابیس">
                            <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('db_pass', this)" title="نمایش/پنهان‌سازی رمز">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-bottom: 15px;">
                    <button type="button" class="btn btn-outline" id="btnTestDb" style="flex: 1; padding: 12px; font-weight: 700;" onclick="testDatabaseConn(true)">
                        <i class="fa-solid fa-plug"></i>
                        <span>تست اتصال به پایگاه داده</span>
                    </button>
                    <button type="button" class="btn btn-primary" style="flex: 1; padding: 12px; font-weight: 700;" onclick="validateStep2AndProceed()">
                        <span>تایید اتصال و مرحله بعد</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-link" style="color: #64748b; font-size: 0.82rem; padding: 0;" onclick="goToStep(1)">
                    <i class="fa-solid fa-arrow-right"></i> بازگشت به بررسی سرور
                </button>
            </div>

            <!-- STEP 3: Site Identity & Branding -->
            <div class="step-panel" id="panelStep3">
                <h3 style="margin: 0 0 8px 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
                    اطلاعات پایه، برندینگ و هویت وب‌سایت
                </h3>
                <p style="color: #64748b; font-size: 0.86rem; margin-bottom: 20px;">
                    اطلاعاتی که مایلید روی وب‌سایت، هدر، فوتر و گزارش‌های تحویل به مشتری قرار گیرد را وارد نمایید:
                </p>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">عنوان رسمی وب‌سایت <span class="required">*</span></label>
                    <input type="text" id="site_name" class="form-control" value="ترموبان | نمایندگی کد ۷۲۷۳ ایران رادیاتور و بوتان" required>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">عنوان فرعی / شعار صفحه اصلی</label>
                        <input type="text" id="site_title" class="form-control" value="نمایندگی رسمی نصب و تعمیرات پکیج دیواری">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">کد نمایندگی مجاز</label>
                        <input type="text" id="agency_code" class="form-control" value="۷۲۷۳">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">توضیحات کوتاه سایت (سئو و فوتر)</label>
                    <textarea id="site_description" class="form-control" rows="2">مرکز تخصصی نصب، سرویس و تعمیرات انواع پکیج شوفاژ دیواری با کد رسمی مجاز ۷۲۷۳ و ضمانت طلایی قطعات</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">شماره همراه پشتیبانی ۲۴ ساعته <span class="required">*</span></label>
                        <input type="tel" id="site_phone_1" class="form-control" value="۰۹۱۲-۳۴۵-۶۷۸۹" dir="ltr" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">تلفن ثابت دفتر مرکزی</label>
                        <input type="text" id="site_phone_2" class="form-control" value="۰۲۱-۱۲۳۴۵۶۷۸" dir="ltr">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">آدرس ایمیل رسمی پشتیبانی</label>
                        <input type="email" id="site_email" class="form-control" value="info@thermoban.ir" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">آدرس دفتر مرکزی و شعب</label>
                        <input type="text" id="site_address" class="form-control" value="تهران، خیابان کمالی، خیابان غفاری، پلاک ۱۷۵">
                    </div>
                </div>

                <!-- Logo & Favicon Upload -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 22px;">
                    <div>
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">
                            آپلود لوگوی اختصاصی سایت (PNG / JPG / WebP / SVG)
                        </label>
                        <div class="upload-dropzone" onclick="document.getElementById('site_logo').click()">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 1.5rem; color: #EF5C16; margin-bottom: 6px;"></i>
                            <div style="font-size: 0.82rem; font-weight: 700;">انتخاب یا رهاسازی فایل لوگو</div>
                            <span style="font-size: 0.74rem; color: #94a3b8;">در صورت خالی بودن، لوگوی پیش‌فرض حفظ می‌شود</span>
                            <input type="file" id="site_logo" name="site_logo" accept="image/*" onchange="previewUploadImage(this, 'logoPreviewImg', 'logoPreviewBox')">
                            <div class="preview-img-box" id="logoPreviewBox" style="display: none;">
                                <img id="logoPreviewImg" src="" alt="Logo Preview">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">
                            آپلود فاوآیکون تب مرورگر (ICO / PNG)
                        </label>
                        <div class="upload-dropzone" onclick="document.getElementById('site_favicon').click()">
                            <i class="fa-solid fa-image" style="font-size: 1.5rem; color: #0284c7; margin-bottom: 6px;"></i>
                            <div style="font-size: 0.82rem; font-weight: 700;">انتخاب آیکون مرورگر (Favicon)</div>
                            <span style="font-size: 0.74rem; color: #94a3b8;">آیکون ۳۲×۳۲ یا فرمت ico/png</span>
                            <input type="file" id="site_favicon" name="site_favicon" accept="image/*" onchange="previewUploadImage(this, 'favPreviewImg', 'favPreviewBox')">
                            <div class="preview-img-box" id="favPreviewBox" style="display: none;">
                                <img id="favPreviewImg" src="" alt="Favicon Preview" style="width: 32px; height: 32px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-bottom: 15px;">
                    <button type="button" class="btn btn-primary" style="flex: 1; padding: 12px; font-weight: 700;" onclick="validateStep3AndProceed()">
                        <span>تأیید هویت سایت و ایجاد حساب مدیر ارشد</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-link" style="color: #64748b; font-size: 0.82rem; padding: 0;" onclick="goToStep(2)">
                    <i class="fa-solid fa-arrow-right"></i> بازگشت به تنظیمات دیتابیس
                </button>
            </div>

            <!-- STEP 4: Super Admin Setup -->
            <div class="step-panel" id="panelStep4">
                <h3 style="margin: 0 0 8px 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
                    ایجاد دسترسی مدیر ارشد (Super Admin)
                </h3>
                <p style="color: #64748b; font-size: 0.86rem; margin-bottom: 20px;">
                    این حساب کاربری دارای دسترسی تام به تمامی تنظیمات، ماژول‌ها و ابزارها جهت آماده‌سازی و تحویل به مشتری است:
                </p>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نام و نام خانوادگی مدیر ارشد <span class="required">*</span></label>
                    <input type="text" id="admin_name" class="form-control" value="شایان نماینده" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">شماره تلفن همراه (نام کاربری اصلی ورود) <span class="required">*</span></label>
                        <input type="tel" id="admin_phone" class="form-control" value="09121111111" dir="ltr" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">آدرس ایمیل مدیر کل</label>
                        <input type="email" id="admin_email" class="form-control" value="admin@thermoban.ir" dir="ltr">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">کلمه عبور پنل مدیریت <span class="required">*</span></label>
                        <div class="password-field-wrapper">
                            <input type="password" id="admin_pass" class="form-control" value="admin123" required oninput="checkPasswordStrength(this.value)">
                            <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('admin_pass', this)" title="نمایش/پنهان‌سازی رمز">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="pwd-strength-bar">
                            <div class="pwd-strength-fill" id="pwdStrengthFill"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">تکرار کلمه عبور <span class="required">*</span></label>
                        <div class="password-field-wrapper">
                            <input type="password" id="admin_pass_confirm" class="form-control" value="admin123" required>
                            <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('admin_pass_confirm', this)" title="نمایش/پنهان‌سازی رمز">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.82rem; color: #475569;">
                    <i class="fa-solid fa-shield-halved" style="color: #16a34a; margin-left: 6px;"></i>
                    پس از اتمام نصب، ورود به سامانه با این شماره موبایل و کلمه عبور انجام خواهد شد و در پنل مدیریت می‌توانید در صورت نیاز برای سایر همکاران حساب محدود فروشگاه بسازید.
                </div>

                <button type="button" class="btn btn-primary w-100" id="btnExecuteInstall" style="padding: 14px; font-weight: 800; font-size: 1rem;" onclick="runFinalInstall()">
                    <i class="fa-solid fa-rocket" style="margin-left: 8px;"></i>
                    <span>نصب نهایی، ساخت جداول و تحویل به مشتری</span>
                </button>
                <div style="margin-top: 12px;">
                    <button type="button" class="btn btn-link" style="color: #64748b; font-size: 0.82rem; padding: 0;" onclick="goToStep(3)">
                        <i class="fa-solid fa-arrow-right"></i> بازگشت به اطلاعات وب‌سایت
                    </button>
                </div>
            </div>

            <!-- STEP 5: Success & Client Handover Ready -->
            <div class="step-panel" id="panelStep5" style="text-align: center; padding: 10px 0;">
                <div style="width: 80px; height: 80px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 16px; box-shadow: 0 10px 25px -5px rgba(22, 163, 74, 0.4);">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h2 style="font-size: 1.4rem; color: #011C39; font-weight: 800; margin-bottom: 6px;">سامانه با موفقیت نصب و آماده تحویل به مشتری شد!</h2>
                <p style="color: #64748b; font-size: 0.88rem; margin-bottom: 24px;">
                    جداول دیتابیس، آپدیت‌های سیستمی، تنظیمات هویت سایت و دسترسی سوپر ادمین با موفقیت ایجاد و فعال گردیدند.
                </p>

                <!-- Handover Summary Card -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; text-align: right; margin-bottom: 25px;">
                    <h4 style="font-size: 0.95rem; font-weight: 800; margin: 0 0 14px 0; color: #011C39; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-file-contract text-orange"></i>
                        <span>شناسنامه و مشخصات تحویل به مشتری:</span>
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85rem;">
                        <div><strong>نام وب‌سایت:</strong> <span id="summarySiteName">-</span></div>
                        <div><strong>کد نمایندگی:</strong> <span id="summaryAgencyCode">-</span></div>
                        <div><strong>آدرس پنل مدیریت:</strong> <span dir="ltr" style="font-family: monospace;">/admin</span></div>
                        <div><strong>نام کاربری سوپر ادمین:</strong> <span id="summaryAdminPhone" dir="ltr" style="font-family: monospace;">-</span></div>
                        <div><strong>وضعیت پایگاه داده:</strong> <span class="badge bg-success" style="color: #166534; background: #dcfce7; padding: 3px 8px; border-radius: 4px;">متصل و آپدیت v3</span></div>
                        <div><strong>وضعیت سیستم:</strong> <span class="badge bg-success" style="color: #166534; background: #dcfce7; padding: 3px 8px; border-radius: 4px;">آماده بهره‌برداری</span></div>
                    </div>
                </div>

                <div style="display: flex; gap: 14px; justify-content: center;">
                    <a href="<?= url('admin') ?>" class="btn btn-primary" id="btnGoAdmin" style="padding: 12px 28px; font-weight: 700;">
                        <i class="fa-solid fa-gauge" style="margin-left: 6px;"></i>
                        <span>ورود به پنل مدیریت (سوپر ادمین)</span>
                    </a>
                    <a href="<?= url() ?>" class="btn btn-outline" id="btnGoSite" style="padding: 12px 24px; font-weight: 700;">
                        <i class="fa-solid fa-house" style="margin-left: 6px;"></i>
                        <span>مشاهده وب‌سایت تحویل داده شده</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Local SweetAlert2 (100% Offline / Zero CDN) -->
    <script src="<?= asset('vendor/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script>
        const BASE_URL = "<?= url() ?>";
        let dbValidated = false;

        function goToStep(step) {
            document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.installer-step-item').forEach(s => s.classList.remove('active'));

            const targetPanel = document.getElementById('panelStep' + step);
            const targetIndicator = document.getElementById('stepIndicator' + step);
            if (targetPanel && targetIndicator) {
                targetPanel.classList.add('active');
                targetIndicator.classList.add('active');
            }

            for (let i = 1; i < step; i++) {
                const prev = document.getElementById('stepIndicator' + i);
                if (prev) prev.classList.add('done');
            }
        }

        // Toggle Password Visibility (Eye Icon)
        function togglePasswordVisibility(fieldId, btn) {
            const input = document.getElementById(fieldId);
            if (!input) return;
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }

        // Image Preview Handler
        function previewUploadImage(input, imgId, boxId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(imgId);
                    const box = document.getElementById(boxId);
                    if (img && box) {
                        img.src = e.target.result;
                        box.style.display = 'inline-block';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Password Strength Check
        function checkPasswordStrength(val) {
            const bar = document.getElementById('pwdStrengthFill');
            if (!bar) return;
            let score = 0;
            if (val.length >= 6) score += 30;
            if (val.length >= 8) score += 20;
            if (/[0-9]/.test(val)) score += 25;
            if (/[^a-zA-Z0-9]/.test(val)) score += 25;

            bar.style.width = Math.min(score, 100) + '%';
            if (score < 40) {
                bar.style.background = '#ef4444';
            } else if (score < 75) {
                bar.style.background = '#f59e0b';
            } else {
                bar.style.background = '#10b981';
            }
        }

        // AJAX Test Database Connection
        async function testDatabaseConn(showAlert = true) {
            const host = document.getElementById('db_host').value.trim();
            const port = document.getElementById('db_port').value.trim();
            const name = document.getElementById('db_name').value.trim();
            const user = document.getElementById('db_user').value.trim();
            const pass = document.getElementById('db_pass').value;

            if (!name || !user) {
                if (showAlert) {
                    Swal.fire({ icon: 'warning', title: 'فیلدهای ناقص', text: 'لطفاً نام دیتابیس و نام کاربری را وارد نمایید.' });
                }
                return false;
            }

            const btn = document.getElementById('btnTestDb');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال بررسی اتصال...';
            }

            const formData = new FormData();
            formData.append('db_host', host);
            formData.append('db_port', port);
            formData.append('db_name', name);
            formData.append('db_user', user);
            formData.append('db_pass', pass);

            try {
                const res = await fetch(BASE_URL + 'install/test-db', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-plug"></i> تست اتصال به پایگاه داده';
                }

                if (data.success) {
                    dbValidated = true;
                    if (showAlert) {
                        Swal.fire({
                            icon: 'success',
                            title: 'اتصال موفق!',
                            text: data.message + (data.version ? ' (MySQL v' + data.version + ')' : '')
                        });
                    }
                    return true;
                } else {
                    dbValidated = false;
                    if (showAlert) {
                        Swal.fire({ icon: 'error', title: 'خطا در اتصال', text: data.message });
                    }
                    return false;
                }
            } catch (err) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-plug"></i> تست اتصال به پایگاه داده';
                }
                dbValidated = false;
                if (showAlert) {
                    Swal.fire({ icon: 'error', title: 'خطا', text: 'پاسخ نامعتبر از سرور دریافت شد.' });
                }
                return false;
            }
        }

        async function validateStep2AndProceed() {
            const ok = await testDatabaseConn(true);
            if (ok) {
                goToStep(3);
            }
        }

        function validateStep3AndProceed() {
            const siteName = document.getElementById('site_name').value.trim();
            const sitePhone = document.getElementById('site_phone_1').value.trim();
            if (!siteName || !sitePhone) {
                Swal.fire({ icon: 'warning', title: 'اطلاعات ناقص', text: 'عنوان سایت و شماره تماس الزامی هستند.' });
                return;
            }
            goToStep(4);
        }

        async function runFinalInstall() {
            const adminPhone = document.getElementById('admin_phone').value.trim();
            const adminPass = document.getElementById('admin_pass').value;
            const adminPassConfirm = document.getElementById('admin_pass_confirm').value;

            if (!adminPhone || !adminPass) {
                Swal.fire({ icon: 'warning', title: 'اطلاعات ناقص', text: 'شماره تلفن و کلمه عبور مدیر ارشد الزامی است.' });
                return;
            }

            if (adminPass !== adminPassConfirm) {
                Swal.fire({ icon: 'error', title: 'عدم تطابق رمز عبور', text: 'کلمه عبور و تکرار آن یکسان نیستند.' });
                return;
            }

            const btn = document.getElementById('btnExecuteInstall');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال ساخت جداول دیتابیس، آپلود لوگو و پیکربندی...';

            const formData = new FormData();
            // Database
            formData.append('db_host', document.getElementById('db_host').value.trim());
            formData.append('db_port', document.getElementById('db_port').value.trim());
            formData.append('db_name', document.getElementById('db_name').value.trim());
            formData.append('db_user', document.getElementById('db_user').value.trim());
            formData.append('db_pass', document.getElementById('db_pass').value);

            // Site Identity
            formData.append('site_name', document.getElementById('site_name').value.trim());
            formData.append('site_title', document.getElementById('site_title').value.trim());
            formData.append('site_description', document.getElementById('site_description').value.trim());
            formData.append('agency_code', document.getElementById('agency_code').value.trim());
            formData.append('site_phone_1', document.getElementById('site_phone_1').value.trim());
            formData.append('site_phone_2', document.getElementById('site_phone_2').value.trim());
            formData.append('site_email', document.getElementById('site_email').value.trim());
            formData.append('site_address', document.getElementById('site_address').value.trim());

            // Files
            const logoInput = document.getElementById('site_logo');
            if (logoInput && logoInput.files[0]) {
                formData.append('site_logo', logoInput.files[0]);
            }
            const favInput = document.getElementById('site_favicon');
            if (favInput && favInput.files[0]) {
                formData.append('site_favicon', favInput.files[0]);
            }

            // Super Admin
            formData.append('admin_name', document.getElementById('admin_name').value.trim());
            formData.append('admin_phone', adminPhone);
            formData.append('admin_email', document.getElementById('admin_email').value.trim());
            formData.append('admin_pass', adminPass);

            try {
                const res = await fetch(BASE_URL + 'install/execute', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('summarySiteName').textContent = document.getElementById('site_name').value.trim();
                    document.getElementById('summaryAgencyCode').textContent = document.getElementById('agency_code').value.trim();
                    document.getElementById('summaryAdminPhone').textContent = adminPhone;
                    goToStep(5);
                } else {
                    Swal.fire({ icon: 'error', title: 'خطای نصب', text: data.message });
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-rocket"></i> تلاش مجدد برای نصب';
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'خطا', text: 'پاسخ نامعتبر از سرور دریافت شد.' });
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-rocket"></i> تلاش مجدد برای نصب';
            }
        }
    </script>
</body>
</html>
