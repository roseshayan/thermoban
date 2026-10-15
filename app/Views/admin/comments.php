<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-comments" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت و پاسخ‌گویی به نظرات و دیدگاه‌ها
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                بررسی نظرات کاربران در مورد پکیج‌ها و مقالات آموزشی، تایید نمایش، ارسال پاسخ رسمی نمایندگی و حذف نظرات اسپم
            </p>
        </div>
        <div style="font-size: 0.88rem; color: #64748b; font-weight: 700;">
            مجموع نظرات: <span style="color: #EF5C16; font-size: 1.1rem;"><?= count($comments) ?></span>
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

<div class="admin-card" style="background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
    <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h3 style="margin: 0; font-size: 1.05rem; color: #011C39; font-weight: 800;">
            فهرست کامل دیدگاه‌ها
        </h3>
        <input type="text" id="commentsSearchInput" placeholder="جستجو در نظرات..." class="form-control" style="max-width: 260px; font-size: 0.84rem; padding: 6px 12px;">
    </div>

    <div class="table-responsive">
        <table class="profile-table" id="commentsTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: right;">
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">نویسنده</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">مرتبط با</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">امتیاز</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">متن دیدگاه و پاسخ</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">وضعیت</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569; text-align: left;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                            هیچ دیدگاهی برای نمایش وجود ندارد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; vertical-align: top;">
                            <td style="padding: 16px 20px;">
                                <strong style="color: #011C39; font-size: 0.92rem; display: block; margin-bottom: 4px;"><?= e($c['author_name']) ?></strong>
                                <?php if (!empty($c['author_phone'])): ?>
                                    <span style="font-size: 0.78rem; color: #64748b;" dir="ltr"><?= e($c['author_phone']) ?></span>
                                <?php endif; ?>
                                <span style="display: block; font-size: 0.75rem; color: #94a3b8; margin-top: 4px;" dir="ltr"><?= e($c['created_at']) ?></span>
                            </td>

                            <td style="padding: 16px 20px; font-size: 0.85rem;">
                                <?php if (!empty($c['product_title'])): ?>
                                    <span style="background: #ffedd5; color: #ea580c; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; display: inline-block; margin-bottom: 4px;">محصول</span>
                                    <div style="font-weight: 600; color: #334155;"><?= e($c['product_title']) ?></div>
                                <?php elseif (!empty($c['article_title'])): ?>
                                    <span style="background: #e0f2fe; color: #0284c7; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; display: inline-block; margin-bottom: 4px;">مقاله</span>
                                    <div style="font-weight: 600; color: #334155;"><?= e($c['article_title']) ?></div>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">سراسری</span>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 16px 20px;">
                                <div style="color: #f59e0b; font-size: 0.85rem; display: flex; gap: 2px;">
                                    <?php for ($i = 0; $i < (int)$c['rating']; $i++): ?>
                                        <i class="fa-solid fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>

                            <td style="padding: 16px 20px; max-width: 380px;">
                                <div style="font-size: 0.88rem; color: #1e293b; line-height: 1.6; margin-bottom: 8px;">
                                    <?= nl2br(e($c['content'])) ?>
                                </div>
                                <?php if (!empty($c['reply'])): ?>
                                    <div style="background: #f8fafc; border-right: 3px solid #EF5C16; padding: 8px 12px; border-radius: 4px; font-size: 0.82rem; color: #475569;">
                                        <strong style="color: #EF5C16; display: block; margin-bottom: 2px;">پاسخ مدیریت نمایندگی ۷۲۷۳:</strong>
                                        <?= nl2br(e($c['reply'])) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 16px 20px;">
                                <?php if ($c['status'] === 'approved'): ?>
                                    <span style="background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;">
                                        <i class="fa-solid fa-check"></i> تایید شده
                                    </span>
                                <?php elseif ($c['status'] === 'rejected'): ?>
                                    <span style="background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;">
                                        <i class="fa-solid fa-xmark"></i> رد شده
                                    </span>
                                <?php else: ?>
                                    <span style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;">
                                        <i class="fa-solid fa-clock"></i> در انتظار تایید
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 16px 20px; text-align: left;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    
                                    <!-- Reply Button -->
                                    <button type="button" class="btn-table-action" style="background: #ede9fe; color: #6d28d9; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;" onclick="openReplyModal(<?= (int)$c['id'] ?>, '<?= e(addslashes($c['author_name'])) ?>', '<?= e(addslashes($c['reply'] ?? '')) ?>')">
                                        <i class="fa-solid fa-reply"></i>
                                        <span>پاسخ</span>
                                    </button>

                                    <!-- Approve Button -->
                                    <?php if ($c['status'] !== 'approved'): ?>
                                        <form action="<?= url('admin/comments') ?>" method="POST" style="margin: 0;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="comment_id" value="<?= (int)$c['id'] ?>">
                                            <button type="submit" class="btn-table-action" style="background: #dcfce7; color: #16a34a; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;" title="تایید دیدگاه">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Reject Button -->
                                    <?php if ($c['status'] !== 'rejected'): ?>
                                        <form action="<?= url('admin/comments') ?>" method="POST" style="margin: 0;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="comment_id" value="<?= (int)$c['id'] ?>">
                                            <button type="submit" class="btn-table-action" style="background: #fff1f2; color: #e11d48; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;" title="رد دیدگاه">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Delete Button -->
                                    <form action="<?= url('admin/comments') ?>" method="POST" style="margin: 0;" onsubmit="return confirmDeleteComment(event, this)">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="comment_id" value="<?= (int)$c['id'] ?>">
                                        <button type="submit" class="btn-table-action" style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;" title="حذف دیدگاه">
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

    <!-- Pagination Controls -->
    <div id="commentsPagination" class="admin-table-pagination" style="padding: 14px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.initAdminTablePro) {
        window.initAdminTablePro('commentsTable', {
            searchInputId: 'commentsSearchInput',
            paginationContainerId: 'commentsPagination',
            pageSize: 10
        });
    }
});

function openReplyModal(commentId, authorName, currentReply) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'پاسخ رسمی مدیریت به ' + authorName,
            html: `
                <div style="text-align: right; font-family: Vazirmatn, sans-serif; font-size: 0.9rem;">
                    <label style="display:block; margin-bottom: 8px; font-weight: 700;">متن پاسخ نمایندگی رسمی ترموبان (کد ۷۲۷۳):</label>
                    <textarea id="swalReplyText" class="form-control" rows="4" style="width: 100%; border-radius: 8px; padding: 10px;" placeholder="پاسخ خود را بنویسید...">${currentReply}</textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'ثبت پاسخ و تایید دیدگاه',
            cancelButtonText: 'انصراف',
            confirmButtonColor: '#EF5C16',
            preConfirm: () => {
                const reply = document.getElementById('swalReplyText').value.trim();
                if (!reply) {
                    Swal.showValidationMessage('لطفاً متن پاسخ را وارد نمایید.');
                    return false;
                }
                return reply;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'csrf_token';
                tokenInput.value = '<?= csrf_token() ?>';
                form.appendChild(tokenInput);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'reply';
                form.appendChild(actionInput);

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'comment_id';
                idInput.value = commentId;
                form.appendChild(idInput);

                const replyInput = document.createElement('input');
                replyInput.type = 'hidden';
                replyInput.name = 'reply';
                replyInput.value = result.value;
                form.appendChild(replyInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    } else {
        const reply = prompt('متن پاسخ به ' + authorName + ':', currentReply);
        if (reply) {
            // submit basic form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.href;
            form.innerHTML = `
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="reply">
                <input type="hidden" name="comment_id" value="${commentId}">
                <input type="hidden" name="reply" value="${reply}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
}

function confirmDeleteComment(e, form) {
    e.preventDefault();
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'حذف دیدگاه',
            text: 'آیا از حذف این نظر اطمینان دارید؟ این عملیات غیرقابل بازگشت است.',
            icon: 'warning',
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
        if (confirm('آیا از حذف این دیدگاه اطمینان دارید؟')) {
            form.submit();
        }
    }
    return false;
}
</script>
