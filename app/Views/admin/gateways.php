<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-credit-card" style="color: #EF5C16; margin-left: 8px;"></i>
                پیکربندی درگاه‌های پرداخت آنلاین (زرین‌پال و زیبال)
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                تنظیمات مرچنت‌کدها، محیط سندباکس و آزمایشی شاپرک، پرداخت در محل (COD) و تعیین درگاه پیش‌فرض
            </p>
        </div>
        <div style="background: #e0f2fe; color: #0369a1; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; border: 1px solid #bae6fd;">
            <i class="fa-solid fa-shield-check" style="margin-left: 4px;"></i>
            پروتکل رسمی شاپرک REST v4 & v1
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 20px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-circle-check" style="margin-left: 8px;"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<form action="<?= url('admin/gateways') ?>" method="POST">
    <?= csrf_field() ?>

    <!-- Default Gateway Selector -->
    <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 24px;">
        <h3 style="font-size: 1.15rem; color: #011C39; font-weight: 800; margin: 0 0 16px 0;">
            درگاه فعال پیش‌فرض سامانه جهت پرداخت مشتریان
        </h3>
        <div style="display: flex; gap: 24px; align-items: center;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
                <input type="radio" name="gateway_default" value="zarinpal" <?= ($settings['gateway_default'] ?? 'zarinpal') === 'zarinpal' ? 'checked' : '' ?>>
                <span>زرین‌پال (ZarinPal PGv4)</span>
            </label>
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
                <input type="radio" name="gateway_default" value="zibal" <?= ($settings['gateway_default'] ?? '') === 'zibal' ? 'checked' : '' ?>>
                <span>زیبال (Zibal Payment v1)</span>
            </label>
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
                <input type="radio" name="gateway_default" value="cod" <?= ($settings['gateway_default'] ?? '') === 'cod' ? 'checked' : '' ?>>
                <span>پرداخت در محل (کارتخوان سیار نصاب)</span>
            </label>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        
        <!-- ZarinPal Gateway Box -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; background: #fef08a; color: #854d0e; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900;">
                        ZP
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.05rem; color: #011C39; font-weight: 800;">درگاه پرداخت زرین‌پال</h4>
                        <span style="font-size: 0.78rem; color: #64748b;">ZarinPal REST API PGv4</span>
                    </div>
                </div>
                <label style="display: flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 700; color: #475569;">
                    <input type="checkbox" name="gateway_zarinpal_sandbox" value="1" <?= ($settings['gateway_zarinpal_sandbox'] ?? '1') === '1' ? 'checked' : '' ?>>
                    حالت سندباکس (تست)
                </label>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">
                    مرچنت‌کد زرین‌پال (Merchant ID)
                </label>
                <input type="text" name="gateway_zarinpal_merchant" class="form-control" dir="ltr" value="<?= e($settings['gateway_zarinpal_merchant'] ?? '') ?>" placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX">
                <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 4px;">کد ۳۶ کاراکتری دریافت شده از پنل پذیرندگان زرین‌پال</small>
            </div>

            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 8px; font-size: 0.8rem; color: #475569; border: 1px solid #e2e8f0;">
                <i class="fa-solid fa-circle-info" style="color: #0284c7; margin-left: 6px;"></i>
                در حالت تست (Sandbox)، پرداخت‌ها بدون کسر وجه واقعی تأیید و شبیه‌سازی می‌شوند.
            </div>
        </div>

        <!-- Zibal Gateway Box -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; background: #bbf7d0; color: #166534; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900;">
                        ZB
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.05rem; color: #011C39; font-weight: 800;">درگاه پرداخت زیبال</h4>
                        <span style="font-size: 0.78rem; color: #64748b;">Zibal Payment Gateway v1</span>
                    </div>
                </div>
                <label style="display: flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 700; color: #475569;">
                    <input type="checkbox" name="gateway_zibal_sandbox" value="1" <?= ($settings['gateway_zibal_sandbox'] ?? '1') === '1' ? 'checked' : '' ?>>
                    حالت سندباکس (تست)
                </label>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="font-weight: 700; font-size: 0.84rem; color: #011C39; margin-bottom: 6px; display: block;">
                    کد مرچنت زیبال (Merchant)
                </label>
                <input type="text" name="gateway_zibal_merchant" class="form-control" dir="ltr" value="<?= e($settings['gateway_zibal_merchant'] ?? 'zibal') ?>" placeholder="zibal یا کد اختصاصی">
                <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 4px;">برای تست مقدار <code dir="ltr">zibal</code> کفایت می‌کند</small>
            </div>

            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 8px; font-size: 0.8rem; color: #475569; border: 1px solid #e2e8f0;">
                <i class="fa-solid fa-circle-info" style="color: #16a34a; margin-left: 6px;"></i>
                سازگار با انواع کارت‌های بانکی عضو شتاب و تسویه شاپرک.
            </div>
        </div>

    </div>

    <!-- COD Box -->
    <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 42px; height: 42px; background: #e0e7ff; color: #4338ca; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 1.05rem; color: #011C39; font-weight: 800;">پرداخت در محل پس از تحویل و نصب پکیج (کارتخوان سیار)</h4>
                    <span style="font-size: 0.78rem; color: #64748b;">تسویه نهایی پس از راه‌اندازی دستگاه توسط تکنسین مجاز کد ۷۲۷۳</span>
                </div>
            </div>
            <label style="display: flex; align-items: center; gap: 6px; font-size: 0.86rem; font-weight: 700; color: #011C39;">
                <input type="checkbox" name="gateway_cod_enabled" value="1" <?= ($settings['gateway_cod_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
                فعال بودن گزینه پرداخت در محل
            </label>
        </div>
    </div>

    <!-- Submit CTA -->
    <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1rem; font-weight: 800; border-radius: 8px;">
        <i class="fa-solid fa-floppy-disk" style="margin-left: 8px;"></i>
        ذخیره تنظیمات درگاه‌های بانکی
    </button>
</form>
