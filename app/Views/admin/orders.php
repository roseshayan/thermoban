<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 class="panel-main-title" style="margin: 0 0 6px 0; font-size: 1.4rem; font-weight: 800; color: #011C39;">مدیریت سفارشات و فاکتورها</h2>
        <p class="panel-subtitle" style="margin: 0; color: #64748b; font-size: 0.9rem;">پیگیری سفارشات، صدور سفارش دستی برای مشتری، تعیین تکنسین رسمی، و چاپ فاکتور</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="openCreateOrderModal()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px; border-radius: 8px;">
            <i class="fa-solid fa-plus-circle"></i>
            <span>ثبت سفارش جدید دستی</span>
        </button>
    </div>
</div>

<div class="dashboard-block" style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 22px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <h3 class="block-title" style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            لیست سفارشات خرید و درخواست‌های نصب 
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">(<?= count($orders) ?> سفارش)</span>
        </h3>
        
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <select id="orderStatusFilter" class="form-control" style="width: 170px; font-size: 0.85rem; height: 38px; border-radius: 8px;" onchange="window.filterTableByColumn('ordersTable', 6, this.value)">
                <option value="">همه وضعیت‌ها</option>
                <option value="registered">ثبت اولیه</option>
                <option value="processing">در حال پردازش</option>
                <option value="assigned">تکنسین اعزام شد</option>
                <option value="completed">تکمیل و نصب شد</option>
                <option value="canceled">لغو شده</option>
            </select>
            <div style="position: relative; width: 280px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                <input type="text" id="ordersSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در کد، نام مشتری، تکنسین...">
            </div>
        </div>
    </div>

    <div class="orders-table-wrapper" style="overflow-x: auto;">
        <table class="profile-table" id="ordersTable" style="width: 100%; border-collapse: collapse;">
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
                    <th style="text-align: center;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr class="no-records-row">
                        <td colspan="9" style="text-align: center; color: #94a3b8; padding: 35px 20px;">
                            <i class="fa-solid fa-box-open" style="font-size: 2.2rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                            هیچ سفارشی ثبت نشده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td>
                                <strong style="color: #011C39;"><?= e($ord['order_number']) ?></strong>
                                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;"><i class="fa-regular fa-clock" style="margin-left: 3px;"></i><?= to_shamsi($ord['created_at']) ?></div>
                            </td>
                            <td><?= e($ord['customer_name'] ?? 'مشتری آزاد / حضوری') ?></td>
                            <td dir="ltr" style="text-align: right;"><?= e($ord['customer_phone'] ?? '-') ?></td>
                            <td><?= e($ord['customer_district'] ?? 'نامشخص') ?></td>
                            <td><strong style="color: #EF5C16;"><?= number_format($ord['payable_amount']) ?> تومان</strong></td>
                            <td>
                                <?php if ($ord['payment_method'] === 'online'): ?>
                                    <span style="color: #059669; font-weight: 600;"><i class="fa-solid fa-credit-card" style="margin-left: 4px;"></i> آنلاین</span>
                                <?php else: ?>
                                    <span style="color: #64748b; font-weight: 600;"><i class="fa-solid fa-hand-holding-dollar" style="margin-left: 4px;"></i> پرداخت در محل</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= ($ord['order_status'] === 'completed') ? 'completed' : (($ord['order_status'] === 'canceled') ? 'closed' : 'in-progress') ?>">
                                    <?php 
                                        $statusMap = [
                                            'registered' => 'ثبت اولیه',
                                            'processing' => 'در حال پردازش',
                                            'assigned'   => 'تکنسین اعزام شد',
                                            'completed'  => 'تکمیل و نصب شد',
                                            'canceled'   => 'لغو شده'
                                        ];
                                        echo $statusMap[$ord['order_status']] ?? e($ord['order_status']);
                                    ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($ord['technician_name'])): ?>
                                    <span style="color: #0284c7; font-weight: 600;"><i class="fa-solid fa-user-gear" style="margin-left: 4px;"></i> <?= e($ord['technician_name']) ?></span>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-style: italic;">تعیین نشده</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <button type="button" class="btn-table-action" onclick="openOrderEditModal('<?= $ord['id'] ?>', '<?= e($ord['order_number']) ?>', '<?= e($ord['order_status']) ?>', '<?= e($ord['technician_name'] ?? '') ?>')" title="ویرایش وضعیت">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>ویرایش</span>
                                    </button>
                                    <a href="<?= url('invoice/' . $ord['order_number']) ?>" target="_blank" class="btn-table-action" title="مشاهده و چاپ فاکتور رسمی">
                                        <i class="fa-solid fa-print"></i>
                                        <span>فاکتور</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div id="ordersTablePagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<!-- Modal: Create New Manual Order -->
<div class="site-modal" id="createOrderModal">
    <div class="modal-dialog" style="max-width: 640px;">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="modal-title" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #011C39; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-cart-plus" style="color: #EF5C16;"></i>
                    ثبت سفارش جدید دستی برای مشتری
                </h3>
                <button type="button" class="modal-close" onclick="closeCreateOrderModal()" style="background: none; border: none; font-size: 1.4rem; color: #94a3b8; cursor: pointer;">&times;</button>
            </div>
            <form action="<?= url('admin/orders') ?>" method="POST" id="createOrderForm">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create_order">
                
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">انتخاب مشتری <span style="color: #ef4444;">*</span></label>
                        <select name="user_id" class="form-control" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                            <option value="">-- مشتری را انتخاب کنید --</option>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>">
                                        <?= e($u['name']) ?> (<?= e($u['phone']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="0">مشتری متفرقه / آزاد (بدون حساب)</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 16px;">
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">انتخاب محصول / پکیج <span style="color: #ef4444;">*</span></label>
                            <select name="product_id" id="manualOrderProduct" class="form-control" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;" onchange="calculateManualOrderTotal()">
                                <option value="" data-price="0">-- انتخاب پکیج یا قطعه --</option>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $prod): ?>
                                        <option value="<?= $prod['id'] ?>" data-price="<?= $prod['price'] ?>">
                                            <?= e($prod['title']) ?> (<?= number_format($prod['price']) ?> تومان)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">تعداد</label>
                            <input type="number" name="quantity" id="manualOrderQty" class="form-control" value="1" min="1" max="99" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;" onchange="calculateManualOrderTotal()" oninput="calculateManualOrderTotal()">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">مبلغ نهایی قابل پرداخت (تومان)</label>
                            <input type="text" name="payable_amount" id="manualOrderPayable" class="form-control" placeholder="محاسبه خودکار..." style="border-radius: 8px; font-size: 0.9rem; padding: 10px; font-weight: 800; color: #EF5C16;">
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">شیوه پرداخت</label>
                            <select name="payment_method" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                                <option value="cod">پرداخت در محل (کارتخوان سیار)</option>
                                <option value="online">آنلاین بانکی (تسویه شده)</option>
                                <option value="card">کارت به کارت رسمی</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">وضعیت سفارش</label>
                            <select name="order_status" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                                <option value="registered">ثبت اولیه (در انتظار تایید)</option>
                                <option value="processing">در حال آماده‌سازی و ارسال</option>
                                <option value="assigned">تکنسین اعزام شد</option>
                                <option value="completed" selected>تکمیل و نصب گردید</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">وضعیت پرداخت</label>
                            <select name="payment_status" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                                <option value="paid" selected>پرداخت شده (موفق)</option>
                                <option value="pending">در انتظار پرداخت</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نام و کد تکنسین رسمی مسئول</label>
                        <input type="text" name="technician_name" class="form-control" placeholder="مثال: مهندس کاظمی (کد ۱۲۴)" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-outline" onclick="closeCreateOrderModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 8px 22px; font-weight: 700;">
                        <i class="fa-solid fa-check" style="margin-left: 6px;"></i> ثبت قطعی سفارش
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Order Status & Dispatch Technician -->
<div class="site-modal" id="adminOrderModal">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="modal-title" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #011C39;">ویرایش وضعیت سفارش و تکنسین</h3>
                <button type="button" class="modal-close" onclick="closeOrderEditModal()" style="background: none; border: none; font-size: 1.4rem; color: #94a3b8; cursor: pointer;">&times;</button>
            </div>
            <form action="<?= url('admin/orders') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="order_id" id="modalOrderId">
                <div class="modal-body" style="padding: 24px;">
                    <p id="modalOrderNumText" style="font-weight: 800; color: #011C39; margin-bottom: 18px; font-size: 1rem;"></p>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">وضعیت مرحله سفارش</label>
                        <select name="order_status" id="modalOrderStatus" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                            <option value="registered">ثبت اولیه (در انتظار تایید)</option>
                            <option value="processing">در حال پردازش و بسته‌بندی در انبار</option>
                            <option value="assigned">تکنسین اعزام شد (در مسیر)</option>
                            <option value="completed">تکمیل و نصب گردید (گارانتی فعال شد)</option>
                            <option value="canceled">لغو سفارش</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">نام و کد تکنسین رسمی اعزامی</label>
                        <input type="text" name="technician_name" id="modalTechnicianName" class="form-control" placeholder="مثال: مهندس کاظمی (کد ۱۲۴)" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-outline" onclick="closeOrderEditModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 8px 22px; font-weight: 700;">ثبت تغییرات سفارش</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateOrderModal() {
    var modal = document.getElementById('createOrderModal');
    if (modal) modal.classList.add('active');
}

function closeCreateOrderModal() {
    var modal = document.getElementById('createOrderModal');
    if (modal) modal.classList.remove('active');
}

function calculateManualOrderTotal() {
    var prodSelect = document.getElementById('manualOrderProduct');
    var qtyInput = document.getElementById('manualOrderQty');
    var payableInput = document.getElementById('manualOrderPayable');
    
    if (!prodSelect || !qtyInput || !payableInput) return;
    
    var selectedOption = prodSelect.options[prodSelect.selectedIndex];
    var price = selectedOption ? parseInt(selectedOption.getAttribute('data-price') || '0', 10) : 0;
    var qty = Math.max(1, parseInt(qtyInput.value || '1', 10));
    var total = price * qty;
    payableInput.value = total > 0 ? total.toLocaleString('en-US') : '';
}

function openOrderEditModal(id, number, status, tech) {
    document.getElementById('modalOrderId').value = id;
    document.getElementById('modalOrderNumText').textContent = 'سفارش شماره: ' + number;
    document.getElementById('modalOrderStatus').value = status;
    document.getElementById('modalTechnicianName').value = tech || '';
    document.getElementById('adminOrderModal').classList.add('active');
}

function closeOrderEditModal() {
    var modal = document.getElementById('adminOrderModal');
    if (modal) modal.classList.remove('active');
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('ordersTable', {
            searchInputId: 'ordersSearchInput',
            paginationContainerId: 'ordersTablePagination',
            pageSize: 10
        });
    }
});
</script>
