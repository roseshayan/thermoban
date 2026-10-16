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
                    
                    <button type="button" class="btn btn-outline service-btn" onclick="openServiceBooking('<?= e($srv['title']) ?>')">
                        درخواست این خدمت
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
                <?php
                $bLogo = $brand['logo'] ?? 'brand-iranradiator-logo.png';
                $logoUrl = (str_starts_with($bLogo, 'http') || str_starts_with($bLogo, '/uploads') || str_starts_with($bLogo, 'uploads/'))
                    ? (str_starts_with($bLogo, 'http') ? $bLogo : url($bLogo))
                    : asset('images/' . $bLogo);
                ?>
                <div class="brand-card <?= !empty($brand['badge']) ? 'has-badge' : '' ?>">
                    <div class="brand-logo-box">
                        <img src="<?= $logoUrl ?>" alt="<?= e($brand['name']) ?>" class="brand-logo-img">
                    </div>
                    <?php if (!empty($brand['badge'])): ?>
                        <div class="brand-pill-badge"><?= e($brand['badge']) ?></div>
                    <?php endif; ?>
                    <h3 class="brand-name"><?= e($brand['name']) ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Shop / Service Tariffs & Articles Section -->
<?php if (!empty($shopEnabled) || !empty($blogEnabled)): ?>
<section class="showcase-section" id="showcase">
    <div class="site-container">
        <div class="showcase-grid">
            
            <?php if (!empty($shopEnabled)): ?>
            <!-- Right in RTL: Product Showcase (فروشگاه) -->
            <div class="showcase-column products-column" id="shop">
                <div class="column-heading">
                    <h2 class="column-title">فروشگاه تجهیزات</h2>
                    <p class="column-subtitle">خرید پکیج دیواری با گارانتی معتبر و نصب رایگان</p>
                </div>

                <div class="products-slider-wrapper">
                    <button class="slider-arrow slider-arrow-prev" id="sliderPrev" aria-label="محصول قبلی">&#10095;</button>
                    
                    <div class="products-slider" id="productsSlider">
                        <?php foreach ($products as $prod): ?>
                            <?php 
                            $pImg = $prod['image'] ?? 'package-bita-24.png';
                            $pImgUrl = (str_starts_with($pImg, 'http') || str_starts_with($pImg, '/uploads') || str_starts_with($pImg, 'uploads/'))
                                ? (str_starts_with($pImg, 'http') ? $pImg : url($pImg))
                                : asset('images/' . $pImg);
                            ?>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="<?= $pImgUrl ?>" alt="<?= e($prod['title']) ?>" class="product-img">
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
            <?php else: ?>
            <!-- Right in RTL: Service Tariffs List (تعرفه و نرخ مصوب خدمات پکیج) -->
            <div class="showcase-column service-tariff-showcase" id="tariffs">
                <div class="column-heading">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <h2 class="column-title">نرخ مصوب خدمات و تعمیرات</h2>
                            <p class="column-subtitle">شفافیت ۱۰۰٪ هزینه‌ها بر اساس تعرفه رسمی اتحادیه تأسیسات</p>
                        </div>
                        <span style="background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; padding: 4px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 800;">نرخ مصوب ۱۴۰۵</span>
                    </div>
                </div>

                <ul class="tariff-list">
                    <li class="tariff-item">
                        <div class="tariff-name-wrap">
                            <i class="fa-solid fa-screwdriver-wrench" style="color: #EF5C16;"></i>
                            <div>
                                <strong>عیب‌یابی، کارشناسی و تست نشتی در محل</strong>
                                <span>(در صورت توافق و انجام تعمیرات کاملاً رایگان)</span>
                            </div>
                        </div>
                        <span class="tariff-price-badge">۲۵۰,۰۰۰ تومان</span>
                    </li>
                    <li class="tariff-item">
                        <div class="tariff-name-wrap">
                            <i class="fa-solid fa-arrows-rotate" style="color: #EF5C16;"></i>
                            <div>
                                <strong>سرویس کامل دوره‌ای، تنظیم فشار و هواگیری</strong>
                                <span>شستشوی مدار، تنظیم باد مخزن، تمیزکاری محفظه</span>
                            </div>
                        </div>
                        <span class="tariff-price-badge">۳۸۰,۰۰۰ تومان</span>
                    </li>
                    <li class="tariff-item">
                        <div class="tariff-name-wrap">
                            <i class="fa-solid fa-fire-burner" style="color: #EF5C16;"></i>
                            <div>
                                <strong>اسیدشویی مبدل اصلی و ثانویه با پمپ مخصوص</strong>
                                <span>رسوب‌زدایی بدون آسیب با دیسکلر استاندارد ۳۳٪</span>
                            </div>
                        </div>
                        <span class="tariff-price-badge">۴۵۰,۰۰۰ تومان</span>
                    </li>
                    <li class="tariff-item">
                        <div class="tariff-name-wrap">
                            <i class="fa-solid fa-microchip" style="color: #EF5C16;"></i>
                            <div>
                                <strong>تعمیر و عیب‌یابی تخصصی برد الکترونیک</strong>
                                <span>رفع ارورهای دیجیتال همراه با ضمانت ۶ ماهه</span>
                            </div>
                        </div>
                        <span class="tariff-price-badge">از ۵۵۰,۰۰۰ تومان</span>
                    </li>
                    <li class="tariff-item">
                        <div class="tariff-name-wrap">
                            <i class="fa-solid fa-shield-halved" style="color: #EF5C16;"></i>
                            <div>
                                <strong>نصب استاندارد و راه‌اندازی اولیه پکیج دیواری</strong>
                                <span>همراه با مهر گارانتی رسمی شرکت ایران رادیاتور</span>
                            </div>
                        </div>
                        <span class="tariff-price-badge">طبق مصوبه شرکت</span>
                    </li>
                </ul>

                <div class="column-footer-action" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <button style="color: #fff;" type="button" class="btn btn-primary btn-pill" onclick="openServiceBooking('تعمیر و سرویس پکیج')">
                        <i class="fa-solid fa-calendar-check" style="margin-left: 6px;"></i> ثبت آنلاین درخواست اعزام تعمیرکار
                    </button>
                    <a href="tel:02122145678" class="btn btn-outline btn-pill">
                        <i class="fa-solid fa-phone" style="margin-left: 6px;"></i> مشاوره تلفنی رایگان
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($blogEnabled)): ?>
            <!-- Left in RTL: Blog / Articles (آخرین مقالات) -->
            <div class="showcase-column articles-column" id="blog">
                <div class="column-heading">
                    <h2 class="column-title">دانشنامه و مقالات تخصصی</h2>
                    <p class="column-subtitle">راهنمای رفع کدهای خطا، نگهداری و عیب‌یابی انواع پکیج</p>
                </div>

                <div class="articles-list">
                    <?php foreach ($articles as $art): ?>
                        <?php 
                        $aImg = $art['image'] ?? 'technician-thumb.jpg';
                        $aImgUrl = (str_starts_with($aImg, 'http') || str_starts_with($aImg, '/uploads') || str_starts_with($aImg, 'uploads/'))
                            ? (str_starts_with($aImg, 'http') ? $aImg : url($aImg))
                            : asset('images/' . $aImg);
                        ?>
                        <article class="article-card">
                            <div class="article-text-side">
                                <h3 class="article-title">
                                    <a href="<?= url('blog/' . ($art['slug'] ?? 'error-e81-iran-radiator-fix')) ?>"><?= e($art['title']) ?></a>
                                </h3>
                                <p class="article-excerpt"><?= e($art['excerpt']) ?></p>
                                <div class="article-meta">
                                    <span class="article-date"><i class="fa-regular fa-clock" style="margin-left: 4px;"></i> <?= e($art['date']) ?></span>
                                </div>
                            </div>
                            <div class="article-thumb-side">
                                <img src="<?= $aImgUrl ?>" alt="<?= e($art['title']) ?>" class="article-thumb-img">
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="column-footer-action">
                    <a href="<?= url('blog') ?>" class="btn btn-outline btn-pill">
                        مشاهده همه مقالات دانشنامه <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- Customer Testimonials Section -->
<section class="testimonials-section" id="testimonials">
    <div class="site-container">
        <div class="section-heading text-center">
            <h2 class="section-title">نظرات و رضایت مشتریان</h2>
            <p class="section-subtitle" style="color: #64748b; font-size: 0.95rem; margin-top: 6px;">
                بیش از ۲۵,۰۰۰ تجربه موفق عیب‌یابی و سرویس پکیج در ۲۲ منطقه تهران
            </p>
            <div class="title-underline"></div>
        </div>

        <div class="testimonials-grid">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $t): ?>
                    <div class="testimonial-card">
                        <div>
                            <div class="testimonial-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?= ($i <= (int)$t['rating']) ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="testimonial-quote">
                                «<?= e($t['content']) ?>»
                            </p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-initials"><?= e(mb_substr($t['customer_name'], 0, 2)) ?></div>
                            <div class="author-meta">
                                <strong><?= e($t['customer_name']) ?></strong>
                                <span><?= e($t['customer_city'] ?? 'تهران') ?><?= !empty($t['service_type']) ? ' • ' . e($t['service_type']) : '' ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="testimonial-card">
                    <div>
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            «پکیج ایران رادیاتور L24FF ارور E81 می‌داد و آب حمام کاملاً سرد شده بود. بعد از تماس کمتر از ۴۰ دقیقه تکنسین رسید و با اسیدشویی اصولی مبدل و تنظیم گیج منبع مشکل کاملاً برطرف شد. برخورد عالی و صدور فاکتور شرکتی با ضمانت ۶ ماهه.»
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-initials">رس</div>
                        <div class="author-meta">
                            <strong>مهندس رامین سلیمانی</strong>
                            <span>منطقه ۲ تهران (سعادت‌آباد) • تعمیر ایران رادیاتور</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div>
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            «در شب‌های سرد آذر ماه پکیج بوتان ما افت فشار شدید داشت و مدام خاموش می‌شد. با کشیک اضطراری ترموبان هماهنگ شدیم و سرویس‌کار با قطعه اصل منبع انبساط آمد و در حضور خودم تعویض کرد. گارانتی کتبی ارائه دادند و بسیار راضی هستم.»
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-initials">فک</div>
                        <div class="author-meta">
                            <strong>دکتر فرزانه کریمی</strong>
                            <span>منطقه ۳ تهران (پاسداران) • تعویض منبع و سرویس دوره‌ای</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div>
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            «بزرگترین مزیت ترموبان شفافیت و صداقت در نرخ‌هاست. دقیقاً طبق نرخ مصوب اتحادیه هزینه را دریافت کردند، بدون هیچ هزینه تراشی اضافی و قطعه داغی قبلی را هم تحویل دادند. واقعاً تیمی متعهد و حرفه‌ای هستند.»
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-initials">حم</div>
                        <div class="author-meta">
                            <strong>مهندس حامد موسوی</strong>
                            <span>منطقه ۵ تهران (پونک) • تعمیر برد الکترونیک</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Frequently Asked Questions Section -->
<section class="faq-section" id="faq">
    <div class="site-container">
        <div class="section-heading text-center">
            <h2 class="section-title">سوالات متداول شما</h2>
            <p class="section-subtitle" style="color: #64748b; font-size: 0.95rem; margin-top: 6px;">
                پاسخ به سوالات پرتکرار مشتریان درباره نحوه اعزام، هزینه‌ها و گارانتی خدمات
            </p>
            <div class="title-underline"></div>
        </div>

        <div class="faq-accordion">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $f): ?>
                    <div class="faq-item">
                        <button type="button" class="faq-question">
                            <span><?= e($f['question']) ?></span>
                            <i class="fa-solid fa-chevron-down faq-icon-arrow"></i>
                        </button>
                        <div class="faq-answer">
                            <?= nl2br(e($f['answer'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="faq-item">
                    <button type="button" class="faq-question">
                        <span>اعزام تکنسین چقدر زمان می‌برد؟</span>
                        <i class="fa-solid fa-chevron-down faq-icon-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        با توجه به استقرار تکنسین‌های ترموبان در شعب ۴ گانه (شمال، شرق، غرب و مرکز)، پس از ثبت درخواست یا تماس تلفنی، نزدیک‌ترین تکنسین متخصص حداکثر بین ۳۰ الی ۶۰ دقیقه در محل شما حاضر خواهد بود.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        <span>آیا خدمات و قطعات تعویض شده دارای گارانتی هستند؟</span>
                        <i class="fa-solid fa-chevron-down faq-icon-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        بله، کلیه خدمات عیب‌یابی و تعمیراتی ترموبان به همراه تمامی قطعات یدکی اورجینال تعویض شده، دارای ۶ ماه ضمانت کتبی شرکتی با مهر رسمی می‌باشند و در صورت بروز هرگونه مشکل مجدد، خدمات به صورت کاملاً رایگان ارائه خواهد شد.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        <span>هزینه ایاب و ذهاب و کارشناسی در صورت عدم تمایل به تعمیر چقدر است؟</span>
                        <i class="fa-solid fa-chevron-down faq-icon-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        هزینه کارشناسی و عیب‌یابی دستگاه بر طبق نرخنامه مصوب اتحادیه محاسبه می‌گردد. شایان ذکر است در صورتی که عیب‌یابی منتهی به انجام تعمیرات توسط تکنسین ترموبان گردد، هزینه کارشناسی و ایاب و ذهاب کاملاً رایگان منظور خواهد شد.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        <span>چه برندهایی از پکیج‌های دیواری را پوشش می‌دهید؟</span>
                        <i class="fa-solid fa-chevron-down faq-icon-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        ترموبان دارای کد رسمی مجاز نصب ایران رادیاتور است و خدمات تخصصی تمامی مدل‌های ایران رادیاتور (L, M, K, Eco)، بوتان (پاویا، پرلا، بیتا، اپتیما، بنسر)، لورچ، ایساتیس، تاچی، بوش، آریستون، مرکوری و گلدیران را با قطعات فابریک ارائه می‌دهد.
                    </div>
                </div>

                <div class="faq-item">
                    <button type="button" class="faq-question">
                        <span>آیا فاکتور رسمی و داغی قطعات تحویل داده می‌شود؟</span>
                        <i class="fa-solid fa-chevron-down faq-icon-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        بله، پس از اتمام کار و تست کامل دستگاه در حضور شما، فاکتور شرکتی با جزئیات کامل خدمات و قطعات تحویل داده می‌شود و تمامی قطعات داغی و فرسوده تعویض شده به مشتری محترم تحویل داده خواهد شد.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Structured Data (Schema.org) for Local HVAC Business & FAQ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "HVACBusiness",
      "name": "<?= e(site_name()) ?>",
      "image": "<?= site_logo() ?>",
      "telephone": "<?= e(clean_phone_for_tel(site_emergency_phone() ?: site_phone_mobile())) ?>",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= e(site_address()) ?>",
        "addressLocality": "تهران",
        "addressRegion": "تهران",
        "addressCountry": "IR"
      },
      "areaServed": "Tehran",
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"
        ],
        "opens": "00:00",
        "closes": "23:59"
      }
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "اعزام تکنسین پکیج در تهران چقدر زمان می‌برد؟",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "حداکثر بین ۳۰ الی ۶۰ دقیقه پس از ثبت درخواست در تمامی مناطق ۲۲‌گانه تهران."
          }
        },
        {
          "@type": "Question",
          "name": "آیا خدمات و قطعات تعویض شده پکیج گارانتی دارند؟",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "بله، کلیه خدمات و قطعات دارای ۶ ماه ضمانت کتبی شرکتی با مهر رسمی می‌باشند."
          }
        }
      ]
    }
  ]
}
</script>

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
                <button type="button" class="btn btn-primary" onclick="document.getElementById('coverageModal').classList.remove('active'); openServiceBooking('اعزام فوری تکنسین');">
                    ثبت فوری سفارش اعزام تکنسین
                </button>
            </div>
        </div>
    </div>
</div>

