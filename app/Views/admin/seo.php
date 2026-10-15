<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-chart-line" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت جامع سئو و بهینه‌سازی موتورهای جستجو (Rank Math Pro)
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                کنترل متاتگ‌ها، قالب‌بندی عناوین صفحات، تولید خودکار نقشه سایت، تنظیمات Robots.txt و اسکیماهای گوگل
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= url('sitemap.xml') ?>" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 8px 14px; background: #fff;">
                <i class="fa-solid fa-sitemap" style="color: #0284c7; margin-left: 6px;"></i>
                مشاهده نقشه سایت (sitemap.xml)
            </a>
            <a href="<?= url('robots.txt') ?>" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 8px 14px; background: #fff;">
                <i class="fa-solid fa-robot" style="color: #16a34a; margin-left: 6px;"></i>
                مشاهده robots.txt
            </a>
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 20px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-circle-check" style="margin-left: 8px;"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<form action="<?= url('admin/seo') ?>" method="POST">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        <!-- Main Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Meta Titles & Descriptions Box -->
            <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                <h3 style="font-size: 1.15rem; color: #011C39; font-weight: 800; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-tags" style="color: #EF5C16;"></i>
                    الگوی عنوان و متادیسکریپشن صفحات (Rank Math Meta Engine)
                </h3>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 6px; display: block;">
                        الگوی عنوان صفحات (Title Template)
                    </label>
                    <input type="text" name="seo_meta_title_template" class="form-control" value="<?= e($settings['seo_meta_title_template'] ?? '%title% | %sitename%') ?>" placeholder="%title% | %sitename%">
                    <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 4px;">متغیرهای مجاز: <code dir="ltr">%title%</code> (عنوان صفحه/محصول/مقاله) و <code dir="ltr">%sitename%</code> (نام سایت)</small>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 6px; display: block;">
                        توضیحات پیش‌فرض متا (Meta Description)
                    </label>
                    <textarea name="seo_meta_description" class="form-control" rows="3"><?= e($settings['seo_meta_description'] ?? '') ?></textarea>
                    <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 4px;">حداکثر ۱۶۰ کاراکتر جهت نمایش بهینه در نتایج جستجوی گوگل</small>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 6px; display: block;">
                        کلمات کلیدی اصلی سایت (Keywords)
                    </label>
                    <input type="text" name="seo_meta_keywords" class="form-control" value="<?= e($settings['seo_meta_keywords'] ?? '') ?>" placeholder="تعمیر پکیج, نمایندگی ایران رادیاتور, پکیج بوتان">
                </div>

                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 6px; display: block;">
                        آدرس کانونیکال پایه (Canonical Base URL)
                    </label>
                    <input type="url" name="seo_canonical_url" class="form-control" dir="ltr" value="<?= e($settings['seo_canonical_url'] ?? 'http://thermoban.test') ?>">
                </div>
            </div>

            <!-- Robots.txt Box -->
            <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                <h3 style="font-size: 1.15rem; color: #011C39; font-weight: 800; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-file-code" style="color: #EF5C16;"></i>
                    ویرایشگر فایل هدایت خزنده‌ها (Robots.txt)
                </h3>
                <div class="form-group" style="margin: 0;">
                    <textarea name="seo_robots_txt" class="form-control" rows="6" dir="ltr" style="font-family: monospace; font-size: 0.88rem; line-height: 1.5;"><?= e($settings['seo_robots_txt'] ?? "User-agent: *\nDisallow: /admin/\nAllow: /\nSitemap: http://thermoban.test/sitemap.xml") ?></textarea>
                </div>
            </div>

        </div>

        <!-- Sidebar Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Schema & Social -->
            <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                <h3 style="font-size: 1.05rem; color: #011C39; font-weight: 800; margin: 0 0 16px 0;">
                    <i class="fa-solid fa-share-nodes" style="color: #EF5C16; margin-left: 6px;"></i>
                    شبکه‌های اجتماعی و اسکیما
                </h3>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">
                        نوع اسکیما کسب‌وکار (JSON-LD)
                    </label>
                    <select name="seo_schema_type" class="form-control">
                        <option value="HVACBusiness" <?= ($settings['seo_schema_type'] ?? '') === 'HVACBusiness' ? 'selected' : '' ?>>HVACBusiness (تاسیسات و سرمایش/گرمایش)</option>
                        <option value="LocalBusiness" <?= ($settings['seo_schema_type'] ?? '') === 'LocalBusiness' ? 'selected' : '' ?>>LocalBusiness (کسب‌وکار محلی)</option>
                        <option value="Organization" <?= ($settings['seo_schema_type'] ?? '') === 'Organization' ? 'selected' : '' ?>>Organization (شرکت و سازمان)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">
                        تصویر اشتراک‌گذاری در شبکه‌ها (OG Image)
                    </label>
                    <input type="text" name="seo_og_image" class="form-control" dir="ltr" value="<?= e($settings['seo_og_image'] ?? 'images/hero-technician.jpg') ?>">
                </div>
            </div>

            <!-- Webmaster Tools Verification -->
            <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                <h3 style="font-size: 1.05rem; color: #011C39; font-weight: 800; margin: 0 0 16px 0;">
                    <i class="fa-brands fa-google" style="color: #EF5C16; margin-left: 6px;"></i>
                    ابزارهای وب‌مستر گوگل
                </h3>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">
                        کد تایید Google Search Console
                    </label>
                    <input type="text" name="seo_search_console" class="form-control" dir="ltr" value="<?= e($settings['seo_search_console'] ?? '') ?>" placeholder="google-site-verification=...">
                </div>

                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">
                        کد رهگیری Google Analytics (GA4)
                    </label>
                    <input type="text" name="seo_google_analytics" class="form-control" dir="ltr" value="<?= e($settings['seo_google_analytics'] ?? '') ?>" placeholder="G-XXXXXXXXXX">
                </div>
            </div>

            <!-- Save CTA -->
            <button type="submit" class="btn btn-primary" style="padding: 14px; font-size: 1rem; font-weight: 800; border-radius: 8px;">
                <i class="fa-solid fa-floppy-disk" style="margin-left: 8px;"></i>
                ذخیره تنظیمات سئو
            </button>

        </div>

    </div>
</form>
