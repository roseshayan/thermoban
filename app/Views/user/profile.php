<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner profile-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <span class="current">حساب کاربری من</span>
            </nav>
            <h1 class="page-title">پروفایل کاربری <?= e($user['name']) ?></h1>
            <p class="page-subtitle">مدیریت آدرس‌ها و لوکیشن، پیگیری سفارشات پکیج، فاکتورهای رسمی و ثبت تیکت پشتیبانی و رزرو</p>
        </div>
    </div>
</section>

<!-- Profile Main Suite Section -->
<section class="profile-section">
    <div class="site-container">
        <div class="profile-layout-grid">
            
            <!-- Profile Sidebar Navigation -->
            <aside class="profile-sidebar">
                
                <!-- User Summary Card -->
                <div class="profile-user-badge">
                    <div class="user-avatar-box">
                        <img src="<?= asset('images/' . $user['avatar']) ?>" alt="<?= e($user['name']) ?>" class="profile-avatar-img">
                        <span class="verified-icon" title="حساب تایید شده">
                            <?= icon('check', 'verified-svg', 14) ?>
                        </span>
                    </div>
                    <div class="user-badge-info">
                        <h3 class="user-badge-name"><?= e($user['name']) ?></h3>
                        <span class="user-badge-phone" dir="ltr"><?= e($user['phone']) ?></span>
                        <div class="membership-pill">
                            <?= icon('shield', '', 14) ?>
                            <span>مشتری ویژه ترموبان</span>
                        </div>
                    </div>
                </div>

                <!-- Nav Tabs -->
                <nav class="profile-nav-menu">
                    <button class="profile-nav-link <?= ($activeTab === 'dashboard') ? 'active' : '' ?>" data-tab="tab-dashboard">
                        <?= icon('building', 'nav-icon', 18) ?>
                        <span>داشبورد و وضعیت حساب</span>
                    </button>
                    <button class="profile-nav-link <?= ($activeTab === 'info') ? 'active' : '' ?>" data-tab="tab-info">
                        <?= icon('user', 'nav-icon', 18) ?>
                        <span>ویرایش اطلاعات فردی</span>
                    </button>
                    <button class="profile-nav-link <?= ($activeTab === 'addresses') ? 'active' : '' ?>" data-tab="tab-addresses">
                        <?= icon('map-pin', 'nav-icon', 18) ?>
                        <span>آدرس‌ها و لوکیشن روی نقشه</span>
                    </button>
                    <button class="profile-nav-link <?= ($activeTab === 'orders') ? 'active' : '' ?>" data-tab="tab-orders">
                        <?= icon('invoice', 'nav-icon', 18) ?>
                        <span>سفارش‌ها و فاکتورهای رسمی</span>
                        <span class="count-badge"><?= count($orders) ?></span>
                    </button>
                    <button class="profile-nav-link <?= ($activeTab === 'likes') ? 'active' : '' ?>" data-tab="tab-likes">
                        <?= icon('heart', 'nav-icon', 18) ?>
                        <span>کالاهای لایک شده</span>
                        <span class="count-badge"><?= count($likedProducts) ?></span>
                    </button>
                    <button class="profile-nav-link <?= ($activeTab === 'tickets') ? 'active' : '' ?>" data-tab="tab-tickets">
                        <?= icon('ticket', 'nav-icon', 18) ?>
                        <span>تیکت‌های پشتیبانی و رزرو</span>
                        <span class="count-badge"><?= count($tickets) ?></span>
                    </button>
                    <a href="<?= url() ?>" class="profile-nav-link logout-link">
                        <?= icon('arrow-left', 'nav-icon', 18) ?>
                        <span>بازگشت به سایت</span>
                    </a>
                </nav>

                <!-- Bale Emergency Support in Sidebar -->
                <div class="profile-bale-widget">
                    <div class="widget-bale-header">
                        <img src="<?= asset('images/badge-shield.png') ?>" alt="بله" class="bale-small-icon">
                        <strong>پشتیبان اختصاصی شما در بله</strong>
                    </div>
                    <p>جهت استعلام آنلاین قطعات یا مشاوره سریع قبل از اعزام تکنسین کلیک کنید.</p>
                    <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener" class="btn btn-bale-sm w-100">
                        پیام‌رسان بله
                    </a>
                </div>

            </aside>

            <!-- Profile Content Area -->
            <main class="profile-content-main">
                
                <!-- Alert Messages Container -->
                <div id="profileGlobalAlert" class="profile-alert" style="display: none;"></div>

                <!-- 1. DASHBOARD TAB -->
                <div class="profile-tab-panel <?= ($activeTab === 'dashboard') ? 'active' : '' ?>" id="tab-dashboard">
                    <h2 class="panel-main-title">داشبورد حساب کاربری</h2>

                    <!-- KPI Cards Grid -->
                    <div class="kpi-cards-grid">
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-blue">
                                <?= icon('truck', '', 24) ?>
                            </div>
                            <div class="kpi-info">
                                <span class="kpi-num"><?= count($orders) ?></span>
                                <span class="kpi-label">سفارش و سرویس ثبت شده</span>
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-green">
                                <?= icon('shield', '', 24) ?>
                            </div>
                            <div class="kpi-info">
                                <span class="kpi-num">۲۴ ماه</span>
                                <span class="kpi-label">گارانتی فعال طلایی</span>
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-orange">
                                <?= icon('ticket', '', 24) ?>
                            </div>
                            <div class="kpi-info">
                                <span class="kpi-num"><?= count($tickets) ?></span>
                                <span class="kpi-label">تیکت پشتیبانی</span>
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-icon-box kpi-purple">
                                <?= icon('map-pin', '', 24) ?>
                            </div>
                            <div class="kpi-info">
                                <span class="kpi-num"><?= count($addresses) ?></span>
                                <span class="kpi-label">آدرس ثبت شده در تهران</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders Section -->
                    <div class="dashboard-block">
                        <div class="block-header">
                            <h3 class="block-title">آخرین سفارشات و خدمات</h3>
                            <button class="btn-link-action" onclick="switchProfileTab('tab-orders')">مشاهده همه سفارش‌ها</button>
                        </div>
                        <div class="orders-table-wrapper">
                            <table class="profile-table">
                                <thead>
                                    <tr>
                                        <th>شماره سفارش</th>
                                        <th>شرح خدمت / کالا</th>
                                        <th>تاریخ</th>
                                        <th>مبلغ (تومان)</th>
                                        <th>وضعیت</th>
                                        <th>فاکتور</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $ord): ?>
                                        <tr>
                                            <td><strong><?= e($ord['id']) ?></strong></td>
                                            <td><?= e($ord['items'][0]['title']) ?></td>
                                            <td><?= e($ord['date']) ?></td>
                                            <td><?= e($ord['total_price']) ?></td>
                                            <td><span class="status-badge <?= $ord['status_class'] ?>"><?= e($ord['status']) ?></span></td>
                                            <td>
                                                <a href="<?= url('invoice/' . $ord['id']) ?>" class="btn-table-action" target="_blank" title="مشاهده فاکتور">
                                                    <?= icon('print', '', 16) ?>
                                                    <span>فاکتور</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Support Tickets Section -->
                    <div class="dashboard-block">
                        <div class="block-header">
                            <h3 class="block-title">آخرین تیکت‌های پشتیبانی و اعزام</h3>
                            <button class="btn-link-action" onclick="switchProfileTab('tab-tickets')">ثبت تیکت جدید</button>
                        </div>
                        <div class="dashboard-tickets-list">
                            <?php foreach ($tickets as $t): ?>
                                <div class="ticket-summary-item">
                                    <div class="ticket-sum-top">
                                        <span class="ticket-id"><?= e($t['id']) ?></span>
                                        <span class="status-badge <?= $t['status_class'] ?>"><?= e($t['status']) ?></span>
                                    </div>
                                    <h4 class="ticket-sum-title"><?= e($t['subject']) ?></h4>
                                    <p class="ticket-sum-reply"><?= e($t['last_reply']) ?></p>
                                    <div class="ticket-sum-meta">
                                        <span>دسته‌بندی: <?= e($t['category']) ?></span>
                                        <span>تاریخ: <?= e($t['date']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

                <!-- 2. EDIT PERSONAL INFO TAB -->
                <div class="profile-tab-panel <?= ($activeTab === 'info') ? 'active' : '' ?>" id="tab-info">
                    <h2 class="panel-main-title">ویرایش مشخصات فردی و حساب کاربری</h2>
                    
                    <form action="<?= url('api/user/update-info') ?>" method="POST" class="profile-form js-validate-form" id="userInfoForm">
                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                        
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="u_name">نام و نام خانوادگی <span class="required">*</span></label>
                                <input type="text" id="u_name" name="name" class="form-control" value="<?= e($user['name']) ?>" required data-msg="لطفاً نام کامل خود را وارد کنید">
                            </div>
                            <div class="form-group">
                                <label for="u_phone">شماره همراه <span class="required">*</span></label>
                                <input type="tel" id="u_phone" name="phone" class="form-control" value="<?= e($user['phone']) ?>" dir="ltr" required data-type="phone" data-msg="شماره موبایل معتبر ۱۱ رقمی وارد نمایید">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="u_national_id">کد ملی (جهت صدور فاکتور رسمی و ضمانت‌نامه)</label>
                                <input type="text" id="u_national_id" name="national_id" class="form-control" value="<?= e($user['national_id']) ?>" dir="ltr" data-type="national_id" data-msg="کد ملی ۱۰ رقمی معتبر وارد نمایید">
                            </div>
                            <div class="form-group">
                                <label for="u_email">پست الکترونیک (ایمیل)</label>
                                <input type="email" id="u_email" name="email" class="form-control" value="<?= e($user['email']) ?>" dir="ltr" data-type="email" data-msg="فرمت ایمیل نامعتبر است">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="u_city">شهر سکونت</label>
                                <input type="text" id="u_city" name="city" class="form-control" value="تهران" readonly>
                            </div>
                            <div class="form-group">
                                <label for="u_password">تغییر رمز عبور (در صورت تمایل)</label>
                                <input type="password" id="u_password" name="password" class="form-control" placeholder="حداقل ۶ کاراکتر...">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-save-info">
                                <?= icon('check', '', 18) ?>
                                <span>ذخیره تغییرات مشخصات</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 3. ADDRESSES & MAP LOCATION TAB -->
                <div class="profile-tab-panel <?= ($activeTab === 'addresses') ? 'active' : '' ?>" id="tab-addresses">
                    <div class="panel-header-flex">
                        <div>
                            <h2 class="panel-main-title">مدیریت آدرس‌ها و موقعیت مکانی با نقشه</h2>
                            <p class="panel-subtitle">آدرس دقیق و لوکیشن خود را مشخص کنید تا تکنسین در کوتاه‌ترین زمان به محل شما اعزام شود.</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-add-address" id="btnOpenAddressModal">
                            <?= icon('plus', '', 18) ?>
                            <span>افزودن آدرس و لوکیشن جدید</span>
                        </button>
                    </div>

                    <!-- Addresses Cards List -->
                    <div class="addresses-cards-grid">
                        <?php foreach ($addresses as $addr): ?>
                            <div class="address-card <?= $addr['is_default'] ? 'default-address' : '' ?>">
                                <div class="address-card-header">
                                    <div class="address-title-wrap">
                                        <?= icon('map-pin', 'addr-pin', 20) ?>
                                        <h4 class="address-title"><?= e($addr['title']) ?></h4>
                                    </div>
                                    <?php if ($addr['is_default']): ?>
                                        <span class="default-badge">آدرس پیش‌فرض</span>
                                    <?php endif; ?>
                                </div>
                                
                                <p class="address-text"><?= e($addr['address']) ?></p>
                                
                                <div class="address-meta-row">
                                    <span><?= icon('building', '', 16) ?> <?= e($addr['district']) ?></span>
                                    <span><?= icon('phone', '', 16) ?> <?= e($addr['phone']) ?></span>
                                    <span>کدپستی: <?= e($addr['postal_code']) ?></span>
                                </div>

                                <div class="address-coords-chip">
                                    <span>مختصات ثبت‌شده نقشه:</span>
                                    <span dir="ltr"><?= $addr['lat'] ?>, <?= $addr['lng'] ?></span>
                                </div>

                                <div class="address-card-footer">
                                    <button type="button" class="btn-card-action text-orange" onclick="alert('امکان ویرایش آدرس فعال است.')">
                                        <?= icon('edit', '', 16) ?>
                                        <span>ویرایش</span>
                                    </button>
                                    <button type="button" class="btn-card-action text-red" onclick="alert('آدرس مورد نظر حذف گردید.')">
                                        <?= icon('trash', '', 16) ?>
                                        <span>حذف</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Real Interactive Map Picker Section (Leaflet & OpenStreetMap) -->
                    <div class="map-picker-container" id="addressMapSection">
                        <div class="map-picker-header">
                            <div>
                                <h3 class="map-picker-title">انتخاب موقعیت دقیق روی نقشه تعاملی ایران و تهران</h3>
                                <p class="map-picker-desc">با کلیک روی نقشه یا جابجایی پین نشانگر، آدرس دقیق خود را برای حضور تکنسین مشخص کنید.</p>
                            </div>
                            <div class="map-city-quicklinks">
                                <span class="quicklink-label">انتخاب سریع:</span>
                                <button type="button" class="btn-city-pill active" onclick="jumpMapTo(35.6892, 51.3890, 12, 'تهران')">تهران</button>
                                <button type="button" class="btn-city-pill" onclick="jumpMapTo(35.8400, 50.9391, 12, 'کرج')">کرج</button>
                                <button type="button" class="btn-city-pill" onclick="jumpMapTo(32.6546, 51.6680, 12, 'اصفهان')">اصفهان</button>
                                <button type="button" class="btn-city-pill" onclick="jumpMapTo(36.2972, 59.6067, 12, 'مشهد')">مشهد</button>
                                <button type="button" class="btn-city-pill" onclick="jumpMapTo(29.5918, 52.5837, 12, 'شیراز')">شیراز</button>
                                <button type="button" class="btn-city-pill" onclick="jumpMapTo(38.0800, 46.2919, 12, 'تبریز')">تبریز</button>
                                <button type="button" class="btn-city-pill" onclick="jumpMapTo(32.4279, 53.6880, 5, 'کل ایران')">نمای کل ایران</button>
                            </div>
                        </div>

                        <!-- Real Leaflet Map Container -->
                        <div class="real-map-wrapper">
                            <div id="realIranMap" class="real-iran-map" style="height: 400px; width: 100%; border-radius: 14px; position: relative; z-index: 1;"></div>
                            
                            <div class="map-bottom-controls">
                                <div class="map-coords-badge">
                                    <?= icon('map-pin', '', 16) ?>
                                    <span>موقعیت جغرافیایی: </span>
                                    <strong id="coordsDisplay">۳۵.۶۸۹۲° شمالی، ۵۱.۳۸۹۰° شرقی</strong>
                                </div>
                                <button type="button" class="btn-locate-me" id="btnLocateMe" onclick="getUserCurrentLocation()">
                                    <?= icon('location-arrow', '', 14) ?>
                                    <span>یافتن لوکیشن من</span>
                                </button>
                            </div>
                        </div>

                        <!-- New Address Form Attached to Map -->
                        <form action="<?= url('api/user/add-address') ?>" method="POST" class="map-address-form js-validate-form" id="newAddressForm">
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                            <input type="hidden" name="lat" id="inputLat" value="35.6892">
                            <input type="hidden" name="lng" id="inputLng" value="51.3890">

                            <div class="form-row-2">
                                <div class="form-group">
                                    <label for="addr_title">عنوان آدرس (مثال: منزل، شرکت، ویلا) <span class="required">*</span></label>
                                    <input type="text" id="addr_title" name="title" class="form-control" placeholder="منزل..." required data-msg="عنوان آدرس الزامی است">
                                </div>
                                <div class="form-group">
                                    <label for="addr_district">منطقه شهرداری تهران <span class="required">*</span></label>
                                    <select id="addr_district" name="district" class="form-control" required data-msg="لطفاً منطقه شهرداری را انتخاب کنید">
                                        <option value="">انتخاب منطقه...</option>
                                        <?php for ($d = 1; $d <= 22; $d++): ?>
                                            <option value="منطقه <?= $d ?>">منطقه <?= $d ?> تهران</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="addr_full">آدرس کامل پستی (خیابان اصلی، کوچه، پلاک، واحد) <span class="required">*</span></label>
                                <textarea id="addr_full" name="address" rows="3" class="form-control" placeholder="تهران، خیابان..." required data-msg="متن کامل آدرس الزامی است"></textarea>
                            </div>

                            <div class="form-row-2">
                                <div class="form-group">
                                    <label for="addr_postal">کد پستی ۱۰ رقمی <span class="required">*</span></label>
                                    <input type="text" id="addr_postal" name="postal_code" class="form-control" placeholder="۱۹۹۷۸۵۴۳۲۱" dir="ltr" required data-type="postal_code" data-msg="کدپستی ۱۰ رقمی معتبر وارد نمایید">
                                </div>
                                <div class="form-group">
                                    <label for="addr_phone">شماره همراه تحویل‌گیرنده <span class="required">*</span></label>
                                    <input type="tel" id="addr_phone" name="phone" class="form-control" value="<?= e($user['phone']) ?>" dir="ltr" required data-type="phone" data-msg="شماره موبایل الزامی است">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-save-address">
                                <?= icon('check', '', 18) ?>
                                <span>ثبت این آدرس و موقعیت روی نقشه</span>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- 4. ORDERS & INVOICES TAB -->
                <div class="profile-tab-panel <?= ($activeTab === 'orders') ? 'active' : '' ?>" id="tab-orders">
                    <h2 class="panel-main-title">سفارش‌ها، خدمات و فاکتورهای رسمی</h2>
                    
                    <div class="orders-cards-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-full-card">
                                <div class="order-card-header">
                                    <div class="order-header-info">
                                        <div class="order-id-badge">
                                            <span>شماره پیگیری:</span>
                                            <strong><?= e($order['id']) ?></strong>
                                        </div>
                                        <span class="order-date"><?= icon('calendar', '', 14) ?> ثبت: <?= e($order['date']) ?></span>
                                    </div>
                                    <span class="status-badge <?= $order['status_class'] ?>"><?= e($order['status']) ?></span>
                                </div>

                                <div class="order-items-list">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="order-item-row">
                                            <div class="item-thumb-box">
                                                <img src="<?= asset('images/' . $item['image']) ?>" alt="<?= e($item['title']) ?>">
                                            </div>
                                            <div class="item-title-box">
                                                <h4 class="item-name"><?= e($item['title']) ?></h4>
                                                <span class="item-qty">تعداد: <?= $item['quantity'] ?> عدد</span>
                                                <span class="item-guarantee-tag"><?= icon('shield', '', 14) ?> <?= e($order['guarantee']) ?></span>
                                            </div>
                                            <div class="item-price-box">
                                                <span class="price-val"><?= e($item['price']) ?></span>
                                                <span class="price-curr">تومان</span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="order-card-footer">
                                    <div class="order-meta-info">
                                        <span><strong>تکنسین اعزامی:</strong> <?= e($order['technician']) ?></span>
                                        <span><strong>روش پرداخت:</strong> <?= e($order['payment_method']) ?></span>
                                    </div>
                                    <div class="order-footer-actions">
                                        <a href="<?= url('invoice/' . $order['id']) ?>" class="btn btn-outline btn-invoice" target="_blank">
                                            <?= icon('print', '', 16) ?>
                                            <span>مشاهده و چاپ فاکتور رسمی</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 5. LIKED PRODUCTS TAB -->
                <div class="profile-tab-panel <?= ($activeTab === 'likes') ? 'active' : '' ?>" id="tab-likes">
                    <h2 class="panel-main-title">کالاهای لایک شده و نشان‌شده</h2>
                    
                    <div class="liked-products-grid">
                        <?php foreach ($likedProducts as $lp): ?>
                            <div class="liked-product-card">
                                <div class="liked-card-thumb">
                                    <a href="<?= url('product/' . $lp['id']) ?>">
                                        <img src="<?= asset('images/' . $lp['image']) ?>" alt="<?= e($lp['title']) ?>">
                                    </a>
                                </div>
                                <div class="liked-card-body">
                                    <span class="liked-brand"><?= e($lp['brand']) ?></span>
                                    <h4 class="liked-title">
                                        <a href="<?= url('product/' . $lp['id']) ?>"><?= e($lp['title']) ?></a>
                                    </h4>
                                    <div class="liked-price-box">
                                        <?php if (!empty($lp['old_price'])): ?>
                                            <span class="old-price"><?= e($lp['old_price']) ?> تومان</span>
                                        <?php endif; ?>
                                        <span class="final-price"><?= e($lp['price']) ?> تومان</span>
                                    </div>
                                </div>
                                <div class="liked-card-actions">
                                    <a href="<?= url('product/' . $lp['id']) ?>" class="btn btn-primary btn-sm w-100">
                                        <?= icon('cart', '', 16) ?>
                                        <span>مشاهده و خرید</span>
                                    </a>
                                    <button type="button" class="btn btn-outline btn-sm btn-remove-like" onclick="this.closest('.liked-product-card').remove();">
                                        <?= icon('trash', '', 16) ?>
                                        <span>حذف از علاقه‌مندی</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 6. SUPPORT TICKETS TAB (Professional Ticketing System) -->
                <div class="profile-tab-panel <?= ($activeTab === 'tickets') ? 'active' : '' ?>" id="tab-tickets">
                    <div class="panel-header-flex">
                        <div>
                            <h2 class="panel-main-title">سامانه جامع پشتیبانی، رزرو تکنسین و گارانتی</h2>
                            <p class="panel-subtitle">ارتباط مستقیم با کارشناسان فنی نمایندگی کد ۷۲۷۳ ایران رادیاتور و بوتان</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-new-ticket-glow" id="btnOpenNewTicket">
                            <?= icon('plus', '', 16) ?>
                            <span>ثبت تیکت جدید</span>
                        </button>
                    </div>

                    <!-- New Ticket Form (Collapsible Modern Card) -->
                    <div class="new-ticket-box-pro" id="newTicketBox" style="display: none;">
                        <div class="box-header-ribbon">
                            <div class="ribbon-title">
                                <?= icon('ticket', '', 18) ?>
                                <span>فرم ثبت تیکت پشتیبانی و درخواست اعزام کارشناس</span>
                            </div>
                            <span class="ribbon-badge">پاسخگویی سریع زیر ۳۰ دقیقه</span>
                        </div>
                        <form action="<?= url('api/user/create-ticket') ?>" method="POST" class="ticket-form js-validate-form" id="createTicketForm">
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                            <div class="form-row-2">
                                <div class="form-group">
                                    <label for="tk_subject">موضوع درخواست <span class="required">*</span></label>
                                    <input type="text" id="tk_subject" name="subject" class="form-control" placeholder="مثال: درخواست اعزام تکنسین جهت سرویس پکیج L24FF..." required data-msg="موضوع تیکت الزامی است">
                                </div>
                                <div class="form-group">
                                    <label for="tk_category">دپارتمان / نوع خدمت <span class="required">*</span></label>
                                    <select id="tk_category" name="category" class="form-control" required data-msg="انتخاب دسته‌بندی الزامی است">
                                        <option value="">انتخاب دپارتمان...</option>
                                        <option value="اعزام تکنسین فوری">اعزام تکنسین فوری در محل (تهران)</option>
                                        <option value="عیب‌یابی و رفع ارور">عیب‌یابی، رفع کد ارور و نشت آب</option>
                                        <option value="گارانتی و سرویس سالیانه">گارانتی طلایی و سرویس دوره‌ای ۲۴ ماهه</option>
                                        <option value="پیگیری سفارش خرید">پیگیری فاکتور خرید و زمان ارسال</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row-2">
                                <div class="form-group">
                                    <label for="tk_priority">سطح اولویت <span class="required">*</span></label>
                                    <select id="tk_priority" name="priority" class="form-control">
                                        <option value="عادی">عادی (پاسخگویی حداکثر ۲ ساعته)</option>
                                        <option value="مهم">مهم (بررسی در اولویت نخست)</option>
                                        <option value="اورژانسی">اورژانسی (بدون آب گرم / افت فشار شدید)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tk_brand">برند و مدل دستگاه پکیج</label>
                                    <input type="text" id="tk_brand" name="device_model" class="form-control" placeholder="مثال: ایران رادیاتور M24FF یا بوتان Perla Pro">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tk_message">شرح کامل مشکل یا درخواست <span class="required">*</span></label>
                                <textarea id="tk_message" name="message" rows="4" class="form-control" placeholder="شرح مشکل دستگاه، کد ارور چشمک‌زن روی نمایشگر یا زمان پیشنهادی برای حضور سرویسکار را وارد کنید..." required data-msg="شرح پیام الزامی است"></textarea>
                            </div>

                            <div class="form-actions-flex">
                                <button type="submit" class="btn btn-primary">
                                    <?= icon('paper-plane', '', 16) ?>
                                    <span>ثبت و ارسال رسمی تیکت</span>
                                </button>
                                <button type="button" class="btn btn-outline" onclick="document.getElementById('newTicketBox').style.display='none';">
                                    انصراف
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tickets Control Bar (Filter tabs & Search) -->
                    <div class="tickets-control-bar">
                        <div class="ticket-filter-pills">
                            <button type="button" class="btn-ticket-filter active" onclick="filterTicketsByStatus('all', this)">
                                <span>همه گفتگوها</span>
                                <span class="badge"><?= count($tickets) ?></span>
                            </button>
                            <button type="button" class="btn-ticket-filter" onclick="filterTicketsByStatus('پاسخ داده شده', this)">
                                <span>پاسخ داده شده</span>
                            </button>
                            <button type="button" class="btn-ticket-filter" onclick="filterTicketsByStatus('در حال بررسی', this)">
                                <span>در حال بررسی</span>
                            </button>
                            <button type="button" class="btn-ticket-filter" onclick="filterTicketsByStatus('بسته', this)">
                                <span>بسته شده</span>
                            </button>
                        </div>
                        <div class="ticket-search-box">
                            <?= icon('search', 'search-icon', 16) ?>
                            <input type="text" placeholder="جستجو در موضوع یا شناسه تیکت..." onkeyup="searchTicketsList(this)">
                        </div>
                    </div>

                    <!-- Tickets List -->
                    <div class="tickets-full-list">
                        <?php if (empty($tickets)): ?>
                            <div class="empty-state-box">
                                <?= icon('ticket', 'empty-icon', 48) ?>
                                <h3>هنوز هیچ تیکتی ثبت نکرده‌اید</h3>
                                <p>هرگونه پرسش فنی در خصوص پکیج، نیاز به اعزام کارشناس یا گارانتی دارید از دکمه ثبت تیکت استفاده نمایید.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <div class="ticket-card-thread-pro" data-status="<?= e($ticket['status']) ?>">
                                    
                                    <!-- Ticket Header Bar -->
                                    <div class="ticket-pro-header">
                                        <div class="ticket-pro-left">
                                            <span class="ticket-code-pill">
                                                <?= icon('ticket', '', 14) ?>
                                                <span><?= e($ticket['id']) ?></span>
                                            </span>
                                            <span class="ticket-dept-pill"><?= e($ticket['category']) ?></span>
                                            <span class="ticket-date-pill">
                                                <?= icon('clock', '', 13) ?>
                                                <span><?= e($ticket['date']) ?></span>
                                            </span>
                                        </div>
                                        <div class="ticket-pro-right">
                                            <span class="ticket-status-pill <?= $ticket['status_class'] ?>">
                                                <span class="status-pulse-dot"></span>
                                                <span><?= e($ticket['status']) ?></span>
                                            </span>
                                        </div>
                                    </div>

                                    <h3 class="ticket-pro-subject"><?= e($ticket['subject']) ?></h3>

                                    <!-- Messages Thread Stream -->
                                    <div class="ticket-messages-stream">
                                        <?php foreach ($ticket['messages'] as $msg): ?>
                                            <?php $isSupport = ($msg['sender'] !== 'user'); ?>
                                            <div class="stream-bubble <?= $isSupport ? 'bubble-support' : 'bubble-user' ?>">
                                                <div class="bubble-avatar">
                                                    <?php if ($isSupport): ?>
                                                        <div class="avatar-support">
                                                            <?= icon('headset', '', 18) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="avatar-user">
                                                            <?= icon('user', '', 18) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="bubble-content-wrap">
                                                    <div class="bubble-meta">
                                                        <span class="bubble-author">
                                                            <?= e($msg['sender_name']) ?>
                                                            <?php if ($isSupport): ?>
                                                                <span class="verified-agent-tag" title="پاسخ رسمی نمایندگی ۷۲۷۳">
                                                                    <?= icon('circle-check', '', 14) ?>
                                                                    <span>کارشناس مجاز</span>
                                                                </span>
                                                            <?php endif; ?>
                                                        </span>
                                                        <span class="bubble-time"><?= e($msg['time']) ?></span>
                                                    </div>
                                                    <div class="bubble-text">
                                                        <?= nl2br(e($msg['text'])) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Quick Reply Interactive Composer -->
                                    <div class="ticket-reply-composer">
                                        <div class="composer-chips">
                                            <span class="chip-label">پاسخ‌های پیشنهادی:</span>
                                            <button type="button" class="reply-chip" onclick="this.closest('.ticket-reply-composer').querySelector('textarea').value = 'سلام، لطفاً هرچه سریع‌تر تکنسین را اعزام نمایید.'">اعزام سریع تکنسین</button>
                                            <button type="button" class="reply-chip" onclick="this.closest('.ticket-reply-composer').querySelector('textarea').value = 'عکس ارور دستگاه را در محل بررسی فرمایید.'">بررسی ارور در محل</button>
                                            <button type="button" class="reply-chip" onclick="this.closest('.ticket-reply-composer').querySelector('textarea').value = 'مشکل با راهنمایی شما حل شد، با تشکر.'">مشکل برطرف شد</button>
                                        </div>
                                        <div class="composer-input-row">
                                            <textarea rows="2" class="form-control composer-textarea" placeholder="پاسخ خود را در مورد این گفتگو بنویسید..."></textarea>
                                            <button type="button" class="btn btn-primary btn-send-reply" onclick="submitTicketQuickReply(this, '<?= e($ticket['id']) ?>')">
                                                <?= icon('paper-plane', '', 16) ?>
                                                <span>ارسال پاسخ</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>

            </main>

        </div>
    </div>
</section>
