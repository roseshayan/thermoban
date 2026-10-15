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
            background: linear-gradient(135deg, #011C39 0%, #0d3b66 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            color: #1e293b;
            font-family: 'Vazirmatn', sans-serif;
            margin: 0;
        }
        .installer-card {
            background: #FFFFFF;
            border-radius: 20px;
            width: 720px;
            max-width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .installer-header {
            background: #011C39;
            color: #FFFFFF;
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #EF5C16;
        }
        .installer-header img {
            height: 48px;
            margin-bottom: 12px;
        }
        .installer-header h1 {
            font-size: 1.4rem;
            margin: 0 0 6px 0;
            font-weight: 800;
        }
        .installer-header p {
            margin: 0;
            font-size: 0.85rem;
            color: #cbd5e1;
        }
        .installer-steps {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .installer-step-item {
            flex: 1;
            padding: 14px 10px;
            text-align: center;
            font-size: 0.82rem;
            font-weight: 700;
            color: #94a3b8;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
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
            padding: 35px 30px;
        }
        .req-list {
            list-style: none;
            padding: 0;
            margin: 0 0 25px 0;
        }
        .req-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.88rem;
        }
        .req-item.pass {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }
        .req-item.fail {
            border-color: #fecaca;
            background: #fef2f2;
        }
        .step-panel {
            display: none;
        }
        .step-panel.active {
            display: block;
        }
    </style>
</head>
<body class="installer-body">

    <div class="installer-card">
        
        <!-- Header -->
        <div class="installer-header">
            <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان">
            <h1>سامانه راه‌انداز خودکار ترموبان (نسخه ۲.۰)</h1>
            <p>نمایندگی رسمی کد ۷۲۷۳ ایران رادیاتور و بوتان | نصب سریع هاست اشتراکی cPanel</p>
        </div>

        <!-- Progress Steps -->
        <div class="installer-steps">
            <div class="installer-step-item active" id="stepIndicator1">
                <i class="fa-solid fa-server"></i>
                <span>۱. بررسی سرور</span>
            </div>
            <div class="installer-step-item" id="stepIndicator2">
                <i class="fa-solid fa-database"></i>
                <span>۲. پایگاه داده</span>
            </div>
            <div class="installer-step-item" id="stepIndicator3">
                <i class="fa-solid fa-user-shield"></i>
                <span>۳. تنظیمات مدیر</span>
            </div>
            <div class="installer-step-item" id="stepIndicator4">
                <i class="fa-solid fa-circle-check"></i>
                <span>۴. پایان نصب</span>
            </div>
        </div>

        <!-- Body Form -->
        <div class="installer-content">

            <!-- STEP 1: Requirements Check -->
            <div class="step-panel active" id="panelStep1">
                <h3 style="margin: 0 0 10px 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
                    بررسی انطباق پیش‌نیازهای سرور و هاست اشتراکی
                </h3>
                <p style="color: #64748b; font-size: 0.86rem; margin-bottom: 20px;">
                    برای اجرای صحیح معماری MVC، ماژول‌های پرداخت و وب‌سرویس‌ها، موارد زیر بررسی شدند:
                </p>

                <ul class="req-list">
                    <?php foreach ($requirements as $key => $req): ?>
                        <li class="req-item <?= $req['status'] ? 'pass' : 'fail' ?>">
                            <span style="font-weight: 700;">
                                <i class="fa-solid <?= $req['status'] ? 'fa-check text-green' : 'fa-xmark text-red' ?>" style="margin-left: 8px;"></i>
                                <?= e($req['name']) ?>
                            </span>
                            <span style="font-family: monospace; font-size: 0.85rem; color: <?= $req['status'] ? '#166534' : '#991b1b' ?>;">
                                <?= e($req['current']) ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($allPassed): ?>
                    <button type="button" class="btn btn-primary w-100" style="padding: 12px; font-weight: 700;" onclick="goToStep(2)">
                        <span>تأیید سرور و رفتن به مرحله بعد</span>
                        <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i>
                    </button>
                <?php else: ?>
                    <div style="background: #fef2f2; color: #991b1b; padding: 14px; border-radius: 8px; font-size: 0.86rem; border: 1px solid #fecaca;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-left: 6px;"></i>
                        برخی پیش‌نیازها در هاست شما فعال نیست. لطفاً از طریق منوی Select PHP Version در cPanel آن‌ها را فعال نمایید.
                    </div>
                <?php endif; ?>
            </div>

            <!-- STEP 2: Database Configuration -->
            <div class="step-panel" id="panelStep2">
                <h3 style="margin: 0 0 10px 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
                    پیکربندی مشخصات پایگاه داده MySQL
                </h3>
                <p style="color: #64748b; font-size: 0.86rem; margin-bottom: 20px;">
                    اطلاعات دیتابیس ایجاد شده در بخش MySQL Databases کنترل‌پنل cPanel را وارد فرمایید:
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
                    <input type="text" id="db_name" class="form-control" value="thermoban_db" dir="ltr" placeholder="cpaneluser_thermoban" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نام کاربری دیتابیس (User) <span class="required">*</span></label>
                        <input type="text" id="db_user" class="form-control" value="root" dir="ltr" placeholder="cpaneluser_dbuser" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">کلمه عبور دیتابیس (Password)</label>
                        <input type="password" id="db_pass" class="form-control" dir="ltr" placeholder="رمز دیتابیس">
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-bottom: 15px;">
                    <button type="button" class="btn btn-outline" style="flex: 1; padding: 10px; font-weight: 700;" onclick="testDatabaseConn()">
                        <i class="fa-solid fa-plug"></i>
                        <span>تست اتصال به پایگاه داده</span>
                    </button>
                    <button type="button" class="btn btn-primary" style="flex: 1; padding: 10px; font-weight: 700;" onclick="goToStep(3)">
                        <span>تایید و ادامه</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-link" style="color: #64748b; font-size: 0.82rem;" onclick="goToStep(1)">
                    <i class="fa-solid fa-arrow-right"></i> بازگشت به مرحله قبل
                </button>
            </div>

            <!-- STEP 3: Site & Admin Settings -->
            <div class="step-panel" id="panelStep3">
                <h3 style="margin: 0 0 10px 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
                    اطلاعات سامانه و ایجاد حساب سوپر ادمین
                </h3>
                <p style="color: #64748b; font-size: 0.86rem; margin-bottom: 20px;">
                    مشخصات مدیر کل و عنوان سامانه نمایندگی را وارد نمایید:
                </p>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">عنوان رسمی وب‌سایت نمایندگی</label>
                    <input type="text" id="site_name" class="form-control" value="ترموبان | نمایندگی کد ۷۲۷۳ ایران رادیاتور و بوتان">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نام و نام خانوادگی مدیر کل (برنامه‌نویس)</label>
                    <input type="text" id="admin_name" class="form-control" value="شایان نماینده">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 25px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">شماره تلفن همراه (نام کاربری ورود) <span class="required">*</span></label>
                        <input type="tel" id="admin_phone" class="form-control" value="09121111111" dir="ltr" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">کلمه عبور پنل مدیریت <span class="required">*</span></label>
                        <input type="password" id="admin_pass" class="form-control" value="admin123" required>
                    </div>
                </div>

                <button type="button" class="btn btn-primary w-100" id="btnExecuteInstall" style="padding: 14px; font-weight: 800; font-size: 1rem;" onclick="runFinalInstall()">
                    <i class="fa-solid fa-rocket" style="margin-left: 8px;"></i>
                    <span>نصب و پیکربندی نهایی سامانه ترموبان</span>
                </button>
            </div>

            <!-- STEP 4: Success & Completed -->
            <div class="step-panel" id="panelStep4" style="text-align: center; padding: 20px 0;">
                <div style="width: 80px; height: 80px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h2 style="font-size: 1.4rem; color: #011C39; font-weight: 800; margin-bottom: 10px;">نصب سامانه با موفقیت انجام شد!</h2>
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 30px; line-height: 1.8;">
                    پایگاه داده، جدول‌ها، ماژول‌ها و حساب کاربری سوپر ادمین شما با موفقیت ایجاد و فعال شدند.
                </p>

                <div style="display: flex; gap: 14px; justify-content: center;">
                    <a href="<?= url('admin') ?>" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700;">
                        <i class="fa-solid fa-gauge" style="margin-left: 6px;"></i>
                        <span>ورود به پنل مدیریت اختصاصی</span>
                    </a>
                    <a href="<?= url() ?>" class="btn btn-outline" style="padding: 12px 24px; font-weight: 700;">
                        <i class="fa-solid fa-house" style="margin-left: 6px;"></i>
                        <span>مشاهده وب‌سایت اصلی</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <script src="<?= asset('vendor/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script>
        const BASE_URL = "<?= url() ?>";

        function goToStep(step) {
            document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.installer-step-item').forEach(s => s.classList.remove('active'));

            document.getElementById('panelStep' + step).classList.add('active');
            document.getElementById('stepIndicator' + step).classList.add('active');

            for (let i = 1; i < step; i++) {
                document.getElementById('stepIndicator' + i).classList.add('done');
            }
        }

        async function testDatabaseConn() {
            const host = document.getElementById('db_host').value.trim();
            const port = document.getElementById('db_port').value.trim();
            const name = document.getElementById('db_name').value.trim();
            const user = document.getElementById('db_user').value.trim();
            const pass = document.getElementById('db_pass').value;

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
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'اتصال موفق', text: data.message });
                } else {
                    Swal.fire({ icon: 'error', title: 'خطا در ارتباط', text: data.message });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'خطا', text: 'خطا در برقراری ارتباط با وب‌سرور.' });
            }
        }

        async function runFinalInstall() {
            const btn = document.getElementById('btnExecuteInstall');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال ساخت دیتابیس و نصب جداول...';

            const formData = new FormData();
            formData.append('db_host', document.getElementById('db_host').value.trim());
            formData.append('db_port', document.getElementById('db_port').value.trim());
            formData.append('db_name', document.getElementById('db_name').value.trim());
            formData.append('db_user', document.getElementById('db_user').value.trim());
            formData.append('db_pass', document.getElementById('db_pass').value);

            formData.append('site_name', document.getElementById('site_name').value.trim());
            formData.append('admin_name', document.getElementById('admin_name').value.trim());
            formData.append('admin_phone', document.getElementById('admin_phone').value.trim());
            formData.append('admin_pass', document.getElementById('admin_pass').value);

            try {
                const res = await fetch(BASE_URL + 'install/execute', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    goToStep(4);
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
