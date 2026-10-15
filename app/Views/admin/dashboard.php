<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="kpi-cards-grid" style="margin-bottom: 30px;">
    <div class="kpi-card" style="background:#fff;">
        <div class="kpi-icon-box kpi-blue">
            <?= icon('invoice', '', 24) ?>
        </div>
        <div class="kpi-info">
            <span class="kpi-num"><?= number_format($stats['orders_count']) ?></span>
            <span class="kpi-label">کل سفارشات ثبت شده</span>
        </div>
    </div>

    <div class="kpi-card" style="background:#fff;">
        <div class="kpi-icon-box kpi-green">
            <?= icon('credit-card', '', 24) ?>
        </div>
        <div class="kpi-info">
            <span class="kpi-num"><?= number_format($stats['total_revenue']) ?></span>
            <span class="kpi-label">درآمد تایید شده (تومان)</span>
        </div>
    </div>

    <div class="kpi-card" style="background:#fff;">
        <div class="kpi-icon-box kpi-orange">
            <?= icon('ticket', '', 24) ?>
        </div>
        <div class="kpi-info">
            <span class="kpi-num"><?= $stats['open_tickets'] ?></span>
            <span class="kpi-label">تیکت‌های در انتظار پاسخ</span>
        </div>
    </div>

    <div class="kpi-card" style="background:#fff;">
        <div class="kpi-icon-box kpi-purple">
            <?= icon('phone', '', 24) ?>
        </div>
        <div class="kpi-info">
            <span class="kpi-num"><?= $stats['pending_leads'] ?></span>
            <span class="kpi-label">سفارش سریع نیازمند تماس</span>
        </div>
    </div>
</div>

<div class="dashboard-block" style="background:#fff; margin-bottom: 30px;">
    <div class="block-header">
        <h3 class="block-title">آخرین سفارش‌ها و اعزام نصاب مجاز</h3>
        <a href="<?= url('admin/orders') ?>" class="btn-link-action">مشاهده کلیه سفارش‌ها &larr;</a>
    </div>

    <div class="orders-table-wrapper">
        <table class="profile-table">
            <thead>
                <tr>
                    <th>کد سفارش</th>
                    <th>نام مشتری</th>
                    <th>تلفن تماس</th>
                    <th>مبلغ قابل پرداخت</th>
                    <th>شیفت تحویل</th>
                    <th>وضعیت</th>
                    <th>تکنسین اعزامی</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 30px;">هنوز سفارشی ثبت نشده است.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $ro): ?>
                        <tr>
                            <td><strong><?= e($ro['order_number']) ?></strong></td>
                            <td><?= e($ro['customer_name'] ?? 'مشتری آزاد') ?></td>
                            <td dir="ltr"><?= e($ro['customer_phone'] ?? '-') ?></td>
                            <td><strong><?= number_format($ro['payable_amount']) ?> تومان</strong></td>
                            <td><?= e($ro['delivery_slot']) ?></td>
                            <td>
                                <span class="status-badge status-<?= ($ro['order_status'] === 'completed') ? 'completed' : 'in-progress' ?>">
                                    <?= e($ro['order_status']) ?>
                                </span>
                            </td>
                            <td><?= e($ro['technician_name'] ?? 'در انتظار تعیین') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="dashboard-block" style="background:#fff;">
    <div class="block-header">
        <h3 class="block-title">درخواست‌های ثبت سفارش سریع (لیدهای تلفنی)</h3>
        <a href="<?= url('admin/leads') ?>" class="btn-link-action">مدیریت لیدها &larr;</a>
    </div>

    <div class="orders-table-wrapper">
        <table class="profile-table">
            <thead>
                <tr>
                    <th>نام متقاضی</th>
                    <th>شماره تماس</th>
                    <th>نوع خدمت درخواستی</th>
                    <th>منطقه تهران</th>
                    <th>تاریخ ثبت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentLeads)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">درخواست جدیدی ثبت نشده است.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentLeads as $lead): ?>
                        <tr>
                            <td><strong><?= e($lead['fullname']) ?></strong></td>
                            <td dir="ltr">
                                <a href="tel:<?= e($lead['phone']) ?>" style="color: var(--color-primary); font-weight: 700;">
                                    <?= e($lead['phone']) ?>
                                </a>
                            </td>
                            <td><?= e($lead['service']) ?></td>
                            <td><?= e($lead['district']) ?></td>
                            <td><?= e($lead['created_at']) ?></td>
                            <td>
                                <a href="tel:<?= e($lead['phone']) ?>" class="btn-table-action" style="background: #10b981; color: #fff;">
                                    <?= icon('phone', '', 14) ?>
                                    <span>تماس فوری</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
