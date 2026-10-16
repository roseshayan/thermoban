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
            <i class="fa-solid fa-circle-question" style="color: var(--color-primary); margin-left: 8px;"></i>
            مدیریت سوالات متداول (FAQ) و ریچ‌اسنیپت گوگل
        </h2>
        <p class="panel-subtitle">افزودن و ویرایش پرسش‌های پرتکرار مشتریان به تفکیک صفحه اصلی، خدمات، درباره ما، مقالات و محصولات</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-primary" onclick="openNewFaqModal()">
            <i class="fa-solid fa-plus" style="margin-left: 4px;"></i>
            <span>افزودن پرسش جدید</span>
        </button>
    </div>
</div>

<!-- Section Filter Tabs -->
<div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
    <a href="<?= url('admin/faqs') ?>" class="btn <?= empty($sectionFilter) ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-size: 0.86rem;">همه بخش‌ها</a>
    <a href="<?= url('admin/faqs?section=home') ?>" class="btn <?= ($sectionFilter === 'home') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-size: 0.86rem;">صفحه اصلی</a>
    <a href="<?= url('admin/faqs?section=services') ?>" class="btn <?= ($sectionFilter === 'services') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-size: 0.86rem;">خدمات و تعمیرات</a>
    <a href="<?= url('admin/faqs?section=about') ?>" class="btn <?= ($sectionFilter === 'about') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-size: 0.86rem;">درباره ما</a>
    <a href="<?= url('admin/faqs?section=contact') ?>" class="btn <?= ($sectionFilter === 'contact') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-size: 0.86rem;">تماس با ما</a>
    <a href="<?= url('admin/faqs?section=article') ?>" class="btn <?= ($sectionFilter === 'article') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-size: 0.86rem;">مقالات آموزشی</a>
    <a href="<?= url('admin/faqs?section=product') ?>" class="btn <?= ($sectionFilter === 'product') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius: 8px; font-size: 0.86rem;">فروشگاه پکیج</a>
</div>

<!-- FAQs Table -->
<div class="dashboard-block" style="background: #fff; padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table" style="margin: 0; width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569; width: 60px;">ترتیب</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569; width: 140px;">بخش نمایش</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">پرسش و پاسخ</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 100px;">وضعیت</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 120px;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($faqs)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                            هیچ سوال متداولی در این بخش یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faqs as $faq): 
                        $secLabel = match($faq['section']) {
                            'home'     => 'صفحه اصلی',
                            'services' => 'خدمات ما',
                            'about'    => 'درباره ما',
                            'contact'  => 'تماس با ما',
                            'article'  => 'مقالات',
                            'product'  => 'محصولات',
                            default    => $faq['section']
                        };
                    ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 18px; font-weight: 700; color: #64748b;"><?= $faq['sort_order'] ?></td>
                            <td style="padding: 14px 18px;">
                                <span style="background: #f1f5f9; color: #334155; font-size: 0.8rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                    <?= e($secLabel) ?>
                                </span>
                            </td>
                            <td style="padding: 14px 18px;">
                                <strong style="color: #1e293b; font-size: 0.95rem; display: block; margin-bottom: 4px;">
                                    <?= e($faq['question']) ?>
                                </strong>
                                <p style="color: #64748b; font-size: 0.86rem; margin: 0; line-height: 1.6; max-height: 50px; overflow: hidden; text-overflow: ellipsis;">
                                    <?= e(mb_substr($faq['answer'], 0, 140)) ?><?= mb_strlen($faq['answer']) > 140 ? '...' : '' ?>
                                </p>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <form action="<?= url('admin/faqs') ?>" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="faq_id" value="<?= $faq['id'] ?>">
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <?php if ((int)$faq['is_active'] === 1): ?>
                                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">فعال</span>
                                        <?php else: ?>
                                            <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">مخفی</span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.82rem;" onclick="openEditFaqModal(<?= htmlspecialchars(json_encode($faq), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="<?= url('admin/faqs') ?>" method="POST" onsubmit="return confirm('آیا از حذف این پرسش متداول اطمینان دارید؟');" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="faq_id" value="<?= $faq['id'] ?>">
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

<!-- Modal For Add / Edit FAQ -->
<div id="faqModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 14px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h3 id="modalFaqTitle" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b;">افزودن پرسش متداول</h3>
            <button type="button" onclick="closeFaqModal()" style="border: none; background: transparent; font-size: 1.4rem; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>

        <form action="<?= url('admin/faqs') ?>" method="POST">
            <input type="hidden" name="action" id="faqAction" value="create">
            <input type="hidden" name="faq_id" id="faqId" value="0">

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">بخش نمایش پرسش <span class="required">*</span></label>
                    <select name="section" id="formFaqSection" class="form-control" required>
                        <option value="home">صفحه اصلی (Home)</option>
                        <option value="services">خدمات و نرخ مصوب (Services)</option>
                        <option value="about">درباره ما (About)</option>
                        <option value="contact">تماس با ما (Contact)</option>
                        <option value="article">مقالات آموزشی (Blog / Articles)</option>
                        <option value="product">محصولات فروشگاه (Products)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">ترتیب نمایش (اولویت)</label>
                    <input type="number" name="sort_order" id="formFaqSort" class="form-control" value="0">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 0.85rem; font-weight: 700;">عنوان سوال (Question) <span class="required">*</span></label>
                <input type="text" name="question" id="formFaqQuestion" class="form-control" placeholder="مثلاً: هزینه ایاب و ذهاب سرویس‌کار چقدر است؟" required>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 0.85rem; font-weight: 700;">پاسخ کامل سوال (Answer) <span class="required">*</span></label>
                <textarea name="answer" id="formFaqAnswer" rows="4" class="form-control" placeholder="پاسخ کامل و شفاف جهت نمایش به کاربر و موتورهای جستجو..." required></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="formFaqIsActive" value="1" checked style="width: 18px; height: 18px;">
                    <span style="font-weight: 700; font-size: 0.9rem;">نمایش فعال در سایت</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeFaqModal()" class="btn btn-outline">انصراف</button>
                <button type="submit" class="btn btn-primary">ذخیره پرسش متداول</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewFaqModal() {
    document.getElementById('modalFaqTitle').innerText = 'افزودن پرسش متداول جدید';
    document.getElementById('faqAction').value = 'create';
    document.getElementById('faqId').value = '0';
    document.getElementById('formFaqSection').value = '<?= $sectionFilter ?: 'home' ?>';
    document.getElementById('formFaqQuestion').value = '';
    document.getElementById('formFaqAnswer').value = '';
    document.getElementById('formFaqSort').value = '0';
    document.getElementById('formFaqIsActive').checked = true;
    document.getElementById('faqModal').style.display = 'flex';
}

function openEditFaqModal(faq) {
    document.getElementById('modalFaqTitle').innerText = 'ویرایش پرسش متداول';
    document.getElementById('faqAction').value = 'update';
    document.getElementById('faqId').value = faq.id;
    document.getElementById('formFaqSection').value = faq.section || 'home';
    document.getElementById('formFaqQuestion').value = faq.question || '';
    document.getElementById('formFaqAnswer').value = faq.answer || '';
    document.getElementById('formFaqSort').value = faq.sort_order || 0;
    document.getElementById('formFaqIsActive').checked = (parseInt(faq.is_active) === 1);
    document.getElementById('faqModal').style.display = 'flex';
}

function closeFaqModal() {
    document.getElementById('faqModal').style.display = 'none';
}
</script>
