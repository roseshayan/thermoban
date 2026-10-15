<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 class="panel-main-title" style="margin: 0 0 6px 0; font-size: 1.4rem; font-weight: 800; color: #011C39;">کاتالوگ محصولات پکیج دیواری</h2>
        <p class="panel-subtitle" style="margin: 0; color: #64748b; font-size: 0.9rem;">مدیریت قیمت‌ها، موجودی انبار، مشخصات فنی و گارانتی پکیج‌های ایران رادیاتور و بوتان</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="toggleNewProductBox()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px; border-radius: 8px;">
        <i class="fa-solid fa-plus-circle"></i>
        <span>افزودن پکیج جدید</span>
    </button>
</div>

<!-- Add Product Form Box (Collapsible) -->
<div class="dashboard-block" id="newProductBox" style="display: none; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <h3 class="block-title" style="margin: 0 0 20px 0; font-size: 1.15rem; color: #011C39; font-weight: 800; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-boxes-stacked" style="color: #EF5C16;"></i>
        مشخصات فنی و قیمت پکیج جدید
    </h3>
    <form action="<?= url('admin/products') ?>" method="POST" class="js-validate-form">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">برند دستگاه <span style="color: #ef4444;">*</span></label>
                <select name="brand_id" class="form-control" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">عنوان کامل تجاری <span style="color: #ef4444;">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="مثال: پکیج دیواری ایران رادیاتور مدل L28FF" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
        </div>

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نام مدل دستگاه <span style="color: #ef4444;">*</span></label>
                <input type="text" name="model" class="form-control" placeholder="مدل L28FF - دو مبدل فن‌دار" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">ظرفیت حرارتی (کیلوکالری) <span style="color: #ef4444;">*</span></label>
                <input type="number" name="capacity" class="form-control" placeholder="28000" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
        </div>

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">متراژ مناسب زیربنا <span style="color: #ef4444;">*</span></label>
                <input type="text" name="area" class="form-control" placeholder="تا ۱۸۰ مترمربع" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نوع مبدل حرارتی <span style="color: #ef4444;">*</span></label>
                <input type="text" name="heat_exchanger" class="form-control" placeholder="دو مبدل مجزا" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
        </div>

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نوع دودکش و محفظه احتراق <span style="color: #ef4444;">*</span></label>
                <input type="text" name="flue_type" class="form-control" placeholder="فن‌دار محفظه بسته" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">شرایط گارانتی و نصب <span style="color: #ef4444;">*</span></label>
                <input type="text" name="warranty" class="form-control" placeholder="۲۴ ماه ضمانت طلایی شرکتی + نصب رایگان" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
        </div>

        <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">قیمت فروش نقدی (تومان) <span style="color: #ef4444;">*</span></label>
                <input type="text" name="price" class="form-control" placeholder="26500000" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">قیمت قبل از تخفیف (اختیاری)</label>
                <input type="text" name="old_price" class="form-control" placeholder="28000000" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">ویژگی‌ها و مزایای برجسته (هر خط یک مورد)</label>
            <textarea name="features" rows="3" class="form-control" placeholder="نمایشگر دیجیتال پیشرفته&#10;سیستم ضد یخ‌زدگی&#10;پمپ سیرکولاتور ۳ سرعته اروپایی" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;"></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">توضیحات و نقد و بررسی کارشناسی <span style="color: #ef4444;">*</span></label>
            <textarea name="description" rows="3" class="form-control" placeholder="بررسی فنی دستگاه، راندمان، نوع قطعات..." required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn btn-outline" onclick="toggleNewProductBox()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
            <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 8px 22px; font-weight: 700;">
                <i class="fa-solid fa-check" style="margin-left: 6px;"></i> ذخیره و انتشار محصول در فروشگاه
            </button>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="dashboard-block" style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            لیست پکیج‌های ثبت شده در انبار 
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">(<?= count($products) ?> محصول)</span>
        </h3>
        
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <select id="productBrandFilter" class="form-control" style="width: 170px; font-size: 0.85rem; height: 38px; border-radius: 8px;" onchange="window.filterTableByColumn('productsTable', 2, this.value)">
                <option value="">همه برندها</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= e($b['name']) ?>"><?= e($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <div style="position: relative; width: 280px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                <input type="text" id="productsSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجوی سریع در نام، مدل یا قیمت...">
            </div>
        </div>
    </div>

    <div class="orders-table-wrapper" style="overflow-x: auto;">
        <table class="profile-table" id="productsTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>تصویر</th>
                    <th>نام پکیج</th>
                    <th>برند</th>
                    <th>مدل</th>
                    <th>ظرفیت</th>
                    <th>قیمت (تومان)</th>
                    <th style="text-align: center;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr class="no-records-row">
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 35px 20px;">محصولی یافت نشد.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <img src="<?= asset('images/' . $p['image']) ?>" alt="<?= e($p['title']) ?>" style="width: 48px; height: 48px; object-fit: contain; background: #fafbfc; border-radius: 6px; padding: 4px; border: 1px solid #e2e8f0;">
                            </td>
                            <td>
                                <strong style="color: #011C39;"><a href="<?= url('product/' . $p['id']) ?>" target="_blank" style="color: inherit; text-decoration: none;"><?= e($p['title']) ?></a></strong>
                            </td>
                            <td><span style="background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.82rem; color: #334155;"><?= e($p['brand_name']) ?></span></td>
                            <td><?= e($p['model']) ?></td>
                            <td><?= number_format($p['capacity']) ?></td>
                            <td><strong style="color: #EF5C16;"><?= number_format($p['price']) ?></strong></td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                    <a href="<?= url('admin/products/edit/' . $p['id']) ?>" class="btn-table-action" style="color: #0284c7; border: 1px solid #bae6fd; background: #fff; text-decoration: none; padding: 5px 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; font-size: 0.8rem;">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>ویرایش</span>
                                    </a>
                                    <form action="<?= url('admin/products') ?>" method="POST" style="display:inline; margin: 0;" onsubmit="return confirmSwalDelete(event, this, '<?= e($p['title']) ?>');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn-table-action" style="color: #ef4444; border: 1px solid #fecaca; background: #fff; padding: 5px 10px; border-radius: 6px; font-size: 0.8rem;" title="حذف پکیج">
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
    <div id="productsTablePagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<script>
function toggleNewProductBox() {
    var box = document.getElementById('newProductBox');
    if (box) {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (box.style.display === 'block') {
            box.scrollIntoView({ behavior: 'smooth' });
        }
    }
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
