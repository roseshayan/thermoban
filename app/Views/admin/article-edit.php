<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-pen-nib" style="color: #EF5C16; margin-left: 8px;"></i>
                ویرایش مقاله آموزشی: <?= e($article['title']) ?>
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                بروزرسانی محتوای آموزشی، نامک، تصویر شاخص و وضعیت انتشار مقاله
            </p>
        </div>
        <a href="<?= url('admin/articles') ?>" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px;">
            <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i>
            بازگشت به مقالات
        </a>
    </div>
</div>

<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    <form action="<?= url('admin/articles/edit/' . $article['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">عنوان مقاله <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= e($article['title']) ?>" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">نامک پیوند یکتا (Slug) <span class="required">*</span></label>
                <input type="text" name="slug" class="form-control" dir="ltr" value="<?= e($article['slug']) ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">نام فایل تصویر شاخص</label>
                <input type="text" name="image" class="form-control" dir="ltr" value="<?= e($article['image']) ?>">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">مدت زمان تقریبی مطالعه</label>
                <input type="text" name="reading_time" class="form-control" value="<?= e($article['reading_time'] ?? '۵ دقیقه') ?>">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">خلاصه یا چکیده کوتاه (برای کارت مقاله و سئو)</label>
            <textarea name="excerpt" class="form-control" rows="2"><?= e($article['excerpt']) ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">متن کامل مقاله <span class="required">*</span></label>
            <textarea name="content" class="form-control" rows="12" required><?= e($article['content']) ?></textarea>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="is_published" value="1" <?= ($article['is_published'] ?? 1) == 1 ? 'checked' : '' ?>>
                وضعیت انتشار عمومی در سایت
            </label>

            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-weight: 700; font-size: 0.95rem;">
                <i class="fa-solid fa-check" style="margin-left: 6px;"></i>
                بروزرسانی مقاله
            </button>
        </div>
    </form>
</div>
