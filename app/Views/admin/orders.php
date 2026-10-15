<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="margin-bottom: 20px;">
    <div>
        <h2 class="panel-main-title">مدیریت سفارشات و فاکتورها</h2>
        <p class="panel-subtitle">پیگیری وضعیت سفارشات مشتریان، تعیین تکنسین رسمی اعزامی، ثبت وضعیت پرداخت و چاپ فاکتور رسمی</p>
    </div>
</div>

<div class="dashboard-block" style="background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0;">لیست سفارشات خرید و درخواست‌های نصب (<?= count($orders) ?> سفارش)</h3>
        <div style="position: relative; width: 320px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" class="form-control" style="padding-right: 38px; border-radius: 20px; font-size: 0.85rem;" placeholder="جستجو در کد سفارش، نام یا تلفن مشتری..." onkeyup="filterAdminTable(this, 'ordersTable')">
        </div>
    </div>
    <div class="orders-table-wrapper">
        <table class="profile-table" id="ordersTable">
            <thead>
                <tr>
                    <th>کد سفارش</th>
                    <th>نام مشتری</th>
                    <th>شماره تماس</th>
                    <th>منطقه تهران</th>
                    <th>مبلغ کل</th>
                    <th>شیوه پرداخت</th>
                    <th>وضعیت</th>
                    <th>تکنسین اعزامی</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: #94a3b8; padding: 30px;">سفارشی یافت نشد.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td><strong><?= e($ord['order_number']) ?></strong></td>
                            <td><?= e($ord['customer_name'] ?? 'مشتری آزاد') ?></td>
                            <td dir="ltr"><?= e($ord['customer_phone'] ?? '-') ?></td>
                            <td><?= e($ord['customer_district'] ?? 'نامشخص') ?></td>
                            <td><strong><?= number_format($ord['payable_amount']) ?> تومان</strong></td>
                            <td><?= ($ord['payment_method'] === 'cod') ? 'پرداخت در محل' : 'آنلاین بانکی' ?></td>
                            <td>
                                <span class="status-badge status-<?= ($ord['order_status'] === 'completed') ? 'completed' : (($ord['order_status'] === 'canceled') ? 'closed' : 'in-progress') ?>">
                                    <?= e($ord['order_status']) ?>
                                </span>
                            </td>
                            <td><?= e($ord['technician_name'] ?: 'تعیین نشده') ?></td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <button type="button" class="btn-table-action" onclick="openOrderEditModal('<?= $ord['id'] ?>', '<?= e($ord['order_number']) ?>', '<?= e($ord['order_status']) ?>', '<?= e($ord['technician_name']) ?>')">
                                        <?= icon('edit', '', 14) ?>
                                        <span>ویرایش</span>
                                    </button>
                                    <a href="<?= url('invoice/' . $ord['order_number']) ?>" target="_blank" class="btn-table-action" title="فاکتور رسمی">
                                        <?= icon('print', '', 14) ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Order Status & Dispatch Technician Modal -->
<div class="site-modal" id="adminOrderModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">ویرایش وضعیت سفارش و تکنسین</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('adminOrderModal').classList.remove('active');">&times;</button>
            </div>
            <form action="<?= url('admin/orders') ?>" method="POST">
                <input type="hidden" name="order_id" id="modalOrderId">
                <div class="modal-body">
                    <p id="modalOrderNumText" style="font-weight: 800; color: var(--color-navy); margin-bottom: 16px;"></p>

                    <div class="form-group">
                        <label>وضعیت مرحله سفارش</label>
                        <select name="order_status" id="modalOrderStatus" class="form-control">
                            <option value="registered">ثبت اولیه (در انتظار تایید)</option>
                            <option value="processing">در حال پردازش و بسته‌بندی در انبار</option>
                            <option value="assigned">تکنسین اعزام شد (در مسیر)</option>
                            <option value="completed">تکمیل و نصب گردید (گارانتی فعال شد)</option>
                            <option value="canceled">لغو سفارش</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>نام و کد تکنسین رسمی اعزامی</label>
                        <input type="text" name="technician_name" id="modalTechnicianName" class="form-control" placeholder="مثال: مهندس کاظمی (کد ۱۲۴)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">ثبت تغییرات سفارش</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openOrderEditModal(id, number, status, tech) {
    document.getElementById('modalOrderId').value = id;
    document.getElementById('modalOrderNumText').textContent = 'سفارش شماره: ' + number;
    document.getElementById('modalOrderStatus').value = status;
    document.getElementById('modalTechnicianName').value = tech || '';
    document.getElementById('adminOrderModal').classList.add('active');
}
</script>
