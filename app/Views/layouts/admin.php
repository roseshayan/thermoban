<?php 
defined('ROOT_PATH') or exit('No direct script access allowed'); 
$isSuperAdmin = \App\Core\Auth::isSuperAdmin();
$modSettings = \App\Models\Setting::allKeyVal();
$shopActive = $isSuperAdmin || (($modSettings['module_shop'] ?? '1') === '1');
$blogActive = $isSuperAdmin || (($modSettings['module_blog'] ?? '1') === '1');
$ticketsActive = $isSuperAdmin || (($modSettings['module_tickets'] ?? '1') === '1');
$analyticsActive = $isSuperAdmin || (($modSettings['module_analytics'] ?? '1') === '1');
$gatewaysActive = $isSuperAdmin || (($modSettings['module_gateways'] ?? '1') === '1');
$smsActive = $isSuperAdmin || (($modSettings['module_sms'] ?? '1') === '1');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'پنل مدیریت اختصاصی ترموبان') ?></title>
    <link rel="icon" type="image/png" href="<?= site_favicon() ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/quill/quill.snow.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/quill-persian.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="admin-body">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <img src="<?= site_favicon() ?>" alt="<?= e(SITE_NAME) ?>">
            <div class="admin-brand-text">
                <h2>پنل مدیریت ترموبان</h2>
                <span>نمایندگی کد </span>
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
            <?php if ($shopActive): ?>
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
            <?php endif; ?>
            <?php if ($ticketsActive): ?>
                <a href="<?= url('admin/tickets') ?>" class="admin-nav-item <?= ($activeNav === 'tickets') ? 'active' : '' ?>">
                    <i class="fa-solid fa-headset" style="font-size: 1.1rem; width: 22px;"></i>
                    <span>تیکت‌های پشتیبانی</span>
                </a>
            <?php endif; ?>
            <a href="<?= url('admin/leads') ?>" class="admin-nav-item <?= ($activeNav === 'leads') ? 'active' : '' ?>">
                <i class="fa-solid fa-phone-volume" style="font-size: 1.1rem; width: 22px;"></i>
                <span>سفارشات سریع و تماس‌ها</span>
            </a>
            <?php if ($blogActive): ?>
                <a href="<?= url('admin/articles') ?>" class="admin-nav-item <?= ($activeNav === 'articles') ? 'active' : '' ?>">
                    <i class="fa-solid fa-newspaper" style="font-size: 1.1rem; width: 22px;"></i>
                    <span>مقالات آموزشی</span>
                </a>
            <?php endif; ?>
            <a href="<?= url('admin/media') ?>" class="admin-nav-item <?= ($activeNav === 'media') ? 'active' : '' ?>">
                <i class="fa-solid fa-photo-film" style="font-size: 1.1rem; width: 22px;"></i>
                <span>کتابخانه پرونده‌ها</span>
            </a>
            <a href="<?= url('admin/comments') ?>" class="admin-nav-item <?= ($activeNav === 'comments') ? 'active' : '' ?>">
                <i class="fa-solid fa-comments" style="font-size: 1.1rem; width: 22px;"></i>
                <span>نظرات و دیدگاه‌ها</span>
            </a>
            <?php if ($analyticsActive): ?>
                <a href="<?= url('admin/analytics') ?>" class="admin-nav-item <?= ($activeNav === 'analytics') ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-simple" style="font-size: 1.1rem; width: 22px;"></i>
                    <span>آمار بازدیدکنندگان</span>
                </a>
            <?php endif; ?>
            <?php if ($isSuperAdmin): ?>
                <a href="<?= url('admin/backups') ?>" class="admin-nav-item <?= ($activeNav === 'backups') ? 'active' : '' ?>">
                    <i class="fa-solid fa-database" style="font-size: 1.1rem; width: 22px;"></i>
                    <span>پشتیبان‌گیری و بازیابی</span>
                </a>
            <?php endif; ?>
            <a href="<?= url('admin/service-requests') ?>" class="admin-nav-item <?= ($activeNav === 'service-requests') ? 'active' : '' ?>">
                <i class="fa-solid fa-screwdriver-wrench" style="font-size: 1.1rem; width: 22px;"></i>
                <span>سفارشات خدمات</span>
            </a>
            <a href="<?= url('admin/schedule') ?>" class="admin-nav-item <?= ($activeNav === 'schedule') ? 'active' : '' ?>">
                <i class="fa-solid fa-calendar-days" style="font-size: 1.1rem; width: 22px;"></i>
                <span>تقویم و برنامه اعزام</span>
            </a>
            <a href="<?= url('admin/tariffs') ?>" class="admin-nav-item <?= ($activeNav === 'tariffs') ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice-dollar" style="font-size: 1.1rem; width: 22px;"></i>
                <span>نرخ مصوب اتحادیه</span>
            </a>
            <a href="<?= url('admin/menus') ?>" class="admin-nav-item <?= ($activeNav === 'menus') ? 'active' : '' ?>">
                <i class="fa-solid fa-bars-staggered" style="font-size: 1.1rem; width: 22px;"></i>
                <span>منوها و مگامنو</span>
            </a>
            <a href="<?= url('admin/faqs') ?>" class="admin-nav-item <?= ($activeNav === 'faqs') ? 'active' : '' ?>">
                <i class="fa-solid fa-circle-question" style="font-size: 1.1rem; width: 22px;"></i>
                <span>سوالات متداول (FAQ)</span>
            </a>
            <a href="<?= url('admin/testimonials') ?>" class="admin-nav-item <?= ($activeNav === 'testimonials') ? 'active' : '' ?>">
                <i class="fa-solid fa-star-half-stroke" style="font-size: 1.1rem; width: 22px;"></i>
                <span>رضایت و نظرات مشتریان</span>
            </a>
            <a href="<?= url('admin/branches') ?>" class="admin-nav-item <?= ($activeNav === 'branches') ? 'active' : '' ?>">
                <i class="fa-solid fa-store" style="font-size: 1.1rem; width: 22px;"></i>
                <span>شعب نمایندگی</span>
            </a>
            <a href="<?= url('admin/users') ?>" class="admin-nav-item <?= ($activeNav === 'users') ? 'active' : '' ?>">
                <i class="fa-solid fa-users" style="font-size: 1.1rem; width: 22px;"></i>
                <span>مدیریت کاربران</span>
            </a>
            <a href="<?= url('admin/addresses') ?>" class="admin-nav-item <?= ($activeNav === 'addresses') ? 'active' : '' ?>">
                <i class="fa-solid fa-location-dot" style="font-size: 1.1rem; width: 22px;"></i>
                <span>آدرس‌های کاربران</span>
            </a>
            <a href="<?= url('admin/seo') ?>" class="admin-nav-item <?= ($activeNav === 'seo') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-line" style="font-size: 1.1rem; width: 22px;"></i>
                <span>سئو (Rank Math Pro)</span>
            </a>
            <?php if ($isSuperAdmin && $gatewaysActive): ?>
                <a href="<?= url('admin/gateways') ?>" class="admin-nav-item <?= ($activeNav === 'gateways') ? 'active' : '' ?>">
                    <i class="fa-solid fa-credit-card" style="font-size: 1.1rem; width: 22px;"></i>
                    <span>درگاه‌های پرداخت</span>
                </a>
            <?php endif; ?>
            <?php if ($isSuperAdmin && $smsActive): ?>
                <a href="<?= url('admin/sms') ?>" class="admin-nav-item <?= ($activeNav === 'sms') ? 'active' : '' ?>">
                    <i class="fa-solid fa-comment-sms" style="font-size: 1.1rem; width: 22px;"></i>
                    <span>پنل پیامک (ملی‌پیامک)</span>
                </a>
            <?php endif; ?>
            <?php if ($isSuperAdmin): ?>
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
                <!-- Live Clock & Persian Shamsi Date Widget -->
                <div class="admin-live-clock-badge" id="adminLiveClockWidget" title="تاریخ و ساعت زنده سیستم">
                    <div class="live-clock-item time">
                        <i class="fa-solid fa-clock text-orange"></i>
                        <span id="liveClockTime"><?= fa_num(date('H:i:s')) ?></span>
                    </div>
                    <div class="live-clock-divider"></div>
                    <div class="live-clock-item date">
                        <i class="fa-solid fa-calendar-days text-orange"></i>
                        <span id="liveClockDate"><?= to_shamsi(null, 'l d F Y') ?></span>
                    </div>
                </div>

                <!-- Notifications Bell & Dropdown -->
                <?php 
                $adminAlerts = \App\Services\NotificationService::getAdminAlerts();
                ?>
                <div class="admin-notifications-dropdown" id="adminNotificationsWrap">
                    <button type="button" class="admin-notif-btn" id="adminNotifToggleBtn" aria-label="اعلان‌ها" title="اعلان‌ها و رویدادهای جدید">
                        <i class="fa-solid fa-bell"></i>
                        <?php if ($adminAlerts['total'] > 0): ?>
                            <span class="notif-badge-pill"><?= fa_num($adminAlerts['total']) ?></span>
                            <span class="notif-pulse"></span>
                        <?php endif; ?>
                    </button>

                    <div class="notif-dropdown-menu" id="adminNotifMenu">
                        <div class="notif-dropdown-header" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; background: #fafafa;">
                            <div style="font-weight: 800; color: #011C39; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-bell text-orange"></i>
                                <span>مرکز اعلان‌ها</span>
                                <span class="notif-header-counter" style="font-size: 0.72rem; background: #ffedd5; color: #ea580c; padding: 2px 8px; border-radius: 10px; font-weight: 800;"><?= $adminAlerts['total'] > 0 ? (fa_num($adminAlerts['total']) . ' جدید') : 'به‌روز' ?></span>
                            </div>
                            <button type="button" id="btnMarkAllAlertsRead" style="background: none; border: 1px solid #bae6fd; color: #0284c7; padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="علامت‌گذاری تمامی اعلان‌ها به عنوان خوانده شده">
                                <i class="fa-solid fa-check-double"></i>
                                <span>خواندن همه</span>
                            </button>
                        </div>

                        <div class="notif-list">
                            <!-- Tickets Alert -->
                            <a href="<?= url('admin/tickets') ?>" class="notif-item <?= $adminAlerts['open_tickets'] > 0 ? 'unread' : '' ?>">
                                <div class="notif-icon-box ticket">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title">تیکت‌های مشتریان</div>
                                    <div class="notif-desc">
                                        <?= $adminAlerts['open_tickets'] > 0 ? (fa_num($adminAlerts['open_tickets']) . ' تیکت جدید در انتظار بررسی') : 'تیکت جدیدی در انتظار نیست' ?>
                                    </div>
                                </div>
                                <?php if ($adminAlerts['open_tickets'] > 0): ?>
                                    <span class="notif-counter"><?= fa_num($adminAlerts['open_tickets']) ?></span>
                                <?php endif; ?>
                            </a>

                            <!-- Contact Messages & Leads Alert -->
                            <a href="<?= url('admin/leads') ?>" class="notif-item <?= $adminAlerts['unread_leads'] > 0 ? 'unread' : '' ?>">
                                <div class="notif-icon-box lead">
                                    <i class="fa-solid fa-phone-volume"></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title">تماس‌ها و پیام‌های جدید</div>
                                    <div class="notif-desc">
                                        <?= $adminAlerts['unread_leads'] > 0 ? (fa_num($adminAlerts['unread_leads']) . ' پیام تماس و سفارش سریع جدید') : 'پیام جدیدی وجود ندارد' ?>
                                    </div>
                                </div>
                                <?php if ($adminAlerts['unread_leads'] > 0): ?>
                                    <span class="notif-counter"><?= fa_num($adminAlerts['unread_leads']) ?></span>
                                <?php endif; ?>
                            </a>

                            <!-- Comments Alert -->
                            <a href="<?= url('admin/comments') ?>" class="notif-item <?= $adminAlerts['pending_comments'] > 0 ? 'unread' : '' ?>">
                                <div class="notif-icon-box comment">
                                    <i class="fa-solid fa-comments"></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title">دیدگاه‌ها و نظرات</div>
                                    <div class="notif-desc">
                                        <?= $adminAlerts['pending_comments'] > 0 ? (fa_num($adminAlerts['pending_comments']) . ' نظر جدید در انتظار تایید') : 'دیدگاه معلقی وجود ندارد' ?>
                                    </div>
                                </div>
                                <?php if ($adminAlerts['pending_comments'] > 0): ?>
                                    <span class="notif-counter"><?= fa_num($adminAlerts['pending_comments']) ?></span>
                                <?php endif; ?>
                            </a>
                        </div>

                        <div class="notif-dropdown-footer">
                            <a href="<?= url('admin') ?>"><i class="fa-solid fa-chart-pie" style="margin-left: 4px;"></i> مشاهده داشبورد آماری کل</a>
                        </div>
                    </div>
                </div>

                <a href="<?= url() ?>" target="_blank" class="btn-live-site">
                    <?= icon('eye', '', 16) ?>
                    <span>مشاهده سایت اصلی</span>
                </a>
                <?php 
                $currAdmin = \App\Core\Auth::user();
                $adminAvatar = avatar_url($currAdmin['avatar'] ?? null);
                ?>
                <a href="<?= url('admin/profile') ?>" class="admin-user-pill" style="text-decoration: none; color: inherit;" title="مشاهده و ویرایش مشخصات من">
                    <img src="<?= $adminAvatar ?>" alt="مدیر" class="admin-user-avatar" id="topbarAdminAvatar">
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
    <script src="<?= asset('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
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
    <script src="<?= asset('js/quill-persian.js') ?>"></script>

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
            items.forEach(function(item) {
                var fileUrl = item.url ? item.url : ("<?= url() ?>" + item.file_path.replace(/^\//, ''));
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

        // Live Clock & Notifications Dropdown Handler
        (function() {
            function updateAdminLiveClock() {
                var now = new Date();
                try {
                    var timeStr = now.toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                    var dateStr = now.toLocaleDateString('fa-IR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    var timeEl = document.getElementById('liveClockTime');
                    var dateEl = document.getElementById('liveClockDate');
                    if (timeEl) timeEl.innerText = timeStr;
                    if (dateEl) dateEl.innerText = dateStr;
                } catch (e) {
                    var h = String(now.getHours()).padStart(2, '0');
                    var m = String(now.getMinutes()).padStart(2, '0');
                    var s = String(now.getSeconds()).padStart(2, '0');
                    var timeEl = document.getElementById('liveClockTime');
                    if (timeEl) timeEl.innerText = h + ':' + m + ':' + s;
                }
            }
            setInterval(updateAdminLiveClock, 1000);
            updateAdminLiveClock();

            var notifBtn = document.getElementById('adminNotifToggleBtn');
            var notifMenu = document.getElementById('adminNotifMenu');
            var notifWrap = document.getElementById('adminNotificationsWrap');

            if (notifBtn && notifMenu) {
                notifBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notifMenu.classList.toggle('show');
                    notifBtn.classList.toggle('active');
                });

                document.addEventListener('click', function(e) {
                    if (notifWrap && !notifWrap.contains(e.target)) {
                        notifMenu.classList.remove('show');
                        notifBtn.classList.remove('active');
                    }
                });
            }

            var markReadBtn = document.getElementById('btnMarkAllAlertsRead');
            if (markReadBtn) {
                markReadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    markReadBtn.disabled = true;
                    markReadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال ثبت...';

                    var formData = new FormData();
                    formData.append('csrf_token', window.THERMOBAN_CONFIG ? window.THERMOBAN_CONFIG.csrfToken : '');

                    fetch("<?= url('admin/notifications/mark-read') ?>", {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        markReadBtn.innerHTML = '<i class="fa-solid fa-check" style="color:#16a34a;"></i> خوانده شد';
                        var badge = document.querySelector('.notif-badge-pill');
                        var pulse = document.querySelector('.notif-pulse');
                        if (badge) badge.style.display = 'none';
                        if (pulse) pulse.style.display = 'none';
                        var headerCounter = document.querySelector('.notif-header-counter');
                        if (headerCounter) {
                            headerCounter.innerText = 'به‌روز';
                            headerCounter.style.background = '#e2e8f0';
                            headerCounter.style.color = '#64748b';
                        }
                        document.querySelectorAll('.notif-item.unread').forEach(function(el) {
                            el.classList.remove('unread');
                        });
                        document.querySelectorAll('.notif-list .notif-counter').forEach(function(el) {
                            el.style.display = 'none';
                        });
                        setTimeout(function() { markReadBtn.disabled = false; }, 1000);
                    })
                    .catch(function() {
                        markReadBtn.disabled = false;
                        markReadBtn.innerHTML = '<i class="fa-solid fa-check-double"></i> خواندن همه';
                    });
                });
            }
        })();
    </script>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
