<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner shop-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <span class="current">فروشگاه پکیج و رادیاتور</span>
            </nav>
            <h1 class="page-title">فروشگاه تخصصی پکیج شوفاژ دیواری با قیمت مصوب شرکتی</h1>
            <p class="page-subtitle">نمایندگی رسمی ایران رادیاتور و بوتان با ۲۴ ماه گارانتی طلایی، حمل رایگان و نصب مجاز در تهران</p>
        </div>
    </div>
</section>

<!-- Shop Catalog Section -->
<section class="shop-catalog-section">
    <div class="site-container">
        
        <!-- Trust Badges Bar -->
        <div class="shop-trust-badges">
            <div class="trust-badge-item">
                <div class="trust-badge-icon"><?= icon('truck', 'text-orange', 24) ?></div>
                <div class="trust-badge-text">
                    <strong>ارسال فوری در تهران</strong>
                    <span>تحویل ۲ ساعته درب منزل</span>
                </div>
            </div>
            <div class="trust-badge-item">
                <div class="trust-badge-icon"><?= icon('wrench', 'text-orange', 24) ?></div>
                <div class="trust-badge-text">
                    <strong>نصب رایگان شرکتی</strong>
                    <span>توسط نصاب دارای کد مجاز</span>
                </div>
            </div>
            <div class="trust-badge-item">
                <div class="trust-badge-icon"><?= icon('shield', 'text-orange', 24) ?></div>
                <div class="trust-badge-text">
                    <strong>۲۴ ماه ضمانت طلایی</strong>
                    <span>گارانتی معتبر شرکتی</span>
                </div>
            </div>
            <div class="trust-badge-item">
                <div class="trust-badge-icon"><?= icon('credit-card', 'text-orange', 24) ?></div>
                <div class="trust-badge-text">
                    <strong>پرداخت در محل</strong>
                    <span>پس از رویت و تایید اصالت کالا</span>
                </div>
            </div>
        </div>

        <div class="shop-layout-grid">
            
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar">
                
                <form action="<?= url('shop') ?>" method="GET" id="shopFilterForm">
                    
                    <!-- Brand Filter -->
                    <div class="filter-widget">
                        <h4 class="filter-widget-title">برند سازنده</h4>
                        <div class="filter-options">
                            <?php foreach ($brands as $b): ?>
                                <label class="filter-radio-label">
                                    <input type="radio" name="brand" value="<?= e($b['slug']) ?>" 
                                           <?= ($selectedBrand === $b['slug']) ? 'checked' : '' ?> 
                                           onchange="this.form.submit()">
                                    <span class="radio-custom"></span>
                                    <span class="label-name"><?= e($b['name']) ?></span>
                                    <span class="label-count"><?= $b['count'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Capacity Filter -->
                    <div class="filter-widget">
                        <h4 class="filter-widget-title">ظرفیت پکیج (متراژ)</h4>
                        <div class="filter-options">
                            <label class="filter-radio-label">
                                <input type="radio" name="capacity" value="0" <?= ($selectedCapacity == 0) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span class="radio-custom"></span>
                                <span class="label-name">همه ظرفیت‌ها</span>
                            </label>
                            <label class="filter-radio-label">
                                <input type="radio" name="capacity" value="24000" <?= ($selectedCapacity == 24000) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span class="radio-custom"></span>
                                <span class="label-name">۲۴,۰۰۰ (تا ۱۴۰ متر)</span>
                            </label>
                            <label class="filter-radio-label">
                                <input type="radio" name="capacity" value="28000" <?= ($selectedCapacity == 28000) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span class="radio-custom"></span>
                                <span class="label-name">۲۸,۰۰۰ (تا ۲۱۰ متر)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Flue & Exchanger Notice -->
                    <div class="filter-widget">
                        <h4 class="filter-widget-title">نوع مبدل و فن</h4>
                        <ul class="filter-static-list">
                            <li><?= icon('check', 'text-green', 14) ?> <span>تمامی مدل‌ها دو مبدل حرارتی می‌باشند</span></li>
                            <li><?= icon('check', 'text-green', 14) ?> <span>مجهز به فن دودکش ایمن FF</span></li>
                            <li><?= icon('check', 'text-green', 14) ?> <span>هیدرولیک تمام برنجی ضد رسوب</span></li>
                        </ul>
                    </div>

                    <!-- Call for Consultation -->
                    <div class="sidebar-widget widget-emergency-cta">
                        <h4 class="widget-cta-title">نیاز به راهنمایی در انتخاب مدل دارید؟</h4>
                        <p class="widget-cta-desc">کارشناسان فروش ترموبان متراژ و شرایط لوله‌کشی شما را بررسی کرده و بهترین مدل را پیشنهاد می‌دهند.</p>
                        <a href="tel:02177428596" class="btn btn-primary w-100 widget-cta-btn">
                            <?= icon('phone', '', 18) ?>
                            <span>مشاوره رایگان خرید: ۰۲۱-۷۷۴۲۸۵۹۶</span>
                        </a>
                    </div>

                </form>

            </aside>

            <!-- Main Catalog Content -->
            <main class="shop-main-content">
                
                <!-- Sort & Header Bar -->
                <div class="shop-toolbar">
                    <div class="toolbar-info">
                        نمایش <strong><?= $totalCount ?></strong> محصول با ضمانت شرکتی
                    </div>
                    <div class="toolbar-sort">
                        <span class="sort-label">مرتب‌سازی:</span>
                        <div class="sort-links">
                            <a href="<?= url('shop?sort=newest' . ($selectedBrand ? '&brand=' . $selectedBrand : '')) ?>" class="sort-link <?= ($selectedSort === 'newest') ? 'active' : '' ?>">جدیدترین</a>
                            <a href="<?= url('shop?sort=cheap' . ($selectedBrand ? '&brand=' . $selectedBrand : '')) ?>" class="sort-link <?= ($selectedSort === 'cheap') ? 'active' : '' ?>">ارزان‌ترین</a>
                            <a href="<?= url('shop?sort=expensive' . ($selectedBrand ? '&brand=' . $selectedBrand : '')) ?>" class="sort-link <?= ($selectedSort === 'expensive') ? 'active' : '' ?>">گران‌ترین</a>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <?php if (empty($products)): ?>
                    <div class="no-results-box">
                        <div class="no-results-icon"><?= icon('cart', '', 48) ?></div>
                        <h3>محصولی مطابق فیلترهای انتخابی یافت نشد</h3>
                        <p>لطفاً فیلترها را حذف کنید یا با بخش فروش تماس بگیرید.</p>
                        <a href="<?= url('shop') ?>" class="btn btn-primary">مشاهده همه محصولات</a>
                    </div>
                <?php else: ?>
                    <div class="products-catalog-grid">
                        <?php foreach ($products as $p): ?>
                            <div class="product-catalog-card">
                                
                                <div class="product-card-top">
                                    <div class="badges-wrapper">
                                        <?php if (!empty($p['discount'])): ?>
                                            <span class="badge-discount"><?= e($p['discount']) ?> تخفیف</span>
                                        <?php endif; ?>
                                        <span class="badge-free-install">نصب رایگان</span>
                                    </div>
                                    <div class="product-card-brand">
                                        <img src="<?= asset('images/' . $p['brand_logo']) ?>" alt="<?= e($p['brand']) ?>">
                                    </div>
                                </div>

                                <div class="product-card-thumb">
                                    <a href="<?= url('product/' . $p['id']) ?>">
                                        <img src="<?= asset('images/' . $p['image']) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                                    </a>
                                </div>

                                <div class="product-card-body">
                                    <div class="product-category"><?= e($p['category']) ?> • <?= e($p['brand']) ?></div>
                                    <h3 class="product-title">
                                        <a href="<?= url('product/' . $p['id']) ?>"><?= e($p['title']) ?></a>
                                    </h3>
                                    <div class="product-model-name"><?= e($p['model']) ?></div>

                                    <!-- Key Specs Chips -->
                                    <div class="product-specs-chips">
                                        <span class="spec-chip"><?= icon('flame', '', 12) ?> <?= number_format($p['capacity']) ?></span>
                                        <span class="spec-chip"><?= icon('ruler', '', 12) ?> <?= e($p['area']) ?></span>
                                        <span class="spec-chip"><?= icon('gear', '', 12) ?> <?= e($p['heat_exchanger']) ?></span>
                                    </div>

                                    <div class="product-guarantee-note">
                                        <?= icon('shield', '', 14) ?>
                                        <span><?= e($p['warranty']) ?></span>
                                    </div>
                                </div>

                                <div class="product-card-footer">
                                    <div class="product-price-box">
                                        <?php if (!empty($p['old_price'])): ?>
                                            <div class="price-old"><?= e($p['old_price']) ?> تومان</div>
                                        <?php endif; ?>
                                        <div class="price-current">
                                            <span class="price-num"><?= e($p['price']) ?></span>
                                            <span class="price-unit">تومان</span>
                                        </div>
                                    </div>
                                    <div class="product-actions">
                                        <a href="<?= url('product/' . $p['id']) ?>" class="btn btn-primary btn-product-buy">
                                            مشاهده و خرید
                                        </a>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </main>

        </div>

    </div>
</section>
