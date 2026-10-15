<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<section class="payment-result-section">
    <div class="site-container">
        <div class="payment-result-card payment-success-card">
            
            <div class="result-icon-circle success-circle">
                <?= icon('check', 'result-svg', 48) ?>
            </div>

            <h1 class="result-title">سفارش شما با موفقیت ثبت و تایید گردید!</h1>
            <p class="result-subtitle">پرداخت شما با موفقیت به پایان رسید. هماهنگی‌های لازم جهت ارسال و اعزام نصاب مجاز انجام شد.</p>

            <!-- Transaction Details Table -->
            <div class="payment-details-box">
                <div class="detail-item">
                    <span class="detail-label">شماره پیگیری سفارش:</span>
                    <strong class="detail-value text-orange"><?= e($orderId) ?></strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">شماره مرجع تراکنش بانکی (RefID):</span>
                    <strong class="detail-value"><?= e($refId) ?></strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">درگاه پرداخت:</span>
                    <span class="detail-value"><?= e($gateway) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاریخ و زمان پرداخت:</span>
                    <span class="detail-value"><?= e($date) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">مبلغ پرداختی:</span>
                    <strong class="detail-value text-green font-bold"><?= e($amount) ?> تومان</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">زمان تحویل و حضور نصاب:</span>
                    <strong class="detail-value"><?= e($delivery_time) ?></strong>
                </div>
            </div>

            <div class="result-notice-box">
                <?= icon('shield', '', 20) ?>
                <p>تکنسین رسمی ترموبان با کد ۷۲۷۳ قبل از حضور با شما هماهنگ خواهد کرد. فاکتور رسمی چاپی و دفترچه گارانتی ۲۴ ماهه ممهور به شما تحویل داده می‌شود.</p>
            </div>

            <div class="result-actions">
                <a href="<?= url('profile?tab=orders') ?>" class="btn btn-primary">
                    <?= icon('invoice', '', 18) ?>
                    <span>مشاهده در پنل کاربری</span>
                </a>
                <a href="<?= url('invoice/' . $orderId) ?>" target="_blank" class="btn btn-outline">
                    <?= icon('print', '', 18) ?>
                    <span>چاپ فاکتور سفارش</span>
                </a>
                <a href="<?= url() ?>" class="btn btn-outline">
                    <span>بازگشت به صفحه اصلی</span>
                </a>
            </div>

        </div>
    </div>
</section>
