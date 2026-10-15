<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="margin-bottom: 20px;">
    <div>
        <h2 class="panel-main-title">تیکت‌های پشتیبانی، رزرو و گارانتی</h2>
        <p class="panel-subtitle">پاسخگویی به استعلامات فنی کاربران، هماهنگی رزرو سرویسکار و پاسخ به سوالات گارانتی</p>
    </div>
</div>

<div class="tickets-cards-list">
    <?php if (empty($tickets)): ?>
        <div class="dashboard-block" style="background:#fff; text-align: center; color: #94a3b8; padding: 40px;">
            تیکت فعالی برای نمایش وجود ندارد.
        </div>
    <?php else: ?>
        <?php foreach ($tickets as $t): ?>
            <div class="ticket-full-card">
                <div class="ticket-card-header">
                    <div>
                        <strong style="color: var(--color-navy);"><?= e($t['ticket_number']) ?></strong>
                        <span style="color: #64748b; margin-right: 12px;">کاربر: <?= e($t['user_name']) ?> (<?= e($t['user_phone']) ?>)</span>
                    </div>
                    <div class="ticket-badges">
                        <span class="status-badge status-<?= $t['status'] ?>"><?= e($t['status']) ?></span>
                        <span class="priority-badge priority-<?= $t['priority'] ?>">اولویت: <?= e($t['priority']) ?></span>
                    </div>
                </div>

                <h3 class="ticket-title"><?= e($t['subject']) ?></h3>
                <div style="padding: 0 24px; font-size: 0.82rem; color: #94a3b8;">
                    دسته‌بندی: <?= e($t['category']) ?> | تاریخ: <?= e($t['created_at']) ?>
                </div>

                <div class="ticket-messages-thread">
                    <?php foreach ($t['messages'] as $msg): ?>
                        <div class="ticket-msg-bubble <?= ($msg['sender_type'] === 'admin') ? 'staff-bubble' : 'user-bubble' ?>">
                            <div class="msg-header">
                                <strong><?= e($msg['sender_name']) ?></strong>
                                <span><?= e($msg['created_at']) ?></span>
                            </div>
                            <div class="msg-body">
                                <?= nl2br(e($msg['message'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Admin Reply Box -->
                <div class="ticket-reply-box">
                    <form action="<?= url('admin/tickets') ?>" method="POST">
                        <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                        <div class="form-group">
                            <label>ارسال پاسخ رسمی به مشتری:</label>
                            <textarea name="reply_message" rows="3" class="form-control" placeholder="پاسخ کارشناسی و راهنمایی فنی خود را بنویسید..." required></textarea>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <label style="margin: 0; font-size: 0.88rem;">تغییر وضعیت تیکت به:</label>
                                <select name="status" class="form-control" style="width: auto; padding: 6px 12px;">
                                    <option value="answered" selected>پاسخ داده شده</option>
                                    <option value="closed">بستن تیکت</option>
                                    <option value="open">در انتظار پیگیری</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">
                                <?= icon('ticket', '', 16) ?>
                                <span>ثبت و ارسال پاسخ</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
