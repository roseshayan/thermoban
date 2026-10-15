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
    <link rel="stylesheet" href="<?= asset('vendor/quill/quill.snow.css') ?>">
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
            width: calc(100% - var(--admin-sidebar-w));
            transition: all 0.3s ease;
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
        .admin-topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .admin-hamburger-btn {
            display: none;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #011C39;
            cursor: pointer;
            transition: all 0.2s;
        }
        .admin-hamburger-btn:hover {
            background: #e2e8f0;
            color: #EF5C16;
        }
        .admin-sidebar-close {
            display: none;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #ffffff;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            font-size: 1.1rem;
            cursor: pointer;
            margin-right: auto;
            align-items: center;
            justify-content: center;
        }
        .admin-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(1, 28, 57, 0.65);
            backdrop-filter: blur(2px);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .admin-sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        .admin-topbar-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #011C39;
        }
        .admin-user-nav {
            display: flex;
            align-items: center;
            gap: 14px;
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
            object-fit: cover;
        }
        .admin-content-area {
            padding: 30px;
            flex-grow: 1;
        }
        .admin-footer-bar {
            padding: 18px 30px;
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
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-live-site:hover {
            background-color: #011C39;
            color: #FFFFFF;
        }

        /* 100% Mobile & Tablet Responsive Engine */
        @media (max-width: 992px) {
            .admin-hamburger-btn {
                display: flex;
            }
            .admin-sidebar-close {
                display: flex;
            }
            .admin-sidebar {
                width: 280px;
                right: -290px;
                z-index: 9999;
                transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: -4px 0 25px rgba(0,0,0,0.3);
            }
            .admin-sidebar.active {
                right: 0;
            }
            .admin-main-wrap {
                margin-right: 0 !important;
                width: 100% !important;
            }
            .admin-topbar {
                padding: 0 16px;
                height: 64px;
            }
            .admin-topbar-title {
                font-size: 0.98rem;
                max-width: 240px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .admin-content-area {
                padding: 16px 12px;
            }
            .admin-footer-bar {
                flex-direction: column;
                gap: 8px;
                text-align: center;
                padding: 14px;
            }
            .btn-live-site span {
                display: none;
            }
            .admin-user-pill span {
                display: none;
            }
        }
        @media (max-width: 576px) {
            .admin-topbar-title {
                display: none;
            }
            .admin-user-nav {
                gap: 8px;
            }
            .admin-card {
                padding: 16px 12px !important;
                border-radius: 10px !important;
        }

        /* Admin Table Pagination & Filtering Styles */
        .admin-table-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.86rem;
            color: #64748b;
        }
        .admin-pagination-pages {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .admin-page-btn {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #334155;
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .admin-page-btn:hover:not(:disabled) {
            background: #f1f5f9;
            color: #EF5C16;
            border-color: #cbd5e1;
        }
        .admin-page-btn.active {
            background: #EF5C16;
            color: #ffffff;
            border-color: #EF5C16;
        }
        .admin-page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .admin-page-size-select {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.82rem;
            color: #334155;
            background: #ffffff;
            outline: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="admin-body">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <img src="<?= asset('images/logo-icon.png') ?>" alt="ترموبان">
            <div class="admin-brand-text">
                <h2>پنل مدیریت ترموبان</h2>
                <span>نمایندگی کد ۷۲۷۳</span>
            </div>
            <button type="button" class="admin-sidebar-close" id="adminSidebarClose" aria-label="بستن منو">
                <i class="fa-solid fa-xmark"></i>
            </button>
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
            <a href="<?= url('admin/brands') ?>" class="admin-nav-item <?= ($activeNav === 'brands') ? 'active' : '' ?>">
                <i class="fa-solid fa-certificate" style="font-size: 1.1rem; width: 22px;"></i>
                <span>برندهای تحت پوشش</span>
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
            <a href="<?= url('admin/media') ?>" class="admin-nav-item <?= ($activeNav === 'media') ? 'active' : '' ?>">
                <i class="fa-solid fa-photo-film" style="font-size: 1.1rem; width: 22px;"></i>
                <span>کتابخانه پرونده‌ها</span>
            </a>
            <a href="<?= url('admin/comments') ?>" class="admin-nav-item <?= ($activeNav === 'comments') ? 'active' : '' ?>">
                <i class="fa-solid fa-comments" style="font-size: 1.1rem; width: 22px;"></i>
                <span>نظرات و دیدگاه‌ها</span>
            </a>
            <a href="<?= url('admin/analytics') ?>" class="admin-nav-item <?= ($activeNav === 'analytics') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-simple" style="font-size: 1.1rem; width: 22px;"></i>
                <span>آمار بازدیدکنندگان</span>
            </a>
            <a href="<?= url('admin/backups') ?>" class="admin-nav-item <?= ($activeNav === 'backups') ? 'active' : '' ?>">
                <i class="fa-solid fa-database" style="font-size: 1.1rem; width: 22px;"></i>
                <span>پشتیبان‌گیری و بازیابی</span>
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
            <a href="<?= url('admin/profile') ?>" class="admin-nav-item <?= ($activeNav === 'profile') ? 'active' : '' ?>">
                <i class="fa-solid fa-id-badge" style="font-size: 1.1rem; width: 22px;"></i>
                <span>پروفایل من</span>
            </a>
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
            <div class="admin-topbar-right">
                <button type="button" class="admin-hamburger-btn" id="adminSidebarToggle" aria-label="منوی ناوبری">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="admin-topbar-title">
                    <?= e($pageTitle ?? 'سیستم مدیریت محتوای اختصاصی ترموبان') ?>
                </div>
            </div>
            <div class="admin-user-nav">
                <a href="<?= url() ?>" target="_blank" class="btn-live-site">
                    <?= icon('eye', '', 16) ?>
                    <span>مشاهده سایت اصلی</span>
                </a>
                <?php 
                $currAdmin = \App\Core\Auth::user();
                $adminAvatar = !empty($currAdmin['avatar']) ? (str_starts_with($currAdmin['avatar'], 'http') ? $currAdmin['avatar'] : url($currAdmin['avatar'])) : asset('images/hero-technician.jpg');
                ?>
                <a href="<?= url('admin/profile') ?>" class="admin-user-pill" style="text-decoration: none; color: inherit;" title="مشاهده و ویرایش مشخصات من">
                    <img src="<?= $adminAvatar ?>" alt="مدیر" class="admin-user-avatar">
                    <span><?= e($currAdmin['name'] ?? 'مدیریت سیستم') ?></span>
                </a>
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

        // Mobile Off-canvas Drawer Navigation
        (function() {
            var toggleBtn = document.getElementById('adminSidebarToggle');
            var closeBtn = document.getElementById('adminSidebarClose');
            var sidebar = document.getElementById('adminSidebar');
            var overlay = document.getElementById('adminSidebarOverlay');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('active');
                if (overlay) overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                    closeSidebar();
                }
            });

            // Close on nav link click on mobile
            if (sidebar) {
                sidebar.querySelectorAll('.admin-nav-item').forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 992) {
                            closeSidebar();
                        }
                    });
                });
            }
        })();
    </script>
    <script src="<?= asset('vendor/quill/quill.js') ?>"></script>

    <!-- Global Media Picker Modal -->
    <div id="globalMediaPickerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65); z-index:99999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:#fff; border-radius:16px; width:100%; max-width:850px; max-height:85vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.3);">
            <div style="padding:16px 24px; background:#011C39; color:#fff; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="margin:0; font-size:1.1rem; font-weight:800;"><i class="fa-solid fa-photo-film" style="margin-left:8px;"></i>انتخاب تصویر از کتابخانه رسانه</h3>
                <button type="button" onclick="closeMediaPicker()" style="background:none; border:none; color:#fff; font-size:1.6rem; cursor:pointer; line-height:1;">&times;</button>
            </div>
            <div style="padding:14px 24px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; gap:12px; background:#f8fafc;">
                <input type="text" id="mediaPickerSearch" placeholder="جستجو در نام فایل..." oninput="filterMediaPickerItems(this.value)" style="padding:8px 14px; border:1px solid #cbd5e1; border-radius:8px; width:260px; font-size:0.88rem;">
                <label style="cursor:pointer; background:#EF5C16; color:#fff; padding:8px 16px; border-radius:8px; font-size:0.88rem; font-weight:700; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>آپلود فایل جدید</span>
                    <input type="file" id="mediaPickerFileInput" accept="image/*" style="display:none;" onchange="uploadFromMediaPicker(this)">
                </label>
            </div>
            <div id="mediaPickerGrid" style="padding:20px; overflow-y:auto; flex-grow:1; display:grid; grid-template-columns:repeat(auto-fill, minmax(130px, 1fr)); gap:14px; min-height:260px;">
                <div style="grid-column: 1 / -1; text-align:center; padding:40px; color:#64748b;">در حال بارگذاری فایل‌ها...</div>
            </div>
            <div style="padding:14px 24px; background:#f1f5f9; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeMediaPicker()" style="padding:8px 18px; border-radius:8px; border:1px solid #cbd5e1; background:#fff; cursor:pointer; font-weight:700;">انصراف</button>
                <button type="button" id="mediaPickerConfirmBtn" onclick="confirmMediaPickerSelection()" disabled style="padding:8px 22px; border-radius:8px; border:none; background:#011C39; color:#fff; cursor:pointer; font-weight:700;">انتخاب و درج</button>
            </div>
        </div>
    </div>

    <script>
        window._mediaPickerCallback = null;
        window._mediaPickerSelectedUrl = null;
        window._mediaPickerItems = [];

        window.openMediaPicker = function(callback) {
            window._mediaPickerCallback = callback;
            window._mediaPickerSelectedUrl = null;
            var modal = document.getElementById('globalMediaPickerModal');
            var confirmBtn = document.getElementById('mediaPickerConfirmBtn');
            if (confirmBtn) confirmBtn.disabled = true;
            if (modal) modal.style.display = 'flex';
            loadMediaPickerItems();
        };

        window.closeMediaPicker = function() {
            var modal = document.getElementById('globalMediaPickerModal');
            if (modal) modal.style.display = 'none';
            window._mediaPickerCallback = null;
            window._mediaPickerSelectedUrl = null;
        };

        function loadMediaPickerItems() {
            var grid = document.getElementById('mediaPickerGrid');
            if (!grid) return;
            grid.innerHTML = '<div style="grid-column: 1 / -1; text-align:center; padding:40px; color:#64748b;"><i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem; margin-bottom:8px;"></i><div>در حال بارگذاری...</div></div>';
            
            fetch("<?= url('admin/media?format=json') ?>")
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    if (res.success && res.media) {
                        window._mediaPickerItems = res.media;
                        renderMediaPickerGrid(res.media);
                    } else {
                        grid.innerHTML = '<div style="grid-column: 1 / -1; text-align:center; padding:40px; color:#ef4444;">خطا در دریافت لیست تصاویر.</div>';
                    }
                })
                .catch(function() {
                    grid.innerHTML = '<div style="grid-column: 1 / -1; text-align:center; padding:40px; color:#ef4444;">ارتباط با سرور برقرار نشد.</div>';
                });
        }

        function renderMediaPickerGrid(items) {
            var grid = document.getElementById('mediaPickerGrid');
            if (!grid) return;
            if (items.length === 0) {
                grid.innerHTML = '<div style="grid-column: 1 / -1; text-align:center; padding:40px; color:#64748b;">هیچ فایلی یافت نشد. می‌توانید فایل جدید آپلود کنید.</div>';
                return;
            }

            var html = '';
            var baseUrl = "<?= url() ?>";
            items.forEach(function(item) {
                var fileUrl = baseUrl + item.file_path.replace(/^\//, '');
                html += '<div class="media-pick-item" onclick="selectMediaPickerItem(\'' + fileUrl + '\', this)" style="border:2px solid #e2e8f0; border-radius:10px; overflow:hidden; cursor:pointer; background:#fff; text-align:center; transition:all 0.2s ease;">' +
                    '<div style="height:100px; display:flex; align-items:center; justify-content:center; background:#f8fafc; overflow:hidden;">' +
                        '<img src="' + fileUrl + '" alt="' + item.original_name + '" style="max-width:100%; max-height:100px; object-fit:contain;">' +
                    '</div>' +
                    '<div style="font-size:0.75rem; padding:6px 8px; color:#334155; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="' + item.original_name + '">' + item.original_name + '</div>' +
                '</div>';
            });
            grid.innerHTML = html;
        }

        function selectMediaPickerItem(url, el) {
            window._mediaPickerSelectedUrl = url;
            var all = document.querySelectorAll('.media-pick-item');
            all.forEach(function(item) {
                item.style.borderColor = '#e2e8f0';
                item.style.boxShadow = 'none';
            });
            el.style.borderColor = '#EF5C16';
            el.style.boxShadow = '0 0 0 3px rgba(239,92,22,0.2)';
            var confirmBtn = document.getElementById('mediaPickerConfirmBtn');
            if (confirmBtn) confirmBtn.disabled = false;
        }

        function confirmMediaPickerSelection() {
            if (window._mediaPickerCallback && window._mediaPickerSelectedUrl) {
                window._mediaPickerCallback(window._mediaPickerSelectedUrl);
            }
            closeMediaPicker();
        }

        function filterMediaPickerItems(query) {
            query = query.trim().toLowerCase();
            if (!query) {
                renderMediaPickerGrid(window._mediaPickerItems);
                return;
            }
            var filtered = window._mediaPickerItems.filter(function(i) {
                return (i.original_name && i.original_name.toLowerCase().indexOf(query) !== -1) ||
                       (i.filename && i.filename.toLowerCase().indexOf(query) !== -1);
            });
            renderMediaPickerGrid(filtered);
        }

        function uploadFromMediaPicker(input) {
            if (!input.files || !input.files[0]) return;
            var file = input.files[0];
            var formData = new FormData();
            formData.append('file', file);
            formData.append('ajax', '1');

            var grid = document.getElementById('mediaPickerGrid');
            if (grid) {
                grid.innerHTML = '<div style="grid-column: 1 / -1; text-align:center; padding:40px; color:#EF5C16;"><i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem; margin-bottom:8px;"></i><div>در حال آپلود فایل...</div></div>';
            }

            fetch("<?= url('admin/media/upload') ?>", {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    loadMediaPickerItems();
                } else {
                    alert(res.message || 'خطا در آپلود');
                    loadMediaPickerItems();
                }
            })
            .catch(function() {
                alert('خطا در برقراری ارتباط جهت آپلود');
                loadMediaPickerItems();
            });
        }
    </script>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
