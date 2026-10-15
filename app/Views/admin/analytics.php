<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-chart-simple" style="color: #EF5C16; margin-left: 8px;"></i>
                آمار و تحلیل ترافیک بازدیدکنندگان سایت
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                ثبت و تحلیل هوشمند بازدیدهای صفحات، نرخ ورودی‌های روزانه و تفکیک دستگاه‌های کاربران بدون افت سرعت لود
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">وضعیت ماژول:</span>
            <span style="background: <?= $analyticsEnabled ? '#ecfdf5' : '#fef2f2' ?>; color: <?= $analyticsEnabled ? '#059669' : '#dc2626' ?>; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; border: 1px solid <?= $analyticsEnabled ? '#a7f3d0' : '#fecaca' ?>;">
                <i class="fa-solid <?= $analyticsEnabled ? 'fa-circle-check' : 'fa-circle-xmark' ?>" style="margin-left: 4px;"></i>
                <?= $analyticsEnabled ? 'فعال و در حال ثبت' : 'غیرفعال توسط سوپر ادمین' ?>
            </span>
        </div>
    </div>
</div>

<?php if (!$analyticsEnabled): ?>
    <div style="background: #fff8e1; border: 1px solid #ffe082; color: #b78103; padding: 16px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.3rem;"></i>
            <span>ماژول آمار بازدید در حال حاضر غیرفعال است و لاگ‌های جدید ثبت نمی‌گردد.</span>
        </div>
        <?php if (\App\Core\Auth::isSuperAdmin()): ?>
            <a href="<?= url('admin/modules') ?>" class="btn btn-outline" style="border: 1px solid #b78103; color: #b78103; padding: 6px 14px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; text-decoration: none;">
                فعال‌سازی در ماژول‌ها
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Stats Overview Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
    
    <div class="admin-card" style="background: #fff; padding: 22px; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div style="width: 54px; height: 54px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-eye"></i>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">کل بازدیدها (Hits)</span>
            <h3 style="margin: 4px 0 0 0; font-size: 1.4rem; color: #011C39; font-weight: 800;"><?= number_format($stats['total_visits']) ?></h3>
        </div>
    </div>

    <div class="admin-card" style="background: #fff; padding: 22px; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div style="width: 54px; height: 54px; border-radius: 14px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">بازدیدهای امروز</span>
            <h3 style="margin: 4px 0 0 0; font-size: 1.4rem; color: #011C39; font-weight: 800;"><?= number_format($stats['today_visits']) ?></h3>
        </div>
    </div>

    <div class="admin-card" style="background: #fff; padding: 22px; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div style="width: 54px; height: 54px; border-radius: 14px; background: #ffedd5; color: #ea580c; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">بازدیدکننده یکتا امروز (IP)</span>
            <h3 style="margin: 4px 0 0 0; font-size: 1.4rem; color: #011C39; font-weight: 800;"><?= number_format($stats['today_unique']) ?></h3>
        </div>
    </div>

    <div class="admin-card" style="background: #fff; padding: 22px; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div style="width: 54px; height: 54px; border-radius: 14px; background: #f3e8ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-calendar-week"></i>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">بازدیدهای ۷ روز اخیر</span>
            <h3 style="margin: 4px 0 0 0; font-size: 1.4rem; color: #011C39; font-weight: 800;"><?= number_format($stats['week_visits']) ?></h3>
        </div>
    </div>

</div>

<!-- Device Breakdown & Top Pages Grid -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
    
    <!-- Devices Breakdown -->
    <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <h3 style="margin: 0 0 20px 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            <i class="fa-solid fa-mobile-screen-button" style="color: #EF5C16; margin-left: 8px;"></i>
            تفکیک دستگاه‌های کاربران (Devices)
        </h3>
        
        <?php
        $totalDevs = array_sum(array_column($stats['devices'], 'cnt')) ?: 1;
        $deviceIcons = [
            'desktop' => 'fa-desktop',
            'mobile'  => 'fa-mobile-screen',
            'tablet'  => 'fa-tablet-screen-button'
        ];
        $deviceLabels = [
            'desktop' => 'رایانه و لپ‌تاپ (Desktop)',
            'mobile'  => 'موبایل و گوشی هوشمند (Mobile)',
            'tablet'  => 'تبلت (Tablet)'
        ];
        ?>

        <div style="display: flex; flex-direction: column; gap: 18px;">
            <?php foreach ($stats['devices'] as $d): ?>
                <?php 
                    $percent = round(($d['cnt'] / $totalDevs) * 100); 
                    $devType = $d['device_type'] ?? 'desktop';
                ?>
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.88rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                        <span><i class="fa-solid <?= $deviceIcons[$devType] ?? 'fa-laptop' ?>" style="margin-left: 6px; color: #64748b;"></i> <?= $deviceLabels[$devType] ?? $devType ?></span>
                        <span><?= $percent ?>% (<?= $d['cnt'] ?> بازدید)</span>
                    </div>
                    <div style="height: 10px; background: #f1f5f9; border-radius: 6px; overflow: hidden;">
                        <div style="width: <?= $percent ?>%; height: 100%; background: #EF5C16; border-radius: 6px;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($stats['devices'])): ?>
                <p style="color: #94a3b8; font-size: 0.88rem; text-align: center; margin: 20px 0;">اطلاعات دستگاه‌ها به مرور پس از ثبت بازدید نمایش می‌یابد.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top Pages -->
    <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <h3 style="margin: 0 0 20px 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            <i class="fa-solid fa-fire" style="color: #EF5C16; margin-left: 8px;"></i>
            محبوب‌ترین صفحات سایت (Top Pages)
        </h3>

        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9; text-align: right; font-size: 0.82rem; color: #64748b;">
                        <th style="padding-bottom: 10px;">آدرس صفحه</th>
                        <th style="padding-bottom: 10px; text-align: left;">تعداد بازدید</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['top_pages'] as $p): ?>
                        <tr style="border-bottom: 1px solid #f8fafc; font-size: 0.88rem;">
                            <td style="padding: 12px 0;">
                                <a href="<?= url(ltrim($p['page_url'], '/')) ?>" target="_blank" dir="ltr" style="color: #0284c7; font-weight: 600; text-decoration: none;">
                                    <?= e($p['page_url']) ?>
                                </a>
                            </td>
                            <td style="padding: 12px 0; text-align: left; font-weight: 700; color: #011C39;">
                                <?= number_format($p['hits']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($stats['top_pages'])): ?>
                        <tr><td colspan="2" style="text-align: center; padding: 20px; color: #94a3b8;">هنوز بازدیدی ثبت نشده است.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Recent Visitors Log Table -->
<div class="admin-card" style="background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0;">
        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">
            <i class="fa-solid fa-list-check" style="color: #EF5C16; margin-left: 8px;"></i>
            آخرین درخواست‌ها و بازدیدهای ثبت‌شده لحظه‌ای
        </h3>
    </div>

    <div class="table-responsive">
        <table class="profile-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: right;">
                    <th style="padding: 14px 24px; font-size: 0.85rem; color: #475569;">آدرس IP</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">صفحه مقصد</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">دستگاه</th>
                    <th style="padding: 14px 20px; font-size: 0.85rem; color: #475569;">ارجاع‌دهنده</th>
                    <th style="padding: 14px 24px; font-size: 0.85rem; color: #475569; text-align: left;">زمان ثبت</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentVisitors)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                            هیچ بازدیدی تا کنون ثبت نگردیده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentVisitors as $v): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.86rem;">
                            <td style="padding: 14px 24px;" dir="ltr">
                                <span style="font-family: monospace; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; color: #334155;">
                                    <?= e($v['ip_address']) ?>
                                </span>
                            </td>
                            <td style="padding: 14px 20px;" dir="ltr">
                                <span style="color: #0284c7; font-weight: 600;"><?= e($v['page_url']) ?></span>
                            </td>
                            <td style="padding: 14px 20px;">
                                <span style="background: #f8fafc; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; color: #475569;">
                                    <?= e($v['device_type']) ?>
                                </span>
                            </td>
                            <td style="padding: 14px 20px; color: #64748b;" dir="ltr">
                                <?= e($v['referer'] ?: 'Direct / مستقیم') ?>
                            </td>
                            <td style="padding: 14px 24px; text-align: left; color: #64748b;" dir="ltr">
                                <?= e($v['created_at']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
