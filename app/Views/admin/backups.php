<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-database" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت و بازیابی فایل‌های پشتیبان (Backup & Restore)
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                تهیه نسخه پشتیبان از ساختار و داده‌های دیتابیس بدون نیاز به mysqldump (سازگار با هاست اشتراکی cPanel)، ری‌استور و آپلود ابری
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <form action="<?= url('admin/backups') ?>" method="POST" style="margin: 0;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create">
                <?php if ($filesIrEnabled): ?>
                    <input type="hidden" name="upload_cloud" value="1">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary" style="background-color: #EF5C16; color: #fff; padding: 10px 20px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i>
                    <span>تهیه پشتیبان جدید هم‌اکنون</span>
                </button>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 20px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 14px 18px; border-radius: 10px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="form-alert-msg alert-error" style="margin-bottom: 20px; background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 14px 18px; border-radius: 10px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<!-- Retention Notice -->
<div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; border-radius: 12px; padding: 16px 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 14px;">
    <i class="fa-solid fa-shield-halved" style="font-size: 1.5rem; color: #3b82f6;"></i>
    <div style="font-size: 0.88rem; line-height: 1.6;">
        <strong>قانون حفظ ۲ نسخه اخیر:</strong> جهت جلوگیری از پر شدن فضای دیسک هاست اشتراکی، سامانه ترموبان همیشه تنها ۲ نسخه از آخرین فایل‌های پشتیبان را در مسیر <code dir="ltr">storage/backups/</code> نگهداری می‌کند و با هر بک‌آپ‌گیری جدید، نسخه‌های قدیمی‌تر به صورت خودکار حذف می‌شوند.
    </div>
</div>

<!-- KPI Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="admin-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 50px; height: 50px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-hard-drive"></i>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">فایل‌های موجود روی دیسک</span>
            <h3 style="margin: 4px 0 0 0; font-size: 1.3rem; color: #011C39; font-weight: 800;"><?= count($backups) ?> <small style="font-size: 0.85rem; color: #94a3b8;">/ ۲ نسخه</small></h3>
        </div>
    </div>

    <div class="admin-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 50px; height: 50px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">آخرین بک‌آپ موفق</span>
            <h3 style="margin: 4px 0 0 0; font-size: 0.98rem; color: #011C39; font-weight: 800;" dir="ltr"><?= e($lastRun) ?></h3>
        </div>
    </div>

    <div class="admin-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 50px; height: 50px; border-radius: 12px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-cloud-arrow-up"></i>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">هاست ابری files.ir</span>
            <h3 style="margin: 4px 0 0 0; font-size: 1rem; color: <?= $filesIrEnabled ? '#16a34a' : '#94a3b8' ?>; font-weight: 800;">
                <?= $filesIrEnabled ? 'فعال (پلن ابری)' : 'غیرفعال (سوپر ادمین)' ?>
            </h3>
        </div>
    </div>
</div>

<!-- Backups Table -->
<div class="admin-card" style="background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            فهرست فایل‌های پشتیبان ذخیره‌شده (SQL Dump)
        </h3>
        
        <!-- Schedule Form -->
        <form action="<?= url('admin/backups') ?>" method="POST" style="margin: 0; display: flex; align-items: center; gap: 10px;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_schedule">
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">زمان‌بندی خودکار:</span>
            <select name="backup_schedule" class="form-control" style="padding: 6px 12px; font-size: 0.85rem; border-radius: 6px;" onchange="this.form.submit()">
                <option value="daily" <?= ($schedule === 'daily') ? 'selected' : '' ?>>روزانه (Daily)</option>
                <option value="weekly" <?= ($schedule === 'weekly') ? 'selected' : '' ?>>هفتگی (Weekly)</option>
                <option value="monthly" <?= ($schedule === 'monthly') ? 'selected' : '' ?>>ماهانه (Monthly)</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="profile-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: right;">
                    <th style="padding: 14px 24px; font-size: 0.85rem; color: #475569;">نام فایل بک‌آپ</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">حجم</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">تاریخ ایجاد</th>
                    <th style="padding: 14px 24px; font-size: 0.85rem; color: #475569; text-align: left;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($backups)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; margin-bottom: 12px; display: block; color: #cbd5e1;"></i>
                            هیچ فایل پشتیبانی هنوز ثبت نشده است. از دکمه بالا برای تهیه اولین بک‌آپ استفاده کنید.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($backups as $b): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px 24px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fa-solid fa-file-code" style="color: #64748b; font-size: 1.1rem;"></i>
                                    <strong dir="ltr" style="font-size: 0.9rem; color: #0f172a;"><?= e($b['filename']) ?></strong>
                                </div>
                            </td>
                            <td style="padding: 16px 20px; font-size: 0.88rem; color: #475569;"><?= e($b['size']) ?></td>
                            <td style="padding: 16px 20px; font-size: 0.88rem; color: #475569;" dir="ltr"><?= e($b['created_at']) ?></td>
                            <td style="padding: 16px 24px; text-align: left;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    
                                    <!-- Download Button -->
                                    <a href="<?= url('admin/backups/download/' . $b['filename']) ?>" class="btn-table-action" style="background: #f1f5f9; color: #334155; padding: 6px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; text-decoration: none;" title="دانلود فایل">
                                        <i class="fa-solid fa-download"></i>
                                        <span>دانلود</span>
                                    </a>

                                    <!-- Restore Button -->
                                    <form action="<?= url('admin/backups') ?>" method="POST" style="margin: 0;" onsubmit="return confirmRestore(event, this, '<?= e($b['filename']) ?>')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="restore">
                                        <input type="hidden" name="filename" value="<?= e($b['filename']) ?>">
                                        <button type="submit" class="btn-table-action" style="background: #dbeafe; color: #1e40af; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;" title="بازیابی پایگاه داده">
                                            <i class="fa-solid fa-rotate-left"></i>
                                            <span>بازیابی (Restore)</span>
                                        </button>
                                    </form>

                                    <!-- Upload to Files.ir Cloud (If enabled) -->
                                    <?php if ($filesIrEnabled): ?>
                                        <form action="<?= url('admin/backups') ?>" method="POST" style="margin: 0;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="upload_cloud">
                                            <input type="hidden" name="filename" value="<?= e($b['filename']) ?>">
                                            <button type="submit" class="btn-table-action" style="background: #ede9fe; color: #6d28d9; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer;" title="آپلود در فضای ابری files.ir">
                                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                                <span>ارسال به files.ir</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Delete Button -->
                                    <form action="<?= url('admin/backups') ?>" method="POST" style="margin: 0;" onsubmit="return confirmDelete(event, this, '<?= e($b['filename']) ?>')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="filename" value="<?= e($b['filename']) ?>">
                                        <button type="submit" class="btn-table-action" style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer;" title="حذف بک‌آپ">
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
    </div>
</div>

<script>
function confirmRestore(e, form, filename) {
    e.preventDefault();
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'هشدار بازیابی پایگاه داده',
            html: `آیا از بازیابی دیتابیس با فایل <strong>${filename}</strong> اطمینان دارید؟<br><span style="color: #ef4444; font-size: 0.85rem;">تمامی اطلاعات کنونی پایگاه داده با محتوای این نسخه جایگزین خواهد شد.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'بله، پایگاه داده را بازیابی کن',
            cancelButtonText: 'انصراف'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    } else {
        if (confirm('آیا از بازیابی پایگاه داده با این فایل اطمینان دارید؟ اطلاعات فعلی بازنویسی می‌شود.')) {
            form.submit();
        }
    }
    return false;
}

function confirmDelete(e, form, filename) {
    e.preventDefault();
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'حذف فایل پشتیبان',
            text: `آیا از حذف دائم فایل ${filename} اطمینان دارید؟`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'بله، حذف کن',
            cancelButtonText: 'انصراف'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    } else {
        if (confirm('آیا از حذف این فایل پشتیبان اطمینان دارید؟')) {
            form.submit();
        }
    }
    return false;
}
</script>
