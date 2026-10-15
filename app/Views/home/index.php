<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Hero Section -->
<section class="hero-section" id="hero">
    <div class="hero-bg-overlay"></div>
    <div class="hero-container">
        
        <!-- Right in RTL: Hero Content & Trust Badges -->
        <div class="hero-content">
            <h1 class="hero-headline">
                مرجع <span class="text-orange">تخصصی</span> نصب،<br>
                تعمیر و فروش پکیج
            </h1>

            <div class="hero-subtext">
                <p>با بیش از ۱۰ سال تجربه تخصصی در ۲۲ منطقه تهران</p>
                <p><span class="text-orange font-bold">خدمات سریع</span>، مطمئن و با ضمانت معتبر</p>
            </div>

            <div class="hero-divider"></div>

            <!-- 3 Trust Badges Row -->
            <div class="hero-trust-badges">
                <?php foreach ($heroStats as $stat): ?>
                    <div class="trust-badge-item">
                        <div class="trust-icon-box">
                            <img src="<?= asset('images/' . $stat['icon']) ?>" alt="<?= e($stat['title']) ?>" class="trust-icon">
                        </div>
                        <div class="trust-info">
                            <span class="trust-title"><?= e($stat['title']) ?></span>
                            <span class="trust-desc"><?= e($stat['subtitle']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Left in RTL: Quick Order Floating Form -->
        <div class="hero-form-wrapper" id="quick-order">
            <div class="quick-order-card">
                <div class="card-header-box">
                    <h2 class="card-title">ثبت سفارش سریع</h2>
                    <p class="card-subtitle">در کمتر از ۳۰ ثانیه درخواست خود را ثبت کنید</p>
                </div>

                <form id="quickOrderForm" class="quick-order-form" action="<?= url('api/quick-order') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                    <div class="form-group">
                        <input type="text" name="fullname" id="fullname" class="form-control" placeholder="نام و نام خانوادگی" required>
                    </div>

                    <div class="form-group">
                        <input type="tel" name="phone" id="phone" class="form-control" placeholder="شماره تماس" required dir="ltr">
                    </div>

                    <div class="form-group">
                        <div class="select-wrapper">
                            <select name="service" id="service" class="form-control" required>
                                <option value="" disabled selected>نوع خدمت خود را انتخاب کنید</option>
                                <option value="تعمیر پکیج">تعمیر پکیج</option>
                                <option value="نصب پکیج">نصب پکیج</option>
                                <option value="سرویس دوره ای">سرویس دوره‌ای پکیج</option>
                                <option value="راه اندازی و تنظیم">راه‌اندازی و تنظیم پکیج</option>
                                <option value="رسوب زدایی و اسید شویی">رسوب‌زدایی و اسیدشویی</option>
                                <option value="خرید و فروش پکیج">خرید و فروش پکیج</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="select-wrapper">
                            <select name="district" id="district" class="form-control" required>
                                <option value="" disabled selected>منطقه خود را انتخاب کنید</option>
                                <?php for ($i = 1; $i <= 22; $i++): ?>
                                    <option value="منطقه <?= $i ?>">منطقه <?= $i ?> تهران</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit-order" id="submitOrderBtn">
                        <span class="btn-text">ثبت سفارش</span>
                        <span class="btn-spinner" style="display:none;"></span>
                    </button>

                    <p class="form-footnote">کارشناسان ما در سریع ترین زمان ممکن با شما تماس خواهند گرفت</p>
                </form>

                <!-- Floating Green Check Badge -->
                <div class="floating-check-badge">
                    <img src="<?= asset('images/check-badge.png') ?>" alt="تاییدیه تخصص" class="badge-icon">
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Services Section -->
<section class="services-section" id="services">
    <div class="site-container">
        
        <div class="section-heading text-center">
            <h2 class="section-title">خدمات ما</h2>
            <div class="title-underline"></div>
        </div>

        <div class="services-grid">
            
            <!-- Tehran Coverage Card (Far Right in RTL) -->
            <div class="service-card tehran-coverage-card">
                <div class="tehran-card-content">
                    <span class="coverage-label">پوشش خدمات در</span>
                    <h3 class="coverage-title text-orange">۲۲ منطقه تهران</h3>
                    
                    <div class="tehran-map-box">
                        <img src="<?= asset('images/tehran-map.png') ?>" alt="نقشه ۲۲ منطقه تهران" class="tehran-map-img">
                        <div class="map-pin-pulse">
                            <div class="pulse-ring"></div>
                            <svg class="pin-icon" viewBox="0 0 24 24" fill="#EF5C16">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                                <circle cx="12" cy="9" r="2.5" fill="#FFFFFF"/>
                            </svg>
                        </div>
                    </div>

                    <a href="#coverage-modal" class="btn btn-primary btn-coverage" id="openCoverageModal">
                        مشاهده مناطق تحت پوشش
                    </a>
                </div>
            </div>

            <!-- 5 Dynamic Service Cards -->
            <?php foreach ($services as $srv): ?>
                <div class="service-card" data-service-id="<?= e($srv['id']) ?>">
                    <div class="service-icon-wrapper">
                        <img src="<?= asset('images/' . $srv['icon']) ?>" alt="<?= e($srv['title']) ?>" class="service-icon icon-normal">
                        <img src="<?= asset('images/' . $srv['iconWhite']) ?>" alt="<?= e($srv['title']) ?>" class="service-icon icon-hover">
                    </div>
                    
                    <h3 class="service-title"><?= e($srv['title']) ?></h3>
                    <p class="service-desc"><?= e($srv['desc']) ?></p>
                    
                    <button type="button" class="btn btn-outline service-btn" onclick="openServiceModal('<?= e($srv['id']) ?>', '<?= e($srv['title']) ?>')">
                        جزئیات بیشتر
                    </button>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- Iran Radiator Authorized Dealer & Statistics Banner -->
<section class="license-stats-section" id="guarantee">
    <div class="site-container">
        <div class="license-banner">
            
            <!-- Right in RTL: Content & Counters -->
            <div class="license-content-box">
                <h2 class="license-title">نمایندگی دارای کد مجاز نصب ایران رادیاتور</h2>
                <p class="license-desc">
                    ترموبان با کسب کد مجاز نصب از ایران رادیاتور، خدمات خود را مطابق با استانداردهای شرکت و با گارانتی رسمی ارائه می‌دهد.
                </p>

                <div class="stats-counter-row">
                    <?php foreach ($stats as $st): ?>
                        <div class="stat-counter-item">
                            <span class="stat-number"><?= e($st['value']) ?></span>
                            <span class="stat-label"><?= e($st['label']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Left in RTL: Golden Shield / Medal -->
            <div class="license-medal-box">
                <img src="<?= asset('images/gold-shield-medal.png') ?>" alt="کد مجاز نصب ایران رادیاتور" class="gold-medal-img">
            </div>

        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="brands-section" id="brands">
    <div class="site-container">
        <div class="section-heading text-center">
            <h2 class="section-title">برندهای تحت پوشش</h2>
            <div class="title-underline"></div>
        </div>

        <div class="brands-grid">
            <?php foreach ($brands as $brand): ?>
                <div class="brand-card <?= $brand['badge'] ? 'has-badge' : '' ?>">
                    <div class="brand-logo-box">
                        <img src="<?= asset('images/' . $brand['logo']) ?>" alt="<?= e($brand['name']) ?>" class="brand-logo-img">
                    </div>
                    <?php if ($brand['badge']): ?>
                        <div class="brand-pill-badge"><?= e($brand['badge']) ?></div>
                    <?php endif; ?>
                    <h3 class="brand-name"><?= e($brand['name']) ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Shop & Articles Section -->
<section class="showcase-section" id="shop">
    <div class="site-container">
        <div class="showcase-grid">
            
            <!-- Right in RTL: Product Showcase (فروشگاه) -->
            <div class="showcase-column products-column">
                <div class="column-heading">
                    <h2 class="column-title">فروشگاه</h2>
                    <p class="column-subtitle">خرید پکیج دیواری با گارانتی معتبر و نصب رایگان</p>
                </div>

                <div class="products-slider-wrapper">
                    <button class="slider-arrow slider-arrow-prev" id="sliderPrev" aria-label="محصول قبلی">&#10095;</button>
                    
                    <div class="products-slider" id="productsSlider">
                        <?php foreach ($products as $prod): ?>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="<?= asset('images/' . $prod['image']) ?>" alt="<?= e($prod['title']) ?>" class="product-img">
                                </div>
                                <h3 class="product-title"><?= e($prod['title']) ?></h3>
                                <span class="product-model"><?= e($prod['model']) ?></span>
                                <div class="product-price-box">
                                    <span class="product-price"><?= e($prod['price']) ?></span>
                                    <span class="price-currency">تومان</span>
                                </div>
                                <a href="<?= url('product/' . $prod['id']) ?>" class="btn btn-primary btn-buy">
                                    مشاهده و خرید
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="slider-arrow slider-arrow-next" id="sliderNext" aria-label="محصول بعدی">&#10094;</button>
                </div>

                <div class="column-footer-action">
                    <a href="<?= url('shop') ?>" class="btn btn-outline btn-pill">مشاهده همه محصولات</a>
                </div>
            </div>

            <!-- Left in RTL: Blog / Articles (آخرین مقالات) -->
            <div class="showcase-column articles-column" id="blog">
                <div class="column-heading">
                    <h2 class="column-title">آخرین مقالات</h2>
                    <p class="column-subtitle">دانشنامه و راهنمای نگهداری پکیج</p>
                </div>

                <div class="articles-list">
                    <?php foreach ($articles as $art): ?>
                        <article class="article-card">
                            <div class="article-text-side">
                                <h3 class="article-title">
                                    <a href="<?= url('blog/' . ($art['slug'] ?? 'error-e81-iran-radiator-fix')) ?>"><?= e($art['title']) ?></a>
                                </h3>
                                <p class="article-excerpt"><?= e($art['excerpt']) ?></p>
                                <div class="article-meta">
                                    <span class="article-date"><?= e($art['date']) ?></span>
                                </div>
                            </div>
                            <div class="article-thumb-side">
                                <img src="<?= asset('images/' . $art['image']) ?>" alt="<?= e($art['title']) ?>" class="article-thumb-img">
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="column-footer-action">
                    <a href="<?= url('blog') ?>" class="btn btn-outline btn-pill">مشاهده همه مقالات</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Interactive Tehran Coverage Modal -->
<div class="site-modal" id="coverageModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">مناطق تحت پوشش خدمات ترموبان در تهران</h3>
                <button class="modal-close" id="coverageModalClose">&times;</button>
            </div>
            <div class="modal-body">
                <p class="modal-description">
                    تکنسین‌های مجرب ترموبان در کوتاه‌ترین زمان ممکن (کمتر از ۱ ساعت) در تمام محله‌های مناطق ۲۲‌گانه تهران در محل شما حاضر خواهند بود.
                </p>
                <div class="districts-tags-grid">
                    <?php 
                    $districtNames = [
                        1 => 'نیاوران، تجریش، فرمانیه، ولنجک، زعفرانیه',
                        2 => 'سعادت آباد، شهرک غرب، گیشا، ستارخان',
                        3 => 'قلهک، پاسداران، میرداماد، ونک، ظفر',
                        4 => 'تهرانپارس، حکیمیه، نارمک، شمس‌آباد',
                        5 => 'پونک، جنت‌آباد، صادقیه، باغ فیض',
                        6 => 'یوسف آباد، امیرآباد، فاطمی، میدان ولیعصر',
                        7 => 'سهروردی، عباس‌آباد، بهار، نظام‌آباد',
                        8 => 'تهرانپارس غربی، مجیدیه، هفت‌حوض',
                        9 => 'مهرآباد جنوبی، استاد معین، سی متری جی',
                        10 => 'بریانک، سلسبیل، کارون، دامپزشکی',
                        11 => 'جمهوری، منیریه، خیابان وحدت اسلامی',
                        12 => 'بهارستان، بازار تهران، خیابان فردوسی',
                        13 => 'پیروزی، نیروی هوایی، دهقان',
                        14 => 'نبرد، محلاتی، چهارصددستگاه',
                        15 => 'افسریه، مسعودیه، مشیریه، خاوران',
                        16 => 'نازی‌آباد، یاخچی‌آباد، جوادیه',
                        17 => 'یافت‌آباد، ابوذر، آذری، بلورسازی',
                        18 => 'تولیددارو، شادآباد، خلیج فارس',
                        19 => 'خانی‌آباد نو، نعمت‌آباد، عبدل‌آباد',
                        20 => 'شهر ری، دولت‌آباد، جوانمرد قصاب',
                        21 => 'تهرانسر، چیتگر، شهرک استقلال',
                        22 => 'دریاچه چیتگر، دهکده المپیک، وردآورد'
                    ];
                    foreach ($districtNames as $num => $desc): ?>
                        <div class="district-badge-card">
                            <span class="badge-district-num">منطقه <?= $num ?></span>
                            <span class="badge-district-desc"><?= $desc ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#quick-order" class="btn btn-primary" onclick="document.getElementById('coverageModal').classList.remove('active');">
                    ثبت فوری سفارش اعزام تکنسین
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Service Detail Modal -->
<div class="site-modal" id="serviceModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="serviceModalTitle">جزئیات خدمات</h3>
                <button class="modal-close" id="serviceModalClose">&times;</button>
            </div>
            <div class="modal-body" id="serviceModalBody">
                <p>توضیحات خدمات تخصصی ترموبان...</p>
            </div>
            <div class="modal-footer">
                <a href="#quick-order" class="btn btn-primary" onclick="document.getElementById('serviceModal').classList.remove('active');">
                    ثبت درخواست این خدمت
                </a>
            </div>
        </div>
    </div>
</div>
