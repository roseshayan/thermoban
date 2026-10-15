<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 class="panel-main-title" style="margin: 0 0 6px 0; font-size: 1.4rem; font-weight: 800; color: #011C39;">مدیریت مقالات و دانشنامه آموزشی</h2>
        <p class="panel-subtitle" style="margin: 0; color: #64748b; font-size: 0.9rem;">انتشار مقالات تخصصی عیب‌یابی پکیج، کدهای خطا و راهنمای نگهداری تأسیسات حرارتی</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="toggleNewArticleBox()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px; border-radius: 8px;">
        <i class="fa-solid fa-plus-circle"></i>
        <span>انتشار مقاله جدید</span>
    </button>
</div>

<!-- Add Article Form Box (Collapsible) -->
<div class="dashboard-block" id="newArticleBox" style="display: none; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <h3 class="block-title" style="margin: 0 0 20px 0; font-size: 1.15rem; color: #011C39; font-weight: 800; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-file-pen" style="color: #EF5C16;"></i>
        ثبت و انتشار مقاله آموزشی جدید
    </h3>
    <form action="<?= url('admin/articles') ?>" method="POST" class="js-validate-form">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">عنوان کامل مقاله <span style="color: #ef4444;">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="مثال: آموزش جامع رفع ارور ۴۰-۶۰-۸۰ ایران رادیاتور" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نامک انگلیسی (Slug) <span style="color: #ef4444;">*</span></label>
                <input type="text" name="slug" class="form-control" placeholder="error-40-60-80-fix" dir="ltr" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
        </div>

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">زمان تقریبی مطالعه</label>
                <input type="text" name="reading_time" class="form-control" placeholder="۵ دقیقه" value="۵ دقیقه" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نام تصویر شاخص</label>
                <input type="text" name="image" class="form-control" placeholder="hero-technician.jpg" value="hero-technician.jpg" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">چکیده و خلاصه مقاله <span style="color: #ef4444;">*</span></label>
            <textarea name="excerpt" rows="2" class="form-control" placeholder="خلاصه ۲ خطی برای پیش‌نمایش در صفحه لیست مقالات..." required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;"></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">متن کامل مقاله <span style="color: #ef4444;">*</span></label>
            <textarea name="content" rows="6" class="form-control" placeholder="محتوای جامع مقاله، راهکارها، مراحل تست و قطعات معیوب..." required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-outline" onclick="toggleNewArticleBox()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
            <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 8px 22px; font-weight: 700;">
                <i class="fa-solid fa-check" style="margin-left: 6px;"></i> انتشار مقاله در وب‌سایت
            </button>
        </div>
    </form>
</div>

<!-- Articles Table -->
<div class="dashboard-block" style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            فهرست مقالات منتشر شده
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">(<?= count($articles) ?> مقاله)</span>
        </h3>
        <div style="position: relative; width: 280px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" id="articlesSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در عنوان یا نامک مقاله...">
        </div>
    </div>

    <div class="orders-table-wrapper" style="overflow-x: auto;">
        <table class="profile-table" id="articlesTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>تصویر</th>
                    <th>عنوان مقاله</th>
                    <th>نامک (Slug)</th>
                    <th>زمان مطالعه</th>
                    <th>تعداد بازدید</th>
                    <th>تاریخ انتشار</th>
                    <th style="text-align: center;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr class="no-records-row">
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 35px 20px;">هیچ مقاله‌ای یافت نشد.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articles as $art): ?>
                        <tr>
                            <td>
                                <img src="<?= asset('images/' . $art['image']) ?>" alt="<?= e($art['title']) ?>" style="width: 55px; height: 40px; object-fit: cover; border-radius: 6px;">
                            </td>
                            <td>
                                <strong style="color: #011C39;"><a href="<?= url('blog/' . $art['slug']) ?>" target="_blank" style="color: inherit; text-decoration: none;"><?= e($art['title']) ?></a></strong>
                            </td>
                            <td dir="ltr" style="text-align: right; color: #64748b; font-size: 0.85rem;"><?= e($art['slug']) ?></td>
                            <td><?= e($art['reading_time']) ?></td>
                            <td><span style="font-weight: 700; color: #EF5C16;"><?= number_format($art['views_count']) ?></span></td>
                            <td dir="ltr" style="text-align: right; font-size: 0.82rem; color: #64748b;"><?= e($art['created_at']) ?></td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                    <a href="<?= url('admin/articles/edit/' . $art['id']) ?>" class="btn-table-action" style="color: #0284c7; border: 1px solid #bae6fd; background: #fff; text-decoration: none; padding: 5px 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; font-size: 0.8rem;">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>ویرایش</span>
                                    </a>
                                    <form action="<?= url('admin/articles') ?>" method="POST" style="display:inline; margin: 0;" onsubmit="return confirm('آیا از حذف این مقاله اطمینان دارید؟');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $art['id'] ?>">
                                        <button type="submit" class="btn-table-action" style="color: #ef4444; border: 1px solid #fecaca; background: #fff; padding: 5px 10px; border-radius: 6px; font-size: 0.8rem;" title="حذف مقاله">
                                            <i class="fa-solid fa-trash"></i>
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
function toggleNewArticleBox() {
    var box = document.getElementById('newArticleBox');
    if (box) {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (box.style.display === 'block') {
            box.scrollIntoView({ behavior: 'smooth' });
        }
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
