<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Banner -->
<section class="page-banner services-banner" style="background: linear-gradient(135deg, #011C39 0%, #032d5a 100%); color: #fff; padding: 45px 0 40px 0;">
    <div class="site-container">
        <div class="page-banner-content" style="max-width: 750px;">
            <nav class="breadcrumbs" aria-label="مسیر راهنما" style="margin-bottom: 12px; font-size: 0.85rem; color: #94a3b8;">
                <a href="<?= url() ?>" style="color: #cbd5e1; text-decoration: none;">صفحه اصلی</a>
                <span class="sep" style="margin: 0 6px;">/</span>
                <span class="current" style="color: #EF5C16; font-weight: 700;">پیگیری وضعیت سفارش و اعزام تکنسین</span>
            </nav>
            <h1 class="page-title" style="font-size: 1.75rem; font-weight: 800; margin-bottom: 8px;">
                <i class="fa-solid fa-magnifying-glass-location text-orange" style="margin-left: 8px;"></i>
                سامانه هوشمند پیگیری سفارش خدمات
            </h1>
            <p class="page-subtitle" style="font-size: 0.92rem; color: #cbd5e1; margin: 0;">
                جهت اطلاع از آخرین وضعیت سفارش، تکنسین اختصاص‌یافته و زمان اعزام، کد پیگیری یا شماره همراه خود را وارد کنید
            </p>
        </div>
    </div>
</section>

<section style="padding: 50px 0 80px 0; background: #f8fafc; min-height: 65vh;">
    <div class="site-container" style="max-width: 800px; margin: 0 auto;">
        
        <!-- Search Card -->
        <div style="background: #fff; border-radius: 18px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 30px;">
            <form action="<?= url('service-order/tracking') ?>" method="GET">
                <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 14px; align-items: end;">
                    <div class="form-group" style="margin: 0;">
                        <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">کد پیگیری سفارش:</label>
                        <input type="text" name="code" value="<?= e($trackingCode) ?>" placeholder="مثال: TRM-1405-12345" class="form-control" dir="ltr" style="border-radius: 10px; height: 46px; font-family: monospace; font-size: 0.95rem;">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">یا شماره تلفن همراه:</label>
                        <input type="tel" name="phone" value="<?= e($phone) ?>" placeholder="۰۹۱۲۳۴۵۶۷۸۹" class="form-control" dir="ltr" style="border-radius: 10px; height: 46px; font-family: monospace; font-size: 0.95rem;">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" style="height: 46px; padding: 0 24px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>استعلام وضعیت</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($searched): ?>
            <?php if (!empty($request)): ?>
                <!-- Result Card -->
                <div style="background: #fff; border-radius: 18px; padding: 35px 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 25px; flex-wrap: wrap; gap: 14px;">
                        <div>
                            <span style="font-size: 0.8rem; color: #64748b;">کد پیگیری سفارش:</span>
                            <h2 style="margin: 4px 0 0 0; font-size: 1.45rem; font-family: monospace; color: #011C39; font-weight: 900;" dir="ltr"><?= e($request['tracking_code']) ?></h2>
                        </div>
                        <div>
                            <?php
                            $st = $request['status'] ?? 'pending';
                            $stBadges = [
                                'pending'     => ['title' => 'در انتظار بررسی اولیه', 'bg' => '#fef3c7', 'color' => '#b45309', 'icon' => 'clock'],
                                'assigned'    => ['title' => 'تکنسین اختصاص یافت', 'bg' => '#e0f2fe', 'color' => '#0369a1', 'icon' => 'user-check'],
                                'in_progress' => ['title' => 'تکنسین در حال اعزام / انجام کار', 'bg' => '#ffedd5', 'color' => '#c2410c', 'icon' => 'truck-fast'],
                                'completed'   => ['title' => 'انجام شده و تحویل فاکتور', 'bg' => '#ecfdf5', 'color' => '#047857', 'icon' => 'circle-check'],
                                'canceled'    => ['title' => 'لغو شده', 'bg' => '#fef2f2', 'color' => '#b91c1c', 'icon' => 'ban']
                            ];
                            $curr = $stBadges[$st] ?? $stBadges['pending'];
                            ?>
                            <span style="background: <?= $curr['bg'] ?>; color: <?= $curr['color'] ?>; padding: 8px 18px; border-radius: 8px; font-weight: 800; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-<?= $curr['icon'] ?>"></i>
                                <?= $curr['title'] ?>
                            </span>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 28px;">
                        <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #f1f5f9;">
                            <span style="font-size: 0.78rem; color: #64748b; display: block; margin-bottom: 4px;">نوع خدمت و برند:</span>
                            <strong style="color: #011C39; font-size: 0.95rem; display: block;"><?= e($request['service_type']) ?></strong>
                            <span style="font-size: 0.82rem; color: #ea580c; font-weight: 600;"><?= e($request['brand']) ?> <?= e($request['model'] ?? '') ?></span>
                        </div>

                        <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #f1f5f9;">
                            <span style="font-size: 0.78rem; color: #64748b; display: block; margin-bottom: 4px;">محدوده و منطقه:</span>
                            <strong style="color: #011C39; font-size: 0.95rem; display: block;"><?= e($request['district']) ?></strong>
                            <span style="font-size: 0.8rem; color: #475569; display: block;"><?= e($request['preferred_date']) ?> (<?= e($request['time_slot']) ?>)</span>
                        </div>

                        <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #f1f5f9;">
                            <span style="font-size: 0.78rem; color: #64748b; display: block; margin-bottom: 4px;">تکنسین اختصاص‌یافته:</span>
                            <?php if (!empty($request['assigned_tech_name'])): ?>
                                <strong style="color: #011C39; font-size: 0.95rem; display: block;">
                                    <i class="fa-solid fa-user-gear text-orange" style="margin-left: 4px;"></i>
                                    <?= e($request['assigned_tech_name']) ?>
                                </strong>
                                <?php if (!empty($request['assigned_tech_phone'])): ?>
                                    <span dir="ltr" style="font-family: monospace; font-size: 0.82rem; color: #0284c7;"><?= e($request['assigned_tech_phone']) ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #d97706; font-size: 0.85rem; font-weight: 600;">در حال تخصیص نزدیک‌ترین نصاب/تکنسین</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($request['problem_type']) || !empty($request['description'])): ?>
                        <div style="background: #fffaf5; border: 1px solid #fed7aa; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                            <h4 style="margin: 0 0 6px 0; font-size: 0.9rem; color: #9a3412; font-weight: 800;">شرح مشکل اعلامی شما:</h4>
                            <p style="margin: 0; font-size: 0.86rem; color: #334155; line-height: 1.6;">
                                <?= e($request['problem_type'] ?? '') ?>
                                <?php if (!empty($request['description'])): ?>
                                    - <?= e($request['description']) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 18px; flex-wrap: wrap; gap: 12px;">
                        <span style="font-size: 0.8rem; color: #94a3b8;">تاریخ ثبت سفارش: <?= to_shamsi($request['created_at']) ?></span>
                        <a href="tel:02177428596" class="btn btn-outline" style="font-size: 0.82rem; padding: 8px 16px; border-radius: 8px; text-decoration: none;">
                            <i class="fa-solid fa-phone" style="margin-left: 4px;"></i>
                            پیگیری تلفنی از پشتیبانی
                        </a>
                    </div>

                </div>
            <?php else: ?>
                <!-- Not Found Box -->
                <div style="background: #fff; border-radius: 18px; padding: 45px 30px; text-align: center; border: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-file-circle-xmark" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 14px; display: block;"></i>
                    <h3 style="margin: 0 0 8px 0; color: #011C39; font-size: 1.25rem; font-weight: 800;">سفارشی با این مشخصات یافت نشد</h3>
                    <p style="margin: 0 0 20px 0; color: #64748b; font-size: 0.9rem;">
                        لطفاً شماره پیگیری را با دقت بررسی نمایید یا با شماره همراهی که هنگام ثبت سفارش وارد کرده‌اید استعلام بگیرید.
                    </p>
                    <a href="<?= url('service-order') ?>" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; border-radius: 8px; text-decoration: none; font-size: 0.9rem;">
                        ثبت سفارش خدمات جدید
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</section>
