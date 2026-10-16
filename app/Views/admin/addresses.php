<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-location-dot" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت آدرس‌های ذخیره‌شده کاربران
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                مشاهده تمامی نشانی‌های ثبت‌شده توسط مشتریان، مناطق شهرداری، کدهای پستی و شماره‌های تحویل سفارش
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= url('admin/users') ?>" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; background: #fff; text-decoration: none;">
                <i class="fa-solid fa-users"></i>
                <span>بازگشت به مدیریت کاربران</span>
            </a>
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 20px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-circle-check" style="margin-left: 8px;"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="form-alert-msg alert-error" style="margin-bottom: 20px; background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-triangle-exclamation" style="margin-left: 8px;"></i>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<!-- Search and Filter Bar -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 16px 20px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
    <div style="display: flex; gap: 14px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <label style="font-weight: 700; color: #011C39; font-size: 0.88rem; margin: 0;">فیلتر منطقه:</label>
            <select id="districtFilter" class="form-control" style="width: 170px; font-size: 0.85rem; height: 38px; border-radius: 8px;" onchange="window.filterTableByColumn('addressesTable', 3, this.value)">
                <option value="">همه مناطق</option>
                <?php for ($d = 1; $d <= 22; $d++): ?>
                    <option value="منطقه <?= fa_num($d) ?>">منطقه <?= fa_num($d) ?></option>
                <?php endfor; ?>
            </select>

            <?php if (!empty($userIdFilter)): ?>
                <a href="<?= url('admin/addresses') ?>" style="font-size: 0.82rem; color: #ef4444; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: #fee2e2; padding: 6px 12px; border-radius: 6px;">
                    <i class="fa-solid fa-xmark"></i>
                    <span>حذف فیلتر کاربر #<?= $userIdFilter ?></span>
                </a>
            <?php endif; ?>
        </div>
        <div style="position: relative; width: 340px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" id="addressesSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در نام کاربر، شماره، منطقه، آدرس یا کد پستی...">
        </div>
    </div>
</div>

<!-- Addresses Table -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow-x: auto;">
    <table class="admin-table" id="addressesTable" style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: right; color: #475569;">
                <th style="padding: 12px 14px;">ردیف</th>
                <th style="padding: 12px 14px;">کاربر مربوطه</th>
                <th style="padding: 12px 14px;">عنوان نشانی</th>
                <th style="padding: 12px 14px;">منطقه تهران</th>
                <th style="padding: 12px 14px;">شرح کامل نشانی</th>
                <th style="padding: 12px 14px;">کد پستی / تلفن</th>
                <th style="padding: 12px 14px; text-align: center;">وضعیت</th>
                <th style="padding: 12px 14px; text-align: center;">عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($addresses)): ?>
                <tr class="no-records-row">
                    <td colspan="8" style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                        <i class="fa-solid fa-map-location-dot" style="font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.4;"></i>
                        هیچ نشانی یا آدرسی یافت نشد.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($addresses as $idx => $addr): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 700; color: #64748b;"><?= fa_num($idx + 1) ?></td>
                        <td style="padding: 14px;">
                            <?php if (!empty($addr['user_name'])): ?>
                                <strong style="color: #011C39; display: block; font-size: 0.92rem;"><?= e($addr['user_name']) ?></strong>
                                <span style="font-family: monospace; font-size: 0.78rem; color: #64748b;" dir="ltr"><?= e($addr['user_account_phone']) ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic;">کاربر نامشخص (مهمان)</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px;">
                            <span style="background: #f1f5f9; color: #334155; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fa-solid fa-tag" style="color: #f97316; font-size: 0.72rem;"></i>
                                <?= e($addr['title']) ?>
                            </span>
                        </td>
                        <td style="padding: 14px; font-weight: 700; color: #011C39;">
                            <?= e($addr['district']) ?>
                        </td>
                        <td style="padding: 14px; max-width: 320px; line-height: 1.6; color: #334155;">
                            <?= e($addr['address']) ?>
                        </td>
                        <td style="padding: 14px;">
                            <?php if (!empty($addr['postal_code'])): ?>
                                <div style="font-family: monospace; font-size: 0.8rem; color: #475569;" dir="ltr">
                                    <i class="fa-solid fa-envelopes-bulk" style="color: #94a3b8; margin-left: 4px;"></i>
                                    <?= e($addr['postal_code']) ?>
                                </div>
                            <?php endif; ?>
                            <div style="font-family: monospace; font-size: 0.82rem; color: #011C39; font-weight: 700; margin-top: 2px;" dir="ltr">
                                <i class="fa-solid fa-phone" style="color: #10b981; margin-left: 4px;"></i>
                                <?= e($addr['phone']) ?>
                            </div>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <?php if ($addr['is_default']): ?>
                                <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-circle-check"></i>
                                    پیش‌فرض
                                </span>
                            <?php else: ?>
                                <form action="<?= url('admin/addresses') ?>" method="POST" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="set_default">
                                    <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                    <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; color: #64748b;" title="تنظیم به عنوان آدرس پیش‌فرض">
                                        انتخاب پیش‌فرض
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                <?php if (!empty($addr['lat']) && !empty($addr['lng'])): ?>
                                    <a href="https://www.google.com/maps?q=<?= e($addr['lat']) ?>,<?= e($addr['lng']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem; color: #0284c7; border-color: #bae6fd; border-radius: 6px;" title="مشاهده لوکیشن روی نقشه گوگل">
                                        <i class="fa-solid fa-map-pin"></i>
                                    </a>
                                <?php endif; ?>
                                <form action="<?= url('admin/addresses') ?>" method="POST" onsubmit="return confirm('آیا از حذف این آدرس اطمینان دارید؟');" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                    <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem; color: #dc2626; border-color: #fca5a5; border-radius: 6px;" title="حذف این آدرس">
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

    <!-- Pagination Controls -->
    <div id="addressesTablePagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('addressesTable', {
            searchInputId: 'addressesSearchInput',
            paginationContainerId: 'addressesTablePagination',
            pageSize: 10
        });
    }
});
</script>
