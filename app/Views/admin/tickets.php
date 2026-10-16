<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 25px;">
        <?= icon('check', '', 18) ?>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<div class="panel-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 class="panel-main-title" style="margin: 0 0 6px 0; font-size: 1.4rem; font-weight: 800; color: #011C39;">تیکت‌های پشتیبانی، رزرو و گارانتی</h2>
        <p class="panel-subtitle" style="margin: 0; color: #64748b; font-size: 0.9rem;">پاسخگویی به استعلامات فنی کاربران، هماهنگی اعزام تکنسین، و اطلاع‌رسانی خودکار ایمیل و پیامک</p>
    </div>
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <button type="button" class="btn btn-primary" onclick="openCreateTicketModal()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px; border-radius: 8px;">
            <i class="fa-solid fa-plus-circle"></i>
            <span>ثبت تیکت جدید برای مشتری</span>
        </button>
    </div>
</div>

<!-- Filter Bar -->
<div class="dashboard-block" style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <span style="font-weight: 700; color: #011C39; font-size: 0.95rem;">فیلتر وضعیت:</span>
        <button type="button" class="btn btn-outline ticket-filter-btn active" data-status="all" onclick="filterTicketsByStatus('all', this)" style="padding: 6px 14px; font-size: 0.85rem; border-radius: 20px;">همه (<?= count($tickets) ?>)</button>
        <button type="button" class="btn btn-outline ticket-filter-btn" data-status="open" onclick="filterTicketsByStatus('open', this)" style="padding: 6px 14px; font-size: 0.85rem; border-radius: 20px;">در انتظار پاسخ</button>
        <button type="button" class="btn btn-outline ticket-filter-btn" data-status="answered" onclick="filterTicketsByStatus('answered', this)" style="padding: 6px 14px; font-size: 0.85rem; border-radius: 20px;">پاسخ داده شده</button>
        <button type="button" class="btn btn-outline ticket-filter-btn" data-status="closed" onclick="filterTicketsByStatus('closed', this)" style="padding: 6px 14px; font-size: 0.85rem; border-radius: 20px;">بسته شده</button>
    </div>
    <div style="position: relative; width: 300px;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
        <input type="text" id="ticketsSearchInput" class="form-control" style="padding-right: 38px; border-radius: 20px; font-size: 0.85rem; height: 38px;" placeholder="جستجو در تیکت‌ها، نام و شماره تماس..." onkeyup="searchAdminTickets(this)">
    </div>
</div>

<div class="tickets-cards-list" id="ticketsListContainer">
    <?php if (empty($tickets)): ?>
        <div class="dashboard-block" style="background:#fff; text-align: center; color: #94a3b8; padding: 45px 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <i class="fa-solid fa-headset" style="font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
            تیکت فعالی برای نمایش وجود ندارد.
        </div>
    <?php else: ?>
        <?php foreach ($tickets as $t): ?>
            <div class="ticket-full-card" data-status="<?= e($t['status']) ?>" data-search-text="<?= e(mb_strtolower($t['ticket_number'] . ' ' . ($t['user_name'] ?? '') . ' ' . ($t['user_phone'] ?? '') . ' ' . $t['subject'] . ' ' . $t['category'])) ?>" style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                <div class="ticket-card-header" style="background: #f8fafc; padding: 16px 22px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <strong style="color: #011C39; font-size: 1.05rem;"><?= e($t['ticket_number']) ?></strong>
                        <span style="color: #475569; margin-right: 14px; font-size: 0.9rem;">
                            <i class="fa-solid fa-user" style="margin-left: 4px; color: #94a3b8;"></i>
                            <?= e($t['user_name'] ?? 'مشتری آزاد') ?> 
                            (<?= e($t['user_phone'] ?? '-') ?>)
                            <?php if (!empty($t['user_email'])): ?>
                                <span style="font-size: 0.8rem; color: #0284c7; margin-right: 6px;"><i class="fa-solid fa-envelope"></i> <?= e($t['user_email']) ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="ticket-badges" style="display: flex; gap: 8px; align-items: center;">
                        <span class="status-badge status-<?= $t['status'] ?>"><?= e($t['status']) ?></span>
                        <span class="priority-badge priority-<?= $t['priority'] ?>">اولویت: <?= e($t['priority']) ?></span>
                    </div>
                </div>

                <div style="padding: 16px 22px 6px 22px;">
                    <h3 class="ticket-title" style="margin: 0 0 8px 0; font-size: 1.15rem; font-weight: 800; color: #011C39;"><?= e($t['subject']) ?></h3>
                    <div style="font-size: 0.84rem; color: #64748b; display: flex; gap: 16px; flex-wrap: wrap;">
                        <span><i class="fa-solid fa-folder-open" style="margin-left: 4px; color: #EF5C16;"></i> دسته‌بندی: <strong><?= e($t['category']) ?></strong></span>
                        <span><i class="fa-solid fa-calendar-days" style="margin-left: 4px;"></i> تاریخ ثبت: <?= to_shamsi($t['created_at'], 'Y/m/d H:i') ?></span>
                    </div>
                </div>

                <div class="ticket-messages-thread" style="padding: 18px 22px;">
                    <?php if (!empty($t['messages'])): ?>
                        <?php foreach ($t['messages'] as $msg): ?>
                            <div class="ticket-msg-bubble <?= ($msg['sender_type'] === 'admin') ? 'staff-bubble' : 'user-bubble' ?>" style="margin-bottom: 12px; border-radius: 10px; padding: 14px 18px; <?= ($msg['sender_type'] === 'admin') ? 'background: #eff6ff; border-right: 4px solid #3b82f6;' : 'background: #f1f5f9; border-right: 4px solid #64748b;' ?>">
                                <div class="msg-header" style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.84rem;">
                                    <strong style="color: <?= ($msg['sender_type'] === 'admin') ? '#1d4ed8' : '#334155' ?>;">
                                        <i class="fa-solid <?= ($msg['sender_type'] === 'admin') ? 'fa-user-tie' : 'fa-user' ?>" style="margin-left: 4px;"></i>
                                        <?= e($msg['sender_name']) ?>
                                    </strong>
                                    <span style="color: #94a3b8; font-size: 0.8rem;"><?= to_shamsi($msg['created_at'], 'Y/m/d H:i') ?></span>
                                </div>
                                <div class="msg-body" style="font-size: 0.92rem; line-height: 1.7; color: #1e293b;">
                                    <?= nl2br(e($msg['message'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #94a3b8; font-size: 0.85rem;">پیامی در این تیکت ثبت نشده است.</p>
                    <?php endif; ?>
                </div>

                <!-- Admin Reply Box -->
                <div class="ticket-reply-box" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 22px;">
                    <form action="<?= url('admin/tickets') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label style="font-weight: 700; color: #011C39; margin-bottom: 6px; display: block; font-size: 0.92rem;">
                                <i class="fa-solid fa-reply" style="margin-left: 6px; color: #EF5C16;"></i> ارسال پاسخ رسمی به مشتری:
                            </label>
                            <textarea name="reply_message" rows="3" class="form-control" placeholder="پاسخ کارشناسی و راهنمایی فنی خود را بنویسید..." required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;"></textarea>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
                            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <label style="margin: 0; font-size: 0.88rem; font-weight: 700; color: #475569;">تغییر وضعیت:</label>
                                    <select name="status" class="form-control" style="width: auto; padding: 6px 12px; font-size: 0.85rem; border-radius: 6px;">
                                        <option value="answered" <?= ($t['status'] === 'answered') ? 'selected' : '' ?>>پاسخ داده شده</option>
                                        <option value="open" <?= ($t['status'] === 'open') ? 'selected' : '' ?>>در انتظار پیگیری</option>
                                        <option value="closed" <?= ($t['status'] === 'closed') ? 'selected' : '' ?>>بستن تیکت</option>
                                    </select>
                                </div>

                                <!-- Notification Checkboxes -->
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; cursor: pointer; color: #334155; margin: 0;">
                                    <input type="checkbox" name="send_email" value="1" checked style="accent-color: #EF5C16;">
                                    <span>ارسال اعلان ایمیل</span>
                                </label>
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; cursor: pointer; color: #334155; margin: 0;">
                                    <input type="checkbox" name="send_sms" value="1" checked style="accent-color: #EF5C16;">
                                    <span>ارسال اعلان پیامک (ملی‌پیامک)</span>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary" style="padding: 8px 22px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>ثبت و ارسال پاسخ</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal: Create New Ticket for User -->
<div class="site-modal" id="createTicketModal">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="modal-title" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #011C39; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-ticket" style="color: #EF5C16;"></i>
                    ثبت تیکت جدید برای مشتری
                </h3>
                <button type="button" class="modal-close" onclick="closeCreateTicketModal()" style="background: none; border: none; font-size: 1.4rem; color: #94a3b8; cursor: pointer;">&times;</button>
            </div>
            <form action="<?= url('admin/tickets') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create_ticket">

                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">انتخاب مشتری <span style="color: #ef4444;">*</span></label>
                        <select name="user_id" class="form-control" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                            <option value="">-- مشتری را انتخاب کنید --</option>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>">
                                        <?= e($u['name']) ?> (<?= e($u['phone']) ?>) <?= !empty($u['email']) ? ' - ' . e($u['email']) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="0">مشتری آزاد / حضوری</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">موضوع تیکت <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="subject" class="form-control" placeholder="مثال: پیگیری هماهنگی سرویس دوره‌ای پکیج بوتان" required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">دسته‌بندی</label>
                            <select name="category" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                                <option value="سرویس و عیب‌یابی">سرویس و عیب‌یابی</option>
                                <option value="رزرو تکنسین در محل">رزرو تکنسین در محل</option>
                                <option value="گارانتی و خدمات پس از فروش">گارانتی و خدمات پس از فروش</option>
                                <option value="استعلام قیمت و خرید پکیج">استعلام قیمت و خرید پکیج</option>
                                <option value="امور مالی و فاکتور">امور مالی و فاکتور</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">اولویت بررسی</label>
                            <select name="priority" class="form-control" style="border-radius: 8px; font-size: 0.9rem; padding: 10px;">
                                <option value="normal" selected>معمولی</option>
                                <option value="high">بالا (فوری)</option>
                                <option value="urgent">بسیار فوری (خرابی و نشتی)</option>
                                <option value="low">کم</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">متن پیام یا توضیحات اولیه کارشناس <span style="color: #ef4444;">*</span></label>
                        <textarea name="message" rows="4" class="form-control" placeholder="توضیحات لازم یا نتیجه تماس اولیه با مشتری را بنویسید..." required style="border-radius: 8px; font-size: 0.9rem; padding: 10px;"></textarea>
                    </div>

                    <div style="background: #f1f5f9; padding: 12px 16px; border-radius: 8px; display: flex; flex-direction: column; gap: 8px;">
                        <span style="font-weight: 700; font-size: 0.85rem; color: #334155;">کانال‌های اطلاع‌رسانی خودکار به مشتری:</span>
                        <div style="display: flex; gap: 18px; flex-wrap: wrap;">
                            <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; cursor: pointer; color: #1e293b; margin: 0;">
                                <input type="checkbox" name="send_email" value="1" checked style="accent-color: #EF5C16;">
                                <span>ارسال ایمیل اطلاع‌رسانی (در صورت وجود ایمیل)</span>
                            </label>
                            <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; cursor: pointer; color: #1e293b; margin: 0;">
                                <input type="checkbox" name="send_sms" value="1" checked style="accent-color: #EF5C16;">
                                <span>ارسال پیامک از طریق ملی‌پیامک (در صورت فعال بودن ماژول)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-outline" onclick="closeCreateTicketModal()" style="border-radius: 8px; padding: 8px 18px;">انصراف</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 8px 22px; font-weight: 700;">
                        <i class="fa-solid fa-paper-plane" style="margin-left: 6px;"></i> ثبت تیکت و ارسال اعلان
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateTicketModal() {
    var modal = document.getElementById('createTicketModal');
    if (modal) modal.classList.add('active');
}

function closeCreateTicketModal() {
    var modal = document.getElementById('createTicketModal');
    if (modal) modal.classList.remove('active');
}

function filterTicketsByStatus(status, btn) {
    document.querySelectorAll('.ticket-filter-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');

    var cards = document.querySelectorAll('.ticket-full-card');
    cards.forEach(function(card) {
        if (status === 'all' || card.getAttribute('data-status') === status) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function searchAdminTickets(input) {
    var query = (input.value || '').trim().toLowerCase();
    var cards = document.querySelectorAll('.ticket-full-card');
    cards.forEach(function(card) {
        var text = card.getAttribute('data-search-text') || '';
        if (!query || text.indexOf(query) !== -1) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
