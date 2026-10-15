<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner cart-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <a href="<?= url('shop') ?>">فروشگاه</a>
                <span class="sep">/</span>
                <span class="current">سبد خرید</span>
            </nav>
            <h1 class="page-title">سبد خرید شما</h1>
            <p class="page-subtitle">بررسی نهایی اقلام انتخابی با تضمین اصالت و گارانتی طلایی شرکت‌های ایران رادیاتور و بوتان</p>
        </div>
    </div>
</section>

<!-- Cart Section -->
<section class="cart-section">
    <div class="site-container">
        
        <?php if (empty($cartItems)): ?>
            <div class="no-results-box">
                <div class="no-results-icon">
                    <?= icon('cart', '', 48) ?>
                </div>
                <h3>سبد خرید شما در حال حاضر خالی است</h3>
                <p>می‌توانید مدل‌های متنوع پکیج دیواری را در فروشگاه مشاهده کرده و به سبد خرید اضافه نمایید.</p>
                <a href="<?= url('shop') ?>" class="btn btn-primary">مشاهده محصولات فروشگاه</a>
            </div>
        <?php else: ?>

            <div class="cart-layout-grid">
                
                <!-- Main Items Column -->
                <div class="cart-items-column">
                    <div class="cart-table-card">
                        <div class="cart-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <h3 class="cart-header-title" style="margin: 0;">اقلام سفارش (<?= count($cartItems) ?> کالا)</h3>
                                <span class="cart-header-badge">ارسال و نصب رایگان</span>
                            </div>
                            <button type="button" class="btn btn-outline" style="font-size: 0.8rem; padding: 6px 12px; color: #ef4444; border-color: #fca5a5; background: #fff;" onclick="clearEntireCart()">
                                <i class="fa-solid fa-trash" style="margin-left: 4px;"></i>
                                <span>خالی کردن سبد خرید</span>
                            </button>
                        </div>

                        <div class="cart-items-list">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="cart-item-row" data-id="<?= $item['id'] ?>" data-price="<?= $item['price_raw'] ?>">
                                    
                                    <div class="cart-item-thumb">
                                        <img src="<?= asset('images/' . $item['image']) ?>" alt="<?= e($item['title']) ?>">
                                    </div>

                                    <div class="cart-item-details">
                                        <h4 class="cart-item-title"><?= e($item['title']) ?></h4>
                                        <div class="cart-item-warranty">
                                            <?= icon('shield', '', 14) ?>
                                            <span><?= e($item['warranty']) ?></span>
                                        </div>
                                        <div class="cart-item-price-mobile">
                                            <span class="price-val"><?= e($item['price']) ?></span> تومان
                                        </div>
                                    </div>

                                    <!-- Quantity Controller -->
                                    <div class="cart-item-qty">
                                        <button type="button" class="qty-btn qty-plus" onclick="updateCartItemQty(this, 1)">
                                            <?= icon('plus', '', 14) ?>
                                        </button>
                                        <input type="text" class="qty-input" value="<?= $item['quantity'] ?>" readonly>
                                        <button type="button" class="qty-btn qty-minus" onclick="updateCartItemQty(this, -1)">
                                            <?= icon('minus', '', 14) ?>
                                        </button>
                                    </div>

                                    <!-- Price Desktop -->
                                    <div class="cart-item-price-desktop">
                                        <div class="price-current">
                                            <span class="price-amount"><?= e($item['price']) ?></span>
                                            <span class="price-curr">تومان</span>
                                        </div>
                                        <?php if (!empty($item['old_price'])): ?>
                                            <div class="price-old"><?= e($item['old_price']) ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Remove Action -->
                                    <button type="button" class="btn-remove-cart-item" title="حذف از سبد خرید" onclick="removeCartItemRow(this)">
                                        <?= icon('trash', '', 18) ?>
                                    </button>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Promo Code Box -->
                    <div class="cart-promo-box">
                        <div class="promo-header">
                            <?= icon('ticket', '', 20) ?>
                            <h4>کد تخفیف دارید؟</h4>
                        </div>
                        <form class="promo-form" id="promoCodeForm" onsubmit="applyPromoCode(event)">
                            <input type="text" id="promoInput" class="form-control promo-input" placeholder="کد تخفیف را وارد کنید (مثال: THERMO5)">
                            <button type="submit" class="btn btn-outline btn-apply-promo">اعمال تخفیف</button>
                        </form>
                    </div>

                </div>

                <!-- Summary Column -->
                <aside class="cart-summary-column">
                    <div class="cart-summary-card">
                        <h3 class="summary-title">خلاصه پیش‌فاکتور</h3>

                        <div class="summary-row">
                            <span class="sum-label">قیمت کالاها:</span>
                            <span class="sum-value" id="summarySubtotal"><?= $subtotal ?> تومان</span>
                        </div>

                        <div class="summary-row text-green">
                            <span class="sum-label">تخفیف کالاها:</span>
                            <span class="sum-value" id="summaryDiscount"><?= $discount ?> تومان</span>
                        </div>

                        <div class="summary-row">
                            <span class="sum-label">هزینه ارسال در تهران:</span>
                            <span class="sum-value text-green">رایگان</span>
                        </div>

                        <div class="summary-row">
                            <span class="sum-label">اجرت نصب رسمی (کد ۷۲۷۳):</span>
                            <span class="sum-value text-green">رایگان</span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row summary-total-row">
                            <span class="total-label">مبلغ قابل پرداخت:</span>
                            <div class="total-price-box">
                                <span class="total-amount" id="summaryTotal"><?= $total ?></span>
                                <span class="total-curr">تومان</span>
                            </div>
                        </div>

                        <a href="<?= url('checkout') ?>" class="btn btn-primary btn-checkout-action w-100">
                            <span>ادامه فرآیند تسویه حساب</span>
                            <?= icon('arrow-left', '', 18) ?>
                        </a>

                        <div class="summary-benefits">
                            <div class="ben-item">
                                <?= icon('shield', '', 16) ?>
                                <span>۲۴ ماه ضمانت طلایی شرکتی</span>
                            </div>
                            <div class="ben-item">
                                <?= icon('truck', '', 16) ?>
                                <span>تحویل اکسپرس ۲ ساعته در تهران</span>
                            </div>
                            <div class="ben-item">
                                <?= icon('credit-card', '', 16) ?>
                                <span>امکان پرداخت در محل پس از تست</span>
                            </div>
                        </div>

                    </div>
                </aside>

            </div>

        <?php endif; ?>

    </div>
</section>
