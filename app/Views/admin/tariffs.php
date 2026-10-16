<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-file-invoice-dollar" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت نرخ‌های مصوب اتحادیه و تعرفه خدمات
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                تنظیم و بروزرسانی قیمت‌های رسمی خدمات، اجرت تعمیرات، سرویس دوره‌ای و نصب پکیج مطابق با آخرین نرخنامه رسمی اتحادیه
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= url('services') ?>" target="_blank" class="btn btn-outline" style="font-size: 0.88rem; padding: 10px 18px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; background: #fff; text-decoration: none;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>مشاهده صفحه عمومی خدمات</span>
            </a>
            <button type="button" class="btn btn-primary" onclick="openNewTariffModal()" style="font-size: 0.88rem; padding: 10px 20px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus"></i>
                <span>افزودن تعرفه جدید</span>
            </button>
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

<!-- Tariffs Cards Grid / Table -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow-x: auto;">
    <table class="admin-table" style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: right; color: #475569;">
                <th style="padding: 12px 14px; width: 60px;">ترتیب</th>
                <th style="padding: 12px 14px;">عنوان خدمت</th>
                <th style="padding: 12px 14px;">نرخ مصوب اتحادیه</th>
                <th style="padding: 12px 14px;">مدت ضمانت شرکتی</th>
                <th style="padding: 12px 14px;">اقدامات تخصصی</th>
                <th style="padding: 12px 14px; text-align: center;">وضعیت نمایش</th>
                <th style="padding: 12px 14px; text-align: center;">عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tariffs)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 35px 20px; color: #94a3b8;">
                        هیچ نرخ مصوبی تعریف نشده است.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($tariffs as $t): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 700; color: #64748b;"><?= fa_num($t['sort_order']) ?></td>
                        <td style="padding: 14px;">
                            <strong style="color: #011C39; font-size: 0.95rem; display: block;"><?= e($t['title']) ?></strong>
                            <?php if (!empty($t['subtitle'])): ?>
                                <span style="font-size: 0.78rem; color: #64748b; display: block; margin-top: 3px; max-width: 320px;"><?= e($t['subtitle']) ?></span>
                            <?php endif; ?>
                            <span style="font-family: monospace; font-size: 0.72rem; color: #94a3b8; display: inline-block; margin-top: 4px; background: #f8fafc; padding: 1px 6px; border-radius: 4px;">key: <?= e($t['service_key']) ?></span>
                        </td>
                        <td style="padding: 14px;">
                            <span style="background: #fff7ed; color: #c2410c; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.85rem; border: 1px solid #ffedd5; display: inline-block;">
                                <?= e($t['price']) ?>
                            </span>
                        </td>
                        <td style="padding: 14px; font-weight: 600; color: #059669;">
                            <i class="fa-solid fa-shield-halved" style="margin-left: 4px;"></i>
                            <?= e($t['guarantee']) ?>
                        </td>
                        <td style="padding: 14px;">
                            <span style="background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 5px; font-size: 0.78rem; font-weight: 700;">
                                <?= fa_num(count($t['features_list'] ?? [])) ?> مورد اقدام
                            </span>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <form action="<?= url('admin/tariffs') ?>" method="POST" style="display: inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="tariff_id" value="<?= $t['id'] ?>">
                                <?php if ($t['is_active']): ?>
                                    <button type="submit" class="btn btn-outline" style="border-color: #86efac; color: #16a34a; padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; background: #f0fdf4;" title="کلیک جهت غیرفعال‌سازی">
                                        <i class="fa-solid fa-check"></i> فعال در سایت
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-outline" style="border-color: #fca5a5; color: #dc2626; padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; background: #fef2f2;" title="کلیک جهت فعال‌سازی">
                                        <i class="fa-solid fa-xmark"></i> غیرفعال
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                <button type="button" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.78rem; border-radius: 6px;" onclick="openEditTariffModal(<?= htmlspecialchars(json_encode($t)) ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>ویرایش نرخ</span>
                                </button>
                                <form action="<?= url('admin/tariffs') ?>" method="POST" onsubmit="return confirm('آیا از حذف این تعرفه اطمینان دارید؟');" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="tariff_id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem; color: #dc2626; border-color: #fca5a5; border-radius: 6px;" title="حذف تعرفه">
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

<!-- Modal: New Tariff -->
<div id="newTariffModal" class="site-modal" style="display: none;">
    <div class="modal-dialog" style="max-width: 560px;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #011C39; color: #fff; padding: 18px 24px; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;"><i class="fa-solid fa-file-circle-plus text-orange" style="margin-left: 8px;"></i>افزودن نرخ مصوب خدمت جدید</h3>
                <button type="button" onclick="closeNewTariffModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: #fff; line-height: 1;">&times;</button>
            </div>
            <form action="<?= url('admin/tariffs') ?>" method="POST" style="padding: 24px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create">

                <div class="form-row-2" style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">عنوان خدمت <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="مثال: رسوب‌زدایی و اسیدشویی مبدل" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">کلید سیستمی (انگلیسی)</label>
                        <input type="text" name="service_key" class="form-control" dir="ltr" placeholder="descaling" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">زیرعنوان / شرح مختصر</label>
                    <input type="text" name="subtitle" class="form-control" placeholder="شرح کوتاه فرآیند خدمت..." style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">نرخ مصوب اتحادیه <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="price" class="form-control" placeholder="مثال: از ۳۵۰,۰۰۰ تومان (طبق نرخنامه)" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">مدت زمان ضمانت کتبی</label>
                        <input type="text" name="guarantee" class="form-control" placeholder="مثال: ۶ ماه ضمانت کتبی شرکتی" value="۶ ماه ضمانت کتبی شرکتی" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">اقدامات تخصصی (هر مورد در یک خط مجزا)</label>
                    <textarea name="features" class="form-control" rows="4" placeholder="اسیدشویی مبدل اصلی با پمپ دیسکلر&#10;تست فشار منبع انبساط&#10;تنظیم شعله گاز" style="border-radius: 8px; font-size: 0.88rem; padding: 10px;"></textarea>
                </div>

                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">ترتیب نمایش (اولویت)</label>
                        <input type="number" name="sort_order" class="form-control" value="1" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; padding-top: 24px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; font-size: 0.88rem;">
                            <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px;">
                            <span>فعال و قابل نمایش در وب‌سایت</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <button type="button" class="btn btn-outline" onclick="closeNewTariffModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="padding: 9px 24px; font-weight: 700; border-radius: 8px;">
                        <i class="fa-solid fa-plus"></i>
                        ثبت نرخ مصوب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Tariff -->
<div id="editTariffModal" class="site-modal" style="display: none;">
    <div class="modal-dialog" style="max-width: 560px;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #011C39; color: #fff; padding: 18px 24px; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;"><i class="fa-solid fa-pen-to-square text-orange" style="margin-left: 8px;"></i>ویرایش نرخ مصوب خدمت</h3>
                <button type="button" onclick="closeEditTariffModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: #fff; line-height: 1;">&times;</button>
            </div>
            <form action="<?= url('admin/tariffs') ?>" method="POST" style="padding: 24px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="tariff_id" id="editTariffId">

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">عنوان خدمت <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="title" id="editTariffTitle" class="form-control" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">زیرعنوان / شرح مختصر</label>
                    <input type="text" name="subtitle" id="editTariffSubtitle" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">نرخ مصوب اتحادیه <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="price" id="editTariffPrice" class="form-control" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">مدت زمان ضمانت کتبی</label>
                        <input type="text" name="guarantee" id="editTariffGuarantee" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">اقدامات تخصصی (هر مورد در یک خط مجزا)</label>
                    <textarea name="features" id="editTariffFeatures" class="form-control" rows="4" style="border-radius: 8px; font-size: 0.88rem; padding: 10px;"></textarea>
                </div>

                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">ترتیب نمایش</label>
                        <input type="number" name="sort_order" id="editTariffSort" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; padding-top: 24px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; font-size: 0.88rem;">
                            <input type="checkbox" name="is_active" id="editTariffActive" value="1" style="width: 18px; height: 18px;">
                            <span>فعال و قابل نمایش در وب‌سایت</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <button type="button" class="btn btn-outline" onclick="closeEditTariffModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="padding: 9px 24px; font-weight: 700; border-radius: 8px;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        ذخیره تغییرات تعرفه
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openNewTariffModal() {
    var m = document.getElementById('newTariffModal');
    if (m) { m.style.display = 'flex'; m.classList.add('active'); }
}
function closeNewTariffModal() {
    var m = document.getElementById('newTariffModal');
    if (m) { m.style.display = 'none'; m.classList.remove('active'); }
}

function openEditTariffModal(tariff) {
    document.getElementById('editTariffId').value = tariff.id;
    document.getElementById('editTariffTitle').value = tariff.title || '';
    document.getElementById('editTariffSubtitle').value = tariff.subtitle || '';
    document.getElementById('editTariffPrice').value = tariff.price || '';
    document.getElementById('editTariffGuarantee').value = tariff.guarantee || '';
    document.getElementById('editTariffSort').value = tariff.sort_order || 0;
    document.getElementById('editTariffActive').checked = (parseInt(tariff.is_active) === 1);

    var feats = tariff.features_list || [];
    document.getElementById('editTariffFeatures').value = feats.join("\n");

    var m = document.getElementById('editTariffModal');
    if (m) { m.style.display = 'flex'; m.classList.add('active'); }
}
function closeEditTariffModal() {
    var m = document.getElementById('editTariffModal');
    if (m) { m.style.display = 'none'; m.classList.remove('active'); }
}
</script>
