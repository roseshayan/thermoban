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
            <i class="fa-solid fa-boxes-stacked text-orange"></i>
            مدیریت محصولات و پکیج‌های گرمایشی
        </h2>
        <p class="panel-subtitle" style="margin: 0; color: #64748b; font-size: 0.9rem;">تعریف و بروزرسانی قیمت روز، کاتالوگ فنی، گالری تصاویر و انبار پکیج‌های دیواری</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="toggleNewProductBox()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px; border-radius: 8px;">
        <i class="fa-solid fa-plus-circle"></i>
        <span>افزودن محصول جدید</span>
    </button>
</div>

<!-- Add Product Form Box (Collapsible) -->
<div class="dashboard-block" id="newProductBox" style="display: none; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 26px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
    <h3 class="block-title" style="margin: 0 0 20px 0; font-size: 1.2rem; color: #011C39; font-weight: 800; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
        <i class="fa-solid fa-plus-circle text-orange"></i>
        مشخصات فنی، قیمت‌گذاری و تصاویر پکیج جدید
    </h3>
    <form action="<?= url('admin/products') ?>" method="POST" enctype="multipart/form-data" id="createProductForm">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">برند دستگاه <span style="color: #ef4444;">*</span></label>
                <select name="brand_id" class="admin-input" required>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">عنوان کامل تجاری <span style="color: #ef4444;">*</span></label>
                <input type="text" name="title" class="admin-input" placeholder="مثال: پکیج دیواری ایران رادیاتور مدل L28FF" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">کد مدل تجاری <span style="color: #ef4444;">*</span></label>
                <input type="text" name="model" class="admin-input" placeholder="L28FF - دو مبدل فن‌دار" dir="ltr" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">ظرفیت (kcal) <span style="color: #ef4444;">*</span></label>
                <input type="number" name="capacity" class="admin-input" placeholder="28000" dir="ltr" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">متراژ پوشش‌دهی <span style="color: #ef4444;">*</span></label>
                <input type="text" name="area" class="admin-input" placeholder="تا ۱۸۰ مترمربع" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نوع مبدل حرارتی <span style="color: #ef4444;">*</span></label>
                <input type="text" name="heat_exchanger" class="admin-input" placeholder="دو مبدل مجزا" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نوع محفظه و دودکش <span style="color: #ef4444;">*</span></label>
                <input type="text" name="flue_type" class="admin-input" placeholder="فن‌دار محفظه بسته" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">قیمت فروش نقدی (تومان) <span style="color: #ef4444;">*</span></label>
                <input type="number" name="price" class="admin-input" placeholder="26500000" dir="ltr" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">قیمت قبل از تخفیف</label>
                <input type="number" name="old_price" class="admin-input" placeholder="28000000" dir="ltr">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">موجودی انبار <span style="color: #ef4444;">*</span></label>
                <input type="number" name="stock" class="admin-input" value="10" dir="ltr" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">شرایط گارانتی و نصب <span style="color: #ef4444;">*</span></label>
                <input type="text" name="warranty" class="admin-input" value="۲۴ ماه ضمانت طلایی شرکتی + نصب رایگان" required>
            </div>
        </div>

        <!-- Images and Gallery Row -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">تصویر اصلی محصول</label>
                    <div style="display: flex; gap: 6px; margin-bottom: 6px;">
                        <input type="text" name="image" id="createProductImgInput" class="admin-input" placeholder="آدرس یا نام فایل تصویر" value="package-thumb.png">
                        <button type="button" class="btn btn-outline" onclick="window.openMediaPicker(function(url){ document.getElementById('createProductImgInput').value = url; })">
                            <i class="fa-solid fa-images"></i> کتابخانه
                        </button>
                    </div>
                    <input type="file" name="image_file" class="admin-input" accept="image/*" style="font-size: 0.85rem;">
                </div>

                <div>
                    <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">گالری تصاویر چندگانه</label>
                    <div id="createGalleryItemsContainer" style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; min-height: 38px;"></div>
                    <button type="button" class="btn btn-outline btn-sm mb-2" onclick="openCreateGalleryMediaPicker()">
                        <i class="fa-solid fa-folder-open"></i> انتخاب از کتابخانه
                    </button>
                    <input type="file" name="gallery_files[]" class="admin-input" accept="image/*" multiple style="font-size: 0.85rem;">
                    <small class="text-muted">امکان انتخاب همزمان چند فایل برای گالری</small>
                </div>
            </div>
        </div>

        <!-- Dynamic Features Builder -->
        <div class="form-group" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 700; color: #1e293b; margin: 0;">ویژگی‌ها و مزایای برجسته محصول</label>
                <button type="button" class="btn btn-sm btn-outline" onclick="addCreateFeatureItem()">
                    <i class="fa-solid fa-plus"></i> افزودن ویژگی
                </button>
            </div>
            <div id="createFeaturesContainer" style="display: flex; flex-direction: column; gap: 8px;">
                <div class="feature-item-row" style="display: flex; gap: 8px;">
                    <input type="text" name="features[]" class="admin-input" value="نمایشگر دیجیتال پیشرفته با نمایش کدهای خطا">
                    <button type="button" class="btn btn-outline text-danger" onclick="this.parentElement.remove()" style="padding: 0 12px;"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="feature-item-row" style="display: flex; gap: 8px;">
                    <input type="text" name="features[]" class="admin-input" value="مجهز به دو مبدل حرارتی مجزا مقاوم در برابر رسوب">
                    <button type="button" class="btn btn-outline text-danger" onclick="this.parentElement.remove()" style="padding: 0 12px;"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="feature-item-row" style="display: flex; gap: 8px;">
                    <input type="text" name="features[]" class="admin-input" value="سیستم محافظت در برابر یخ‌زدگی و پمپ ۳ سرعته اروپایی">
                    <button type="button" class="btn btn-outline text-danger" onclick="this.parentElement.remove()" style="padding: 0 12px;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        </div>

        <!-- Quill Description -->
        <div class="form-group" style="margin-bottom: 24px;">
            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: flex; justify-content: space-between;">
                <span>توضیحات و نقد و بررسی کارشناسی <span style="color: #ef4444;">*</span></span>
                <span style="font-size: 0.8rem; color: #10b981; font-weight: normal;"><i class="fa-solid fa-check"></i> ویرایشگر هوشمند Quill.js</span>
            </label>
            <div id="createProductQuill" style="min-height: 220px; background: #fff; direction: rtl; text-align: right; border-radius: 0 0 8px 8px;"></div>
            <input type="hidden" name="description" id="createProductQuillHidden">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-outline" onclick="toggleNewProductBox()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
            <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 10px 26px; font-weight: 700;">
                <i class="fa-solid fa-check" style="margin-left: 6px;"></i> ذخیره و انتشار محصول در فروشگاه
            </button>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="admin-card" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">
            لیست پکیج‌های ثبت شده در انبار 
            <span class="badge badge-info"><?= count($products) ?> محصول</span>
        </h3>
        
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <select id="productBrandFilter" class="admin-input" style="width: 170px; font-size: 0.85rem; height: 38px;" onchange="window.filterTableByColumn('productsTable', 2, this.value)">
                <option value="">همه برندها</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= e($b['name']) ?>"><?= e($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <div style="position: relative; width: 280px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                <input type="text" id="productsSearchInput" class="admin-input" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجوی سریع در نام، مدل یا قیمت...">
            </div>
        </div>
    </div>

    <div class="orders-table-wrapper" style="overflow-x: auto;">
        <table class="admin-table pro-table" id="productsTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 70px;">تصویر</th>
                    <th>نام پکیج</th>
                    <th>برند</th>
                    <th>مدل</th>
                    <th>ظرفیت</th>
                    <th>قیمت (تومان)</th>
                    <th>موجودی</th>
                    <th style="text-align: center; width: 130px;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr class="no-records-row">
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 35px 20px;">محصولی یافت نشد.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <?php
                        $pImg = $p['image'] ?? 'package-thumb.png';
                        $thumbUrl = (str_starts_with($pImg, 'http') || str_starts_with($pImg, '/uploads') || str_starts_with($pImg, 'uploads/'))
                            ? (str_starts_with($pImg, 'http') ? $pImg : url($pImg))
                            : asset('images/' . $pImg);
                        ?>
                        <tr>
                            <td>
                                <img src="<?= $thumbUrl ?>" alt="<?= e($p['title']) ?>" style="width: 48px; height: 48px; object-fit: contain; background: #fafbfc; border-radius: 6px; padding: 4px; border: 1px solid #e2e8f0;">
                            </td>
                            <td>
                                <strong style="color: #011C39;"><a href="<?= url('product/' . $p['id']) ?>" target="_blank" style="color: inherit; text-decoration: none;"><?= e($p['title']) ?></a></strong>
                            </td>
                            <td><span style="background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.82rem; color: #334155;"><?= e($p['brand_name'] ?? $p['brand'] ?? 'ایران رادیاتور') ?></span></td>
                            <td dir="ltr" style="text-align: right;"><?= e($p['model']) ?></td>
                            <td><?= number_format($p['capacity']) ?> kcal</td>
                            <td><strong style="color: #EF5C16;"><?= number_format($p['price']) ?></strong></td>
                            <td>
                                <span class="badge <?= ($p['stock'] ?? 10) > 0 ? 'badge-success' : 'badge-danger' ?>">
                                    <?= ($p['stock'] ?? 10) ?> عدد
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                    <a href="<?= url('admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.8rem;" title="ویرایش محصول">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="<?= url('admin/products') ?>" method="POST" style="display:inline; margin: 0;" onsubmit="return confirmSwalDelete(event, this, '<?= e($p['title']) ?>');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline text-danger" style="padding: 4px 10px; font-size: 0.8rem;" title="حذف پکیج">
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
    <div id="productsTablePagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<script>
var createProductQuillInst;
function toggleNewProductBox() {
    var box = document.getElementById('newProductBox');
    if (box) {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (box.style.display === 'block') {
            box.scrollIntoView({ behavior: 'smooth' });
            if (!createProductQuillInst && typeof Quill !== 'undefined') {
                createProductQuillInst = new Quill('#createProductQuill', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image', 'clean']
                        ]
                    }
                });

                var form = document.getElementById('createProductForm');
                if (form) {
                    form.addEventListener('submit', function() {
                        document.getElementById('createProductQuillHidden').value = createProductQuillInst.root.innerHTML;
                    });
                }
            }
        }
    }
}

function openCreateGalleryMediaPicker() {
    window.openMediaPicker(function(selectedUrl) {
        var container = document.getElementById('createGalleryItemsContainer');
        var card = document.createElement('div');
        card.style.cssText = 'position: relative; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; width: 60px; height: 60px; background: #fff; display: flex; align-items: center; justify-content: center;';
        card.innerHTML = '<img src="' + selectedUrl + '" alt="Gallery" style="max-width: 100%; max-height: 100%; object-fit: contain;">' +
            '<input type="hidden" name="gallery[]" value="' + selectedUrl + '">' +
            '<button type="button" onclick="this.parentElement.remove()" style="position: absolute; top: 2px; left: 2px; background: rgba(239,68,68,0.9); color: #fff; border: none; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.65rem;">&times;</button>';
        container.appendChild(card);
    });
}

function addCreateFeatureItem() {
    var container = document.getElementById('createFeaturesContainer');
    var row = document.createElement('div');
    row.className = 'feature-item-row';
    row.style.cssText = 'display: flex; gap: 8px;';
    row.innerHTML = '<input type="text" name="features[]" class="admin-input" placeholder="ویژگی جدید...">' +
        '<button type="button" class="btn btn-outline text-danger" onclick="this.parentElement.remove()" style="padding: 0 12px;"><i class="fa-solid fa-xmark"></i></button>';
    container.appendChild(row);
}

function confirmSwalDelete(e, form, name) {
    e.preventDefault();
    Swal.fire({
        title: 'حذف پکیج ' + name,
        text: 'آیا از حذف این محصول اطمینان دارید؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'بله، حذف کن',
        cancelButtonText: 'انصراف'
    }).then(function(r) {
        if (r.isConfirmed) form.submit();
    });
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('productsTable', {
            searchInputId: 'productsSearchInput',
            paginationContainerId: 'productsTablePagination',
            pageSize: 10
        });
    }
});
</script>
