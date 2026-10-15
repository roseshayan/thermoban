<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-box-open" style="color: #EF5C16; margin-left: 8px;"></i>
                ویرایش محصول: <?= e($product['title']) ?>
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                بروزرسانی مشخصات فنی پکیج، قیمت‌گذاری، موجودی انبار و گارانتی طلایی
            </p>
        </div>
        <a href="<?= url('admin/products') ?>" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px;">
            <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i>
            بازگشت به محصولات
        </a>
    </div>
</div>

<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
    <form action="<?= url('admin/products/edit/' . $product['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">عنوان کامل محصول <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= e($product['title']) ?>" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">برند سازنده <span class="required">*</span></label>
                <select name="brand_id" class="form-control">
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= $b['id'] == $product['brand_id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">کد مدل تجاری <span class="required">*</span></label>
                <input type="text" name="model" class="form-control" dir="ltr" value="<?= e($product['model']) ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">ظرفیت حرارتی (kcal)</label>
                <input type="number" name="capacity" class="form-control" dir="ltr" value="<?= e($product['capacity']) ?>" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">متراژ پوشش‌دهی</label>
                <input type="text" name="area" class="form-control" value="<?= e($product['area']) ?>" placeholder="تا ۱۵۰ متر">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">نوع مبدل</label>
                <input type="text" name="heat_exchanger" class="form-control" value="<?= e($product['heat_exchanger']) ?>" placeholder="دو مبدل">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">نوع دودکش</label>
                <input type="text" name="flue_type" class="form-control" value="<?= e($product['flue_type']) ?>" placeholder="فن‌دار">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">قیمت فروش (تومان) <span class="required">*</span></label>
                <input type="number" name="price" class="form-control" dir="ltr" value="<?= e($product['price']) ?>" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">قیمت قبل از تخفیف</label>
                <input type="number" name="old_price" class="form-control" dir="ltr" value="<?= e($product['old_price'] ?? '') ?>" placeholder="اختیاری">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">موجودی در انبار</label>
                <input type="number" name="stock" class="form-control" dir="ltr" value="<?= e($product['stock']) ?>" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 6px;">ضمانت و گارانتی</label>
                <input type="text" name="warranty" class="form-control" value="<?= e($product['warranty']) ?>" placeholder="۲۴ ماه ضمانت طلایی">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">نام فایل تصویر</label>
                <input type="text" name="image" class="form-control" dir="ltr" value="<?= e($product['image']) ?>">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">ویژگی‌های برجسته (هر خط یک ویژگی)</label>
                <?php 
                    $feats = json_decode($product['features'] ?? '[]', true) ?: [];
                    $featStr = implode("\n", $feats);
                ?>
                <textarea name="features" class="form-control" rows="3"><?= e($featStr) ?></textarea>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label style="font-weight: 700; font-size: 0.88rem; display: block; margin-bottom: 6px;">توضیحات و نقد و بررسی دستگاه</label>
            <textarea name="description" class="form-control" rows="6"><?= e($product['description']) ?></textarea>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" <?= ($product['is_active'] ?? 1) == 1 ? 'checked' : '' ?>>
                وضعیت فعال در فروشگاه آنلاین
            </label>

            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-weight: 700; font-size: 0.95rem;">
                <i class="fa-solid fa-check" style="margin-left: 6px;"></i>
                ذخیره تغییرات محصول
            </button>
        </div>
    </form>
</div>
