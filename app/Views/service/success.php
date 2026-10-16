<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<section style="padding: 70px 0 90px 0; background: #f8fafc; min-height: 75vh; display: flex; align-items: center;">
    <div class="site-container" style="max-width: 680px; margin: 0 auto;">
        
        <div style="background: #fff; border-radius: 20px; padding: 40px 35px; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.05); text-align: center;">
            
            <div style="width: 80px; height: 80px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2.8rem; margin-bottom: 22px; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h1 style="font-size: 1.65rem; color: #011C39; font-weight: 800; margin-bottom: 10px;">
                سفارش شما با موفقیت در سامانه ثبت شد
            </h1>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin-bottom: 25px;">
                درخواست شما به نزدیک‌ترین تکنسین کشیک در منطقه ارجاع داده شد و به زودی جهت هماهنگی ساعت دقیق مراجعه با شما تماس گرفته خواهد شد.
            </p>

            <!-- Tracking Code Box -->
            <div style="background: #fff7ed; border: 2px dashed #fdba74; border-radius: 14px; padding: 20px; margin-bottom: 30px;">
                <span style="font-size: 0.82rem; color: #9a3412; font-weight: 700; display: block; margin-bottom: 6px;">شماره پیگیری یکتای شما:</span>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <span id="trackingCodeText" style="font-family: monospace; font-size: 1.6rem; font-weight: 900; color: #c2410c; letter-spacing: 2px;" dir="ltr"><?= e($trackingCode) ?></span>
                    <button type="button" onclick="copyTrackingCode()" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; border-radius: 6px; border-color: #fdba74; color: #c2410c;" title="کپی شماره پیگیری">
                        <i class="fa-solid fa-copy"></i>
                        <span id="copyBtnText">کپی</span>
                    </button>
                </div>
            </div>

            <?php if (!empty($request)): ?>
                <div style="background: #f8fafc; border-radius: 12px; padding: 18px 20px; text-align: right; margin-bottom: 30px; font-size: 0.88rem; border: 1px solid #f1f5f9;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 8px;">
                        <div><strong style="color: #64748b;">مشتری:</strong> <span style="color: #011C39;"><?= e($request['customer_name']) ?></span></div>
                        <div><strong style="color: #64748b;">تلفن هماهنگی:</strong> <span dir="ltr" style="font-family: monospace; color: #011C39;"><?= e($request['customer_phone']) ?></span></div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 8px;">
                        <div><strong style="color: #64748b;">نوع خدمت:</strong> <span style="color: #011C39;"><?= e($request['service_type']) ?></span></div>
                        <div><strong style="color: #64748b;">برند پکیج:</strong> <span style="color: #011C39;"><?= e($request['brand']) ?></span></div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div><strong style="color: #64748b;">منطقه و محدوده:</strong> <span style="color: #011C39;"><?= e($request['district']) ?></span></div>
                        <div><strong style="color: #64748b;">زمان انتخابی:</strong> <span style="color: #011C39;"><?= e($request['preferred_date']) ?> (<?= e($request['time_slot']) ?>)</span></div>
                    </div>
                </div>
            <?php endif; ?>

            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= url('service-order/tracking?code=' . urlencode($trackingCode)) ?>" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700; border-radius: 10px; font-size: 0.92rem; text-decoration: none;">
                    <i class="fa-solid fa-magnifying-glass" style="margin-left: 6px;"></i>
                    پیگیری آنلاین وضعیت سفارش
                </a>
                <a href="<?= url() ?>" class="btn btn-outline" style="padding: 12px 24px; font-weight: 700; border-radius: 10px; font-size: 0.92rem; text-decoration: none;">
                    بازگشت به صفحه اصلی
                </a>
            </div>

        </div>

    </div>
</section>

<script>
function copyTrackingCode() {
    var text = document.getElementById('trackingCodeText').innerText;
    navigator.clipboard.writeText(text).then(function() {
        var btn = document.getElementById('copyBtnText');
        btn.innerText = 'کپی شد!';
        setTimeout(function() { btn.innerText = 'کپی'; }, 2000);
    });
}
</script>
