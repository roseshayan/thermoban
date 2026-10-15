<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
            <h1 style="font-size: 1.45rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-file-pen text-orange" style="margin-left: 8px;"></i>
                ویرایش مقاله: <?= e($article['title']) ?>
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                ویرایش تخصصی محتوا با ادیتور پیشرفته محلی و بهینه‌سازی سئو (Rank Math Pro)
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= url('blog/' . ($article['slug'] ?? '')) ?>" target="_blank" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>مشاهده در سایت</span>
            </a>
            <a href="<?= url('admin/articles') ?>" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px;">
                <i class="fa-solid fa-arrow-right"></i>
                <span>بازگشت به مقالات</span>
            </a>
        </div>
    </div>
</div>

<form action="<?= url('admin/articles/edit/' . $article['id']) ?>" method="POST" enctype="multipart/form-data" id="articleEditForm">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
        
        <!-- Main Content Column (Right in RTL) -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Article Main Information -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;">
                <div class="admin-form-group" style="margin-bottom: 20px;">
                    <label class="admin-label" style="font-size: 0.95rem; font-weight: 800;">عنوان مقاله <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="artTitleInput" class="admin-input" value="<?= e($article['title']) ?>" style="font-size: 1.05rem; font-weight: 700;" required oninput="updateSeoPreview()">
                </div>

                <div class="admin-form-group" style="margin-bottom: 20px;">
                    <label class="admin-label">نامک پیوند یکتا (Slug) <span class="text-danger">*</span></label>
                    <input type="text" name="slug" id="artSlugInput" class="admin-input" dir="ltr" value="<?= e($article['slug']) ?>" required oninput="updateSeoPreview()">
                </div>

                <div class="admin-form-group" style="margin-bottom: 20px;">
                    <label class="admin-label">چکیده و خلاصه مقاله (Excerpt) <span class="text-danger">*</span></label>
                    <textarea name="excerpt" id="artExcerptInput" class="admin-textarea" rows="3" required oninput="updateSeoPreview()"><?= e($article['excerpt']) ?></textarea>
                </div>

                <!-- Professional Quill WYSIWYG Editor -->
                <div class="admin-form-group">
                    <label class="admin-label" style="font-size: 0.95rem; font-weight: 800; display: flex; justify-content: space-between; align-items: center;">
                        <span>متن کامل محتوا <span class="text-danger">*</span></span>
                        <span style="font-size: 0.78rem; font-weight: normal; color: #10b981;"><i class="fa-solid fa-check-circle"></i> ویرایشگر هوشمند محلی (Quill.js)</span>
                    </label>
                    
                    <div id="quillEditor" style="min-height: 380px; font-size: 1rem; line-height: 1.8; direction: rtl; text-align: right; background: #fff; border-radius: 0 0 10px 10px;">
                        <?= $article['content'] ?>
                    </div>
                    <input type="hidden" name="content" id="quillHiddenContent">
                </div>
            </div>

            <!-- Rank Math Pro SEO Meta Box -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px;">
                    <h2 style="font-size: 1.15rem; color: #011C39; margin: 0; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-chart-line text-orange"></i>
                        تنظیمات سئو مقاله (Rank Math Pro)
                    </h2>
                    <div id="seoScoreBadge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 4px 14px; border-radius: 20px; font-weight: 800; font-size: 0.9rem;">
                        امتیاز سئو: <span id="seoScoreValue">85</span> / ۱۰۰
                    </div>
                </div>

                <!-- Google SERP Live Snippet Preview -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 24px;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-brands fa-google text-primary"></i>
                        پیش‌نمایش زنده در نتایج جستجوی گوگل (SERP)
                    </div>
                    <div style="direction: ltr; text-align: left; background: #fff; padding: 14px 18px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.82rem; color: #202124; display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <img src="<?= asset('images/logo-icon.png') ?>" alt="icon" style="width: 16px; height: 16px; border-radius: 50%;">
                            <span>thermoban.ir &rsaquo; blog &rsaquo; <span id="serpSlugPreview"><?= e($article['slug']) ?></span></span>
                        </div>
                        <h4 id="serpTitlePreview" style="margin: 0 0 4px 0; color: #1a0dab; font-size: 1.15rem; font-weight: 500; cursor: pointer; text-decoration: none;">
                            <?= e($article['meta_title'] ?: $article['title']) ?> | ترموبان
                        </h4>
                        <p id="serpDescPreview" style="margin: 0; color: #4d5156; font-size: 0.88rem; line-height: 1.5;">
                            <?= e($article['meta_description'] ?: $article['excerpt']) ?>
                        </p>
                    </div>
                </div>

                <!-- Focus Keyword -->
                <div class="admin-form-group" style="margin-bottom: 18px;">
                    <label class="admin-label">کلمه کلیدی کانونی (Focus Keyword)</label>
                    <input type="text" name="focus_keyword" id="focusKeywordInput" class="admin-input" placeholder="مثال: ارور e81 ایران رادیاتور" value="<?= e($article['focus_keyword'] ?? '') ?>" oninput="updateSeoPreview()">
                    <small class="text-muted">کلمه یا عبارتی که می‌خواهید این مقاله برای آن در صفحه اول گوگل رتبه بگیرد.</small>
                </div>

                <!-- Meta Title -->
                <div class="admin-form-group" style="margin-bottom: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="admin-label" style="margin: 0;">عنوان سئو (Meta Title)</label>
                        <span style="font-size: 0.78rem; color: #64748b;" id="metaTitleCount">0 / 60 حرف</span>
                    </div>
                    <input type="text" name="meta_title" id="metaTitleInput" class="admin-input" placeholder="<?= e($article['title']) ?>" value="<?= e($article['meta_title'] ?? '') ?>" oninput="updateSeoPreview()">
                </div>

                <!-- Meta Description -->
                <div class="admin-form-group" style="margin-bottom: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="admin-label" style="margin: 0;">توضیحات متای سئو (Meta Description)</label>
                        <span style="font-size: 0.78rem; color: #64748b;" id="metaDescCount">0 / 160 حرف</span>
                    </div>
                    <textarea name="meta_description" id="metaDescInput" class="admin-textarea" rows="3" placeholder="<?= e($article['excerpt']) ?>" oninput="updateSeoPreview()"><?= e($article['meta_description'] ?? '') ?></textarea>
                </div>

                <!-- Canonical URL -->
                <div class="admin-form-group" style="margin-bottom: 24px;">
                    <label class="admin-label">آدرس کانونیکال (Canonical URL)</label>
                    <input type="url" name="canonical_url" class="admin-input" dir="ltr" placeholder="https://thermoban.ir/blog/<?= e($article['slug']) ?>" value="<?= e($article['canonical_url'] ?? '') ?>">
                </div>

                <!-- Real-time SEO Checklist -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <h4 style="margin: 0 0 12px 0; font-size: 0.92rem; color: #011C39; font-weight: 800;">چک‌لیست استانداردهای سئو آن‌پیج:</h4>
                    <ul id="seoChecklist" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 0.85rem;">
                        <li id="checkKwInTitle"><i class="fa-solid fa-circle-check text-success"></i> کلمه کلیدی در عنوان مقاله به کار رفته است.</li>
                        <li id="checkKwInDesc"><i class="fa-solid fa-circle-check text-success"></i> کلمه کلیدی در توضیحات متای سئو موجود است.</li>
                        <li id="checkKwInSlug"><i class="fa-solid fa-circle-check text-success"></i> نامک پیوند یکتا متناسب و بدون کاراکترهای اضافه است.</li>
                        <li id="checkContentLength"><i class="fa-solid fa-circle-check text-success"></i> محتوای مقاله به اندازه کافی جامع و مفصل است.</li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Sidebar Options Column (Left in RTL) -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Publish Action Box -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 16px 0; font-size: 1rem; color: #011C39; font-weight: 800;">
                    <i class="fa-solid fa-paper-plane text-orange"></i>
                    وضعیت انتشار
                </h3>

                <div style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; font-size: 0.9rem;">
                        <input type="checkbox" name="is_published" value="1" <?= ($article['is_published'] ?? 1) == 1 ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #EF5C16;">
                        <span>منتشر شده در سایت</span>
                    </label>
                </div>

                <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 16px;">
                    <div><i class="fa-solid fa-calendar-days" style="margin-left: 6px;"></i> تاریخ ثبت: <?= date('Y/m/d', strtotime($article['created_at'])) ?></div>
                    <div style="margin-top: 6px;"><i class="fa-solid fa-eye" style="margin-left: 6px;"></i> مجموع بازدید: <?= number_format($article['views_count'] ?? 0) ?></div>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="padding: 12px; font-weight: 800; font-size: 0.95rem;">
                    <i class="fa-solid fa-check"></i>
                    ذخیره و بروزرسانی مقاله
                </button>
            </div>

            <!-- Featured Image Box -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 16px 0; font-size: 1rem; color: #011C39; font-weight: 800;">
                    <i class="fa-solid fa-image text-orange"></i>
                    تصویر شاخص مقاله
                </h3>

                <?php
                $artImg = $article['image'] ?? 'hero-technician.jpg';
                $artImgUrl = (str_starts_with($artImg, 'http') || str_starts_with($artImg, '/uploads') || str_starts_with($artImg, 'uploads/'))
                    ? (str_starts_with($artImg, 'http') ? $artImg : url($artImg))
                    : asset('images/' . $artImg);
                ?>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; height: 160px; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 14px;">
                    <img id="articleImagePreview" src="<?= $artImgUrl ?>" alt="<?= e($article['title']) ?>" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                </div>

                <div class="admin-form-group" style="margin-bottom: 12px;">
                    <div style="display: flex; gap: 6px;">
                        <input type="text" name="image" id="articleImageInput" class="admin-input" value="<?= e($article['image']) ?>" style="font-size: 0.82rem;" placeholder="نام یا لینک تصویر">
                        <button type="button" class="btn btn-outline" style="padding: 6px 12px;" onclick="window.openMediaPicker(function(url){ document.getElementById('articleImageInput').value = url; document.getElementById('articleImagePreview').src = url; })">
                            <i class="fa-solid fa-images"></i>
                        </button>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px; display: block;">یا آپلود تصویر شاخص جدید:</label>
                    <input type="file" name="image_file" class="admin-input" accept="image/*" style="font-size: 0.82rem;" onchange="previewArticleImage(this)">
                </div>
            </div>

            <!-- Reading Time Box -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 14px 0; font-size: 1rem; color: #011C39; font-weight: 800;">
                    <i class="fa-solid fa-clock text-orange"></i>
                    زمان مطالعه
                </h3>
                <input type="text" name="reading_time" class="admin-input" value="<?= e($article['reading_time'] ?? '۵ دقیقه') ?>" placeholder="مثال: ۶ دقیقه">
            </div>

        </div>

    </div>
</form>

<script>
    // Initialize Quill Editor
    var quill;
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Quill !== 'undefined') {
            quill = new Quill('#quillEditor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['link', 'image', 'blockquote', 'code-block'],
                        ['clean']
                    ]
                }
            });

            // Sync with hidden input on submit
            var form = document.getElementById('articleEditForm');
            if (form) {
                form.addEventListener('submit', function() {
                    var html = quill.root.innerHTML;
                    document.getElementById('quillHiddenContent').value = html;
                });
            }
        }
        updateSeoPreview();
    });

    function previewArticleImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('articleImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateSeoPreview() {
        var title = document.getElementById('metaTitleInput').value.trim() || document.getElementById('artTitleInput').value.trim() || 'عنوان مقاله';
        var desc = document.getElementById('metaDescInput').value.trim() || document.getElementById('artExcerptInput').value.trim() || 'توضیحات متای مقاله...';
        var slug = document.getElementById('artSlugInput').value.trim() || 'post-slug';
        var keyword = document.getElementById('focusKeywordInput').value.trim().toLowerCase();

        document.getElementById('serpTitlePreview').innerText = title + ' | ترموبان';
        document.getElementById('serpDescPreview').innerText = desc;
        document.getElementById('serpSlugPreview').innerText = slug;

        document.getElementById('metaTitleCount').innerText = title.length + ' / 60 حرف';
        document.getElementById('metaDescCount').innerText = desc.length + ' / 160 حرف';

        // Score calculation & checklist
        var score = 50;
        if (keyword) {
            var inTitle = title.toLowerCase().indexOf(keyword) !== -1;
            var inDesc = desc.toLowerCase().indexOf(keyword) !== -1;
            var inSlug = slug.toLowerCase().indexOf(keyword.replace(/\s+/g, '-')) !== -1 || slug.toLowerCase().indexOf(keyword) !== -1;

            updateChecklistItem('checkKwInTitle', inTitle, 'کلمه کلیدی در عنوان سئو وجود دارد', 'کلمه کلیدی در عنوان یافت نشد');
            updateChecklistItem('checkKwInDesc', inDesc, 'کلمه کلیدی در توضیحات متای سئو وجود دارد', 'کلمه کلیدی در توضیحات متا یافت نشد');
            updateChecklistItem('checkKwInSlug', inSlug, 'کلمه کلیدی در نامک پیوند یکتا وجود دارد', 'کلمه کلیدی در نامک وجود ندارد');

            if (inTitle) score += 20;
            if (inDesc) score += 15;
            if (inSlug) score += 10;
        }

        if (desc.length >= 100 && desc.length <= 165) score += 5;

        score = Math.min(score, 100);
        var scoreValEl = document.getElementById('seoScoreValue');
        var scoreBadge = document.getElementById('seoScoreBadge');
        if (scoreValEl) scoreValEl.innerText = score;
        if (scoreBadge) {
            if (score >= 80) {
                scoreBadge.style.background = '#ecfdf5';
                scoreBadge.style.color = '#059669';
                scoreBadge.style.borderColor = '#a7f3d0';
            } else if (score >= 60) {
                scoreBadge.style.background = '#fffbeb';
                scoreBadge.style.color = '#d97706';
                scoreBadge.style.borderColor = '#fde68a';
            } else {
                scoreBadge.style.background = '#fef2f2';
                scoreBadge.style.color = '#dc2626';
                scoreBadge.style.borderColor = '#fecaca';
            }
        }
    }

    function updateChecklistItem(id, passed, successText, failText) {
        var el = document.getElementById(id);
        if (!el) return;
        if (passed) {
            el.innerHTML = '<i class="fa-solid fa-circle-check" style="color: #10b981; margin-left: 6px;"></i> ' + successText;
            el.style.color = '#1e293b';
        } else {
            el.innerHTML = '<i class="fa-solid fa-circle-xmark" style="color: #ef4444; margin-left: 6px;"></i> ' + failText;
            el.style.color = '#ef4444';
        }
    }
</script>
