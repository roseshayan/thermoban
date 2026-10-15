<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-users-gear" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت کاربران، تکنسین‌ها و سطوح دسترسی
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                مشاهده فهرست کاربران ثبت‌نامی، افزودن کاربر جدید توسط مدیر، تغییر نقش، مسدودسازی و بازنشانی کلمه عبور
            </p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openNewUserModal()" style="font-size: 0.88rem; padding: 10px 20px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-user-plus"></i>
            <span>افزودن کاربر جدید</span>
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

<!-- Search and Filter Bar -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 16px 20px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
    <div style="display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <label style="font-weight: 700; color: #011C39; font-size: 0.88rem; margin: 0;">فیلتر نقش:</label>
            <select id="userRoleFilter" class="form-control" style="width: 170px; font-size: 0.85rem; height: 38px; border-radius: 8px;" onchange="window.filterTableByColumn('usersTable', 3, this.value)">
                <option value="">همه نقش‌ها</option>
                <option value="مشتری">مشتری / کاربر عادی</option>
                <option value="تکنسین">تکنسین نصب و تعمیر</option>
                <option value="مدیر سیستم">مدیر سیستم</option>
                <option value="سوپر ادمین">سوپر ادمین</option>
            </select>
        </div>
        <div style="position: relative; width: 320px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" id="usersSearchInput" class="form-control" style="padding-right: 38px; border-radius: 8px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در نام، شماره موبایل، کدملی یا ایمیل...">
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow-x: auto;">
    <table class="admin-table" id="usersTable" style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: right; color: #475569;">
                <th style="padding: 12px 14px;">شناسه</th>
                <th style="padding: 12px 14px;">نام و نام خانوادگی</th>
                <th style="padding: 12px 14px;">شماره تماس</th>
                <th style="padding: 12px 14px;">نقش کاربری</th>
                <th style="padding: 12px 14px;">وضعیت</th>
                <th style="padding: 12px 14px;">تاریخ ثبت‌نام</th>
                <th style="padding: 12px 14px; text-align: center;">عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr class="no-records-row">
                    <td colspan="7" style="text-align: center; padding: 35px 20px; color: #94a3b8;">
                        <i class="fa-solid fa-user-slash" style="font-size: 2.2rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                        هیچ کاربری یافت نشد.
                    </td>
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
                            <?php if (!empty($u['national_id'])): ?>
                                <span style="display: block; font-size: 0.76rem; color: #94a3b8; font-weight: 500;">کد ملی: <?= e($u['national_id']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px; font-family: monospace; text-align: right;" dir="ltr"><?= e($u['phone']) ?></td>
                        <td style="padding: 14px;">
                            <?php if ($u['role'] === 'super_admin'): ?>
                                <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem;">سوپر ادمین</span>
                            <?php elseif ($u['role'] === 'admin'): ?>
                                <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem;">مدیر سیستم</span>
                            <?php elseif ($u['role'] === 'technician'): ?>
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.78rem;">تکنسین</span>
                            <?php else: ?>
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.78rem;">مشتری</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px;">
                            <?php if (($u['status'] ?? 'active') === 'active'): ?>
                                <span style="color: #16a34a; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> فعال
                                </span>
                            <?php else: ?>
                                <span style="color: #dc2626; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> مسدود
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 14px; color: #64748b; font-size: 0.8rem;"><?= substr($u['created_at'] ?? '', 0, 10) ?></td>
                        <td style="padding: 14px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px; justify-content: center;">
                                <button type="button" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.78rem; border-radius: 6px;" onclick="openEditModal(<?= htmlspecialchars(json_encode($u)) ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>ویرایش</span>
                                </button>
                                <button type="button" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.78rem; color: #d97706; border-color: #fde68a; border-radius: 6px;" onclick="openPasswordModal(<?= $u['id'] ?>, '<?= e($u['name']) ?>')">
                                    <i class="fa-solid fa-key"></i>
                                    <span>رمز</span>
                                </button>
                                <?php if ($u['id'] !== \App\Core\Auth::id() && $u['role'] !== 'super_admin'): ?>
                                    <form action="<?= url('admin/users') ?>" method="POST" onsubmit="return confirm('آیا از حذف این کاربر اطمینان دارید؟');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.78rem; color: #dc2626; border-color: #fca5a5; border-radius: 6px;" title="حذف کاربر">
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

    <!-- Pagination Controls -->
    <div id="usersTablePagination" class="admin-table-pagination" style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<!-- Modal: New User -->
<div id="newUserModal" class="site-modal" style="display: none;">
    <div class="modal-dialog" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; border-radius: 14px 14px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-user-plus" style="color: #EF5C16;"></i>
                    افزودن کاربر جدید به سیستم
                </h3>
                <button type="button" onclick="closeNewUserModal()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #94a3b8;">&times;</button>
            </div>
            <form action="<?= url('admin/users') ?>" method="POST" style="padding: 24px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create">

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">نام و نام خانوادگی <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="مثال: علیرضا حسینی" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">شماره تلفن همراه <span style="color: #ef4444;">*</span></label>
                    <input type="tel" name="phone" class="form-control" dir="ltr" required placeholder="۰۹۱۲۳۴۵۶۷۸۹" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">کد ملی (اختیاری)</label>
                    <input type="text" name="national_id" maxlength="10" class="form-control" dir="ltr" placeholder="۱۰ رقم کد ملی" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">ایمیل (اختیاری)</label>
                    <input type="email" name="email" class="form-control" dir="ltr" placeholder="user@example.com" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">کلمه عبور اولیه</label>
                    <input type="text" name="password" class="form-control" value="123456" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">نقش کاربری</label>
                        <select name="role" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                            <option value="user">مشتری / کاربر عادی</option>
                            <option value="technician">تکنسین نصب و تعمیر</option>
                            <option value="admin">مدیر سیستم</option>
                            <?php if (\App\Core\Auth::isSuperAdmin()): ?>
                                <option value="super_admin">سوپر ادمین</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">وضعیت حساب</label>
                        <select name="status" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                            <option value="active">فعال</option>
                            <option value="blocked">مسدود</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-outline" onclick="closeNewUserModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="padding: 9px 22px; font-weight: 700; border-radius: 8px;">ایجاد حساب کاربری</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit All User Info -->
<div id="editModal" class="site-modal" style="display: none;">
    <div class="modal-dialog" style="max-width: 520px;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #011C39; color: #fff; padding: 18px 24px; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;"><i class="fa-solid fa-user-pen text-orange" style="margin-left: 8px;"></i>ویرایش کامل مشخصات کاربر</h3>
                <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: #fff; line-height: 1;">&times;</button>
            </div>
            <form action="<?= url('admin/users') ?>" method="POST" style="padding: 24px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_info">
                <input type="hidden" name="user_id" id="editUserId">

                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">نام و نام خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editUserNameInput" class="admin-input" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">شماره همراه <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="editUserPhoneInput" class="admin-input" dir="ltr" required>
                    </div>
                </div>

                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">کد ملی</label>
                        <input type="text" name="national_id" id="editUserNationalIdInput" class="admin-input" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">آدرس ایمیل</label>
                        <input type="email" name="email" id="editUserEmailInput" class="admin-input" dir="ltr">
                    </div>
                </div>

                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 22px;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">نقش کاربری</label>
                        <select name="role" id="editRole" class="admin-input">
                            <option value="user">مشتری / کاربر عادی</option>
                            <option value="technician">تکنسین نصب و تعمیر</option>
                            <option value="admin">مدیر سیستم</option>
                            <?php if (\App\Core\Auth::isSuperAdmin()): ?>
                                <option value="super_admin">سوپر ادمین</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">وضعیت حساب</label>
                        <select name="status" id="editStatus" class="admin-input">
                            <option value="active">فعال</option>
                            <option value="blocked">مسدود (غیرفعال)</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <button type="button" class="btn btn-outline" onclick="closeEditModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="padding: 9px 24px; font-weight: 700; border-radius: 8px;">
                        <i class="fa-solid fa-check"></i>
                        ذخیره مشخصات کاربر
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Reset Password -->
<div id="passwordModal" class="site-modal" style="display: none;">
    <div class="modal-dialog" style="max-width: 420px;">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; border-radius: 14px 14px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">تغییر کلمه عبور کاربر</h3>
                <button type="button" onclick="closePasswordModal()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #94a3b8;">&times;</button>
            </div>
            <form action="<?= url('admin/users') ?>" method="POST" style="padding: 24px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="user_id" id="pwdUserId">

                <p style="font-size: 0.95rem; color: #334155; margin-bottom: 18px;">کاربر: <strong id="pwdUserName" style="color: #011C39;"></strong></p>

                <div class="form-group" style="margin-bottom: 22px;">
                    <label style="font-weight: 700; font-size: 0.86rem; display: block; margin-bottom: 4px; color: #1e293b;">کلمه عبور جدید</label>
                    <input type="text" name="new_password" class="form-control" placeholder="حداقل ۶ کاراکتر..." required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-outline" onclick="closePasswordModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="padding: 9px 22px; font-weight: 700; border-radius: 8px;">بروزرسانی کلمه عبور</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openNewUserModal() {
    var m = document.getElementById('newUserModal');
    if (m) { m.style.display = 'flex'; m.classList.add('active'); }
}
function closeNewUserModal() {
    var m = document.getElementById('newUserModal');
    if (m) { m.style.display = 'none'; m.classList.remove('active'); }
}

function openEditModal(user) {
    document.getElementById('editUserId').value = user.id;
    if (document.getElementById('editUserNameInput')) document.getElementById('editUserNameInput').value = user.name || '';
    if (document.getElementById('editUserPhoneInput')) document.getElementById('editUserPhoneInput').value = user.phone || '';
    if (document.getElementById('editUserNationalIdInput')) document.getElementById('editUserNationalIdInput').value = user.national_id || '';
    if (document.getElementById('editUserEmailInput')) document.getElementById('editUserEmailInput').value = user.email || '';
    if (document.getElementById('editRole')) document.getElementById('editRole').value = user.role;
    if (document.getElementById('editStatus')) document.getElementById('editStatus').value = user.status || 'active';
    var m = document.getElementById('editModal');
    if (m) { m.style.display = 'flex'; m.classList.add('active'); }
}
function closeEditModal() {
    var m = document.getElementById('editModal');
    if (m) { m.style.display = 'none'; m.classList.remove('active'); }
}

function openPasswordModal(id, name) {
    document.getElementById('pwdUserId').value = id;
    document.getElementById('pwdUserName').innerText = name;
    var m = document.getElementById('passwordModal');
    if (m) { m.style.display = 'flex'; m.classList.add('active'); }
}
function closePasswordModal() {
    var m = document.getElementById('passwordModal');
    if (m) { m.style.display = 'none'; m.classList.remove('active'); }
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('usersTable', {
            searchInputId: 'usersSearchInput',
            paginationContainerId: 'usersTablePagination',
            pageSize: 10
        });
    }
});
</script>
