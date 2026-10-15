<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="admin-alert admin-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 class="panel-main-title" style="margin: 0 0 6px 0; font-size: 1.4rem; font-weight: 800; color: #011C39;">
            <i class="fa-solid fa-newspaper text-orange"></i>
            مدیریت مقالات و دانشنامه آموزشی
        </h2>
        <p class="panel-subtitle" style="margin: 0; color: #64748b; font-size: 0.9rem;">انتشار مقالات تخصصی عیب‌یابی پکیج، کدهای خطا، راهنمای نگهداری و بهینه‌سازی سئو</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="toggleNewArticleBox()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px; border-radius: 8px;">
        <i class="fa-solid fa-plus-circle"></i>
        <span>انتشار مقاله جدید</span>
    </button>
</div>

<!-- Add Article Form Box (Collapsible) -->
<div class="dashboard-block" id="newArticleBox" style="display: none; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 26px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
    <h3 class="block-title" style="margin: 0 0 20px 0; font-size: 1.2rem; color: #011C39; font-weight: 800; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
        <i class="fa-solid fa-file-pen text-orange"></i>
        ثبت و انتشار مقاله آموزشی جدید
    </h3>
    <form action="<?= url('admin/articles') ?>" method="POST" enctype="multipart/form-data" id="createArticleForm">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">

        <div class="form-row-2" style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">عنوان کامل مقاله <span style="color: #ef4444;">*</span></label>
                <input type="text" name="title" id="createArtTitle" class="admin-input" placeholder="مثال: آموزش جامع رفع ارور ۴۰-۶۰-۸۰ ایران رادیاتور" required oninput="syncCreateSeo()">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نامک انگلیسی (Slug) <span style="color: #ef4444;">*</span></label>
                <input type="text" name="slug" id="createArtSlug" class="admin-input" placeholder="error-40-60-80-fix" dir="ltr" required>
            </div>
        </div>

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">زمان تقریبی مطالعه</label>
                <input type="text" name="reading_time" class="admin-input" placeholder="۵ دقیقه" value="۵ دقیقه">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">تصویر شاخص مقاله</label>
                <div style="display: flex; gap: 6px;">
                    <input type="text" name="image" id="createArtImageInput" class="admin-input" placeholder="نام فایل یا لینک تصویر" value="hero-technician.jpg">
                    <button type="button" class="btn btn-outline" style="padding: 6px 14px;" onclick="window.openMediaPicker(function(url){ document.getElementById('createArtImageInput').value = url; })">
                        <i class="fa-solid fa-images"></i> کتابخانه
                    </button>
                </div>
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">یا آپلود فایل تصویر مستقیم:</div>
                <input type="file" name="image_file" class="admin-input" accept="image/*" style="margin-top: 4px;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 18px;">
            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">چکیده و خلاصه مقاله <span style="color: #ef4444;">*</span></label>
            <textarea name="excerpt" id="createArtExcerpt" rows="2" class="admin-textarea" placeholder="خلاصه ۲ خطی برای پیش‌نمایش در صفحه لیست مقالات..." required></textarea>
        </div>

        <!-- Quill WYSIWYG Editor -->
        <div class="form-group" style="margin-bottom: 24px;">
            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: flex; justify-content: space-between;">
                <span>متن کامل مقاله <span style="color: #ef4444;">*</span></span>
                <span style="font-size: 0.8rem; color: #10b981; font-weight: normal;"><i class="fa-solid fa-check"></i> ویرایشگر هوشمند محلی Quill.js</span>
            </label>
            <div id="createQuillEditor" style="min-height: 280px; background: #fff; direction: rtl; text-align: right; border-radius: 0 0 8px 8px;"></div>
            <input type="hidden" name="content" id="createQuillHidden">
        </div>

        <!-- SEO Box (Rank Math Pro style) -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h4 style="margin: 0 0 16px 0; font-size: 1rem; color: #011C39; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-chart-line text-orange"></i>
                بهینه‌سازی سئو مقاله (Rank Math Pro)
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 14px;">
                <div class="form-group">
                    <label class="admin-label">کلمه کلیدی کانونی (Focus Keyword)</label>
                    <input type="text" name="focus_keyword" class="admin-input" placeholder="مثال: تعمیر پکیج دیواری">
                </div>
                <div class="form-group">
                    <label class="admin-label">عنوان متای سئو (Meta Title)</label>
                    <input type="text" name="meta_title" id="createMetaTitle" class="admin-input" placeholder="پیش‌فرض: همان عنوان مقاله">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="admin-label">توضیحات متای سئو (Meta Description)</label>
                <textarea name="meta_description" class="admin-textarea" rows="2" placeholder="توضیحات جذاب و خلاصه برای جذب کلیک در صفحه اول گوگل (حداکثر ۱۶۰ حرف)..."></textarea>
            </div>

            <div class="form-group">
                <label class="admin-label">آدرس کانونیکال (Canonical URL اختیاری)</label>
                <input type="url" name="canonical_url" class="admin-input" dir="ltr" placeholder="https://thermoban.ir/blog/...">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-outline" onclick="toggleNewArticleBox()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
            <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 10px 24px; font-weight: 700;">
                <i class="fa-solid fa-check" style="margin-left: 6px;"></i> انتشار مقاله در وب‌سایت
            </button>
        </div>
    </form>
</div>

<!-- Articles Table -->
<div class="admin-card" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
            فهرست مقالات منتشر شده
            <span class="badge badge-info"><?= count($articles) ?> مقاله</span>
        </h3>
        <div style="position: relative; width: 280px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" id="articlesSearchInput" class="admin-input" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در عنوان یا نامک مقاله...">
        </div>
    </div>

    <div class="orders-table-wrapper" style="overflow-x: auto;">
        <table class="admin-table pro-table" id="articlesTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 70px;">تصویر</th>
                    <th>عنوان مقاله</th>
                    <th>نامک (Slug)</th>
                    <th>زمان مطالعه</th>
                    <th>تعداد بازدید</th>
                    <th>کلمه کانونی</th>
                    <th>تاریخ انتشار</th>
                    <th style="text-align: center; width: 130px;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr class="no-records-row">
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 35px 20px;">هیچ مقاله‌ای یافت نشد.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articles as $art): ?>
                        <?php
                        $artThumb = $art['image'] ?? 'hero-technician.jpg';
                        $thumbUrl = (str_starts_with($artThumb, 'http') || str_starts_with($artThumb, '/uploads') || str_starts_with($artThumb, 'uploads/'))
                            ? (str_starts_with($artThumb, 'http') ? $artThumb : url($artThumb))
                            : asset('images/' . $artThumb);
                        ?>
                        <tr>
                            <td>
                                <img src="<?= $thumbUrl ?>" alt="<?= e($art['title']) ?>" style="width: 55px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                            </td>
                            <td>
                                <strong style="color: #011C39;"><a href="<?= url('blog/' . $art['slug']) ?>" target="_blank" style="color: inherit; text-decoration: none;"><?= e($art['title']) ?></a></strong>
                            </td>
                            <td dir="ltr" style="text-align: right; color: #64748b; font-size: 0.85rem;"><code><?= e($art['slug']) ?></code></td>
                            <td><?= e($art['reading_time']) ?></td>
                            <td><span style="font-weight: 700; color: #EF5C16;"><?= number_format($art['views_count'] ?? 0) ?></span></td>
                            <td>
                                <?php if (!empty($art['focus_keyword'])): ?>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3; font-size: 0.75rem;"><?= e($art['focus_keyword']) ?></span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">—</span>
                                <?php endif; ?>
                            </td>
                            <td dir="ltr" style="text-align: right; font-size: 0.82rem; color: #64748b;"><?= !empty($art['created_at']) ? date('Y/m/d', strtotime($art['created_at'])) : '—' ?></td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                    <a href="<?= url('admin/articles/edit/' . $art['id']) ?>" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.8rem;" title="ویرایش مقاله">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="<?= url('admin/articles') ?>" method="POST" style="display:inline; margin: 0;" onsubmit="return confirm('آیا از حذف این مقاله اطمینان دارید؟');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $art['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline text-danger" style="padding: 4px 10px; font-size: 0.8rem;" title="حذف مقاله">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div id="articlesPagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<script>
var createQuill;
function toggleNewArticleBox() {
    var box = document.getElementById('newArticleBox');
    if (box) {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (box.style.display === 'block') {
            box.scrollIntoView({ behavior: 'smooth' });
            if (!createQuill && typeof Quill !== 'undefined') {
                createQuill = new Quill('#createQuillEditor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['link', 'image', 'blockquote', 'code-block'],
                            ['clean']
                        ]
                    }
                });

                var form = document.getElementById('createArticleForm');
                if (form) {
                    form.addEventListener('submit', function() {
                        document.getElementById('createQuillHidden').value = createQuill.root.innerHTML;
                    });
                }
            }
        }
    }
}

function syncCreateSeo() {
    var title = document.getElementById('createArtTitle').value;
    var meta = document.getElementById('createMetaTitle');
    if (meta && !meta.value) {
        meta.placeholder = title;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('articlesTable', {
            searchInputId: 'articlesSearchInput',
            paginationContainerId: 'articlesPagination',
            pageSize: 10
        });
    }
});
</script>
