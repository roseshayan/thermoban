<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<section class="payment-result-section">
    <div class="site-container">
        <div class="payment-result-card payment-failed-card">
            
            <div class="result-icon-circle failed-circle">
                <?= icon('alert', 'result-svg', 48) ?>
            </div>

            <h1 class="result-title text-red">پرداخت ناموفق بود!</h1>
            <p class="result-subtitle">تراکنش بانکی به دلیل انصراف یا خطای اتصال به درگاه تایید نشد و سفارش هنوز ثبت نهایی نگردیده است.</p>

            <!-- Error Details Table -->
            <div class="payment-details-box error-details-box">
                <div class="detail-item">
                    <span class="detail-label">کد خطای گزارش‌شده:</span>
                    <strong class="detail-value text-red"><?= e($errorCode) ?></strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">علت خطا:</span>
                    <span class="detail-value"><?= e($reason) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">زمان تراکنش:</span>
                    <span class="detail-value"><?= e($date) ?></span>
                </div>
            </div>

            <div class="result-notice-box alert-notice">
                <?= icon('info', '', 20) ?>
                <p>در صورتی که مبلغی از حساب شما کسر شده است، طبق قوانین شبکه شاپرک ظرف حداکثر ۷۲ ساعت به صورت خودکار به حساب بانکی شما بازگردانده خواهد شد.</p>
            </div>

            <div class="result-actions">
                <a href="<?= url('checkout') ?>" class="btn btn-primary">
                    <?= icon('credit-card', '', 18) ?>
                    <span>تلاش مجدد برای پرداخت</span>
                </a>
                <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener" class="btn btn-bale-inline">
                    <span>ارتباط با پشتیبانی در بله</span>
                </a>
                <a href="<?= url('cart') ?>" class="btn btn-outline">
                    <span>بازگشت به سبد خرید</span>
                </a>
            </div>

        </div>
    </div>
</section>
