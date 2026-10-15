<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header">
    <div class="admin-header-title">
        <h1><i class="fa-solid fa-certificate text-orange"></i> مدیریت برندهای تحت پوشش و نمایندگی‌ها</h1>
        <p>ثبت، ویرایش و مدیریت برندهای مجاز، نشان‌های اختصاصی نمایندگی و وضعیت نمایش در صفحه اصلی و فروشگاه</p>
    </div>
    <div class="admin-header-actions">
        <button type="button" class="btn btn-primary" onclick="openAddBrandModal()">
            <i class="fa-solid fa-plus"></i>
            <span>افزودن برند جدید</span>
        </button>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="admin-alert admin-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="admin-alert admin-alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<!-- Stats Overview Cards -->
<div class="admin-stats-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="admin-stat-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 10px; background: rgba(239,92,22,0.1); color: #EF5C16; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-certificate"></i>
        </div>
        <div>
            <div style="font-size: 1.4rem; font-weight: 800; color: #011C39;"><?= count($brands) ?></div>
            <div style="font-size: 0.85rem; color: #64748b;">کل برندهای ثبت شده</div>
        </div>
    </div>

    <div class="admin-stat-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
        <?php $activeCount = count(array_filter($brands, fn($b) => ($b['is_active'] ?? 1) == 1)); ?>
        <div style="width: 48px; height: 48px; border-radius: 10px; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size: 1.4rem; font-weight: 800; color: #011C39;"><?= $activeCount ?></div>
            <div style="font-size: 0.85rem; color: #64748b;">برندهای فعال در سایت</div>
        </div>
    </div>
</div>

<!-- Brands Table Card -->
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title"><i class="fa-solid fa-list-check"></i> فهرست برندهای پکیج و رادیاتور</h2>
        <div class="admin-card-tools">
            <span class="badge badge-info"><?= count($brands) ?> برند</span>
        </div>
    </div>

    <div class="admin-card-body p-0">
        <div class="admin-table-container">
            <table class="admin-table pro-table" id="brandsTable">
                <thead>
                    <tr>
                        <th style="width: 60px;">شناسه</th>
                        <th style="width: 100px;">لوگوی برند</th>
                        <th>نام برند</th>
                        <th>نامک (Slug)</th>
                        <th>بج / نشان ویژه</th>
                        <th>وضعیت نمایش</th>
                        <th>توضیحات کوتاه</th>
                        <th style="width: 140px; text-align: center;">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($brands)): ?>
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px; color: #94a3b8;">
                                هیچ برندی ثبت نشده است. از دکمه «افزودن برند جدید» استفاده کنید.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($brands as $brand): ?>
                            <?php
                            $bLogo = $brand['logo'] ?? 'brand-iranradiator-logo.png';
                            $logoUrl = (str_starts_with($bLogo, 'http') || str_starts_with($bLogo, '/uploads') || str_starts_with($bLogo, 'uploads/'))
                                ? (str_starts_with($bLogo, 'http') ? $bLogo : url($bLogo))
                                : asset('images/' . $bLogo);
                            $isActive = (int)($brand['is_active'] ?? 1) === 1;
                            ?>
                            <tr>
                                <td><span class="badge badge-secondary">#<?= $brand['id'] ?></span></td>
                                <td>
                                    <div style="width: 65px; height: 50px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 4px;">
                                        <img src="<?= $logoUrl ?>" alt="<?= e($brand['name']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: #011C39; font-size: 0.95rem;"><?= e($brand['name']) ?></strong>
                                </td>
                                <td><code style="font-size: 0.85rem; color: #64748b;"><?= e($brand['slug']) ?></code></td>
                                <td>
                                    <?php if (!empty($brand['badge'])): ?>
                                        <span class="badge badge-warning" style="background: #fef3c7; color: #b45309; font-weight: 700; border: 1px solid #fde68a;">
                                            <i class="fa-solid fa-award" style="margin-left: 4px;"></i><?= e($brand['badge']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-size: 0.85rem;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="<?= url('admin/brands') ?>" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="brand_id" value="<?= $brand['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $isActive ? 'btn-success' : 'btn-outline' ?>" style="font-size: 0.78rem; border-radius: 20px; padding: 4px 12px; cursor: pointer;" title="کلیک برای تغییر وضعیت">
                                            <i class="fa-solid <?= $isActive ? 'fa-check' : 'fa-xmark' ?>"></i>
                                            <?= $isActive ? 'فعال در سایت' : 'غیرفعال' ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: #64748b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-width: 250px;">
                                        <?= e($brand['description'] ?? '—') ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                        <button type="button" class="btn btn-sm btn-outline" onclick='openEditBrandModal(<?= json_encode($brand, JSON_UNESCAPED_UNICODE) ?>)' title="ویرایش برند">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline text-danger" onclick="confirmDeleteBrand(<?= $brand['id'] ?>, '<?= e($brand['name']) ?>')" title="حذف برند">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add New Brand -->
<div class="site-modal" id="addBrandModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; border-radius: 16px; width: 100%; max-width: 580px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
        <div style="padding: 18px 24px; background: #011C39; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;"><i class="fa-solid fa-certificate text-orange" style="margin-left: 8px;"></i>افزودن برند تحت پوشش جدید</h3>
            <button type="button" onclick="closeAddBrandModal()" style="background: none; border: none; color: #fff; font-size: 1.6rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/brands') ?>" enctype="multipart/form-data" style="padding: 24px;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">

            <div class="admin-form-group">
                <label class="admin-label">نام برند <span class="text-danger">*</span></label>
                <input type="text" name="name" class="admin-input" placeholder="مثال: لورچ، بوش، ایران رادیاتور" required>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">نامک انگلیسی (Slug)</label>
                <input type="text" name="slug" class="admin-input" placeholder="مثال: lorch-boiler">
                <small class="text-muted">در صورت خالی بودن خودکار تولید می‌شود.</small>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">لوگوی برند</label>
                <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                    <input type="text" name="logo" id="addBrandLogoUrl" class="admin-input" placeholder="آدرس تصویر یا فایل انتخابی" style="flex-grow: 1;">
                    <button type="button" class="btn btn-outline" onclick="window.openMediaPicker(function(url){ document.getElementById('addBrandLogoUrl').value = url; })">
                        <i class="fa-solid fa-images"></i> کتابخانه
                    </button>
                </div>
                <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 8px;">یا آپلود مستقیم لوگو از سیستم:</div>
                <input type="file" name="logo_file" class="admin-input" accept="image/*">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">نشان / بج اختصاصی</label>
                <input type="text" name="badge" class="admin-input" placeholder="مثال: کد مجاز نصب، نمایندگی رسمی">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">توضیحات کوتاه درباره برند</label>
                <textarea name="description" class="admin-textarea" rows="3" placeholder="توضیحات مختصر در مورد تاریخچه، استانداردها و تکنولوژی برند..."></textarea>
            </div>

            <div class="admin-form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #EF5C16;">
                    <span>نمایش برند در صفحه اصلی و اسلایدرها (فعال)</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closeAddBrandModal()" class="btn btn-outline">انصراف</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> ذخیره برند</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Brand -->
<div class="site-modal" id="editBrandModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; border-radius: 16px; width: 100%; max-width: 580px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
        <div style="padding: 18px 24px; background: #011C39; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;"><i class="fa-solid fa-pen-to-square text-orange" style="margin-left: 8px;"></i>ویرایش اطلاعات برند</h3>
            <button type="button" onclick="closeEditBrandModal()" style="background: none; border: none; color: #fff; font-size: 1.6rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/brands') ?>" enctype="multipart/form-data" style="padding: 24px;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="brand_id" id="editBrandId">

            <div class="admin-form-group">
                <label class="admin-label">نام برند <span class="text-danger">*</span></label>
                <input type="text" name="name" id="editBrandName" class="admin-input" required>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">نامک انگلیسی (Slug)</label>
                <input type="text" name="slug" id="editBrandSlug" class="admin-input">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">لوگوی برند</label>
                <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                    <input type="text" name="logo" id="editBrandLogoUrl" class="admin-input" style="flex-grow: 1;">
                    <button type="button" class="btn btn-outline" onclick="window.openMediaPicker(function(url){ document.getElementById('editBrandLogoUrl').value = url; })">
                        <i class="fa-solid fa-images"></i> کتابخانه
                    </button>
                </div>
                <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 8px;">یا جایگزینی مستقیم با آپلود فایل جدید:</div>
                <input type="file" name="logo_file" class="admin-input" accept="image/*">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">نشان / بج اختصاصی</label>
                <input type="text" name="badge" id="editBrandBadge" class="admin-input" placeholder="مثال: نمایندگی رسمی کد ۷۲۷۳">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">توضیحات کوتاه</label>
                <textarea name="description" id="editBrandDesc" class="admin-textarea" rows="3"></textarea>
            </div>

            <div class="admin-form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
                    <input type="checkbox" name="is_active" id="editBrandActive" value="1" style="width: 18px; height: 18px; accent-color: #EF5C16;">
                    <span>نمایش برند در سایت (فعال)</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closeEditBrandModal()" class="btn btn-outline">انصراف</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> ذخیره تغییرات</button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteBrandForm" method="POST" action="<?= url('admin/brands') ?>" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="brand_id" id="deleteBrandId">
</form>

<script>
    function openAddBrandModal() {
        var m = document.getElementById('addBrandModal');
        if (m) m.style.display = 'flex';
    }
    function closeAddBrandModal() {
        var m = document.getElementById('addBrandModal');
        if (m) m.style.display = 'none';
    }

    function openEditBrandModal(b) {
        document.getElementById('editBrandId').value = b.id;
        document.getElementById('editBrandName').value = b.name || '';
        document.getElementById('editBrandSlug').value = b.slug || '';
        document.getElementById('editBrandLogoUrl').value = b.logo || '';
        document.getElementById('editBrandBadge').value = b.badge || '';
        document.getElementById('editBrandDesc').value = b.description || '';
        document.getElementById('editBrandActive').checked = (parseInt(b.is_active) !== 0);

        var m = document.getElementById('editBrandModal');
        if (m) m.style.display = 'flex';
    }
    function closeEditBrandModal() {
        var m = document.getElementById('editBrandModal');
        if (m) m.style.display = 'none';
    }

    function confirmDeleteBrand(id, name) {
        Swal.fire({
            title: 'حذف برند ' + name,
            text: 'آیا از حذف این برند مطمئن هستید؟ این عمل غیرقابل بازگشت است.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'بله، حذف کن',
            cancelButtonText: 'انصراف'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('deleteBrandId').value = id;
                document.getElementById('deleteBrandForm').submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.AdminTablePro) {
            new AdminTablePro('brandsTable', {
                pageSize: 10,
                searchPlaceholder: 'جستجو در نام، نشان، یا توضیحات برند...'
            });
        }
    });
</script>
