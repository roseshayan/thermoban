<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <a href="<?= url() ?>" class="header-logo" title="ترموبان">
            <img src="<?= asset('images/logo-thermoban.png') ?>" alt="ترموبان THERMOBAN" class="logo-img">
        </a>

        <!-- Desktop Navigation Menu -->
        <nav class="main-nav" aria-label="منوی اصلی">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= url() ?>" class="nav-link <?= ($currentPage ?? 'home') === 'home' ? 'active' : '' ?>">صفحه اصلی</a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('services') ?>" class="nav-link <?= ($currentPage ?? '') === 'services' ? 'active' : '' ?>">خدمات ما</a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('shop') ?>" class="nav-link <?= ($currentPage ?? '') === 'shop' ? 'active' : '' ?>">فروشگاه</a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('blog') ?>" class="nav-link <?= ($currentPage ?? '') === 'blog' ? 'active' : '' ?>">مقالات</a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('about') ?>" class="nav-link <?= ($currentPage ?? '') === 'about' ? 'active' : '' ?>">درباره ما</a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('contact') ?>" class="nav-link <?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>">تماس با ما</a>
                </li>
            </ul>
        </nav>

        <!-- Header Actions: Cart, Profile & CTA -->
        <div class="header-action">
            <a href="<?= url('cart') ?>" class="header-icon-btn header-cart-btn" title="سبد خرید" aria-label="سبد خرید">
                <?= icon('cart', 'header-svg-icon', 22) ?>
                <span class="cart-count-badge">۲</span>
            </a>

            <?php if (\App\Core\Auth::isAdmin()): ?>
                <a href="<?= url('admin') ?>" class="header-icon-btn header-admin-btn" title="پنل مدیریت CMS">
                    <?= icon('gear', 'header-svg-icon', 20) ?>
                    <span class="user-text-label" style="color: var(--color-primary); font-weight: 800;">پنل CMS</span>
                </a>
            <?php endif; ?>

            <a href="<?= \App\Core\Auth::check() ? url('profile') : url('login') ?>" class="header-icon-btn header-user-btn" title="حساب کاربری" aria-label="حساب کاربری">
                <?= icon('user', 'header-svg-icon', 20) ?>
                <span class="user-text-label"><?= \App\Core\Auth::check() ? 'پروفایل من' : 'ورود / عضویت' ?></span>
            </a>

            <a href="<?= url('#quick-order') ?>" class="btn btn-primary btn-header">
                ثبت سفارش سریع
            </a>

            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="منوی موبایل">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="mobile-drawer-header">
            <img src="<?= asset('images/logo-thermoban.png') ?>" alt="ترموبان" class="mobile-logo">
            <button class="mobile-drawer-close" id="mobileDrawerClose" aria-label="بستن منو">&times;</button>
        </div>
        <ul class="mobile-nav-list">
            <li><a href="<?= url() ?>" class="mobile-nav-link <?= ($currentPage ?? 'home') === 'home' ? 'active' : '' ?>"><?= icon('building', '', 18) ?> <span>صفحه اصلی</span></a></li>
            <li><a href="<?= url('services') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'services' ? 'active' : '' ?>"><?= icon('wrench', '', 18) ?> <span>خدمات ما</span></a></li>
            <li><a href="<?= url('shop') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'shop' ? 'active' : '' ?>"><?= icon('flame', '', 18) ?> <span>فروشگاه پکیج</span></a></li>
            <li><a href="<?= url('blog') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'blog' ? 'active' : '' ?>"><?= icon('calendar', '', 18) ?> <span>مقالات آموزشی</span></a></li>
            <?php if (\App\Core\Auth::isAdmin()): ?>
                <li><a href="<?= url('admin') ?>" class="mobile-nav-link" style="color: var(--color-primary); font-weight: 800;"><?= icon('gear', '', 18) ?> <span>پنل مدیریت CMS</span></a></li>
            <?php endif; ?>
            <li><a href="<?= \App\Core\Auth::check() ? url('profile') : url('login') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>"><?= icon('user', '', 18) ?> <span><?= \App\Core\Auth::check() ? 'پروفایل کاربری' : 'ورود / عضویت' ?></span></a></li>
            <li><a href="<?= url('cart') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'cart' ? 'active' : '' ?>"><?= icon('cart', '', 18) ?> <span>سبد خرید (۲ کالا)</span></a></li>
            <li><a href="<?= url('about') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'about' ? 'active' : '' ?>"><?= icon('shield', '', 18) ?> <span>درباره ما</span></a></li>
            <li><a href="<?= url('contact') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>"><?= icon('phone', '', 18) ?> <span>تماس با ما</span></a></li>
        </ul>
        <div class="mobile-drawer-footer">
            <a href="<?= url('#quick-order') ?>" class="btn btn-primary w-100">ثبت سفارش سریع</a>
            <a href="tel:<?= str_replace('-', '', SITE_PHONE_2) ?>" class="mobile-phone-btn">
                <span>تماس فوری: <?= SITE_PHONE_2 ?></span>
            </a>
        </div>
    </div>
    <div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>
</header>
