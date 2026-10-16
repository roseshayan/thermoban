<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php
$mobilePhone = site_phone_mobile();
$landlinePhone = site_phone_landline();
$siteEmail = site_email();
$siteAddress = site_address();
$workingHours = site_working_hours();
$socials = site_socials();
$trustBadges = site_trust_badges();
$footerMenus = \App\Models\Menu::getTree('footer');
?>

<!-- Floating CTA Consultation Banner -->
<section class="cta-banner-wrapper">
    <div class="site-container">
        <div class="cta-banner">
            
            <!-- Right in RTL: Phones & Action Button -->
            <div class="cta-phones-side">
                <div class="cta-phones">
                    <?php if (!empty($mobilePhone)): ?>
                    <a href="tel:<?= clean_phone_for_tel($mobilePhone) ?>" class="cta-phone-item cta-phone-mobile">
                        <svg class="phone-svg-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <span><?= e($mobilePhone) ?></span>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($landlinePhone)): ?>
                    <a href="tel:<?= clean_phone_for_tel($landlinePhone) ?>" class="cta-phone-item">
                        <i class="fa-solid fa-phone" style="font-size: 0.85rem; margin-left: 4px;"></i>
                        <span><?= e($landlinePhone) ?></span>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="cta-divider"></div>
                <a href="<?= url('contact') ?>" class="btn btn-navy cta-contact-btn">تماس با ما</a>
            </div>

            <!-- Left in RTL: Headset & Question text -->
            <div class="cta-info-side">
                <div class="cta-text">
                    <h3 class="cta-title">نیاز به مشاوره یا ثبت سفارش دارید؟</h3>
                    <p class="cta-subtitle">کارشناسان ما در ساعات کاری آماده پاسخگویی به شما هستند.</p>
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
                <h4 class="footer-col-title">اطلاعات تماس و نشانی</h4>
                <ul class="footer-contact-list">
                    <?php if (!empty($siteAddress)): ?>
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                            <circle cx="12" cy="9" r="2.5"/>
                        </svg>
                        <span><?= e($siteAddress) ?></span>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($mobilePhone)): ?>
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-mobile-screen-button footer-icon" style="color: #EF5C16;"></i>
                        <a href="tel:<?= clean_phone_for_tel($mobilePhone) ?>" dir="ltr"><?= e($mobilePhone) ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($landlinePhone)): ?>
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:<?= clean_phone_for_tel($landlinePhone) ?>" dir="ltr"><?= e($landlinePhone) ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($siteEmail)): ?>
                    <li class="footer-contact-item">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <a href="mailto:<?= e($siteEmail) ?>" dir="ltr"><?= e($siteEmail) ?></a>
                    </li>
                    <?php endif; ?>

                    <!-- Working Hours -->
                    <?php if (!empty($workingHours['weekdays'])): ?>
                    <li class="footer-contact-item">
                        <i class="fa-solid fa-clock footer-icon" style="color: #0284c7;"></i>
                        <span style="font-size: 0.82rem; color: #cbd5e1;"><?= e($workingHours['weekdays']) ?></span>
                    </li>
                    <?php endif; ?>

                    <!-- Social Icons Row -->
                    <li class="footer-contact-item" style="margin-top: 6px;">
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <?php if (!empty($socials['instagram'])): ?>
                                <a href="<?= e($socials['instagram']) ?>" target="_blank" rel="noopener noreferrer" style="color: #e1306c; font-size: 1.25rem;" title="اینستاگرام">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($socials['telegram'])): ?>
                                <a href="<?= e($socials['telegram']) ?>" target="_blank" rel="noopener noreferrer" style="color: #229ed9; font-size: 1.25rem;" title="تلگرام">
                                    <i class="fa-brands fa-telegram"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($socials['whatsapp'])): ?>
                                <a href="<?= e($socials['whatsapp']) ?>" target="_blank" rel="noopener noreferrer" style="color: #25d366; font-size: 1.25rem;" title="واتساپ">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($socials['bale'])): ?>
                                <a href="<?= e($socials['bale']) ?>" target="_blank" rel="noopener noreferrer" style="color: #10b981; font-size: 1.15rem;" title="پیام‌رسان بله">
                                    <i class="fa-solid fa-comment-dots"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Col 2: Useful Links (Dynamic Footer Menus) -->
            <div class="footer-col footer-col-links">
                <h4 class="footer-col-title">لینک‌های مفید</h4>
                <ul class="footer-nav-list">
                    <?php if (!empty($footerMenus)): ?>
                        <?php foreach ($footerMenus as $fm): ?>
                            <li><a href="<?= e($fm['url']) ?>"><?= e($fm['title']) ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php if (\App\Models\Setting::get('module_shop', '0') === '1'): ?>
                            <li><a href="<?= url('shop') ?>">فروشگاه</a></li>
                        <?php endif; ?>
                        <li><a href="<?= url('services') ?>">گارانتی و خدمات</a></li>
                        <?php if (\App\Models\Setting::get('module_blog', '1') === '1'): ?>
                            <li><a href="<?= url('blog') ?>">مقالات آموزشی</a></li>
                        <?php endif; ?>
                        <li><a href="<?= url('contact') ?>">تماس با ما</a></li>
                        <li><a href="<?= url('about') ?>">درباره ما</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Col 3: Services -->
            <div class="footer-col footer-col-services">
                <h4 class="footer-col-title">خدمات تخصصی</h4>
                <ul class="footer-nav-list">
                    <li><a href="<?= url('services#install') ?>">نصب پکیج دیواری</a></li>
                    <li><a href="<?= url('services#repair') ?>">تعمیر و رفع ارور پکیج</a></li>
                    <li><a href="<?= url('services#maintenance') ?>">سرویس دوره‌ای و اسیدشویی</a></li>
                    <li><a href="<?= url('services#setup') ?>">راه‌اندازی فصلی</a></li>
                    <li><a href="<?= url('service-order') ?>" style="color: #fb923c; font-weight: 700;">ثبت آنلاین سفارش خدمات</a></li>
                    <li><a href="<?= url('service-order/tracking') ?>">پیگیری وضعیت سفارش</a></li>
                </ul>
            </div>

            <!-- Col 4: About Thermoban & Trust Badges (Far Left in RTL) -->
            <div class="footer-col footer-col-about" id="about">
                <h4 class="footer-col-title">درباره ترموبان</h4>
                <p class="footer-about-text">
                    مرکز تخصصی فروش، نصب و تعمیرات انواع پکیج شوفاژ دیواری و رادیاتور در ۲۲ منطقه تهران با کد مجاز نمایندگی و ضمانت کتبی قطعات اورجینال شرکتی.
                </p>

                <!-- Controlled Trust Badges & Licenses -->
                <?php if ($trustBadges['active']): ?>
                <div class="footer-enamad-row" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-top: 14px;">
                    <?php if (!empty($trustBadges['enamad_code'])): ?>
                        <div class="enamad-card" style="background: #fff; padding: 6px; border-radius: 8px;">
                            <?= $trustBadges['enamad_code'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($trustBadges['samandehi_code'])): ?>
                        <div class="enamad-card" style="background: #fff; padding: 6px; border-radius: 8px;">
                            <?= $trustBadges['samandehi_code'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($trustBadges['union_license'])): ?>
                        <div style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; padding: 8px 12px; font-size: 0.74rem; color: #e2e8f0; line-height: 1.5;">
                            <i class="fa-solid fa-certificate" style="color: #f59e0b; margin-left: 4px;"></i>
                            <?= e($trustBadges['union_license']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom-line">
            <p class="copyright-text">تمامی حقوق مادی و معنوی این وب‌سایت متعلق به <?= e(site_name()) ?> است.</p>
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

<!-- Global Interactive Service Booking Modal -->
<div class="site-modal" id="serviceModal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="serviceModalTitle">درخواست اعزام تکنسین پکیج</h3>
                <button type="button" class="modal-close" id="serviceModalClose" aria-label="بستن">&times;</button>
            </div>
            <div class="modal-body" id="serviceModalBody">
                <form id="globalServiceBookingForm" class="site-contact-form" onsubmit="submitGlobalBooking(event)">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>نام و نام خانوادگی <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="fullname" class="form-control" placeholder="مثال: محمد احمدی" required>
                        </div>
                        <div class="form-group">
                            <label>شماره تماس همراه <span style="color:#ef4444;">*</span></label>
                            <input type="tel" name="phone" class="form-control" placeholder="۰۹۱۲۳۴۵۶۷۸۹" dir="ltr" required>
                        </div>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>نوع خدمت مورد نیاز</label>
                            <select name="service" id="modalServiceSelect" class="form-control">
                                <option value="تعمیر و عیب‌یابی پکیج">تعمیر و عیب‌یابی پکیج</option>
                                <option value="نصب پکیج دیواری">نصب پکیج دیواری</option>
                                <option value="سرویس دوره‌ای و اسیدشویی">سرویس دوره‌ای و اسیدشویی</option>
                                <option value="راه‌اندازی و تنظیم فصلی">راه‌اندازی و تنظیم فصلی</option>
                                <option value="مشاوره و استعلام قیمت">مشاوره و استعلام قیمت</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>برند پکیج دیواری</label>
                            <select name="brand" class="form-control">
                                <option value="ایران رادیاتور">ایران رادیاتور</option>
                                <option value="بوتان">بوتان</option>
                                <option value="لورچ">لورچ</option>
                                <option value="ایساتیس">ایساتیس</option>
                                <option value="بوش">بوش</option>
                                <option value="آریستون">آریستون</option>
                                <option value="تاچی">تاچی</option>
                                <option value="سایر برندها">سایر برندها</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>منطقه سکونت در تهران <span style="color:#ef4444;">*</span></label>
                        <select name="district" class="form-control" required>
                            <option value="">انتخاب منطقه...</option>
                            <option value="منطقه ۱">منطقه ۱ (تجریش، نیاوران، زعفرانیه، ولنجک)</option>
                            <option value="منطقه ۲">منطقه ۲ (سعادت‌آباد، شهرک غرب، گیشا، صادقیه)</option>
                            <option value="منطقه ۳">منطقه ۳ (پاسداران، میرداماد، ونک، ظفر)</option>
                            <option value="منطقه ۴">منطقه ۴ (تهرانپارس، حکیمیه، رسالت، نارمک)</option>
                            <option value="منطقه ۵">منطقه ۵ (پونک، جنت‌آباد، باغ فیض، فردوس)</option>
                            <option value="منطقه ۶">منطقه ۶ (یوسف‌آباد، مطهری، امیرآباد، ولیعصر)</option>
                            <option value="منطقه ۷">منطقه ۷ (سهروردی، بهشتی، هفت تیر، نامجو)</option>
                            <option value="منطقه ۸">منطقه ۸ (نارمک، هفت حوض، مجیدیه)</option>
                            <option value="منطقه ۱۱ و ۱۲">منطقه ۱۱ و ۱۲ (جمهوری، بازار، بهارستان)</option>
                            <option value="منطقه ۱۳ و ۱۴">منطقه ۱۳ و ۱۴ (پیروزی، نیروهوایی، محلاتی)</option>
                            <option value="منطقه ۱۵ تا ۲۰">جنوب تهران (شهرری، نازی‌آباد، افسریه)</option>
                            <option value="منطقه ۲۱ و ۲۲">غرب تهران (تهرانسر، چیتگر، دریاچه، المپیک)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>شرح مشکل یا کد خطا (اختیاری)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="مدل دستگاه و شرح مشکل، مثلاً افت بار یا ارور E81..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="globalBookingSubmitBtn" style="padding: 12px; font-weight: 800; font-size: 1rem; margin-top: 10px;">
                        ثبت نهایی درخواست و اعزام فوری تکنسین
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Floating Emergency Call Button (Bottom Left) -->
<?php $emergencyNumber = site_emergency_phone(); ?>
<?php if (!empty($emergencyNumber)): ?>
<div class="emergency-call-float">
    <a href="tel:<?= clean_phone_for_tel($emergencyNumber) ?>" class="emergency-btn-inner" title="تماس فوری شبانه‌روزی" aria-label="تماس فوری شبانه‌روزی">
        <i class="fa-solid fa-phone-volume"></i>
        <span class="emergency-btn-label">تماس فوری: <?= e($emergencyNumber) ?></span>
    </a>
</div>
<?php endif; ?>
