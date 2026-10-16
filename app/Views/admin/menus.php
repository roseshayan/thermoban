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
            <i class="fa-solid fa-bars-staggered" style="color: var(--color-primary); margin-left: 8px;"></i>
            مدیریت منوهای اصلی و مگامنو
        </h2>
        <p class="panel-subtitle">ساخت و چیدمان لینک‌های ناوبری هدر و فوتر با قابلیت ساخت زیرمنو، مگامنو چندستونه و برچسب‌های ویژه</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-primary" onclick="openNewMenuModal()">
            <i class="fa-solid fa-plus" style="margin-left: 4px;"></i>
            <span>افزودن آیتم جدید</span>
        </button>
    </div>
</div>

<!-- Location Filter Tabs -->
<div style="display: flex; gap: 8px; margin-bottom: 20px;">
    <a href="<?= url('admin/menus?location=header') ?>" class="btn <?= ($locationFilter === 'header') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-weight: 700;">
        <i class="fa-solid fa-window-maximize" style="margin-left: 6px;"></i> منوی بالای سایت (Header)
    </a>
    <a href="<?= url('admin/menus?location=footer') ?>" class="btn <?= ($locationFilter === 'footer') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-weight: 700;">
        <i class="fa-solid fa-table-cells" style="margin-left: 6px;"></i> لینک‌های فوتر (Footer)
    </a>
</div>

<!-- Menus Table -->
<div class="dashboard-block" style="background: #fff; padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table" style="margin: 0; width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">ترتیب</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">عنوان آیتم</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">آدرس لینک (URL)</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">نوع نمایش</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">والد (زیرمجموعه)</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569;">وضعیت</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            هیچ آیتمی برای منوی <?= ($locationFilter === 'header') ? 'هدر' : 'فوتر' ?> ثبت نشده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 18px; font-weight: 700; color: #64748b;"><?= $item['sort_order'] ?></td>
                            <td style="padding: 14px 18px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <?php if (!empty($item['parent_id'])): ?>
                                        <span style="color: #cbd5e1; margin-left: 6px;">↳</span>
                                    <?php endif; ?>
                                    <?php if (!empty($item['icon'])): ?>
                                        <i class="<?= e($item['icon']) ?>" style="color: var(--color-primary);"></i>
                                    <?php endif; ?>
                                    <strong style="color: #1e293b; font-size: 0.95rem;"><?= e($item['title']) ?></strong>
                                    <?php if (!empty($item['badge'])): ?>
                                        <span class="nav-badge-pill" style="font-size: 0.7rem;"><?= e($item['badge']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="padding: 14px 18px; direction: ltr; text-align: right; font-family: monospace; font-size: 0.85rem; color: #475569;">
                                <?= e($item['url']) ?>
                            </td>
                            <td style="padding: 14px 18px;">
                                <?php if ($item['type'] === 'megamenu'): ?>
                                    <span style="background: #fdf2f8; color: #db2777; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">مگامنو</span>
                                <?php elseif ($item['type'] === 'dropdown'): ?>
                                    <span style="background: #eff6ff; color: #2563eb; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">کشویی (Dropdown)</span>
                                <?php else: ?>
                                    <span style="background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">لینک مستقیم</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 18px; color: #64748b; font-size: 0.88rem;">
                                <?= !empty($item['parent_title']) ? e($item['parent_title']) : '<span style="color:#cbd5e1;">(آیتم اصلی)</span>' ?>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <form action="<?= url('admin/menus') ?>" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="menu_id" value="<?= $item['id'] ?>">
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <?php if ((int)$item['is_active'] === 1): ?>
                                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">فعال</span>
                                        <?php else: ?>
                                            <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">مخفی</span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.82rem;" onclick="openEditMenuModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="<?= url('admin/menus') ?>" method="POST" onsubmit="return confirm('آیا از حذف این منو و کلیه زیرمجموعه‌های آن اطمینان دارید؟');" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="menu_id" value="<?= $item['id'] ?>">
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

<!-- Modal For Add / Edit Menu Item -->
<div id="menuItemModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 14px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h3 id="modalMenuTitle" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b;">افزودن آیتم جدید به منو</h3>
            <button type="button" onclick="closeMenuModal()" style="border: none; background: transparent; font-size: 1.4rem; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>

        <form action="<?= url('admin/menus') ?>" method="POST">
            <input type="hidden" name="action" id="menuAction" value="create">
            <input type="hidden" name="menu_id" id="menuId" value="0">
            <input type="hidden" name="location" value="<?= e($locationFilter) ?>">

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">عنوان آیتم <span class="required">*</span></label>
                    <input type="text" name="title" id="formTitle" class="form-control" placeholder="مثلاً: خدمات تعمیرات" required>
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">آدرس لینک (URL) <span class="required">*</span></label>
                    <input type="text" name="url" id="formUrl" class="form-control" placeholder="services یا https://..." dir="ltr" required>
                </div>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">نوع آیتم</label>
                    <select name="type" id="formType" class="form-control" onchange="toggleMegamenuBox(this.value)">
                        <option value="simple">لینک معمولی (تک لینک)</option>
                        <option value="dropdown">منوی کشویی دارای زیرمنو (Dropdown)</option>
                        <option value="megamenu">مگامنو بزرگ چندستونه (Mega Menu)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">منوی والد (اختیاری)</label>
                    <select name="parent_id" id="formParentId" class="form-control">
                        <option value="">-- بدون والد (آیتم اصلی منو) --</option>
                        <?php foreach ($parents as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">آیکون (FontAwesome)</label>
                    <input type="text" name="icon" id="formIcon" class="form-control" placeholder="مثلاً fa-solid fa-wrench" dir="ltr">
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">برچسب کوچک (بج)</label>
                    <input type="text" name="badge" id="formBadge" class="form-control" placeholder="مثلاً: ویژه، جدید، ۵۰٪">
                </div>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">ترتیب نمایش (عدد کمتر = اولویت بالاتر)</label>
                    <input type="number" name="sort_order" id="formSort" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">نحوه باز شدن پیوند</label>
                    <select name="target" id="formTarget" class="form-control">
                        <option value="_self">در همان برگه (_self)</option>
                        <option value="_blank">در برگه جدید (_blank)</option>
                    </select>
                </div>
            </div>

            <!-- Mega Menu JSON Block -->
            <div id="megamenuBox" style="display: none; margin-bottom: 14px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #db2777;">
                    ساختار ستون‌ها و لینک‌های مگامنو (فرمت JSON استاندارد)
                </label>
                <textarea name="megamenu_data" id="formMegamenuData" rows="6" class="form-control" dir="ltr" style="font-family: monospace; font-size: 0.82rem;" placeholder='{"columns": [{"title": "خدمات تخصصی", "links": [{"title": "تعمیر پکیج", "url": "services"}]}]}'></textarea>
                <small style="color: #64748b; font-size: 0.78rem;">می‌توانید ستون‌های مگامنو را با کلیدهای title و آرایه links در قالب JSON مشخص فرمایید.</small>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="formIsActive" value="1" checked style="width: 18px; height: 18px;">
                    <span style="font-weight: 700; font-size: 0.9rem;">نمایش و انتشار در سایت</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeMenuModal()" class="btn btn-outline">انصراف</button>
                <button type="submit" class="btn btn-primary">ذخیره آیتم منو</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewMenuModal() {
    document.getElementById('modalMenuTitle').innerText = 'افزودن آیتم جدید به منو';
    document.getElementById('menuAction').value = 'create';
    document.getElementById('menuId').value = '0';
    document.getElementById('formTitle').value = '';
    document.getElementById('formUrl').value = '';
    document.getElementById('formType').value = 'simple';
    document.getElementById('formParentId').value = '';
    document.getElementById('formIcon').value = '';
    document.getElementById('formBadge').value = '';
    document.getElementById('formSort').value = '0';
    document.getElementById('formTarget').value = '_self';
    document.getElementById('formMegamenuData').value = '';
    document.getElementById('formIsActive').checked = true;
    toggleMegamenuBox('simple');
    document.getElementById('menuItemModal').style.display = 'flex';
}

function openEditMenuModal(item) {
    document.getElementById('modalMenuTitle').innerText = 'ویرایش آیتم منو: ' + item.title;
    document.getElementById('menuAction').value = 'update';
    document.getElementById('menuId').value = item.id;
    document.getElementById('formTitle').value = item.title || '';
    document.getElementById('formUrl').value = item.url || '';
    document.getElementById('formType').value = item.type || 'simple';
    document.getElementById('formParentId').value = item.parent_id || '';
    document.getElementById('formIcon').value = item.icon || '';
    document.getElementById('formBadge').value = item.badge || '';
    document.getElementById('formSort').value = item.sort_order || 0;
    document.getElementById('formTarget').value = item.target || '_self';
    document.getElementById('formMegamenuData').value = item.megamenu_data || '';
    document.getElementById('formIsActive').checked = (parseInt(item.is_active) === 1);
    toggleMegamenuBox(item.type || 'simple');
    document.getElementById('menuItemModal').style.display = 'flex';
}

function closeMenuModal() {
    document.getElementById('menuItemModal').style.display = 'none';
}

function toggleMegamenuBox(val) {
    var box = document.getElementById('megamenuBox');
    if (val === 'megamenu') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}
</script>
