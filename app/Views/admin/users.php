<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-users-gear" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت کاربران، تکنسین‌ها و سطوح دسترسی
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                مشاهده فهرست کاربران ثبت‌نامی، تغییر نقش (مدیر، تکنسین، مشتری)، مسدودسازی و بازنشانی کلمه عبور
            </p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('newUserModal').style.display='flex';" style="font-size: 0.88rem; padding: 10px 20px; font-weight: 700;">
            <i class="fa-solid fa-user-plus" style="margin-left: 6px;"></i>
            افزودن کاربر جدید
        </button>
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

<!-- Search Bar -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 16px 20px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
    <form action="<?= url('admin/users') ?>" method="GET" style="display: flex; gap: 12px; align-items: center;">
        <input type="text" name="q" class="form-control" value="<?= e($search ?? '') ?>" placeholder="جستجو بر اساس نام، شماره موبایل یا ایمیل..." style="flex-grow: 1;">
        <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-size: 0.88rem; font-weight: 700;">
            <i class="fa-solid fa-magnifying-glass" style="margin-left: 6px;"></i>
            جستجو
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= url('admin/users') ?>" class="btn btn-outline" style="padding: 10px 18px; font-size: 0.88rem;">پاکسازی</a>
        <?php endif; ?>
    </form>
</div>

<!-- Users Table -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow-x: auto;">
    <table class="admin-table" style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: right; color: #475569;">
                <th style="padding: 12px 14px;">شناسه</th>
                <th style="padding: 12px 14px;">نام کاربر</th>
                <th style="padding: 12px 14px;">شماره تماس</th>
                <th style="padding: 12px 14px;">نقش کاربری</th>
                <th style="padding: 12px 14px;">وضعیت</th>
                <th style="padding: 12px 14px;">تاریخ ثبت‌نام</th>
                <th style="padding: 12px 14px; text-align: center;">عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">هیچ کاربری یافت نشد.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $u): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 700; color: #64748b;">#<?= $u['id'] ?></td>
                        <td style="padding: 14px; font-weight: 700; color: #011C39;">
                            <?= e($u['name']) ?>
                            <?php if ($u['id'] === \App\Core\Auth::id()): ?>
                                <span style="background: #e0f2fe; color: #0284c7; padding: 2px 6px; border-radius: 4px; font-size: 0.72rem; margin-right: 6px;">شما</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px; font-family: monospace;" dir="ltr"><?= e($u['phone']) ?></td>
                        <td style="padding: 14px;">
                            <?php if ($u['role'] === 'super_admin'): ?>
                                <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem;">سوپر ادمین (برنامه‌نویس)</span>
                            <?php elseif ($u['role'] === 'admin'): ?>
                                <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem;">مدیر سیستم</span>
                            <?php elseif ($u['role'] === 'technician'): ?>
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem;">تکنسین نصب و تعمیر</span>
                            <?php else: ?>
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.78rem;">مشتری / کاربر عادی</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px;">
                            <?php if (($u['status'] ?? 'active') === 'active'): ?>
                                <span style="color: #16a34a; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> فعال
                                </span>
                            <?php else: ?>
                                <span style="color: #dc2626; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> مسدود
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px; color: #64748b; font-size: 0.8rem;"><?= substr($u['created_at'] ?? '', 0, 10) ?></td>
                        <td style="padding: 14px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem;" onclick="openEditModal(<?= htmlspecialchars(json_encode($u)) ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    ویرایش
                                </button>
                                <button type="button" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem; color: #d97706;" onclick="openPasswordModal(<?= $u['id'] ?>, '<?= e($u['name']) ?>')">
                                    <i class="fa-solid fa-key"></i>
                                    رمز
                                </button>
                                <?php if ($u['id'] !== \App\Core\Auth::id() && $u['role'] !== 'super_admin'): ?>
                                    <form action="<?= url('admin/users') ?>" method="POST" onsubmit="return confirm('آیا از حذف این کاربر اطمینان دارید؟');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem; color: #dc2626;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal: New User -->
<div id="newUserModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #fff; border-radius: 14px; width: 450px; max-width: 90%; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">افزودن کاربر جدید</h3>
            <button type="button" onclick="document.getElementById('newUserModal').style.display='none';" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        <form action="<?= url('admin/users') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نام و نام خانوادگی</label>
                <input type="text" name="name" class="form-control" required placeholder="علیرضا حسینی">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">شماره تلفن همراه</label>
                <input type="tel" name="phone" class="form-control" dir="ltr" required placeholder="۰۹۱۲۳۴۵۶۷۸۹">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">ایمیل (اختیاری)</label>
                <input type="email" name="email" class="form-control" dir="ltr" placeholder="user@example.com">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">کلمه عبور اولیه</label>
                <input type="text" name="password" class="form-control" value="123456" required>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نقش کاربری</label>
                <select name="role" class="form-control">
                    <option value="user">مشتری / کاربر عادی</option>
                    <option value="technician">تکنسین نصب و تعمیر</option>
                    <option value="admin">مدیر سیستم</option>
                    <?php if (\App\Core\Auth::isSuperAdmin()): ?>
                        <option value="super_admin">سوپر ادمین</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">وضعیت حساب</label>
                <select name="status" class="form-control">
                    <option value="active">فعال</option>
                    <option value="blocked">مسدود</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding: 10px; font-weight: 700;">ایجاد حساب کاربری</button>
        </form>
    </div>
</div>

<!-- Modal: Edit Role & Status -->
<div id="editModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #fff; border-radius: 14px; width: 420px; max-width: 90%; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">ویرایش نقش و دسترسی</h3>
            <button type="button" onclick="document.getElementById('editModal').style.display='none';" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        <form action="<?= url('admin/users') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_role">
            <input type="hidden" name="user_id" id="editUserId">

            <p style="font-size: 0.9rem; color: #334155; margin-bottom: 16px;">کاربر: <strong id="editUserName"></strong></p>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">نقش کاربری</label>
                <select name="role" id="editRole" class="form-control">
                    <option value="user">مشتری / کاربر عادی</option>
                    <option value="technician">تکنسین نصب و تعمیر</option>
                    <option value="admin">مدیر سیستم</option>
                    <?php if (\App\Core\Auth::isSuperAdmin()): ?>
                        <option value="super_admin">سوپر ادمین</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">وضعیت حساب</label>
                <select name="status" id="editStatus" class="form-control">
                    <option value="active">فعال</option>
                    <option value="blocked">مسدود (غیرفعال)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding: 10px; font-weight: 700;">ذخیره تغییرات</button>
        </form>
    </div>
</div>

<!-- Modal: Reset Password -->
<div id="passwordModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #fff; border-radius: 14px; width: 400px; max-width: 90%; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">تغییر کلمه عبور کاربر</h3>
            <button type="button" onclick="document.getElementById('passwordModal').style.display='none';" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        <form action="<?= url('admin/users') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="reset_password">
            <input type="hidden" name="user_id" id="pwdUserId">

            <p style="font-size: 0.9rem; color: #334155; margin-bottom: 16px;">کاربر: <strong id="pwdUserName"></strong></p>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 700; font-size: 0.84rem; display: block; margin-bottom: 4px;">کلمه عبور جدید</label>
                <input type="text" name="new_password" class="form-control" placeholder="حداقل ۶ کاراکتر..." required>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding: 10px; font-weight: 700;">بروزرسانی کلمه عبور</button>
        </form>
    </div>
</div>

<script>
function openEditModal(user) {
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUserName').innerText = user.name + ' (' + user.phone + ')';
    document.getElementById('editRole').value = user.role;
    document.getElementById('editStatus').value = user.status || 'active';
    document.getElementById('editModal').style.display = 'flex';
}

function openPasswordModal(id, name) {
    document.getElementById('pwdUserId').value = id;
    document.getElementById('pwdUserName').innerText = name;
    document.getElementById('passwordModal').style.display = 'flex';
}
</script>
