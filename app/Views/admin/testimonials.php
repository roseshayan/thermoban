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
            <i class="fa-solid fa-star-half-stroke" style="color: var(--color-primary); margin-left: 8px;"></i>
            مدیریت نظرات و رضایت مشتریان
        </h2>
        <p class="panel-subtitle">ثبت و مدیریت دیدگاه‌ها و بازخوردهای واقعی مشتریان خدمات پکیج و رادیاتور جهت جلب اعتماد بیشتر</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-primary" onclick="openNewTestimonialModal()">
            <i class="fa-solid fa-plus" style="margin-left: 4px;"></i>
            <span>ثبت نظر مشتری جدید</span>
        </button>
    </div>
</div>

<!-- Testimonials Table -->
<div class="dashboard-block" style="background: #fff; padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table" style="margin: 0; width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569; width: 50px;">ترتیب</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">مشتری و منطقه</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">نوع خدمت دریافتی</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 120px;">امتیاز رضایت</th>
                    <th style="padding: 14px 18px; text-align: right; font-size: 0.85rem; color: #475569;">متن دیدگاه مشتری</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 90px;">منتخب</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 90px;">وضعیت</th>
                    <th style="padding: 14px 18px; text-align: center; font-size: 0.85rem; color: #475569; width: 110px;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($testimonials)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                            هنوز هیچ نظری برای نمایش ثبت نشده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($testimonials as $item): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 18px; font-weight: 700; color: #64748b;"><?= $item['sort_order'] ?></td>
                            <td style="padding: 14px 18px;">
                                <strong style="color: #1e293b; font-size: 0.95rem; display: block;"><?= e($item['customer_name']) ?></strong>
                                <span style="font-size: 0.8rem; color: #64748b;"><?= e($item['customer_city'] ?? 'تهران') ?></span>
                            </td>
                            <td style="padding: 14px 18px; font-size: 0.88rem; color: #334155;">
                                <?= e($item['service_type'] ?? 'تعمیرات پکیج') ?>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <div style="color: #f59e0b; font-size: 0.85rem; display: inline-flex; gap: 2px;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= ($i <= (int)$item['rating']) ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td style="padding: 14px 18px; font-size: 0.86rem; color: #475569; line-height: 1.6;">
                                <?= e(mb_substr($item['content'], 0, 120)) ?><?= mb_strlen($item['content']) > 120 ? '...' : '' ?>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <?php if ((int)$item['is_featured'] === 1): ?>
                                    <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">★ منتخب</span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <form action="<?= url('admin/testimonials') ?>" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="testimonial_id" value="<?= $item['id'] ?>">
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <?php if ((int)$item['is_active'] === 1): ?>
                                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">منتشر شده</span>
                                        <?php else: ?>
                                            <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">پیش‌نویس</span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <button type="button" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.82rem;" onclick="openEditTestimonialModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="<?= url('admin/testimonials') ?>" method="POST" onsubmit="return confirm('آیا از حذف این نظر اطمینان دارید؟');" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="testimonial_id" value="<?= $item['id'] ?>">
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

<!-- Modal For Add / Edit Testimonial -->
<div id="testimonialModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 580px; border-radius: 14px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h3 id="modalTestimonialTitle" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b;">ثبت نظر مشتری جدید</h3>
            <button type="button" onclick="closeTestimonialModal()" style="border: none; background: transparent; font-size: 1.4rem; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>

        <form action="<?= url('admin/testimonials') ?>" method="POST">
            <input type="hidden" name="action" id="testimonialAction" value="create">
            <input type="hidden" name="testimonial_id" id="testimonialId" value="0">

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">نام و نام خانوادگی مشتری <span class="required">*</span></label>
                    <input type="text" name="customer_name" id="formTestName" class="form-control" placeholder="مثلاً: مهندس رضایی" required>
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">شهر و منطقه سکونت</label>
                    <input type="text" name="customer_city" id="formTestCity" class="form-control" placeholder="مثلاً: تهران - پاسداران">
                </div>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">نوع خدمت یا مدل پکیج</label>
                    <input type="text" name="service_type" id="formTestService" class="form-control" placeholder="مثلاً: سرویس پکیج بوتان پرلا پرو">
                </div>
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">امتیاز رضایت (۱ الی ۵ ستاره)</label>
                    <select name="rating" id="formTestRating" class="form-control">
                        <option value="5">★★★★★ (۵ ستاره - عالی)</option>
                        <option value="4">★★★★☆ (۴ ستاره - خیلی خوب)</option>
                        <option value="3">★★★☆☆ (۳ ستاره - متوسط)</option>
                        <option value="2">★★☆☆☆ (۲ ستاره)</option>
                        <option value="1">★☆☆☆☆ (۱ ستاره)</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 0.85rem; font-weight: 700;">متن تجربه و نظر مشتری <span class="required">*</span></label>
                <textarea name="content" id="formTestContent" rows="4" class="form-control" placeholder="توضیحات و تجربه مشتری از سرعت اعزام تکنسین، تخصص و قطعات اورجینال..." required></textarea>
            </div>

            <div class="form-row-2" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label style="font-size: 0.85rem; font-weight: 700;">ترتیب نمایش</label>
                    <input type="number" name="sort_order" id="formTestSort" class="form-control" value="0">
                </div>
                <div class="form-group" style="padding-top: 26px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_featured" id="formTestFeatured" value="1" style="width: 18px; height: 18px;">
                        <span style="font-weight: 700; font-size: 0.88rem; color: #b45309;">نشان داده شود در صفحه اصلی (ویژه)</span>
                    </label>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="formTestActive" value="1" checked style="width: 18px; height: 18px;">
                    <span style="font-weight: 700; font-size: 0.9rem;">نمایش فعال در سایت</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeTestimonialModal()" class="btn btn-outline">انصراف</button>
                <button type="submit" class="btn btn-primary">ذخیره نظر مشتری</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewTestimonialModal() {
    document.getElementById('modalTestimonialTitle').innerText = 'ثبت نظر مشتری جدید';
    document.getElementById('testimonialAction').value = 'create';
    document.getElementById('testimonialId').value = '0';
    document.getElementById('formTestName').value = '';
    document.getElementById('formTestCity').value = 'تهران';
    document.getElementById('formTestService').value = '';
    document.getElementById('formTestRating').value = '5';
    document.getElementById('formTestContent').value = '';
    document.getElementById('formTestSort').value = '0';
    document.getElementById('formTestFeatured').checked = false;
    document.getElementById('formTestActive').checked = true;
    document.getElementById('testimonialModal').style.display = 'flex';
}

function openEditTestimonialModal(item) {
    document.getElementById('modalTestimonialTitle').innerText = 'ویرایش نظر مشتری: ' + item.customer_name;
    document.getElementById('testimonialAction').value = 'update';
    document.getElementById('testimonialId').value = item.id;
    document.getElementById('formTestName').value = item.customer_name || '';
    document.getElementById('formTestCity').value = item.customer_city || '';
    document.getElementById('formTestService').value = item.service_type || '';
    document.getElementById('formTestRating').value = item.rating || 5;
    document.getElementById('formTestContent').value = item.content || '';
    document.getElementById('formTestSort').value = item.sort_order || 0;
    document.getElementById('formTestFeatured').checked = (parseInt(item.is_featured) === 1);
    document.getElementById('formTestActive').checked = (parseInt(item.is_active) === 1);
    document.getElementById('testimonialModal').style.display = 'flex';
}

function closeTestimonialModal() {
    document.getElementById('testimonialModal').style.display = 'none';
}
</script>
