<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-screwdriver-wrench" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت سفارشات خدمات، عیب‌یابی و اعزام تکنسین
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                بررسی درخواست‌های ثبت‌شده، مشاهده عکس‌ها و ویدیوهای ارسالی مشتری، تخصیص تکنسین و صدور فاکتور
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= url('service-order') ?>" target="_blank" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; background: #fff; text-decoration: none;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>فرم عمومی ثبت سفارش</span>
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
            <label style="font-weight: 700; color: #011C39; font-size: 0.88rem; margin: 0;">فیلتر وضعیت:</label>
            <select id="statusFilterSelect" class="form-control" style="width: 190px; font-size: 0.85rem; height: 38px; border-radius: 8px;" onchange="window.location.href='<?= url('admin/service-requests') ?>?status=' + this.value">
                <option value="">همه وضعیت‌ها</option>
                <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : '' ?>>در انتظار بررسی</option>
                <option value="assigned" <?= ($statusFilter === 'assigned') ? 'selected' : '' ?>>تکنسین اختصاص‌یافته</option>
                <option value="in_progress" <?= ($statusFilter === 'in_progress') ? 'selected' : '' ?>>در حال انجام کار</option>
                <option value="completed" <?= ($statusFilter === 'completed') ? 'selected' : '' ?>>تکمیل و پایان یافته</option>
                <option value="canceled" <?= ($statusFilter === 'canceled') ? 'selected' : '' ?>>لغو شده</option>
            </select>

            <?php if (!empty($statusFilter)): ?>
                <a href="<?= url('admin/service-requests') ?>" style="font-size: 0.82rem; color: #ef4444; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: #fee2e2; padding: 6px 12px; border-radius: 6px;">
                    <i class="fa-solid fa-xmark"></i>
                    <span>حذف فیلتر</span>
                </a>
            <?php endif; ?>
        </div>
        <div style="position: relative; width: 340px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" id="requestsSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در کد پیگیری، نام مشتری، تلفن، منطقه...">
        </div>
    </div>
</div>

<!-- Requests Table -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow-x: auto;">
    <table class="admin-table" id="serviceRequestsTable" style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: right; color: #475569;">
                <th style="padding: 12px 14px;">کد پیگیری</th>
                <th style="padding: 12px 14px;">مشتری و تماس</th>
                <th style="padding: 12px 14px;">خدمت و دستگاه</th>
                <th style="padding: 12px 14px;">منطقه و زمان</th>
                <th style="padding: 12px 14px; text-align: center;">پیوست رسانه‌ای</th>
                <th style="padding: 12px 14px;">تکنسین مسئول</th>
                <th style="padding: 12px 14px; text-align: center;">وضعیت</th>
                <th style="padding: 12px 14px; text-align: center;">عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($requests)): ?>
                <tr class="no-records-row">
                    <td colspan="8" style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                        <i class="fa-solid fa-wrench" style="font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.4;"></i>
                        هیچ سفارش خدماتی یافت نشد.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($requests as $r): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px;">
                            <strong style="color: #011C39; font-family: monospace; font-size: 0.92rem;" dir="ltr"><?= e($r['tracking_code']) ?></strong>
                            <span style="display: block; font-size: 0.74rem; color: #94a3b8;"><?= to_shamsi($r['created_at']) ?></span>
                        </td>
                        <td style="padding: 14px;">
                            <strong style="color: #011C39; display: block;"><?= e($r['customer_name']) ?></strong>
                            <span dir="ltr" style="font-family: monospace; font-size: 0.8rem; color: #0284c7;"><?= e($r['customer_phone']) ?></span>
                        </td>
                        <td style="padding: 14px;">
                            <span style="color: #011C39; font-weight: 700; display: block; font-size: 0.88rem;"><?= e($r['service_type']) ?></span>
                            <span style="font-size: 0.78rem; color: #ea580c; font-weight: 600;"><?= e($r['brand']) ?> <?= e($r['model'] ?? '') ?></span>
                            <?php if (!empty($r['problem_type'])): ?>
                                <span style="display: block; font-size: 0.74rem; color: #64748b; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= e($r['problem_type']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px;">
                            <strong style="color: #011C39; display: block;"><?= e($r['district']) ?></strong>
                            <span style="font-size: 0.78rem; color: #64748b;"><?= e($r['preferred_date']) ?> (<?= e($r['time_slot']) ?>)</span>
                            <?php if ($r['urgency'] === 'urgent'): ?>
                                <span style="background: #fef2f2; color: #dc2626; padding: 1px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: 800; display: inline-block; margin-top: 2px;">فوری (۲ ساعته)</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <div style="display: inline-flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                <?php if (!empty($r['photos_array'])): ?>
                                    <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;" title="<?= count($r['photos_array']) ?> عکس پیوست شده">
                                        <i class="fa-solid fa-camera"></i> <?= fa_num(count($r['photos_array'])) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($r['video_path'])): ?>
                                    <span style="background: #ffedd5; color: #c2410c; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;" title="ویدیو ضمیمه شده است">
                                        <i class="fa-solid fa-video"></i> ویدیو
                                    </span>
                                <?php endif; ?>
                                <?php if (empty($r['photos_array']) && empty($r['video_path'])): ?>
                                    <span style="color: #cbd5e1; font-size: 0.75rem;">بدون رسانه</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding: 14px;">
                            <?php if (!empty($r['tech_name'])): ?>
                                <strong style="color: #011C39; font-size: 0.88rem; display: block;">
                                    <i class="fa-solid fa-user-check text-orange" style="margin-left: 4px;"></i>
                                    <?= e($r['tech_name']) ?>
                                </strong>
                                <span dir="ltr" style="font-family: monospace; font-size: 0.76rem; color: #64748b;"><?= e($r['tech_phone']) ?></span>
                            <?php else: ?>
                                <span style="color: #d97706; font-size: 0.8rem; font-weight: 600;">تخصیص نیافته</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <?php
                            $st = $r['status'] ?? 'pending';
                            $badges = [
                                'pending'     => ['title' => 'در انتظار بررسی', 'bg' => '#fef3c7', 'color' => '#b45309'],
                                'assigned'    => ['title' => 'تکنسین اختصاص یافت', 'bg' => '#e0f2fe', 'color' => '#0369a1'],
                                'in_progress' => ['title' => 'در حال انجام کار', 'bg' => '#ffedd5', 'color' => '#c2410c'],
                                'completed'   => ['title' => 'تکمیل شده', 'bg' => '#ecfdf5', 'color' => '#047857'],
                                'canceled'    => ['title' => 'لغو شده', 'bg' => '#fef2f2', 'color' => '#b91c1c']
                            ];
                            $b = $badges[$st] ?? $badges['pending'];
                            ?>
                            <span style="background: <?= $b['bg'] ?>; color: <?= $b['color'] ?>; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem;">
                                <?= $b['title'] ?>
                            </span>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                <button type="button" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.78rem; border-radius: 6px;" onclick="openManageRequestModal(<?= htmlspecialchars(json_encode($r)) ?>)">
                                    <i class="fa-solid fa-sliders"></i>
                                    <span>مدیریت</span>
                                </button>
                                <form action="<?= url('admin/service-requests') ?>" method="POST" onsubmit="return confirm('آیا از حذف این سفارش اطمینان دارید؟');" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="request_id" value="<?= $r['id'] ?>">
                                    <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem; color: #dc2626; border-color: #fca5a5; border-radius: 6px;" title="حذف سفارش">
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
    <div id="serviceRequestsPagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<!-- Modal: Manage Single Service Request -->
<div id="manageRequestModal" class="site-modal" style="display: none;">
    <div class="modal-dialog" style="max-width: 680px;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #011C39; color: #fff; padding: 18px 24px; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">
                    <i class="fa-solid fa-wrench text-orange" style="margin-left: 8px;"></i>
                    بررسی سفارش خدمات: <span id="modalTrackingCode" style="font-family: monospace;"></span>
                </h3>
                <button type="button" onclick="closeManageRequestModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: #fff; line-height: 1;">&times;</button>
            </div>
            
            <form action="<?= url('admin/service-requests') ?>" method="POST" style="padding: 24px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="request_id" id="modalRequestId">

                <!-- Customer & Address Summary -->
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 20px; font-size: 0.88rem; border: 1px solid #e2e8f0;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 8px;">
                        <div><strong style="color: #64748b;">مشتری:</strong> <span id="modalCustName" style="color: #011C39; font-weight: 700;"></span></div>
                        <div><strong style="color: #64748b;">شماره تماس:</strong> <span id="modalCustPhone" dir="ltr" style="font-family: monospace; color: #0284c7; font-weight: 700;"></span></div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 8px;">
                        <div><strong style="color: #64748b;">نوع خدمت:</strong> <span id="modalServiceType" style="color: #011C39;"></span></div>
                        <div><strong style="color: #64748b;">برند و مدل:</strong> <span id="modalBrandModel" style="color: #011C39;"></span></div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong style="color: #64748b;">نشانی دقیق:</strong> <span id="modalFullAddress" style="color: #011C39;"></span>
                    </div>
                    <div>
                        <strong style="color: #64748b;">مشکل اعلامی:</strong> <span id="modalProblemDesc" style="color: #c2410c; font-weight: 600;"></span>
                    </div>
                </div>

                <!-- Media Attachments (Photos & HTML5 Video Player) -->
                <div id="modalMediaSection" style="margin-bottom: 20px; display: none;">
                    <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 8px; display: block;">فایل‌های پیوست مشتری (عکس و ویدیو):</label>
                    
                    <!-- Photos Preview -->
                    <div id="modalPhotosList" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px;"></div>

                    <!-- Video Preview -->
                    <div id="modalVideoWrap" style="display: none; background: #000; border-radius: 10px; overflow: hidden; max-height: 280px; text-align: center;">
                        <video id="modalVideoPlayer" controls style="width: 100%; max-height: 280px; border-radius: 10px;"></video>
                    </div>
                </div>

                <!-- Technician Assignment & Status -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 6px; color: #1e293b;">تخصیص تکنسین مسئول:</label>
                        <select name="technician_id" id="modalTechSelect" class="form-control" style="border-radius: 8px; font-size: 0.88rem; height: 42px;">
                            <option value="">-- بدون تکنسین --</option>
                            <?php foreach ($technicians as $tech): ?>
                                <option value="<?= $tech['id'] ?>"><?= e($tech['name']) ?> (<?= e($tech['phone']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 6px; color: #1e293b;">وضعیت جاری سفارش:</label>
                        <select name="status" id="modalStatusSelect" class="form-control" style="border-radius: 8px; font-size: 0.88rem; height: 42px;">
                            <option value="pending">در انتظار بررسی</option>
                            <option value="assigned">تکنسین اختصاص یافت</option>
                            <option value="in_progress">در حال انجام کار / اعزام</option>
                            <option value="completed">تکمیل شده و پایان یافته</option>
                            <option value="canceled">لغو شده</option>
                        </select>
                    </div>
                </div>

                <!-- Estimated Cost & Final Cost -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 6px; color: #1e293b;">تخمین اولیه هزینه (تومان):</label>
                        <input type="number" name="estimated_cost" id="modalEstCost" class="form-control" placeholder="مثال: ۴۵۰۰۰۰" style="border-radius: 8px; font-size: 0.9rem; height: 42px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 6px; color: #1e293b;">هزینه نهایی فاکتور (تومان):</label>
                        <input type="number" name="final_cost" id="modalFinCost" class="form-control" placeholder="مبلغ نهایی دریافتی" style="border-radius: 8px; font-size: 0.9rem; height: 42px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 22px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 6px; color: #1e293b;">یادداشت‌های داخلی و گزارش فنی تکنسین:</label>
                    <textarea name="admin_notes" id="modalAdminNotes" class="form-control" rows="3" placeholder="قطعات تعویضی، شماره سریال فاکتور یا توضیحات عیب دستگاه..." style="border-radius: 8px; font-size: 0.88rem; padding: 10px;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <button type="button" class="btn btn-outline" onclick="closeManageRequestModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="padding: 9px 24px; font-weight: 700; border-radius: 8px;">
                        <i class="fa-solid fa-check"></i>
                        ذخیره و بروزرسانی وضعیت
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openManageRequestModal(req) {
    document.getElementById('modalRequestId').value = req.id;
    document.getElementById('modalTrackingCode').innerText = req.tracking_code;
    document.getElementById('modalCustName').innerText = req.customer_name;
    document.getElementById('modalCustPhone').innerText = req.customer_phone;
    document.getElementById('modalServiceType').innerText = req.service_type;
    document.getElementById('modalBrandModel').innerText = req.brand + (req.model ? (' - ' + req.model) : '');
    document.getElementById('modalFullAddress').innerText = req.district + ' - ' + req.address;
    document.getElementById('modalProblemDesc').innerText = (req.problem_type || '') + (req.description ? (' (' + req.description + ')') : 'بدون توضیح');

    document.getElementById('modalTechSelect').value = req.technician_id || '';
    document.getElementById('modalStatusSelect').value = req.status || 'pending';
    document.getElementById('modalEstCost').value = req.estimated_cost || '';
    document.getElementById('modalFinCost').value = req.final_cost || '';
    document.getElementById('modalAdminNotes').value = req.admin_notes || '';

    // Handle Photos & Video
    var mediaSec = document.getElementById('modalMediaSection');
    var photosList = document.getElementById('modalPhotosList');
    var videoWrap = document.getElementById('modalVideoWrap');
    var videoPlayer = document.getElementById('modalVideoPlayer');

    photosList.innerHTML = '';
    var hasMedia = false;

    if (req.photos_array && req.photos_array.length > 0) {
        hasMedia = true;
        req.photos_array.forEach(function(pUrl) {
            var fullUrl = window.THERMOBAN_CONFIG ? (window.THERMOBAN_CONFIG.baseUrl + '/' + pUrl) : ('/' + pUrl);
            var a = document.createElement('a');
            a.href = fullUrl;
            a.target = '_blank';
            a.style.display = 'block';
            a.style.width = '80px';
            a.style.height = '80px';
            a.style.borderRadius = '8px';
            a.style.overflow = 'hidden';
            a.style.border = '2px solid #e2e8f0';
            a.innerHTML = '<img src="' + fullUrl + '" style="width: 100%; height: 100%; object-fit: cover;" alt="عکس ضمیمه">';
            photosList.appendChild(a);
        });
    }

    if (req.video_path) {
        hasMedia = true;
        videoWrap.style.display = 'block';
        var videoUrl = window.THERMOBAN_CONFIG ? (window.THERMOBAN_CONFIG.baseUrl + '/' + req.video_path) : ('/' + req.video_path);
        videoPlayer.src = videoUrl;
    } else {
        videoWrap.style.display = 'none';
        videoPlayer.src = '';
    }

    mediaSec.style.display = hasMedia ? 'block' : 'none';

    var m = document.getElementById('manageRequestModal');
    if (m) { m.style.display = 'flex'; m.classList.add('active'); }
}

function closeManageRequestModal() {
    var v = document.getElementById('modalVideoPlayer');
    if (v) { v.pause(); v.src = ''; }
    var m = document.getElementById('manageRequestModal');
    if (m) { m.style.display = 'none'; m.classList.remove('active'); }
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('serviceRequestsTable', {
            searchInputId: 'requestsSearchInput',
            paginationContainerId: 'serviceRequestsPagination',
            pageSize: 10
        });
    }
});
</script>
