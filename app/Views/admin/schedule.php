<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="form-alert-msg alert-error" style="margin-bottom: 25px;">
        <?= icon('alert', '', 18) ?>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="margin-bottom: 20px;">
    <div>
        <h2 class="panel-main-title">
            <i class="fa-solid fa-calendar-days" style="color: var(--color-primary); margin-left: 8px;"></i>
            تقویم کاری و زمان‌بندی اعزام تکنسین‌ها
        </h2>
        <p class="panel-subtitle">سازمان‌دهی درخواست‌های خدمات بر اساس تاریخ مراجعه، بازه‌های زمانی روز و تکنسین‌های مسئول</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="<?= url('admin/service-requests') ?>" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-list-check"></i>
            <span>لیست جدولی سفارشات</span>
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="dashboard-block" style="background: #fff; margin-bottom: 25px; padding: 18px 24px;">
    <form action="<?= url('admin/schedule') ?>" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
        <div style="flex: 1; min-width: 180px;">
            <label style="font-size: 0.85rem; font-weight: 700; color: #475569; display: block; margin-bottom: 5px;">تکنسین مسئول</label>
            <select name="technician_id" class="form-control" style="font-size: 0.88rem;">
                <option value="">همه تکنسین‌ها</option>
                <?php foreach ($technicians as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= ($techFilter == $t['id']) ? 'selected' : '' ?>>
                        <?= e($t['name']) ?> (<?= e($t['phone']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex: 1; min-width: 160px;">
            <label style="font-size: 0.85rem; font-weight: 700; color: #475569; display: block; margin-bottom: 5px;">وضعیت سفارش</label>
            <select name="status" class="form-control" style="font-size: 0.88rem;">
                <option value="">همه وضعیت‌ها</option>
                <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : '' ?>>در انتظار بررسی</option>
                <option value="scheduled" <?= ($statusFilter === 'scheduled') ? 'selected' : '' ?>>زمان‌بندی شده</option>
                <option value="technician_dispatched" <?= ($statusFilter === 'technician_dispatched') ? 'selected' : '' ?>>تکنسین در محل / اعزام</option>
                <option value="completed" <?= ($statusFilter === 'completed') ? 'selected' : '' ?>>انجام شده</option>
                <option value="cancelled" <?= ($statusFilter === 'cancelled') ? 'selected' : '' ?>>لغو شده</option>
            </select>
        </div>

        <div style="flex: 1; min-width: 160px;">
            <label style="font-size: 0.85rem; font-weight: 700; color: #475569; display: block; margin-bottom: 5px;">فیلتر تاریخ (شمسی یا میلادی)</label>
            <input type="text" name="date" class="form-control" value="<?= e($dateFilter) ?>" placeholder="مثلاً ۱۴۰۳/۰۸ یا ۲۰۲۶" style="font-size: 0.88rem;">
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                <i class="fa-solid fa-filter" style="margin-left: 4px;"></i> اعمال فیلتر
            </button>
            <?php if (!empty($techFilter) || !empty($statusFilter) || !empty($dateFilter)): ?>
                <a href="<?= url('admin/schedule') ?>" class="btn btn-outline" style="padding: 10px 14px;">پاک‌سازی</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Schedule Slots Matrix -->
<?php 
// Group requests by date
$byDate = [];
foreach ($requests as $r) {
    $dateKey = !empty($r['preferred_date']) ? $r['preferred_date'] : 'تعیین نشده';
    $byDate[$dateKey][] = $r;
}
?>

<?php if (empty($requests)): ?>
    <div class="dashboard-block text-center" style="background: #fff; padding: 50px 20px;">
        <i class="fa-solid fa-calendar-xmark" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px;"></i>
        <h3 style="font-size: 1.15rem; color: #475569; margin-bottom: 6px;">هیچ نوبت اعزامی با فیلترهای مشخص شده یافت نشد</h3>
        <p style="color: #94a3b8; font-size: 0.88rem;">سفارشات جدید ثبت شده پس از ثبت تاریخ و بازه زمانی در این بخش نمایان می‌شوند.</p>
    </div>
<?php else: ?>
    <?php foreach ($byDate as $dateLabel => $dayRequests): ?>
        <div style="margin-bottom: 35px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="background: var(--color-primary); color: #fff; padding: 6px 14px; border-radius: 8px; font-weight: 800; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>تاریخ حضور: <?= e($dateLabel) ?></span>
                </div>
                <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">(<?= count($dayRequests) ?> نوبت اعزام)</span>
                <div style="flex-grow: 1; height: 1px; background: #e2e8f0;"></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 20px;">
                <?php foreach ($dayRequests as $req): 
                    $statusBadge = match($req['status'] ?? 'pending') {
                        'pending'               => '<span class="status-badge" style="background:#fef3c7; color:#92400e;"><i class="fa-solid fa-clock"></i> در انتظار بررسی</span>',
                        'scheduled'             => '<span class="status-badge" style="background:#e0f2fe; color:#0369a1;"><i class="fa-solid fa-calendar-check"></i> نوبت قطعی</span>',
                        'technician_dispatched' => '<span class="status-badge" style="background:#ede9fe; color:#6d28d9;"><i class="fa-solid fa-van-shuttle"></i> تکنسین در مسیر</span>',
                        'completed'             => '<span class="status-badge" style="background:#dcfce7; color:#15803d;"><i class="fa-solid fa-circle-check"></i> انجام شده</span>',
                        'cancelled'             => '<span class="status-badge" style="background:#fee2e2; color:#b91c1c;"><i class="fa-solid fa-ban"></i> لغو شده</span>',
                        default                 => '<span class="status-badge">' . e($req['status']) . '</span>'
                    };
                ?>
                    <div class="dashboard-block" style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; position: relative; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="font-size: 0.78rem; font-weight: 800; color: #475569; background: #f1f5f9; padding: 3px 8px; border-radius: 6px;">
                                    کد: <?= e($req['tracking_code']) ?>
                                </span>
                                <?= $statusBadge ?>
                            </div>

                            <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--color-navy); margin: 0 0 8px 0;">
                                <?= e($req['customer_name'] ?? 'مشتری ناشناس') ?>
                            </h4>

                            <div style="font-size: 0.86rem; color: #64748b; margin-bottom: 12px; display: flex; flex-direction: column; gap: 6px;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-phone" style="color: var(--color-primary); width: 16px;"></i>
                                    <a href="tel:<?= e($req['customer_phone']) ?>" dir="ltr" style="font-weight: 700; color: #1e293b;">
                                        <?= e($req['customer_phone']) ?>
                                    </a>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-clock" style="color: #3b82f6; width: 16px;"></i>
                                    <span>بازه ساعت: <strong><?= e($req['time_slot'] ?? 'توافقی') ?></strong></span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 6px;">
                                    <i class="fa-solid fa-location-dot" style="color: #ef4444; width: 16px; margin-top: 3px;"></i>
                                    <span style="line-height: 1.5;">منطقه <?= e($req['district'] ?? '-') ?>: <?= e($req['address'] ?? 'آدرس ثبت نشده') ?></span>
                                </div>
                            </div>

                            <!-- Map Navigation Pill if lat/lng available -->
                            <?php if (!empty($req['lat']) && !empty($req['lng'])): ?>
                                <div style="margin-bottom: 14px; padding: 8px 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 0.78rem; font-weight: 700; color: #166534;">
                                        <i class="fa-solid fa-map-location-dot" style="margin-left: 4px;"></i> لوکیشن ثبت شده
                                    </span>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="https://neshan.org/maps/@<?= e($req['lat']) ?>,<?= e($req['lng']) ?>,16z" target="_blank" class="btn" style="padding: 3px 8px; font-size: 0.75rem; background: #0070f3; color: #fff; border-radius: 4px;">نشان</a>
                                        <a href="https://balad.ir/location?latitude=<?= e($req['lat']) ?>&longitude=<?= e($req['lng']) ?>" target="_blank" class="btn" style="padding: 3px 8px; font-size: 0.75rem; background: #00897b; color: #fff; border-radius: 4px;">بلد</a>
                                        <a href="https://www.google.com/maps?q=<?= e($req['lat']) ?>,<?= e($req['lng']) ?>" target="_blank" class="btn" style="padding: 3px 8px; font-size: 0.75rem; background: #ea4335; color: #fff; border-radius: 4px;">گوگل</a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Assigned Technician -->
                            <div style="background: #f8fafc; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; border: 1px dashed #cbd5e1;">
                                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">تکنسین مسئول:</div>
                                <?php if (!empty($req['technician_name'])): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="color: #0f172a; font-size: 0.9rem;">
                                            <i class="fa-solid fa-user-gear" style="color: #0284c7; margin-left: 4px;"></i>
                                            <?= e($req['technician_name']) ?>
                                        </strong>
                                        <a href="tel:<?= e($req['technician_phone']) ?>" class="btn" style="padding: 3px 8px; font-size: 0.76rem; background: #0284c7; color: #fff; border-radius: 4px;">
                                            <i class="fa-solid fa-phone" style="margin-left: 2px;"></i> تماس با سرویس‌کار
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #d97706; font-size: 0.84rem; font-weight: 700;">
                                        <i class="fa-solid fa-triangle-exclamation"></i> هنوز سرویس‌کاری اختصاص داده نشده
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Update Dispatch Form -->
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; margin-top: 8px;">
                            <button type="button" class="btn btn-outline w-100" style="padding: 6px; font-size: 0.84rem;" onclick="document.getElementById('dispatchModal<?= $req['id'] ?>').style.display='flex'">
                                <i class="fa-solid fa-pen-to-square" style="margin-left: 4px;"></i> تغییر زمان‌بندی یا تکنسین
                            </button>
                        </div>
                    </div>

                    <!-- Modal For Quick Dispatch -->
                    <div id="dispatchModal<?= $req['id'] ?>" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
                        <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 14px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">
                                    مدیریت نوبت: <?= e($req['customer_name'] ?? 'مشتری') ?> (<?= e($req['tracking_code']) ?>)
                                </h3>
                                <button type="button" onclick="document.getElementById('dispatchModal<?= $req['id'] ?>').style.display='none'" style="border: none; background: transparent; font-size: 1.4rem; cursor: pointer; color: #94a3b8;">&times;</button>
                            </div>

                            <form action="<?= url('admin/schedule') ?>" method="POST">
                                <input type="hidden" name="action" value="update_dispatch">
                                <input type="hidden" name="request_id" value="<?= $req['id'] ?>">

                                <div class="form-group" style="margin-bottom: 14px;">
                                    <label style="font-size: 0.85rem; font-weight: 700;">تکنسین اعزامی</label>
                                    <select name="technician_id" class="form-control">
                                        <option value="">بدون تکنسین (نامشخص)</option>
                                        <?php foreach ($technicians as $t): ?>
                                            <option value="<?= $t['id'] ?>" <?= ($req['technician_id'] == $t['id']) ? 'selected' : '' ?>>
                                                <?= e($t['name']) ?> (<?= e($t['phone']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-row-2" style="margin-bottom: 14px;">
                                    <div class="form-group">
                                        <label style="font-size: 0.85rem; font-weight: 700;">تاریخ حضور</label>
                                        <input type="text" name="preferred_date" class="form-control" value="<?= e($req['preferred_date'] ?? '') ?>" placeholder="مثلاً: ۱۴۰۳/۰۸/۲۵">
                                    </div>
                                    <div class="form-group">
                                        <label style="font-size: 0.85rem; font-weight: 700;">بازه ساعتی حضور</label>
                                        <select name="time_slot" class="form-control">
                                            <option value="صبح (۸ الی ۱۲)" <?= (($req['time_slot'] ?? '') === 'صبح (۸ الی ۱۲)') ? 'selected' : '' ?>>صبح (۸ الی ۱۲)</option>
                                            <option value="ظهر (۱۲ الی ۱۶)" <?= (($req['time_slot'] ?? '') === 'ظهر (۱۲ الی ۱۶)') ? 'selected' : '' ?>>ظهر (۱۲ الی ۱۶)</option>
                                            <option value="عصر (۱۶ الی ۲۰)" <?= (($req['time_slot'] ?? '') === 'عصر (۱۶ الی ۲۰)') ? 'selected' : '' ?>>عصر (۱۶ الی ۲۰)</option>
                                            <option value="شب و اضطراری (۲۰ الی ۲۴)" <?= (($req['time_slot'] ?? '') === 'شب و اضطراری (۲۰ الی ۲۴)') ? 'selected' : '' ?>>شب و اضطراری (۲۰ الی ۲۴)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 14px;">
                                    <label style="font-size: 0.85rem; font-weight: 700;">وضعیت سفارش</label>
                                    <select name="status" class="form-control">
                                        <option value="pending" <?= ($req['status'] === 'pending') ? 'selected' : '' ?>>در انتظار بررسی</option>
                                        <option value="scheduled" <?= ($req['status'] === 'scheduled') ? 'selected' : '' ?>>زمان‌بندی شده</option>
                                        <option value="technician_dispatched" <?= ($req['status'] === 'technician_dispatched') ? 'selected' : '' ?>>تکنسین اعزام شد / در محل</option>
                                        <option value="completed" <?= ($req['status'] === 'completed') ? 'selected' : '' ?>>تکمیل و رفع عیب شد</option>
                                        <option value="cancelled" <?= ($req['status'] === 'cancelled') ? 'selected' : '' ?>>لغو شده</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="font-size: 0.85rem; font-weight: 700;">یادداشت داخلی برای تکنسین</label>
                                    <textarea name="technician_notes" rows="2" class="form-control" placeholder="مثلاً: هماهنگی قبلی تلفنی، قطعه تعویضی فلومتر"><?= e($req['technician_notes'] ?? '') ?></textarea>
                                </div>

                                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <button type="button" onclick="document.getElementById('dispatchModal<?= $req['id'] ?>').style.display='none'" class="btn btn-outline">انصراف</button>
                                    <button type="submit" class="btn btn-primary">ثبت و ذخیره تغییرات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
