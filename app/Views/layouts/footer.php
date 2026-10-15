<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Floating CTA Consultation Banner -->
<section class="cta-banner-wrapper">
    <div class="site-container">
        <div class="cta-banner">
            
            <!-- Right in RTL: Phones & Action Button -->
            <div class="cta-phones-side">
                <div class="cta-phones">
                    <a href="tel:09123456789" class="cta-phone-item cta-phone-mobile">
                        <svg class="phone-svg-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <span>۰۹۱۲-۳۴۵-۶۷۸۹</span>
                    </a>
                    <a href="tel:02112345678" class="cta-phone-item">۰۲۱-۱۲۳۴۵۶۷۸</a>
                </div>
                <div class="cta-divider"></div>
                <a href="#quick-order" class="btn btn-navy cta-contact-btn">تماس با ما</a>
            </div>

            <!-- Left in RTL: Headset & Question text -->
            <div class="cta-info-side">
                <div class="cta-text">
                    <h3 class="cta-title">نیاز به مشاوره یا ثبت سفارش دارید؟</h3>
                    <p class="cta-subtitle">کارشناسان ما آماده پاسخگویی به شما هستند.</p>
                </div>
                <div class="cta-icon-box">
                    <img src="<?= asset('images/headset-icon.png') ?>" alt="مشاوره رایگان" class="cta-icon">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Main Site Footer -->
<footer class="site-footer" id="contact">
    <div class="site-container">
        <div class="footer-grid">
            <!-- Col 1: Contact Information (Far Right in RTL) -->
            <div class="footer-col footer-col-contact">
                <h4 class="footer-col-title">اطلاعات تماس</h4>
                <ul class="footer-contact-list">
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                            <circle cx="12" cy="9" r="2.5"/>
                        </svg>
                        <span><?= SITE_ADDRESS ?></span>
                    </li>
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                        <a href="tel:09123456789"><?= SITE_PHONE_1 ?></a>
                    </li>
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:02112345678"><?= SITE_PHONE_2 ?></a>
                    </li>
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <a href="mailto:info@gmail.com">info@gmail.com</a>
                    </li>
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                        <a href="https://instagram.com" target="_blank" rel="noopener">instagram</a>
                    </li>
                </ul>
            </div>

            <!-- Col 2: Useful Links -->
            <div class="footer-col footer-col-links">
                <h4 class="footer-col-title">لینک های مفید</h4>
                <ul class="footer-nav-list">
                    <?php if (\App\Models\Setting::get('module_shop', '0') === '1'): ?>
                        <li><a href="<?= url('shop') ?>">فروشگاه</a></li>
                    <?php endif; ?>
                    <li><a href="<?= url('services') ?>">گارانتی و خدمات</a></li>
                    <?php if (\App\Models\Setting::get('module_blog', '1') === '1'): ?>
                        <li><a href="<?= url('blog') ?>">مقالات آموزشی</a></li>
                    <?php endif; ?>
                    <li><a href="<?= url('contact') ?>">تماس با ما</a></li>
                    <li><a href="<?= url('about') ?>">درباره ما</a></li>
                </ul>
            </div>

            <!-- Col 3: Services -->
            <div class="footer-col footer-col-services">
                <h4 class="footer-col-title">خدمات</h4>
                <ul class="footer-nav-list">
                    <li><a href="<?= url('services#install') ?>">نصب پکیج</a></li>
                    <li><a href="<?= url('services#repair') ?>">تعمیر پکیج</a></li>
                    <li><a href="<?= url('services#maintenance') ?>">سرویس پکیج</a></li>
                    <li><a href="<?= url('services#setup') ?>">راه‌اندازی فصلی</a></li>
                    <li><a href="<?= url('services#sale') ?>">فروش پکیج</a></li>
                </ul>
            </div>

            <!-- Col 4: About Thermoban (Far Left in RTL) -->
            <div class="footer-col footer-col-about" id="about">
                <h4 class="footer-col-title">درباره ترموبان</h4>
                <p class="footer-about-text">
                    ترموبان با بیش از ۱۰ سال تجربه در زمینه نصب و تعمیر پکیج و آب گرم کن، با بهره گیری از تکنیسین های متخصص و کد مجاز ایران رادیاتور، بهترین خدمات را در ۲۲ منطقه تهران ارائه می‌دهد.
                </p>
                <div class="footer-enamad-row">
                    <div class="enamad-card">
                        <img src="<?= asset('images/enamad.png') ?>" alt="نماد اعتماد الکترونیکی" class="enamad-img">
                    </div>
                    <div class="enamad-card">
                        <img src="<?= asset('images/enamad.png') ?>" alt="نماد اعتماد الکترونیکی" class="enamad-img">
                    </div>
                    <div class="enamad-card">
                        <img src="<?= asset('images/enamad.png') ?>" alt="نماد اعتماد الکترونیکی" class="enamad-img">
                    </div>
                    <div class="enamad-card">
                        <img src="<?= asset('images/enamad.png') ?>" alt="نماد اعتماد الکترونیکی" class="enamad-img">
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom-line">
            <p class="copyright-text">تمامی حقوق مادی و معنوی این وبسایت متعلق به ترموبان است.</p>
            <div class="developer-credit">
                طراحی و برنامه‌نویسی: <a href="https://devshayan.ir" target="_blank" rel="noopener"><strong>شایان نماینده</strong></a>
                <span class="sep">•</span>
                ایمیل: <a href="mailto:namayandeshayan@gmail.com">namayandeshayan@gmail.com</a>
                <span class="sep">•</span>
                وب‌سایت: <a href="https://devshayan.ir" target="_blank" rel="noopener">devshayan.ir</a>
            </div>
        </div>
    </div>
</footer>
