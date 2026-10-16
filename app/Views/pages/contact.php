<?php defined('ROOT_PATH') or exit('No direct script access allowed'); 

$activeBranches = [];
try {
    if (class_exists('\App\Models\Branch')) {
        $activeBranches = \App\Models\Branch::getActive();
    }
} catch (\Throwable $e) {}

if (empty($activeBranches) && !empty($branches)) {
    $activeBranches = $branches;
}

$workingHours = site_working_hours();
$socials = site_socials();
?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner contact-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <span class="current">تماس با ما</span>
            </nav>
            <h1 class="page-title">تماس با شعب و تکنسین‌های سیار ترموبان در سراسر تهران</h1>
            <p class="page-subtitle">پاسخگویی ۲۴ ساعته در تمام روزهای هفته حتی تعطیلات رسمی | اعزام کمتر از ۱ ساعت</p>
        </div>
    </div>
</section>

<!-- Branches & Emergency Numbers Section -->
<section class="contact-branches-section">
    <div class="site-container">
        <div class="section-header text-center">
            <span class="section-tag-badge">پوشش ۲۲ منطقه تهران</span>
            <h2 class="section-title">شماره‌های تماس شعب تخصصی ترموبان</h2>
            <p class="section-subtitle">جهت تسریع در اعزام، لطفاً با شماره نزدیک‌ترین شعبه به محل سکونت خود تماس حاصل فرمایید</p>
        </div>

        <div class="branches-grid">
            <?php foreach ($activeBranches as $branch): 
                $bLandline = $branch['phone_landline'] ?? ($branch['phone'] ?? '');
                $bMobile = $branch['phone_mobile'] ?? ($branch['mobile'] ?? '');
                $bCallPhone = !empty($bLandline) ? $bLandline : $bMobile;
            ?>
                <div class="branch-card">
                    <div class="branch-header">
                        <div class="branch-icon"><?= icon('map-pin', 'text-orange', 22) ?></div>
                        <h3 class="branch-title">
                            <?= e($branch['name'] ?? ($branch['area'] ?? 'شعبه')) ?>
                            <?php if (!empty($branch['is_main'])): ?>
                                <span style="font-size: 0.72rem; background: #e0f2fe; color: #0284c7; padding: 2px 6px; border-radius: 4px; margin-right: 4px;">مرکزی</span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <p class="branch-districts"><strong>مناطق تحت پوشش:</strong> <?= e($branch['district'] ?? ($branch['districts'] ?? '')) ?></p>
                    <div class="branch-phones">
                        <?php if (!empty($bLandline)): ?>
                            <a href="tel:<?= e(clean_phone_for_tel($bLandline)) ?>" class="branch-phone-link">
                                <span class="phone-icon"><?= icon('phone', 'text-orange', 16) ?></span>
                                <span class="phone-text" dir="ltr"><?= e($bLandline) ?></span>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($bMobile)): ?>
                            <a href="tel:<?= e(clean_phone_for_tel($bMobile)) ?>" class="branch-mobile-link">
                                <span class="phone-icon"><?= icon('mobile', 'text-orange', 16) ?></span>
                                <span class="phone-text" dir="ltr"><?= e($bMobile) ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($bCallPhone)): ?>
                        <a href="tel:<?= e(clean_phone_for_tel($bCallPhone)) ?>" class="btn btn-outline btn-call-branch w-100">
                            تماس فوری با این شعبه
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact Form & Online Support Section -->
<section class="contact-form-section">
    <div class="site-container">
        <div class="contact-layout-grid">
            
            <!-- Right: Interactive Contact Form -->
            <div class="contact-form-container">
                <div class="form-header">
                    <h3 class="form-heading">ثبت آنلاین پیام و درخواست مشاوره</h3>
                    <p class="form-subheading">فرم زیر را تکمیل فرمایید تا کارشناسان فنی ما در کمتر از ۱۵ دقیقه با شما تماس بگیرند.</p>
                </div>

                <form action="<?= url('api/contact') ?>" method="POST" class="site-contact-form" id="contactMainForm">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="contact_name">نام و نام خانوادگی <span class="required">*</span></label>
                            <input type="text" id="contact_name" name="name" class="form-control" placeholder="مثال: علیرضا حسینی" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_phone">شماره تلفن همراه <span class="required">*</span></label>
                            <input type="tel" id="contact_phone" name="phone" class="form-control" placeholder="۰۹۱۲۳۴۵۶۷۸۹" dir="ltr" required>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="contact_district">منطقه سکونت در تهران <span class="required">*</span></label>
                            <select id="contact_district" name="district" class="form-control" required>
                                <option value="">انتخاب منطقه...</option>
                                <option value="منطقه ۱">منطقه ۱ (تجریش، نیاوران، زعفرانیه، ولنجک)</option>
                                <option value="منطقه ۲">منطقه ۲ (سعادت‌آباد، شهرک غرب، صادقیه)</option>
                                <option value="منطقه ۳">منطقه ۳ (پاسداران، میرداماد، ونک، قلهک)</option>
                                <option value="منطقه ۴">منطقه ۴ (تهرانپارس، حکیمیه، رسالت، پاسداران)</option>
                                <option value="منطقه ۵">منطقه ۵ (پونک، جنت‌آباد، باغ فیض، مرزداران)</option>
                                <option value="منطقه ۶">منطقه ۶ (یوسف‌آباد، مطهری، امیرآباد، ولیعصر)</option>
                                <option value="منطقه ۷">منطقه ۷ (سهروردی، بهشتی، هفت تیر، نامجو)</option>
                                <option value="منطقه ۸">منطقه ۸ (نارمک، هفت حوض، مجیدیه)</option>
                                <option value="منطقه ۱۱ و ۱۲">منطقه ۱۱ و ۱۲ (مرکز شهر، بازار، بهارستان)</option>
                                <option value="منطقه ۱۳ و ۱۴">منطقه ۱۳ و ۱۴ (پیروزی، نیروهوایی، محلاتی)</option>
                                <option value="سایر مناطق">سایر مناطق ۲۲ گانه تهران</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contact_service">نوع خدمات درخواستی</label>
                            <select id="contact_service" name="service" class="form-control">
                                <option value="تعمیر پکیج">تعمیر و عیب‌یابی پکیج</option>
                                <option value="سرویس دوره‌ای">سرویس دوره‌ای و اسیدشویی</option>
                                <option value="نصب پکیج">نصب پکیج جدید</option>
                                <option value="خرید پکیج">مشاوره و خرید پکیج دیواری</option>
                                <option value="سایر">سایر موارد</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_message">توضیحات و شرح مشکل پکیج</label>
                        <textarea id="contact_message" name="message" rows="4" class="form-control" placeholder="برند پکیج، کد خطا یا علائم خرابی دستگاه را یادداشت فرمایید..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit-full" id="contactSubmitBtn">
                        <span>ارسال درخواست و اعزام تکنسین</span>
                    </button>
                    <div id="contactFormAlert" class="form-alert-msg" style="display:none;"></div>
                </form>
            </div>

            <!-- Left: Direct Communication & Bale Support Box -->
            <div class="contact-info-col">
                
                <!-- Bale Direct Online Support Card -->
                <div class="bale-direct-card">
                    <div class="bale-header">
                        <div class="bale-badge-circle">
                            <svg viewBox="0 0 64 64" width="36" height="36" fill="none">
                                <circle cx="32" cy="32" r="32" fill="#00A693"/>
                                <path d="M44.5 24.2C43.8 20.8 41.2 18.2 37.8 17.5C32.1 16.3 26.2 18.2 22.3 22.4C18.4 26.6 17 32.6 18.7 38C20 42.1 23.4 45.3 27.6 46.4C33.8 48 40.3 45.4 43.8 40C44.7 38.6 43.9 36.7 42.2 36.6C40.8 36.5 39.6 37.4 39 38.6C36.6 42.6 31.7 44.5 27.1 43.3C23.9 42.5 21.3 40 20.3 36.8C18.9 32.5 20.1 27.8 23.2 24.5C26.3 21.2 31 19.8 35.5 20.7C38.2 21.2 40.3 23.2 40.9 25.9C41.7 29.8 39.4 33.6 35.6 34.7C33.3 35.4 30.8 34.6 29.3 32.8C27.9 31 27.8 28.5 29 26.6C29.6 25.7 29.3 24.5 28.4 23.9C27.5 23.3 26.3 23.6 25.7 24.5C23.7 27.5 23.9 31.5 26.1 34.3C28.4 37.2 32.4 38.4 36 37.3C41.6 35.6 45.4 29.9 44.5 24.2Z" fill="white"/>
                            </svg>
                        </div>
                        <div>
                            <h4>پشتیبانی فوری در پیام‌رسان بله</h4>
                            <p>ارسال عکس ارور و قطعه جهت استعلام قیمت آنلاین</p>
                        </div>
                    </div>
                    <p class="bale-desc">
                        می‌توانید عکس یا ویدیوی کوتاه از صدای غیرعادی یا کد خطای پکیج خود را در پیام‌رسان بله ارسال فرمایید تا تکنسین کشیک فوراً شما را راهنمایی کند.
                    </p>
                    <a href="<?= e($socials['bale'] ?? 'https://ble.ir/thermoban_support') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-bale-full w-100">
                        <span><?= icon('ticket', '', 18) ?> شروع گفتگو در پیام‌رسان بله</span>
                    </a>
                </div>

                <!-- Office Info Box -->
                <div class="office-info-card">
                    <h4 class="office-title">اطلاعات دفتر مرکزی و هماهنگی</h4>
                    <ul class="office-info-list">
                        <li>
                            <span class="info-icon"><?= icon('building', 'text-orange', 20) ?></span>
                            <div>
                                <strong>آدرس دفتر مرکزی:</strong>
                                <span><?= e(site_address()) ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="info-icon"><?= icon('clock', 'text-orange', 20) ?></span>
                            <div>
                                <strong>ساعات کاری و پذیرش:</strong>
                                <span><?= e($workingHours['weekdays']) ?><br><?= e($workingHours['weekend']) ?></span>
                            </div>
                        </li>
                        <?php $sEmail = site_email(); if (!empty($sEmail)): ?>
                        <li>
                            <span class="info-icon"><?= icon('mail', 'text-orange', 20) ?></span>
                            <div>
                                <strong>ایمیل پشتیبانی:</strong>
                                <span><?= e($sEmail) ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>

        </div>
    </div>
</section>

