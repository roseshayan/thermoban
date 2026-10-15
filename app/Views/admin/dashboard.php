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

<!-- Interactive Visual Analytics (Chart.js - Zero CDN) -->
<div class="admin-charts-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 30px;">
    <div class="dashboard-block" style="background:#fff; border-radius: 14px; padding: 22px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
        <div class="block-header" style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
            <h3 class="block-title" style="font-size: 1.05rem; font-weight: 800; color: #011C39; margin: 0;">
                <i class="fa-solid fa-chart-line" style="color: var(--color-primary); margin-left: 8px;"></i>
                روند فروش ماهانه و خدمات نصب پکیج (میلیون تومان)
            </h3>
            <span style="font-size: 0.8rem; background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 20px; font-weight: 700;">سال جاری</span>
        </div>
        <div style="height: 280px; position: relative;">
            <canvas id="salesTrendsChart"></canvas>
        </div>
    </div>

    <div class="dashboard-block" style="background:#fff; border-radius: 14px; padding: 22px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
        <div class="block-header" style="margin-bottom: 16px;">
            <h3 class="block-title" style="font-size: 1.05rem; font-weight: 800; color: #011C39; margin: 0;">
                <i class="fa-solid fa-chart-pie" style="color: var(--color-primary); margin-left: 8px;"></i>
                سهم فروش برندها
            </h3>
        </div>
        <div style="height: 280px; position: relative;">
            <canvas id="brandsShareChart"></canvas>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;

    // Sales Trends Line Chart
    const salesCanvas = document.getElementById('salesTrendsChart');
    if (salesCanvas) {
        new Chart(salesCanvas, {
            type: 'line',
            data: {
                labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر'],
                datasets: [
                    {
                        label: 'فروش پکیج ایران رادیاتور',
                        data: [42, 58, 65, 80, 95, 110, 134],
                        borderColor: '#EF5C16',
                        backgroundColor: 'rgba(239, 92, 22, 0.12)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#EF5C16'
                    },
                    {
                        label: 'فروش پکیج بوتان',
                        data: [28, 35, 42, 55, 68, 79, 92],
                        borderColor: '#011C39',
                        backgroundColor: 'rgba(1, 28, 57, 0.05)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#011C39'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        rtl: true,
                        labels: { font: { family: 'Vazirmatn', size: 12 } }
                    }
                },
                scales: {
                    y: {
                        ticks: { font: { family: 'Vazirmatn' } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { family: 'Vazirmatn' } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Brands Share Doughnut Chart
    const brandsCanvas = document.getElementById('brandsShareChart');
    if (brandsCanvas) {
        new Chart(brandsCanvas, {
            type: 'doughnut',
            data: {
                labels: ['ایران رادیاتور', 'بوتان', 'خدمات نصب و سرویس'],
                datasets: [{
                    data: [58, 34, 8],
                    backgroundColor: ['#EF5C16', '#011C39', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        labels: { font: { family: 'Vazirmatn', size: 12 } }
                    }
                },
                cutout: '68%'
            }
        });
    }
});
</script>
