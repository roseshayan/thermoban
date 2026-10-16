<?php 
$shopEnabled = (\App\Models\Setting::get('module_shop', '0') === '1');
$blogEnabled = (\App\Models\Setting::get('module_blog', '1') === '1');
$cartItemsCount = count($_SESSION['cart'] ?? []);

$headerMenuItems = [];
try {
    if (class_exists('\App\Models\Menu')) {
        $headerMenuItems = \App\Models\Menu::getTree('header');
    }
} catch (\Throwable $e) {
    $headerMenuItems = [];
}

$emergencyPhone = site_emergency_phone();
$emergencyPhoneClean = clean_phone_for_tel($emergencyPhone);
?>
<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <a href="<?= url() ?>" class="header-logo" title="<?= e(site_name()) ?>">
            <img src="<?= site_logo() ?>" alt="<?= e(site_name()) ?>" class="logo-img">
        </a>

        <!-- Desktop Navigation Menu -->
        <nav class="main-nav" aria-label="منوی اصلی">
            <ul class="nav-list">
                <?php if (!empty($headerMenuItems)): ?>
                    <?php foreach ($headerMenuItems as $item): 
                        $hasChildren = !empty($item['children']);
                        $isMega = ($item['type'] ?? '') === 'megamenu';
                        $hasDropdown = ($item['type'] ?? '') === 'dropdown' || ($hasChildren && !$isMega);
                        
                        $itemUrl = $item['url'] ?? '#';
                        if ($itemUrl !== '#' && !preg_match('#^https?://#i', $itemUrl) && !str_starts_with($itemUrl, '/')) {
                            $itemUrl = url($itemUrl);
                        }
                        
                        $isActive = false;
                        $cur = $currentPage ?? 'home';
                        if (($item['url'] === '' || $item['url'] === '/') && $cur === 'home') {
                            $isActive = true;
                        } elseif (!empty($item['url']) && trim($item['url'], '/') === $cur) {
                            $isActive = true;
                        }
                    ?>
                        <?php if ($isMega): ?>
                            <li class="nav-item has-megamenu">
                                <a href="<?= e($itemUrl) ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
                                    <?php if (!empty($item['icon'])): ?><i class="<?= e($item['icon']) ?>" style="margin-left: 5px;"></i><?php endif; ?>
                                    <span><?= e($item['title']) ?></span>
                                    <?php if (!empty($item['badge'])): ?><span class="nav-badge-pill"><?= e($item['badge']) ?></span><?php endif; ?>
                                    <i class="fa-solid fa-chevron-down" style="font-size: 0.65rem; margin-right: 4px; opacity: 0.7;"></i>
                                </a>
                                <div class="nav-megamenu-panel">
                                    <div class="megamenu-grid">
                                        <?php 
                                        $columns = $item['megamenu_parsed']['columns'] ?? [];
                                        if (empty($columns) && !empty($item['children'])) {
                                            $columns = [['title' => $item['title'], 'links' => array_map(fn($c) => ['title' => $c['title'], 'url' => $c['url']], $item['children'])]];
                                        }
                                        foreach ($columns as $col): ?>
                                            <div class="megamenu-col">
                                                <h4 class="megamenu-col-title"><?= e($col['title'] ?? '') ?></h4>
                                                <ul class="megamenu-col-list">
                                                    <?php foreach ($col['links'] ?? [] as $sub): 
                                                        $subUrl = $sub['url'] ?? '#';
                                                        if ($subUrl !== '#' && !preg_match('#^https?://#i', $subUrl) && !str_starts_with($subUrl, '/')) {
                                                            $subUrl = url($subUrl);
                                                        }
                                                    ?>
                                                        <li><a href="<?= e($subUrl) ?>"><?= e($sub['title'] ?? '') ?></a></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </li>
                        <?php elseif ($hasDropdown): ?>
                            <li class="nav-item has-dropdown">
                                <a href="<?= e($itemUrl) ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
                                    <?php if (!empty($item['icon'])): ?><i class="<?= e($item['icon']) ?>" style="margin-left: 5px;"></i><?php endif; ?>
                                    <span><?= e($item['title']) ?></span>
                                    <?php if (!empty($item['badge'])): ?><span class="nav-badge-pill"><?= e($item['badge']) ?></span><?php endif; ?>
                                    <i class="fa-solid fa-chevron-down" style="font-size: 0.65rem; margin-right: 4px; opacity: 0.7;"></i>
                                </a>
                                <ul class="nav-dropdown-menu">
                                    <?php foreach ($item['children'] as $child): 
                                        $childUrl = $child['url'] ?? '#';
                                        if ($childUrl !== '#' && !preg_match('#^https?://#i', $childUrl) && !str_starts_with($childUrl, '/')) {
                                            $childUrl = url($childUrl);
                                        }
                                    ?>
                                        <li>
                                            <a href="<?= e($childUrl) ?>" <?= !empty($child['target']) ? 'target="' . e($child['target']) . '"' : '' ?>>
                                                <?php if (!empty($child['icon'])): ?><i class="<?= e($child['icon']) ?>" style="margin-left: 6px;"></i><?php endif; ?>
                                                <span><?= e($child['title']) ?></span>
                                                <?php if (!empty($child['badge'])): ?><span class="nav-badge-pill"><?= e($child['badge']) ?></span><?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a href="<?= e($itemUrl) ?>" class="nav-link <?= $isActive ? 'active' : '' ?>" <?= !empty($item['target']) ? 'target="' . e($item['target']) . '"' : '' ?>>
                                    <?php if (!empty($item['icon'])): ?><i class="<?= e($item['icon']) ?>" style="margin-left: 5px;"></i><?php endif; ?>
                                    <span><?= e($item['title']) ?></span>
                                    <?php if (!empty($item['badge'])): ?><span class="nav-badge-pill"><?= e($item['badge']) ?></span><?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default Fallback Navigation -->
                    <li class="nav-item"><a href="<?= url() ?>" class="nav-link <?= ($currentPage ?? 'home') === 'home' ? 'active' : '' ?>">صفحه اصلی</a></li>
                    <li class="nav-item"><a href="<?= url('services') ?>" class="nav-link <?= ($currentPage ?? '') === 'services' ? 'active' : '' ?>">خدمات ما</a></li>
                    <?php if ($shopEnabled): ?>
                    <li class="nav-item"><a href="<?= url('shop') ?>" class="nav-link <?= ($currentPage ?? '') === 'shop' ? 'active' : '' ?>">فروشگاه</a></li>
                    <?php endif; ?>
                    <?php if ($blogEnabled): ?>
                    <li class="nav-item"><a href="<?= url('blog') ?>" class="nav-link <?= ($currentPage ?? '') === 'blog' ? 'active' : '' ?>">مقالات</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="<?= url('about') ?>" class="nav-link <?= ($currentPage ?? '') === 'about' ? 'active' : '' ?>">درباره ما</a></li>
                    <li class="nav-item"><a href="<?= url('contact') ?>" class="nav-link <?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>">تماس با ما</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Header Actions: Cart, Profile & CTA -->
        <div class="header-action">
            <?php if ($shopEnabled): ?>
            <a href="<?= url('cart') ?>" class="header-icon-btn header-cart-btn" title="سبد خرید" aria-label="سبد خرید">
                <?= icon('cart', 'header-svg-icon', 22) ?>
                <?php if ($cartItemsCount > 0): ?>
                    <span class="cart-count-badge"><?= $cartItemsCount ?></span>
                <?php endif; ?>
            </a>
            <?php endif; ?>

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

            <a href="<?= url('service-order') ?>" class="btn btn-primary btn-header" style="display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-screwdriver-wrench" style="font-size: 0.85rem;"></i>
                <span>سفارش خدمات</span>
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
            <img src="<?= site_logo() ?>" alt="<?= e(site_name()) ?>" class="mobile-logo">
            <button class="mobile-drawer-close" id="mobileDrawerClose" aria-label="بستن منو">&times;</button>
        </div>
        <ul class="mobile-nav-list">
            <li><a href="<?= url() ?>" class="mobile-nav-link <?= ($currentPage ?? 'home') === 'home' ? 'active' : '' ?>"><?= icon('building', '', 18) ?> <span>صفحه اصلی</span></a></li>
            <li><a href="<?= url('service-order') ?>" class="mobile-nav-link" style="color: var(--color-primary); font-weight: 800;"><?= icon('wrench', '', 18) ?> <span>سفارش آنلاین خدمات</span></a></li>
            <li><a href="<?= url('service-order/tracking') ?>" class="mobile-nav-link"><?= icon('check', '', 18) ?> <span>پیگیری سفارشات</span></a></li>
            <li><a href="<?= url('services') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'services' ? 'active' : '' ?>"><?= icon('wrench', '', 18) ?> <span>نرخ مصوب خدمات</span></a></li>
            <?php if ($shopEnabled): ?>
            <li><a href="<?= url('shop') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'shop' ? 'active' : '' ?>"><?= icon('flame', '', 18) ?> <span>فروشگاه پکیج</span></a></li>
            <?php endif; ?>
            <?php if ($blogEnabled): ?>
            <li><a href="<?= url('blog') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'blog' ? 'active' : '' ?>"><?= icon('calendar', '', 18) ?> <span>مقالات آموزشی</span></a></li>
            <?php endif; ?>
            <?php if (\App\Core\Auth::isAdmin()): ?>
                <li><a href="<?= url('admin') ?>" class="mobile-nav-link" style="color: var(--color-primary); font-weight: 800;"><?= icon('gear', '', 18) ?> <span>پنل مدیریت CMS</span></a></li>
            <?php endif; ?>
            <li><a href="<?= \App\Core\Auth::check() ? url('profile') : url('login') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>"><?= icon('user', '', 18) ?> <span><?= \App\Core\Auth::check() ? 'پروفایل کاربری' : 'ورود / عضویت' ?></span></a></li>
            <?php if ($shopEnabled): ?>
            <li><a href="<?= url('cart') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'cart' ? 'active' : '' ?>"><?= icon('cart', '', 18) ?> <span>سبد خرید (<?= $cartItemsCount ?> کالا)</span></a></li>
            <?php endif; ?>
            <li><a href="<?= url('about') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'about' ? 'active' : '' ?>"><?= icon('shield', '', 18) ?> <span>درباره ما</span></a></li>
            <li><a href="<?= url('contact') ?>" class="mobile-nav-link <?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>"><?= icon('phone', '', 18) ?> <span>تماس با ما</span></a></li>
        </ul>
        <div class="mobile-drawer-footer">
            <a href="<?= url('service-order') ?>" class="btn btn-primary w-100">ثبت سفارش سریع خدمات</a>
            <?php if (!empty($emergencyPhone)): ?>
            <a href="tel:<?= e($emergencyPhoneClean) ?>" class="mobile-phone-btn">
                <span>تماس فوری: <?= e($emergencyPhone) ?></span>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>
</header>
