<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner shop-detail-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <a href="<?= url('shop') ?>">فروشگاه</a>
                <span class="sep">/</span>
                <a href="<?= url('shop?brand=' . $product['brand_slug']) ?>"><?= e($product['brand']) ?></a>
                <span class="sep">/</span>
                <span class="current"><?= e($product['title']) ?></span>
            </nav>
        </div>
    </div>
</section>

<!-- Single Product Overview Section -->
<section class="product-single-section">
    <div class="site-container">
        
        <div class="product-overview-card">
            
            <!-- Product Gallery / Right in RTL -->
            <div class="product-gallery-side">
                <div class="product-gallery-main">
                    <img src="<?= asset('images/' . $product['image']) ?>" alt="<?= e($product['title']) ?>" id="mainProductImg">
                    <div class="gallery-badges">
                        <span class="badge-install">نصب رایگان</span>
                        <span class="badge-guarantee">گارانتی طلایی ۲۴ ماهه</span>
                    </div>
                </div>
                <div class="product-gallery-thumbs">
                    <button class="thumb-btn active">
                        <img src="<?= asset('images/' . $product['image']) ?>" alt="<?= e($product['title']) ?>">
                    </button>
                    <button class="thumb-btn">
                        <img src="<?= asset('images/package-thumb.png') ?>" alt="نمای جانبی">
                    </button>
                    <button class="thumb-btn">
                        <img src="<?= asset('images/gold-shield-medal.png') ?>" alt="ضمانت نامه">
                    </button>
                </div>
            </div>

            <!-- Product Details / Left in RTL -->
            <div class="product-info-side">
                <div class="product-info-header">
                    <div class="brand-tag">
                        <img src="<?= asset('images/' . $product['brand_logo']) ?>" alt="<?= e($product['brand']) ?>" class="brand-small-logo">
                        <span>نمایندگی رسمی <?= e($product['brand']) ?></span>
                    </div>
                    <h1 class="single-product-title"><?= e($product['title']) ?></h1>
                    <div class="single-product-model"><?= e($product['model']) ?></div>
                    <div class="single-product-rating">
                        <span class="stars text-orange">
                            <?php for ($si = 0; $si < 5; $si++): ?>
                                <?= icon('star', 'star-fill', 16) ?>
                            <?php endfor; ?>
                        </span>
                        <span class="score"><?= $product['rating'] ?></span>
                        <span class="reviews-count">(<?= $product['reviews_count'] ?> دیدگاه خریداران)</span>
                    </div>
                </div>

                <!-- Brief Key Highlights -->
                <div class="product-highlights">
                    <div class="highlight-item">
                        <span class="hl-label">ظرفیت حرارتی:</span>
                        <span class="hl-val"><?= number_format($product['capacity']) ?> کیلوکالری</span>
                    </div>
                    <div class="highlight-item">
                        <span class="hl-label">متراژ مناسب:</span>
                        <span class="hl-val"><?= e($product['area']) ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="hl-label">نوع مبدل:</span>
                        <span class="hl-val"><?= e($product['heat_exchanger']) ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="hl-label">نوع دودکش:</span>
                        <span class="hl-val"><?= e($product['flue_type']) ?></span>
                    </div>
                </div>

                <!-- Warranty & Delivery Box -->
                <div class="product-services-box">
                    <div class="service-row">
                        <span class="srv-icon"><?= icon('shield', 'text-orange') ?></span>
                        <span><strong>گارانتی رسمی:</strong> <?= e($product['warranty']) ?></span>
                    </div>
                    <div class="service-row">
                        <span class="srv-icon"><?= icon('truck', 'text-orange') ?></span>
                        <span><strong>ارسال در تهران:</strong> تحویل اکسپرس کمتر از ۳ ساعت + امکان پرداخت در محل</span>
                    </div>
                    <div class="service-row">
                        <span class="srv-icon"><?= icon('technician', 'text-orange') ?></span>
                        <span><strong>اعزام نصاب مجاز:</strong> همزمان با تحویل دستگاه جهت تایید و فعال‌سازی گارانتی</span>
                    </div>
                </div>

                <!-- Price & Order Action Box -->
                <div class="product-order-box">
                    <div class="order-price-row">
                        <div class="price-wrapper">
                            <?php if (!empty($product['old_price'])): ?>
                                <span class="old-price-text"><?= e($product['old_price']) ?> تومان</span>
                            <?php endif; ?>
                            <div class="final-price-text">
                                <span class="num"><?= e($product['price']) ?></span>
                                <span class="unit">تومان</span>
                            </div>
                        </div>
                        <div class="stock-status in-stock">
                            <span class="stock-dot"></span>
                            <span>موجود در انبار تهران (آماده ارسال فوری)</span>
                        </div>
                    </div>

                    <div class="order-action-buttons">
                        <button type="button" class="btn btn-primary btn-lg btn-buy-now" onclick="document.getElementById('serviceModal').classList.add('active')">
                            <span><?= icon('cart', '', 18) ?> ثبت سفارش و اعزام نصاب</span>
                        </button>
                        <a href="tel:02177428596" class="btn btn-outline btn-lg btn-phone-order">
                            <span><?= icon('phone', '', 18) ?> مشاوره قبل از خرید: ۰۲۱-۷۷۴۲۸۵۹۶</span>
                        </a>
                        <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener" class="btn btn-navy btn-bale-order">
                            <span>پیام‌رسان بله</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <!-- Product Detailed Tabs -->
        <div class="product-tabs-wrapper">
            
            <div class="product-tabs-nav">
                <button class="tab-btn active" data-tab="tab-specs">مشخصات فنی دستگاه</button>
                <button class="tab-btn" data-tab="tab-desc">بررسی تخصصی کارشناسی</button>
                <button class="tab-btn" data-tab="tab-install">شرایط نصب رایگان و گارانتی</button>
            </div>

            <!-- Tab 1: Technical Specs Table -->
            <div class="tab-content-panel active" id="tab-specs">
                <h3 class="tab-panel-title">جدول کامل مشخصات فنی <?= e($product['title']) ?></h3>
                <div class="specs-table-container">
                    <table class="specs-table">
                        <tbody>
                            <?php foreach ($product['specs'] as $specKey => $specVal): ?>
                                <tr>
                                    <th class="spec-name"><?= e($specKey) ?></th>
                                    <td class="spec-value"><?= e($specVal) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Detailed Review -->
            <div class="tab-content-panel" id="tab-desc">
                <h3 class="tab-panel-title">نقد و بررسی کارشناسی پکیج <?= e($product['title']) ?></h3>
                <div class="expert-review-body">
                    <p><?= e($product['description']) ?></p>
                    
                    <h4>ویژگی‌ها و مزایای برجسته این مدل:</h4>
                    <ul class="features-checklist">
                        <?php foreach ($product['features'] as $f): ?>
                            <li><?= icon('check', 'text-orange', 16) ?> <?= e($f) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <h4>چرا خرید از نمایندگی رسمی ترموبان؟</h4>
                    <p>خرید پکیج از فروشگاه‌های متفرقه خطر ابطال گارانتی شرکتی و ارائه دستگاه‌های استوک یا قطعات تعویض شده را در پی دارد. ترموبان به عنوان نماینده رسمی با کد ، دستگاه پلمپ کارخانه را به همراه برگه ضمانت معتبر و فاکتور رسمی به دست شما می‌رساند.</p>
                </div>
            </div>

            <!-- Tab 3: Installation Requirements -->
            <div class="tab-content-panel" id="tab-install">
                <h3 class="tab-panel-title">شرایط و لوازم مورد نیاز جهت نصب رایگان</h3>
                <div class="install-conditions-body">
                    <div class="article-callout alert-info">
                        <div class="callout-icon"><?= icon('info', 'text-orange', 24) ?></div>
                        <div class="callout-body">
                            <h4>اجرت نصب دستگاه کاملاً رایگان است</h4>
                            <p>طبق مصوبه شرکت، هزینه نصب پکیج رایگان می‌باشد و توسط نصاب رسمی انجام می‌گردد. تنها هزینه لوازم جانبی و اتصالات استاندارد (در صورت نیاز به تعویض) طبق فاکتور شرکتی محاسبه می‌شود.</p>
                        </div>
                    </div>

                    <h4>لوازم استاندارد الزامی جهت تایید و فعال‌سازی گارانتی:</h4>
                    <ul class="article-checklist">
                        <li>شیلنگ‌های خرطومی استاندارد استیل نسوز (۴ عدد)</li>
                        <li>شیرهای ربع‌گرد برنجی با کیفیت</li>
                        <li>فیلتر مغناطیسی مدار گرمایش جهت جلوگیری از رسوب در پمپ</li>
                        <li>فیلتر پلی‌فسفات آب ورودی سرد</li>
                        <li>دودکش استاندارد دوجداره با شیب به سمت بیرون</li>
                        <li>محافظ برق استاندارد پکیج</li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="related-products-section">
                <h3 class="related-section-title">مدل‌های مشابه و پیشنهادی</h3>
                <div class="products-catalog-grid">
                    <?php foreach ($relatedProducts as $relP): ?>
                        <div class="product-catalog-card">
                            <div class="product-card-top">
                                <span class="badge-free-install">نصب رایگان</span>
                                <div class="product-card-brand">
                                    <img src="<?= asset('images/' . $relP['brand_logo']) ?>" alt="<?= e($relP['brand']) ?>">
                                </div>
                            </div>
                            <div class="product-card-thumb">
                                <a href="<?= url('product/' . $relP['id']) ?>">
                                    <img src="<?= asset('images/' . $relP['image']) ?>" alt="<?= e($relP['title']) ?>">
                                </a>
                            </div>
                            <div class="product-card-body">
                                <div class="product-category"><?= e($relP['brand']) ?></div>
                                <h4 class="product-title">
                                    <a href="<?= url('product/' . $relP['id']) ?>"><?= e($relP['title']) ?></a>
                                </h4>
                                <div class="product-model-name"><?= e($relP['model']) ?></div>
                            </div>
                            <div class="product-card-footer">
                                <div class="product-price-box">
                                    <div class="price-current">
                                        <span class="price-num"><?= e($relP['price']) ?></span>
                                        <span class="price-unit">تومان</span>
                                    </div>
                                </div>
                                <a href="<?= url('product/' . $relP['id']) ?>" class="btn btn-primary btn-product-buy">مشاهده</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
