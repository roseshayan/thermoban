<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
            <h1 style="font-size: 1.45rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-boxes-stacked text-orange" style="margin-left: 8px;"></i>
                ویرایش محصول: <?= e($product['title']) ?>
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                مدیریت مشخصات فنی، قیمت، تصویر اصلی، گالری چندگانه، ویژگی‌های برجسته و توضیحات
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= url('product/' . $product['id']) ?>" target="_blank" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>مشاهده در فروشگاه</span>
            </a>
            <a href="<?= url('admin/products') ?>" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px;">
                <i class="fa-solid fa-arrow-right"></i>
                <span>بازگشت به لیست محصولات</span>
            </a>
        </div>
    </div>
</div>

<form action="<?= url('admin/products/edit/' . $product['id']) ?>" method="POST" enctype="multipart/form-data" id="editProductForm">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
        
        <!-- Right Column: Main Info, Specs, Gallery, Quill -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Basic Product Info -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 20px 0; font-size: 1.1rem; color: #011C39; font-weight: 800; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    <i class="fa-solid fa-circle-info text-orange"></i>
                    اطلاعات پایه و برند
                </h3>

                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                    <div class="admin-form-group">
                        <label class="admin-label">عنوان تجاری محصول <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="admin-input" value="<?= e($product['title']) ?>" required>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">برند سازنده <span class="text-danger">*</span></label>
                        <select name="brand_id" class="admin-input" required>
                            <?php foreach ($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= $b['id'] == $product['brand_id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">کد مدل تجاری <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="admin-input" dir="ltr" value="<?= e($product['model']) ?>" required>
                    </div>
                </div>

                <!-- Pricing & Stock -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    <div class="admin-form-group">
                        <label class="admin-label">قیمت فروش نقدی (تومان) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="admin-input" dir="ltr" value="<?= e($product['price']) ?>" required>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">قیمت خط خورده (تخفیف)</label>
                        <input type="number" name="old_price" class="admin-input" dir="ltr" value="<?= e($product['old_price'] ?? '') ?>" placeholder="اختیاری">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">موجودی در انبار (عدد) <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="admin-input" dir="ltr" value="<?= e($product['stock']) ?>" required>
                    </div>
                </div>
            </div>

            <!-- Technical Specifications -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 20px 0; font-size: 1.1rem; color: #011C39; font-weight: 800; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    <i class="fa-solid fa-wrench text-orange"></i>
                    مشخصات فنی و شرایط گارانتی
                </h3>

                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 18px;">
                    <div class="admin-form-group">
                        <label class="admin-label">ظرفیت حرارتی (kcal) <span class="text-danger">*</span></label>
                        <input type="number" name="capacity" class="admin-input" dir="ltr" value="<?= e($product['capacity']) ?>" required>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">متراژ پوشش‌دهی</label>
                        <input type="text" name="area" class="admin-input" value="<?= e($product['area']) ?>" placeholder="تا ۱۵۰ متر">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">نوع مبدل حرارتی</label>
                        <input type="text" name="heat_exchanger" class="admin-input" value="<?= e($product['heat_exchanger']) ?>" placeholder="دو مبدل مجزا">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">نوع دودکش</label>
                        <input type="text" name="flue_type" class="admin-input" value="<?= e($product['flue_type']) ?>" placeholder="فن‌دار دو جداره">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">شرایط ضمانت و خدمات پس از فروش</label>
                    <input type="text" name="warranty" class="admin-input" value="<?= e($product['warranty']) ?>" placeholder="۲۴ ماه ضمانت طلایی + نصب رایگان">
                </div>
            </div>

            <!-- Multi-Image Gallery Manager -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 18px;">
                    <div>
                        <h3 style="margin: 0 0 4px 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
                            <i class="fa-solid fa-images text-orange"></i>
                            گالری تصاویر چندگانه محصول
                        </h3>
                        <p style="margin: 0; font-size: 0.82rem; color: #64748b;">تصاویر زوایای مختلف، پنل دیجیتال و قطعات داخلی پکیج</p>
                    </div>
                    <button type="button" class="btn btn-outline" onclick="openGalleryMediaPicker()">
                        <i class="fa-solid fa-folder-open"></i>
                        <span>انتخاب از کتابخانه رسانه</span>
                    </button>
                </div>

                <!-- Gallery Preview Grid -->
                <?php
                $existingGallery = [];
                if (!empty($product['gallery'])) {
                    $decoded = json_decode($product['gallery'], true);
                    if (is_array($decoded)) $existingGallery = $decoded;
                }
                ?>
                <div id="galleryItemsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 14px; margin-bottom: 16px; min-height: 80px;">
                    <?php if (empty($existingGallery)): ?>
                        <div id="noGalleryNotice" style="grid-column: 1 / -1; padding: 24px; text-align: center; color: #94a3b8; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                            <i class="fa-solid fa-photo-film" style="font-size: 1.8rem; margin-bottom: 6px; opacity: 0.6;"></i>
                            <div>هنوز هیچ تصویری در گالری این محصول ثبت نشده است.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($existingGallery as $gImg): ?>
                            <?php
                            $gUrl = (str_starts_with($gImg, 'http') || str_starts_with($gImg, '/uploads') || str_starts_with($gImg, 'uploads/'))
                                ? (str_starts_with($gImg, 'http') ? $gImg : url($gImg))
                                : asset('images/' . $gImg);
                            ?>
                            <div class="gallery-item-card" style="position: relative; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; height: 110px; display: flex; align-items: center; justify-content: center;">
                                <img src="<?= $gUrl ?>" alt="Gallery" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                <input type="hidden" name="existing_gallery[]" value="<?= e($gImg) ?>">
                                <button type="button" onclick="this.parentElement.remove()" style="position: absolute; top: 6px; left: 6px; background: rgba(239,68,68,0.9); color: #fff; border: none; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.75rem;" title="حذف این تصویر">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="admin-form-group">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">
                        یا آپلود گروهی تصاویر جدید از سیستم:
                    </label>
                    <input type="file" name="gallery_files[]" class="admin-input" accept="image/*" multiple style="font-size: 0.85rem;">
                    <small class="text-muted">می‌توانید همزمان چند فایل را با کلید Ctrl یا Shift انتخاب کنید.</small>
                </div>
            </div>

            <!-- Features Builder -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 16px;">
                    <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
                        <i class="fa-solid fa-list-check text-orange"></i>
                        ویژگی‌ها و مزایای محصول
                    </h3>
                    <button type="button" class="btn btn-sm btn-outline" onclick="addFeatureItem()">
                        <i class="fa-solid fa-plus"></i>
                        <span>افزودن مورد</span>
                    </button>
                </div>

                <?php 
                $feats = json_decode($product['features'] ?? '[]', true) ?: [];
                ?>
                <div id="featuresListContainer" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px;">
                    <?php if (empty($feats)): ?>
                        <div class="feature-row" style="display: flex; gap: 8px;">
                            <input type="text" name="features[]" class="admin-input" placeholder="مثال: نمایشگر دیجیتال هوشمند با عیب‌یاب کدهای خطا">
                            <button type="button" class="btn btn-outline text-danger" onclick="this.parentElement.remove()" style="padding: 0 12px;"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($feats as $f): ?>
                            <div class="feature-row" style="display: flex; gap: 8px;">
                                <input type="text" name="features[]" class="admin-input" value="<?= e($f) ?>">
                                <button type="button" class="btn btn-outline text-danger" onclick="this.parentElement.remove()" style="padding: 0 12px;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quill Description Editor -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 16px 0; font-size: 1.1rem; color: #011C39; font-weight: 800; display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fa-solid fa-align-right text-orange"></i> نقد و بررسی تخصصی و معرفی دستگاه</span>
                    <span style="font-size: 0.78rem; font-weight: normal; color: #10b981;"><i class="fa-solid fa-check"></i> ویرایشگر هوشمند Quill.js</span>
                </h3>

                <div id="productQuillEditor" style="min-height: 280px; font-size: 0.95rem; line-height: 1.8; direction: rtl; text-align: right; background: #fff;">
                    <?= $product['description'] ?>
                </div>
                <input type="hidden" name="description" id="productQuillHidden">
            </div>

        </div>

        <!-- Left Column: Actions & Main Image -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Publish / Active Card -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 16px 0; font-size: 1rem; color: #011C39; font-weight: 800;">
                    <i class="fa-solid fa-circle-check text-orange"></i>
                    وضعیت در فروشگاه
                </h3>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; font-size: 0.9rem;">
                        <input type="checkbox" name="is_active" value="1" <?= ($product['is_active'] ?? 1) == 1 ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #EF5C16;">
                        <span>نمایش و امکان خرید آنلاین</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="padding: 12px; font-weight: 800; font-size: 0.95rem;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    ذخیره تغییرات محصول
                </button>
            </div>

            <!-- Featured Image Card -->
            <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 16px 0; font-size: 1rem; color: #011C39; font-weight: 800;">
                    <i class="fa-solid fa-image text-orange"></i>
                    تصویر اصلی محصول
                </h3>

                <?php
                $pImg = $product['image'] ?? 'package-bita-24.png';
                $pImgUrl = (str_starts_with($pImg, 'http') || str_starts_with($pImg, '/uploads') || str_starts_with($pImg, 'uploads/'))
                    ? (str_starts_with($pImg, 'http') ? $pImg : url($pImg))
                    : asset('images/' . $pImg);
                ?>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; height: 190px; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 14px; padding: 10px;">
                    <img id="productImagePreview" src="<?= $pImgUrl ?>" alt="<?= e($product['title']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>

                <div class="admin-form-group" style="margin-bottom: 12px;">
                    <div style="display: flex; gap: 6px;">
                        <input type="text" name="image" id="productImageInput" class="admin-input" value="<?= e($product['image']) ?>" style="font-size: 0.82rem;" placeholder="آدرس یا نام تصویر">
                        <button type="button" class="btn btn-outline" style="padding: 6px 12px;" onclick="window.openMediaPicker(function(url){ document.getElementById('productImageInput').value = url; document.getElementById('productImagePreview').src = url; })">
                            <i class="fa-solid fa-images"></i>
                        </button>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px; display: block;">یا آپلود فایل مستقیم از سیستم:</label>
                    <input type="file" name="image_file" class="admin-input" accept="image/*" style="font-size: 0.82rem;" onchange="previewProductImage(this)">
                </div>
            </div>

        </div>

    </div>
</form>

<script>
var productQuill;
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.initPersianQuill === 'function') {
        productQuill = window.initPersianQuill('#productQuillEditor', '#productQuillHidden', {
            placeholder: 'توضیحات و نقد و بررسی کارشناسی دستگاه را اینجا وارد نمایید...'
        });
    }
});

function previewProductImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('productImagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function openGalleryMediaPicker() {
    window.openMediaPicker(function(selectedUrl) {
        var notice = document.getElementById('noGalleryNotice');
        if (notice) notice.remove();

        var container = document.getElementById('galleryItemsContainer');
        var card = document.createElement('div');
        card.className = 'gallery-item-card';
        card.style.cssText = 'position: relative; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; height: 110px; display: flex; align-items: center; justify-content: center;';
        card.innerHTML = '<img src="' + selectedUrl + '" alt="Gallery" style="max-width: 100%; max-height: 100%; object-fit: contain;">' +
            '<input type="hidden" name="existing_gallery[]" value="' + selectedUrl + '">' +
            '<button type="button" onclick="this.parentElement.remove()" style="position: absolute; top: 6px; left: 6px; background: rgba(239,68,68,0.9); color: #fff; border: none; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.75rem;" title="حذف این تصویر">' +
                '<i class="fa-solid fa-trash"></i>' +
            '</button>';
        container.appendChild(card);
    });
}

function addFeatureItem() {
    var container = document.getElementById('featuresListContainer');
    var row = document.createElement('div');
    row.className = 'feature-row';
    row.style.cssText = 'display: flex; gap: 8px;';
    row.innerHTML = '<input type="text" name="features[]" class="admin-input" placeholder="ویژگی جدید...">' +
        '<button type="button" class="btn btn-outline text-danger" onclick="this.parentElement.remove()" style="padding: 0 12px;"><i class="fa-solid fa-xmark"></i></button>';
    container.appendChild(row);
}
</script>
