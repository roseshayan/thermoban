<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="margin-bottom: 20px;">
    <div>
        <h2 class="panel-main-title">کاتالوگ محصولات پکیج دیواری</h2>
        <p class="panel-subtitle">مدیریت قیمت‌ها، موجودی انبار، مشخصات فنی و گارانتی پکیج‌های ایران رادیاتور و بوتان</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('newProductBox').style.display = (document.getElementById('newProductBox').style.display === 'none' ? 'block' : 'none');">
        <?= icon('plus', '', 18) ?>
        <span>افزودن پکیج جدید</span>
    </button>
</div>

<!-- Add Product Form Box (Collapsible) -->
<div class="dashboard-block" id="newProductBox" style="display: none; background: #fff; margin-bottom: 30px;">
    <h3 class="block-title" style="margin-bottom: 20px;">مشخصات فنی و قیمت پکیج جدید</h3>
    <form action="<?= url('admin/products') ?>" method="POST" class="js-validate-form">
        <input type="hidden" name="action" value="create">

        <div class="form-row-2">
            <div class="form-group">
                <label>برند دستگاه <span class="required">*</span></label>
                <select name="brand_id" class="form-control" required>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>عنوان کامل تجاری <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="مثال: پکیج دیواری ایران رادیاتور مدل L28FF" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>نام مدل دستگاه <span class="required">*</span></label>
                <input type="text" name="model" class="form-control" placeholder="مدل L28FF - دو مبدل فن‌دار" required>
            </div>
            <div class="form-group">
                <label>ظرفیت حرارتی (کیلوکالری) <span class="required">*</span></label>
                <input type="number" name="capacity" class="form-control" placeholder="28000" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>متراژ مناسب زیربنا <span class="required">*</span></label>
                <input type="text" name="area" class="form-control" placeholder="تا ۱۸۰ مترمربع" required>
            </div>
            <div class="form-group">
                <label>نوع مبدل حرارتی <span class="required">*</span></label>
                <input type="text" name="heat_exchanger" class="form-control" placeholder="دو مبدل مجزا" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>نوع دودکش و محفظه احتراق <span class="required">*</span></label>
                <input type="text" name="flue_type" class="form-control" placeholder="فن‌دار محفظه بسته" required>
            </div>
            <div class="form-group">
                <label>شرایط گارانتی و نصب <span class="required">*</span></label>
                <input type="text" name="warranty" class="form-control" placeholder="۲۴ ماه ضمانت طلایی شرکتی + نصب رایگان" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>قیمت فروش نقدی (تومان) <span class="required">*</span></label>
                <input type="text" name="price" class="form-control" placeholder="26500000" required>
            </div>
            <div class="form-group">
                <label>قیمت قبل از تخفیف (اختیاری)</label>
                <input type="text" name="old_price" class="form-control" placeholder="28000000">
            </div>
        </div>

        <div class="form-group">
            <label>ویژگی‌ها و مزایای برجسته (هر خط یک مورد)</label>
            <textarea name="features" rows="3" class="form-control" placeholder="نمایشگر دیجیتال پیشرفته&#10;سیستم ضد یخ‌زدگی&#10;پمپ سیرکولاتور ۳ سرعته اروپایی"></textarea>
        </div>

        <div class="form-group">
            <label>توضیحات و نقد و بررسی کارشناسی <span class="required">*</span></label>
            <textarea name="description" rows="3" class="form-control" placeholder="بررسی فنی دستگاه، راندمان، نوع قطعات..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= icon('check', '', 18) ?>
            <span>ذخیره و انتشار محصول در فروشگاه</span>
        </button>
    </form>
</div>

<!-- Products Table -->
<div class="dashboard-block" style="background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0;">لیست پکیج‌های ثبت شده در انبار (<?= count($products) ?> محصول)</h3>
        <div style="position: relative; width: 320px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" class="form-control" style="padding-right: 38px; border-radius: 20px; font-size: 0.85rem;" placeholder="جستجوی سریع در نام، مدل یا برند..." onkeyup="filterAdminTable(this, 'productsTable')">
        </div>
    </div>

    <div class="orders-table-wrapper">
        <table class="profile-table" id="productsTable">
            <thead>
                <tr>
                    <th>تصویر</th>
                    <th>نام پکیج</th>
                    <th>برند</th>
                    <th>مدل</th>
                    <th>ظرفیت</th>
                    <th>قیمت (تومان)</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <img src="<?= asset('images/' . $p['image']) ?>" alt="<?= e($p['title']) ?>" style="width: 48px; height: 48px; object-fit: contain; background: #fafbfc; border-radius: 6px; padding: 4px;">
                        </td>
                        <td>
                            <strong><a href="<?= url('product/' . $p['id']) ?>" target="_blank"><?= e($p['title']) ?></a></strong>
                        </td>
                        <td><?= e($p['brand_name']) ?></td>
                        <td><?= e($p['model']) ?></td>
                        <td><?= number_format($p['capacity']) ?></td>
                        <td><strong><?= number_format($p['price']) ?></strong></td>
                        <td>
                            <form action="<?= url('admin/products') ?>" method="POST" style="display:inline;" onsubmit="return confirmSwalDelete(event, this, '<?= e($p['title']) ?>');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-table-action" style="color: #ef4444; border: 1px solid #fecaca; background: #fff;">
                                    <?= icon('trash', '', 14) ?>
                                    <span>حذف</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
