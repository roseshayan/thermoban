<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="margin-bottom: 20px;">
    <div>
        <h2 class="panel-main-title">درخواست‌های تماس، مشاوره و سفارش سریع</h2>
        <p class="panel-subtitle">لیست لیدهای ثبت شده از فرم شناور صفحه اصلی و فرم تماس با شعب ترموبان در تهران</p>
    </div>
</div>

<!-- Quick Orders (From Hero Form) -->
<div class="dashboard-block" style="background:#fff; margin-bottom: 30px;">
    <h3 class="block-title" style="margin-bottom: 18px;">سفارشات سریع ثبت شده در سایت (فرم هیرو)</h3>
    <div class="orders-table-wrapper">
        <table class="profile-table">
            <thead>
                <tr>
                    <th>نام مشتری</th>
                    <th>تلفن همراه</th>
                    <th>نوع خدمت</th>
                    <th>منطقه تهران</th>
                    <th>تاریخ و ساعت</th>
                    <th>وضعیت پیگیری</th>
                    <th>عملیات تماس</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quickOrders)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 25px;">سفارشی ثبت نشده است.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($quickOrders as $qo): ?>
                        <tr>
                            <td><strong><?= e($qo['fullname']) ?></strong></td>
                            <td dir="ltr">
                                <a href="tel:<?= e($qo['phone']) ?>" style="color: var(--color-primary); font-weight: 700;">
                                    <?= e($qo['phone']) ?>
                                </a>
                            </td>
                            <td><?= e($qo['service']) ?></td>
                            <td><?= e($qo['district']) ?></td>
                            <td><?= e($qo['created_at']) ?></td>
                            <td>
                                <form action="<?= url('admin/leads') ?>" method="POST" style="display: flex; gap: 6px; align-items: center;">
                                    <input type="hidden" name="lead_id" value="<?= $qo['id'] ?>">
                                    <select name="status" class="form-control" style="font-size: 0.8rem; padding: 4px 8px; width: auto;" onchange="this.form.submit()">
                                        <option value="pending" <?= ($qo['status'] === 'pending') ? 'selected' : '' ?>>در انتظار تماس</option>
                                        <option value="called" <?= ($qo['status'] === 'called') ? 'selected' : '' ?>>تماس گرفته شد</option>
                                        <option value="assigned" <?= ($qo['status'] === 'assigned') ? 'selected' : '' ?>>تکنسین اعزام شد</option>
                                        <option value="closed" <?= ($qo['status'] === 'closed') ? 'selected' : '' ?>>بسته شد</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="tel:<?= e($qo['phone']) ?>" class="btn-table-action" style="background: #10b981; color: #fff;">
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

<!-- Contact Form Messages -->
<div class="dashboard-block" style="background:#fff;">
    <h3 class="block-title" style="margin-bottom: 18px;">پیام‌های ثبت شده از صفحه تماس با ما</h3>
    <div class="orders-table-wrapper">
        <table class="profile-table">
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
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 25px;">پیامی دریافت نشده است.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $c): ?>
                        <tr>
                            <td><strong><?= e($c['name']) ?></strong></td>
                            <td dir="ltr">
                                <a href="tel:<?= e($c['phone']) ?>" style="color: var(--color-primary); font-weight: 700;">
                                    <?= e($c['phone']) ?>
                                </a>
                            </td>
                            <td><?= e($c['district']) ?></td>
                            <td><?= e($c['service']) ?></td>
                            <td style="max-width: 320px;"><?= nl2br(e($c['message'])) ?></td>
                            <td><?= e($c['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
