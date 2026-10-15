<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner services-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <span class="current">خدمات ما</span>
            </nav>
            <h1 class="page-title">خدمات تخصصی نصب، تعمیر و سرویس دوره‌ای پکیج دیواری</h1>
            <p class="page-subtitle">ارائه فاکتور رسمی، نرخ مصوب اتحادیه و ۶ ماه ضمانت کتبی شرکتی با کد مجاز ۷۲۷۳</p>
        </div>
    </div>
</section>

<!-- Services Detailed Catalog -->
<section class="services-catalog-section">
    <div class="site-container">
        
        <div class="services-detailed-list">
            <?php foreach ($services as $srv): ?>
                <div class="service-detail-card" id="<?= e($srv['id']) ?>">
                    <div class="service-card-header">
                        <div class="service-icon-wrap">
                            <img src="<?= asset('images/icon-service-' . $srv['id'] . '.png') ?>" alt="<?= e($srv['title']) ?>">
                        </div>
                        <div class="service-title-wrap">
                            <h2 class="service-main-title"><?= e($srv['title']) ?></h2>
                            <p class="service-main-subtitle"><?= e($srv['subtitle']) ?></p>
                        </div>
                        <div class="service-price-badge">
                            <span class="badge-label">تعرفه و هزینه:</span>
                            <span class="badge-val"><?= e($srv['price']) ?></span>
                        </div>
                    </div>

                    <div class="service-card-body">
                        <h4 class="features-title">اقدامات تخصصی انجام شده در این خدمت:</h4>
                        <ul class="service-features-list">
                            <?php foreach ($srv['features'] as $f): ?>
                                <li>
                                    <span class="check-icon">✓</span>
                                    <span><?= e($f) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="service-card-footer">
                        <div class="guarantee-chip">
                            🛡️ <?= e($srv['guarantee']) ?>
                        </div>
                        <div class="service-cta-buttons">
                            <button type="button" class="btn btn-primary" onclick="openServiceBooking('<?= e($srv['title']) ?>')">
                                ثبت درخواست این خدمت
                            </button>
                            <a href="tel:02177428596" class="btn btn-outline">
                                📞 مشاوره تلفنی فوری
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Dispatch Workflow Section -->
<section class="services-workflow-section">
    <div class="site-container">
        <div class="section-header text-center">
            <span class="section-tag-badge">مراحل خدمت‌رسانی</span>
            <h2 class="section-title">فرآیند اعزام تکنسین از تماس تا تحویل فاکتور</h2>
            <p class="section-subtitle">سریع‌ترین، شفاف‌ترین و مطمئن‌ترین سیستم ارائه خدمات گرمایشی در پایتخت</p>
        </div>

        <div class="workflow-steps-grid">
            <div class="workflow-step-card">
                <div class="step-num">۱</div>
                <h4 class="step-title">ثبت درخواست</h4>
                <p class="step-desc">تماس تلفنی با شعب یا ثبت فرم آنلاین در وبسایت در هر ساعت از شبانه‌روز</p>
            </div>
            <div class="workflow-step-card">
                <div class="step-num">۲</div>
                <h4 class="step-title">مشاوره و اعزام</h4>
                <p class="step-desc">تشخیص اولیه مشکل، اعلام حدود هزینه و اعزام نزدیک‌ترین تکنسین در کمتر از ۶۰ دقیقه</p>
            </div>
            <div class="workflow-step-card">
                <div class="step-num">۳</div>
                <h4 class="step-title">عیب‌یابی و تعمیر</h4>
                <p class="step-desc">بررسی دقیق دستگاه، تست با تجهیزات روز و استفاده انحصاری از قطعات اصل شرکتی</p>
            </div>
            <div class="workflow-step-card">
                <div class="step-num">۴</div>
                <h4 class="step-title">تست و ضمانت</h4>
                <p class="step-desc">تست کامل داغی آب و رادیاتورها، ارائه فاکتور رسمی و گارانتی کتبی ۶ ماهه قطعات</p>
            </div>
        </div>
    </div>
</section>
