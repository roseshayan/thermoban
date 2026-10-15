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
                    <a href="<?= url() ?>" class="nav-link active">صفحه اصلی</a>
                </li>
                <li class="nav-item">
                    <a href="#services" class="nav-link">خدمات ما</a>
                </li>
                <li class="nav-item">
                    <a href="#shop" class="nav-link">فروشگاه</a>
                </li>
                <li class="nav-item">
                    <a href="#blog" class="nav-link">مقالات</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">تماس با ما</a>
                </li>
                <li class="nav-item">
                    <a href="#about" class="nav-link">درباره ما</a>
                </li>
            </ul>
        </nav>

        <!-- CTA Action Button -->
        <div class="header-action">
            <a href="#quick-order" class="btn btn-primary btn-header">
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
            <li><a href="<?= url() ?>" class="mobile-nav-link active">صفحه اصلی</a></li>
            <li><a href="#services" class="mobile-nav-link">خدمات ما</a></li>
            <li><a href="#shop" class="mobile-nav-link">فروشگاه</a></li>
            <li><a href="#blog" class="mobile-nav-link">مقالات</a></li>
            <li><a href="#contact" class="mobile-nav-link">تماس با ما</a></li>
            <li><a href="#about" class="mobile-nav-link">درباره ما</a></li>
        </ul>
        <div class="mobile-drawer-footer">
            <a href="#quick-order" class="btn btn-primary w-100">ثبت سفارش سریع</a>
            <a href="tel:<?= str_replace('-', '', SITE_PHONE_2) ?>" class="mobile-phone-btn">
                <span>تماس فوری: <?= SITE_PHONE_2 ?></span>
            </a>
        </div>
    </div>
    <div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>
</header>
