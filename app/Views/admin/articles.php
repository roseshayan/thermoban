<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="margin-bottom: 20px;">
    <div>
        <h2 class="panel-main-title">مدیریت مقالات و دانشنامه آموزشی</h2>
        <p class="panel-subtitle">انتشار مقالات تخصصی عیب‌یابی پکیج، کدهای خطا و راهنمای نگهداری تأسیسات حرارتی</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('newArticleBox').style.display = (document.getElementById('newArticleBox').style.display === 'none' ? 'block' : 'none');">
        <?= icon('plus', '', 18) ?>
        <span>انتشار مقاله جدید</span>
    </button>
</div>

<!-- Add Article Form Box (Collapsible) -->
<div class="dashboard-block" id="newArticleBox" style="display: none; background: #fff; margin-bottom: 30px;">
    <h3 class="block-title" style="margin-bottom: 20px;">ثبت و انتشار مقاله آموزشی جدید</h3>
    <form action="<?= url('admin/articles') ?>" method="POST" class="js-validate-form">
        <input type="hidden" name="action" value="create">

        <div class="form-row-2">
            <div class="form-group">
                <label>عنوان کامل مقاله <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="مثال: آموزش رفع ارور ۴۰-۶۰-۸۰ ایران رادیاتور" required>
            </div>
            <div class="form-group">
                <label>نامک انگلیسی (Slug) <span class="required">*</span></label>
                <input type="text" name="slug" class="form-control" placeholder="error-40-60-80-fix" dir="ltr" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>زمان تقریبی مطالعه</label>
                <input type="text" name="reading_time" class="form-control" placeholder="۵ دقیقه" value="۵ دقیقه">
            </div>
            <div class="form-group">
                <label>نام تصویر شاخص</label>
                <input type="text" name="image" class="form-control" placeholder="hero-technician.jpg" value="hero-technician.jpg">
            </div>
        </div>

        <div class="form-group">
            <label>چکیده و خلاصه مقاله <span class="required">*</span></label>
            <textarea name="excerpt" rows="2" class="form-control" placeholder="خلاصه ۲ خطی برای پیش‌نمایش در صفحه لیست مقالات..." required></textarea>
        </div>

        <div class="form-group">
            <label>متن کامل مقاله <span class="required">*</span></label>
            <textarea name="content" rows="6" class="form-control" placeholder="محتوای جامع مقاله، راهکارها، مراحل تست و قطعات معیوب..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= icon('check', '', 18) ?>
            <span>انتشار مقاله در وب‌سایت</span>
        </button>
    </form>
</div>

<!-- Articles Table -->
<div class="dashboard-block" style="background: #fff;">
    <div class="orders-table-wrapper">
        <table class="profile-table">
            <thead>
                <tr>
                    <th>تصویر</th>
                    <th>عنوان مقاله</th>
                    <th>نامک (Slug)</th>
                    <th>زمان مطالعه</th>
                    <th>تعداد بازدید</th>
                    <th>تاریخ انتشار</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $art): ?>
                    <tr>
                        <td>
                            <img src="<?= asset('images/' . $art['image']) ?>" alt="<?= e($art['title']) ?>" style="width: 55px; height: 40px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td>
                            <strong><a href="<?= url('blog/' . $art['slug']) ?>" target="_blank"><?= e($art['title']) ?></a></strong>
                        </td>
                        <td dir="ltr"><?= e($art['slug']) ?></td>
                        <td><?= e($art['reading_time']) ?></td>
                        <td><?= number_format($art['views_count']) ?></td>
                        <td><?= e($art['created_at']) ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= url('admin/articles/edit/' . $art['id']) ?>" class="btn-table-action" style="color: #0284c7; border: 1px solid #bae6fd; background: #fff; text-decoration: none; padding: 4px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>ویرایش</span>
                                </a>
                                <form action="<?= url('admin/articles') ?>" method="POST" style="display:inline;" onsubmit="return confirm('آیا از حذف این مقاله اطمینان دارید؟');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $art['id'] ?>">
                                    <button type="submit" class="btn-table-action" style="color: #ef4444; border: 1px solid #fecaca; background: #fff; padding: 4px 10px; border-radius: 4px;">
                                        <i class="fa-solid fa-trash"></i>
                                        <span>حذف</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
