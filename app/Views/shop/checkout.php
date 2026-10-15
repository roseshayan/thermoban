<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner checkout-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <a href="<?= url('cart') ?>">سبد خرید</a>
                <span class="sep">/</span>
                <span class="current">تسویه حساب و ارسال</span>
            </nav>
            <h1 class="page-title">نهایی‌سازی سفارش و اطلاعات تحویل</h1>
            <p class="page-subtitle">آدرس محل نصب، زمان اعزام تکنسین مجاز و روش پرداخت را تعیین فرمایید</p>
        </div>
    </div>
</section>

<!-- Checkout Section -->
<section class="checkout-section">
    <div class="site-container">
        
        <!-- Steps Indicator -->
        <div class="checkout-steps-bar">
            <div class="checkout-step-item completed">
                <span class="step-circle"><?= icon('check', '', 14) ?></span>
                <span class="step-name">۱. سبد خرید</span>
            </div>
            <div class="step-line active"></div>
            <div class="checkout-step-item active">
                <span class="step-circle">۲</span>
                <span class="step-name">۲. آدرس و زمان نصب</span>
            </div>
            <div class="step-line"></div>
            <div class="checkout-step-item">
                <span class="step-circle">۳</span>
                <span class="step-name">۳. پرداخت و تایید</span>
            </div>
        </div>

        <form action="<?= url('checkout') ?>" method="POST" class="checkout-form js-validate-form" id="checkoutMainForm">
            <?= csrf_field() ?>

            <div class="checkout-layout-grid">
                
                <!-- Main Form Column -->
                <div class="checkout-form-column">
                    
                    <!-- 1. Address Selection -->
                    <div class="checkout-card">
                        <div class="checkout-card-head">
                            <span class="step-badge">۱</span>
                            <h3 class="card-head-title">انتخاب آدرس محل نصب و تحویل</h3>
                        </div>
                        <div class="checkout-card-body">
                            <div class="checkout-addresses-list">
                                <?php foreach ($addresses as $i => $addr): ?>
                                    <label class="checkout-address-option">
                                        <input type="radio" name="selected_address" value="<?= $addr['id'] ?>" <?= ($i === 0) ? 'checked' : '' ?>>
                                        <div class="option-content">
                                            <div class="option-title-row">
                                                <strong><?= e($addr['title']) ?> (<?= e($addr['district']) ?>)</strong>
                                                <span class="option-phone"><?= icon('phone', '', 14) ?> <?= e($addr['phone']) ?></span>
                                            </div>
                                            <p class="option-address-text"><?= e($addr['address']) ?></p>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="add-new-address-link">
                                <a href="<?= url('profile?tab=addresses') ?>" target="_blank" class="text-orange font-bold">
                                    + افزودن یا تغییر آدرس روی نقشه
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Delivery & Technician Schedule -->
                    <div class="checkout-card">
                        <div class="checkout-card-head">
                            <span class="step-badge">۲</span>
                            <h3 class="card-head-title">زمان تحویل و حضور نصاب مجاز در محل</h3>
                        </div>
                        <div class="checkout-card-body">
                            <div class="schedule-slots-grid">
                                <label class="schedule-slot-option">
                                    <input type="radio" name="delivery_slot" value="امروز عصر" checked>
                                    <div class="slot-box">
                                        <span class="slot-day">امروز</span>
                                        <span class="slot-time">۱۶:۰۰ الی ۲۰:۰۰</span>
                                        <span class="slot-badge">تحویل اکسپرس</span>
                                    </div>
                                </label>
                                <label class="schedule-slot-option">
                                    <input type="radio" name="delivery_slot" value="فردا صبح">
                                    <div class="slot-box">
                                        <span class="slot-day">فردا</span>
                                        <span class="slot-time">۱۰:۰۰ الی ۱۳:۰۰</span>
                                        <span class="slot-badge">شیفت صبح</span>
                                    </div>
                                </label>
                                <label class="schedule-slot-option">
                                    <input type="radio" name="delivery_slot" value="فردا عصر">
                                    <div class="slot-box">
                                        <span class="slot-day">فردا</span>
                                        <span class="slot-time">۱۵:۰۰ الی ۱۹:۰۰</span>
                                        <span class="slot-badge">شیفت عصر</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Method -->
                    <div class="checkout-card">
                        <div class="checkout-card-head">
                            <span class="step-badge">۳</span>
                            <h3 class="card-head-title">شیوه پرداخت</h3>
                        </div>
                        <div class="checkout-card-body">
                            <div class="payment-methods-list">
                                <label class="payment-method-option">
                                    <input type="radio" name="payment_method" value="online" checked>
                                    <div class="method-box">
                                        <div class="method-info">
                                            <strong>پرداخت اینترنتی (کلیه کارت‌های عضو شتاب)</strong>
                                            <p>پرداخت امن از طریق درگاه مستقیم بانکی با تضمین بازگشت وجه</p>
                                        </div>
                                        <div class="method-gateways">
                                            <span class="gateway-badge">بانک سامان</span>
                                            <span class="gateway-badge">بانک ملت</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-method-option">
                                    <input type="radio" name="payment_method" value="cod">
                                    <div class="method-box">
                                        <div class="method-info">
                                            <strong>پرداخت در محل (کارتخوان سیار تکنسین)</strong>
                                            <p>پرداخت پس از تحویل دستگاه، رویت سلامت فیزیکی و تایید پلمپ کارخانه</p>
                                        </div>
                                        <span class="pos-badge">دستگاه POS سیار</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="checkout-card">
                        <div class="checkout-card-body">
                            <div class="form-group mb-0">
                                <label for="order_notes">یادداشت و توضیحات تکمیلی (اختیاری)</label>
                                <textarea id="order_notes" name="notes" rows="2" class="form-control" placeholder="شماره طبقه، وضعیت لوله‌کشی قبلی یا هر نکته ضروری برای نصاب..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Invoice Summary Column -->
                <aside class="checkout-summary-column">
                    <div class="cart-summary-card checkout-summary-card">
                        <h3 class="summary-title">خلاصه سفارش</h3>

                        <div class="summary-row">
                            <span class="sum-label">تعداد اقلام:</span>
                            <span class="sum-value"><?= $itemsCount ?> کالا</span>
                        </div>

                        <div class="summary-row">
                            <span class="sum-label">هزینه ارسال تهران:</span>
                            <span class="sum-value text-green">رایگان</span>
                        </div>

                        <div class="summary-row">
                            <span class="sum-label">اجرت نصب رسمی (کد ۷۲۷۳):</span>
                            <span class="sum-value text-green">رایگان</span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row summary-total-row">
                            <span class="total-label">مبلغ نهایی قابل پرداخت:</span>
                            <div class="total-price-box">
                                <span class="total-amount"><?= $totalAmount ?></span>
                                <span class="total-curr">تومان</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-checkout-action w-100">
                            <span>ثبت نهایی و انتقال به پرداخت</span>
                            <?= icon('arrow-left', '', 18) ?>
                        </button>

                        <div class="checkout-guarantee-note">
                            <?= icon('shield', '', 18) ?>
                            <span>سفارش شما شامل ۲۴ ماه گارانتی طلایی و فاکتور رسمی شرکتی است.</span>
                        </div>

                    </div>
                </aside>

            </div>
        </form>

    </div>
</section>
