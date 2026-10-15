<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'پنل مدیریت اختصاصی ترموبان') ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/logo-icon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        :root {
            --admin-sidebar-w: 260px;
        }
        body.admin-body {
            background-color: #f1f5f9;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: var(--admin-sidebar-w);
            background-color: #011C39;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        .admin-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .admin-brand img {
            width: 36px;
            height: 36px;
        }
        .admin-brand-text h2 {
            font-size: 1.1rem;
            color: #FFFFFF;
            margin: 0;
            font-weight: 800;
        }
        .admin-brand-text span {
            font-size: 0.75rem;
            color: #EF5C16;
            font-weight: 700;
        }
        .admin-menu {
            padding: 20px 0;
            flex-grow: 1;
            overflow-y: auto;
        }
        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: #cbd5e1;
            font-size: 0.92rem;
            font-weight: 700;
            transition: all 0.2s ease;
            text-decoration: none;
            border-right: 4px solid transparent;
        }
        .admin-nav-item:hover {
            background-color: rgba(255,255,255,0.06);
            color: #FFFFFF;
        }
        .admin-nav-item.active {
            background-color: rgba(239, 92, 22, 0.15);
            color: #EF5C16;
            border-right-color: #EF5C16;
        }
        .admin-main-wrap {
            margin-right: var(--admin-sidebar-w);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .admin-topbar {
            background-color: #FFFFFF;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .admin-topbar-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #011C39;
        }
        .admin-user-nav {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .admin-user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #334155;
        }
        .admin-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #EF5C16;
        }
        .admin-content-area {
            padding: 35px;
            flex-grow: 1;
        }
        .admin-footer-bar {
            padding: 18px 35px;
            background-color: #FFFFFF;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            color: #64748b;
        }
        .btn-live-site {
            background-color: #f1f5f9;
            color: #011C39;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.84rem;
            font-weight: 700;
        }
        .btn-live-site:hover {
            background-color: #011C39;
            color: #FFFFFF;
        }
        @media (max-width: 900px) {
            .admin-sidebar {
                width: 70px;
            }
            .admin-brand-text, .admin-nav-item span {
                display: none;
            }
            .admin-main-wrap {
                margin-right: 70px;
            }
            .admin-nav-item {
                justify-content: center;
                padding: 16px;
            }
        }
    </style>
</head>
<body class="admin-body">

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان">
            <div class="admin-brand-text">
                <h2>پنل مدیریت ترموبان</h2>
                <span>نمایندگی کد ۷۲۷۳</span>
            </div>
        </div>

        <nav class="admin-menu">
            <a href="<?= url('admin') ?>" class="admin-nav-item <?= ($activeNav === 'dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie" style="font-size: 1.1rem; width: 22px;"></i>
                <span>داشبورد آماری</span>
            </a>
            <a href="<?= url('admin/orders') ?>" class="admin-nav-item <?= ($activeNav === 'orders') ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice-dollar" style="font-size: 1.1rem; width: 22px;"></i>
                <span>مدیریت سفارش‌ها</span>
            </a>
            <a href="<?= url('admin/products') ?>" class="admin-nav-item <?= ($activeNav === 'products') ? 'active' : '' ?>">
                <i class="fa-solid fa-boxes-stacked" style="font-size: 1.1rem; width: 22px;"></i>
                <span>محصولات پکیج</span>
            </a>
            <a href="<?= url('admin/tickets') ?>" class="admin-nav-item <?= ($activeNav === 'tickets') ? 'active' : '' ?>">
                <i class="fa-solid fa-headset" style="font-size: 1.1rem; width: 22px;"></i>
                <span>تیکت‌های پشتیبانی</span>
            </a>
            <a href="<?= url('admin/leads') ?>" class="admin-nav-item <?= ($activeNav === 'leads') ? 'active' : '' ?>">
                <i class="fa-solid fa-phone-volume" style="font-size: 1.1rem; width: 22px;"></i>
                <span>سفارشات سریع و تماس‌ها</span>
            </a>
            <a href="<?= url('admin/articles') ?>" class="admin-nav-item <?= ($activeNav === 'articles') ? 'active' : '' ?>">
                <i class="fa-solid fa-newspaper" style="font-size: 1.1rem; width: 22px;"></i>
                <span>مقالات آموزشی</span>
            </a>
            <a href="<?= url('admin/users') ?>" class="admin-nav-item <?= ($activeNav === 'users') ? 'active' : '' ?>">
                <i class="fa-solid fa-users" style="font-size: 1.1rem; width: 22px;"></i>
                <span>مدیریت کاربران</span>
            </a>
            <a href="<?= url('admin/seo') ?>" class="admin-nav-item <?= ($activeNav === 'seo') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-line" style="font-size: 1.1rem; width: 22px;"></i>
                <span>سئو (Rank Math Pro)</span>
            </a>
            <a href="<?= url('admin/gateways') ?>" class="admin-nav-item <?= ($activeNav === 'gateways') ? 'active' : '' ?>">
                <i class="fa-solid fa-credit-card" style="font-size: 1.1rem; width: 22px;"></i>
                <span>درگاه‌های پرداخت</span>
            </a>
            <a href="<?= url('admin/sms') ?>" class="admin-nav-item <?= ($activeNav === 'sms') ? 'active' : '' ?>">
                <i class="fa-solid fa-comment-sms" style="font-size: 1.1rem; width: 22px;"></i>
                <span>پنل پیامک (ملی‌پیامک)</span>
            </a>
            <?php if (\App\Core\Auth::isSuperAdmin()): ?>
                <a href="<?= url('admin/modules') ?>" class="admin-nav-item <?= ($activeNav === 'modules') ? 'active' : '' ?>" style="border-right-color: #f59e0b; color: #fbbf24;">
                    <i class="fa-solid fa-wand-magic-sparkles" style="font-size: 1.1rem; width: 22px;"></i>
                    <span>ماژول‌ها و پلن‌ها</span>
                </a>
            <?php endif; ?>
            <a href="<?= url('admin/settings') ?>" class="admin-nav-item <?= ($activeNav === 'settings') ? 'active' : '' ?>">
                <i class="fa-solid fa-gears" style="font-size: 1.1rem; width: 22px;"></i>
                <span>تنظیمات نمایندگی</span>
            </a>
        </nav>
    </aside>

    <!-- Admin Main Body Wrapper -->
    <div class="admin-main-wrap">
        
        <!-- Top Navbar -->
        <header class="admin-topbar">
            <div class="admin-topbar-title">
                <?= e($pageTitle ?? 'سیستم مدیریت محتوای اختصاصی ترموبان') ?>
            </div>
            <div class="admin-user-nav">
                <a href="<?= url() ?>" target="_blank" class="btn-live-site">
                    <?= icon('eye', '', 16) ?>
                    <span>مشاهده سایت اصلی</span>
                </a>
                <div class="admin-user-pill">
                    <img src="<?= asset('images/hero-technician.jpg') ?>" alt="مدیر" class="admin-user-avatar">
                    <span>مدیریت سیستم</span>
                </div>
                <a href="<?= url('logout') ?>" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.82rem; color: #ef4444; border-color: #fca5a5;">
                    خروج
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <main class="admin-content-area">
            <?= $content ?>
        </main>

        <!-- Footer -->
        <footer class="admin-footer-bar">
            <div>سامانه مدیریت محتوای اختصاصی و اتوماسیون خدمات ترموبان (نسخه ۲.۰)</div>
            <div class="developer-credit">
                طراحی و توسعه: <a href="https://devshayan.ir" target="_blank" rel="noopener"><strong>شایان نماینده</strong></a>
            </div>
        </footer>

    </div>

    <!-- Vendor JS (Zero CDN) -->
    <script src="<?= asset('vendor/chartjs/chart.umd.min.js') ?>"></script>
    <script src="<?= asset('vendor/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script>
        window.THERMOBAN_CONFIG = {
            baseUrl: "<?= url() ?>",
            csrfToken: "<?= e($csrfToken ?? '') ?>"
        };
    </script>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
