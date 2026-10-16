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
            <i class="fa-solid fa-store" style="color: var(--color-primary); margin-left: 8px;"></i>
            مدیریت شعب و دفاتر نمایندگی
        </h2>
        <p class="panel-subtitle">مدیریت آدرس، تلفن‌های ثابت و همراه شعب منطقه‌ای جهت نمایش در صفحه تماس با ما و اعزام فوری تکنسین</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-primary" onclick="openNewBranchModal()">
            <i class="fa-solid fa-plus" style="margin-left: 4px;"></i>
            <span>افزودن شعبه جدید</span>
        </button>
    </div>
</div>

<!-- Branches Table -->
<div class="dashboard-block" style="background: #fff; padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table" style="margin: 0; width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569; width: 50px;">ترتیب</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">نام شعبه</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">مناطق و محله‌های تحت پوشش</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">شماره‌های تماس</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">نشانی و ساعت کاری</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 90px;">وضعیت</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 110px;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($branches)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            هیچ شعبه‌ای برای نمایش ثبت نشده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($branches as $b): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 18px; font-weight: 700; color: #64748b;"><?= $b['sort_order'] ?></td>
                            <td style="padding: 14px 18px;">
                                <strong style="color: #1e293b; font-size: 0.95rem; display: block; margin-bottom: 4px;">
                                    <?= e($b['name']) ?>
                                </strong>
                                <?php if ((int)$b['is_main'] === 1): ?>
                                    <span style="background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; font-size: 0.74rem; font-weight: 800;">★ دفتر مرکزی</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 18px; font-size: 0.88rem; color: #475569;">
                                <?= e($b['district'] ?? '-') ?>
                            </td>
                            <td style="padding: 14px 18px;">
                                <?php if (!empty($b['phone_landline'])): ?>
                                    <div style="font-size: 0.84rem; color: #1e293b; font-weight: 700;">
                                        <i class="fa-solid fa-phone" style="color: #64748b; width: 16px;"></i>
                                        <span dir="ltr"><?= e($b['phone_landline']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($b['phone_mobile'])): ?>
                                    <div style="font-size: 0.84rem; color: var(--color-primary); font-weight: 700; margin-top: 3px;">
                                        <i class="fa-solid fa-mobile-screen" style="width: 16px;"></i>
                                        <span dir="ltr"><?= e($b['phone_mobile']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 18px; font-size: 0.84rem; color: #64748b; line-height: 1.5;">
                                <div><?= e($b['address'] ?? 'آدرس ثبت نشده') ?></div>
                                <?php if (!empty($b['working_hours'])): ?>
                                    <div style="color: #059669; font-weight: 600; margin-top: 2px;">
                                        <i class="fa-solid fa-clock" style="font-size: 0.75rem;"></i> <?= e($b['working_hours']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <form action="<?= url('admin/branches') ?>" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="branch_id" value="<?= $b['id'] ?>">
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <?php if ((int)$b['is_active'] === 1): ?>
                                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">فعال</span>
                                        <?php else: ?>
                                            <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">غیرفعال</span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.82rem;" onclick="openEditBranchModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="<?= url('admin/branches') ?>" method="POST" onsubmit="return confirm('آیا از حذف این شعبه اطمینان دارید؟');" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="branch_id" value="<?= $b['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.82rem; color: #ef4444; border-color: #fca5a5;">
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

<!-- Modal For Add / Edit Branch -->
<div id="branchModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 14px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h3 id="modalBranchTitle" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b;">افزودن شعبه جدید نمایندگی</h3>
            <button type="button" onclick="closeBranchModal()" style="border: none; background: transparent; font-size: 1.4rem; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>

        <form action="<?= url('admin/branches') ?>" method="POST">
            <input type="hidden" name="action" id="branchAction" value="create">
            <input type="hidden" name="branch_id" id="branchId" value="0">

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">نام شعبه <span class="required">*</span></label>
                    <input type="text" name="name" id="formBranchName" class="form-control" placeholder="مثلاً: شعبه شمال تهران" required>
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">شهر</label>
                    <input type="text" name="city" id="formBranchCity" class="form-control" value="تهران" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 0.85rem; font-weight: 700;">مناطق و محله‌های تحت پوشش <span class="required">*</span></label>
                <input type="text" name="district" id="formBranchDistrict" class="form-control" placeholder="مثلاً: مناطق ۱ الی ۳، تجریش، نیاوران، پاسداران، الهیه، قلهک" required>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">شماره تلفن ثابت شعبه</label>
                    <input type="text" name="phone_landline" id="formBranchLandline" class="form-control" placeholder="مثلاً: ۰۲۱۲۲۳۳۴۴۵۵" dir="ltr">
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">شماره همراه سرپرست تکنسین‌ها</label>
                    <input type="tel" name="phone_mobile" id="formBranchMobile" class="form-control" placeholder="مثلاً: ۰۹۱۲۳۴۵۶۷۸۹" dir="ltr">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 0.85rem; font-weight: 700;">نشانی دقیق شعبه</label>
                <textarea name="address" id="formBranchAddress" rows="2" class="form-control" placeholder="تهران، خیابان ولیعصر، بالاتر از پارک وی، ..."></textarea>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">ساعات کاری و پذیرش</label>
                    <input type="text" name="working_hours" id="formBranchHours" class="form-control" value="شنبه تا پنج‌شنبه: ۸:۰۰ الی ۲۰:۰۰">
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">ترتیب نمایش</label>
                    <input type="number" name="sort_order" id="formBranchSort" class="form-control" value="0">
                </div>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">عرض جغرافیایی (Latitude)</label>
                    <input type="text" name="lat" id="formBranchLat" class="form-control" placeholder="مثلاً: 35.79" dir="ltr">
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">طول جغرافیایی (Longitude)</label>
                    <input type="text" name="lng" id="formBranchLng" class="form-control" placeholder="مثلاً: 51.42" dir="ltr">
                </div>
            </div>

            <div class="form-row-2" style="margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_main" id="formBranchMain" value="1" style="width: 18px; height: 18px;">
                        <span style="font-weight: 700; font-size: 0.88rem; color: #0284c7;">به عنوان دفتر مرکزی در نظر گرفته شود</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" id="formBranchActive" value="1" checked style="width: 18px; height: 18px;">
                        <span style="font-weight: 700; font-size: 0.9rem;">نمایش فعال در سایت</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeBranchModal()" class="btn btn-outline">انصراف</button>
                <button type="submit" class="btn btn-primary">ذخیره شعبه نمایندگی</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewBranchModal() {
    document.getElementById('modalBranchTitle').innerText = 'افزودن شعبه جدید نمایندگی';
    document.getElementById('branchAction').value = 'create';
    document.getElementById('branchId').value = '0';
    document.getElementById('formBranchName').value = '';
    document.getElementById('formBranchCity').value = 'تهران';
    document.getElementById('formBranchDistrict').value = '';
    document.getElementById('formBranchLandline').value = '';
    document.getElementById('formBranchMobile').value = '';
    document.getElementById('formBranchAddress').value = '';
    document.getElementById('formBranchHours').value = 'شنبه تا پنج‌شنبه: ۸:۰۰ الی ۲۰:۰۰';
    document.getElementById('formBranchSort').value = '0';
    document.getElementById('formBranchLat').value = '';
    document.getElementById('formBranchLng').value = '';
    document.getElementById('formBranchMain').checked = false;
    document.getElementById('formBranchActive').checked = true;
    document.getElementById('branchModal').style.display = 'flex';
}

function openEditBranchModal(item) {
    document.getElementById('modalBranchTitle').innerText = 'ویرایش شعبه: ' + item.name;
    document.getElementById('branchAction').value = 'update';
    document.getElementById('branchId').value = item.id;
    document.getElementById('formBranchName').value = item.name || '';
    document.getElementById('formBranchCity').value = item.city || 'تهران';
    document.getElementById('formBranchDistrict').value = item.district || '';
    document.getElementById('formBranchLandline').value = item.phone_landline || '';
    document.getElementById('formBranchMobile').value = item.phone_mobile || '';
    document.getElementById('formBranchAddress').value = item.address || '';
    document.getElementById('formBranchHours').value = item.working_hours || '';
    document.getElementById('formBranchSort').value = item.sort_order || 0;
    document.getElementById('formBranchLat').value = item.lat || '';
    document.getElementById('formBranchLng').value = item.lng || '';
    document.getElementById('formBranchMain').checked = (parseInt(item.is_main) === 1);
    document.getElementById('formBranchActive').checked = (parseInt(item.is_active) === 1);
    document.getElementById('branchModal').style.display = 'flex';
}

function closeBranchModal() {
    document.getElementById('branchModal').style.display = 'none';
}
</script>
