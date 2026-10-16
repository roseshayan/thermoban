<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 class="panel-main-title" style="margin: 0 0 6px 0; font-size: 1.4rem; font-weight: 800; color: #011C39;">درخواست‌های تماس، مشاوره و سفارش سریع</h2>
        <p class="panel-subtitle" style="margin: 0; color: #64748b; font-size: 0.9rem;">لیست لیدهای ثبت شده از فرم شناور صفحه اصلی و فرم تماس با شعب ترموبان در تهران</p>
    </div>
</div>

<!-- Quick Orders (From Hero Form) -->
<div class="dashboard-block" style="background:#fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 22px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            سفارشات سریع ثبت شده در سایت (فرم هیرو)
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">(<?= count($quickOrders) ?> درخواست)</span>
        </h3>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <div style="position: relative; width: 280px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                <input type="text" id="quickOrdersSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در نام، شماره یا منطقه...">
            </div>
        </div>
    </div>

    <div class="orders-table-wrapper" style="overflow-x: auto;">
        <table class="profile-table" id="quickOrdersTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>نام مشتری</th>
                    <th>تلفن همراه</th>
                    <th>نوع خدمت</th>
                    <th>منطقه تهران</th>
                    <th>تاریخ و ساعت</th>
                    <th>وضعیت پیگیری</th>
                    <th style="text-align: center;">عملیات تماس</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quickOrders)): ?>
                    <tr class="no-records-row">
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 35px 20px;">سفارشی ثبت نشده است.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($quickOrders as $qo): ?>
                        <tr>
                            <td><strong><?= e($qo['fullname']) ?></strong></td>
                            <td dir="ltr" style="text-align: right;">
                                <a href="tel:<?= e($qo['phone']) ?>" style="color: #EF5C16; font-weight: 700; text-decoration: none;">
                                    <?= e($qo['phone']) ?>
                                </a>
                            </td>
                            <td><?= e($qo['service']) ?></td>
                            <td><?= e($qo['district']) ?></td>
                            <td dir="rtl" style="text-align: right; font-size: 0.84rem; color: #64748b;"><?= to_shamsi($qo['created_at'], 'Y/m/d H:i') ?></td>
                            <td>
                                <form action="<?= url('admin/leads') ?>" method="POST" style="display: flex; gap: 6px; align-items: center; margin: 0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="lead_id" value="<?= $qo['id'] ?>">
                                    <select name="status" class="form-control" style="font-size: 0.82rem; padding: 4px 8px; width: auto; border-radius: 6px;" onchange="this.form.submit()">
                                        <option value="pending" <?= ($qo['status'] === 'pending') ? 'selected' : '' ?>>در انتظار تماس</option>
                                        <option value="called" <?= ($qo['status'] === 'called') ? 'selected' : '' ?>>تماس گرفته شد</option>
                                        <option value="assigned" <?= ($qo['status'] === 'assigned') ? 'selected' : '' ?>>تکنسین اعزام شد</option>
                                        <option value="closed" <?= ($qo['status'] === 'closed') ? 'selected' : '' ?>>بسته شد</option>
                                    </select>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <a href="tel:<?= e($qo['phone']) ?>" class="btn-table-action" style="background: #10b981; color: #fff; padding: 6px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 700;">
                                    <i class="fa-solid fa-phone"></i>
                                    <span>تماس فوری</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div id="quickOrdersPagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<!-- Contact Form Messages -->
<div class="dashboard-block" style="background:#fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            پیام‌های ثبت شده از صفحه تماس با ما
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">(<?= count($contacts) ?> پیام)</span>
        </h3>
        <div style="position: relative; width: 280px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" id="contactsSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در پیام‌های تماس...">
        </div>
    </div>

    <div class="orders-table-wrapper" style="overflow-x: auto;">
        <table class="profile-table" id="contactsTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>نام فرستنده</th>
                    <th>شماره تماس</th>
                    <th>منطقه سکونت</th>
                    <th>نوع خدمات</th>
                    <th>متن پیام و توضیحات خرابی</th>
                    <th>تاریخ ثبت</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr class="no-records-row">
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 35px 20px;">پیامی دریافت نشده است.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $c): ?>
                        <tr>
                            <td><strong><?= e($c['name']) ?></strong></td>
                            <td dir="ltr" style="text-align: right;">
                                <a href="tel:<?= e($c['phone']) ?>" style="color: #EF5C16; font-weight: 700; text-decoration: none;">
                                    <?= e($c['phone']) ?>
                                </a>
                            </td>
                            <td><?= e($c['district']) ?></td>
                            <td><?= e($c['service']) ?></td>
                            <td style="max-width: 320px; font-size: 0.88rem; line-height: 1.6;"><?= nl2br(e($c['message'])) ?></td>
                            <td dir="rtl" style="text-align: right; font-size: 0.84rem; color: #64748b;"><?= to_shamsi($c['created_at'], 'Y/m/d H:i') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div id="contactsPagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('quickOrdersTable', {
            searchInputId: 'quickOrdersSearchInput',
            paginationContainerId: 'quickOrdersPagination',
            pageSize: 10
        });
        window.initAdminTablePro('contactsTable', {
            searchInputId: 'contactsSearchInput',
            paginationContainerId: 'contactsPagination',
            pageSize: 10
        });
    }
});
</script>
