<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" type="image/png" href="<?= asset('images/logo-icon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        body {
            background-color: #f1f5f9;
            padding: 40px 20px;
            font-family: var(--font-family);
        }
        .invoice-paper {
            max-width: 900px;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .invoice-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #011C39;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .invoice-seller-info h2 {
            font-size: 1.4rem;
            color: #011C39;
            margin-bottom: 4px;
        }
        .invoice-seller-info p {
            font-size: 0.86rem;
            color: #64748b;
            margin: 0;
        }
        .invoice-logo-side {
            text-align: left;
        }
        .invoice-logo-side img {
            height: 48px;
            margin-bottom: 8px;
        }
        .invoice-meta-side {
            text-align: left;
            font-size: 0.88rem;
            color: #1e293b;
        }
        .invoice-parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        .party-col h4 {
            font-size: 0.95rem;
            color: #011C39;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .party-col p {
            font-size: 0.86rem;
            color: #334155;
            margin: 4px 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #cbd5e1;
            padding: 12px;
            font-size: 0.88rem;
            text-align: center;
        }
        .invoice-table th {
            background-color: #011C39;
            color: #FFFFFF;
        }
        .invoice-table td.text-right {
            text-align: right;
        }
        .invoice-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px solid #011C39;
            padding-top: 20px;
        }
        .invoice-stamp {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #059669;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .invoice-stamp img {
            width: 70px;
        }
        .invoice-totals {
            width: 320px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 0.9rem;
            color: #334155;
        }
        .totals-row.final {
            font-size: 1.15rem;
            font-weight: 800;
            color: #EF5C16;
            border-top: 1px solid #e2e8f0;
            margin-top: 6px;
            padding-top: 10px;
        }
        .invoice-actions {
            max-width: 900px;
            margin: 20px auto 0;
            display: flex;
            justify-content: space-between;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-actions { display: none !important; }
            .invoice-paper { box-shadow: none; border: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="invoice-paper">
        <!-- Invoice Header -->
        <div class="invoice-header-row">
            <div class="invoice-seller-info">
                <h2>صورتحساب رسمی فروش کالا و خدمات</h2>
                <p>مرکز تخصصی ترموبان - نمایندگی رسمی کد ۷۲۷۳ ایران رادیاتور و بوتان</p>
                <p>پروانه کسب معتبر اتحادیه تأسیسات مکانیکی استان تهران</p>
            </div>
            <div class="invoice-logo-side">
                <img src="<?= asset('images/logo-thermoban.png') ?>" alt="ترموبان">
                <div class="invoice-meta-side">
                    <div><strong>شماره فاکتور:</strong> <?= e($order['id']) ?></div>
                    <div><strong>تاریخ صدور:</strong> <?= e($order['date']) ?></div>
                    <div><strong>شناسه رهگیری:</strong> IR-<?= rand(100000, 999999) ?></div>
                </div>
            </div>
        </div>

        <!-- Buyer & Seller Details -->
        <div class="invoice-parties-grid">
            <div class="party-col">
                <h4>مشخصات فروشنده / ارائه‌دهنده خدمت</h4>
                <p><strong>نام شخص حقوقی:</strong> مرکز تأسیسات گرمایشی ترموبان</p>
                <p><strong>کد نمایندگی:</strong> ۷۲۷۳ رسمی ایران رادیاتور</p>
                <p><strong>تلفن مرکزی:</strong> ۰۲۱-۷۷۴۲۸۵۹۶</p>
                <p><strong>نشانی:</strong> تهران، خیابان دماوند، بعد از پل خاقانی، پلاک ۳۴</p>
            </div>
            <div class="party-col">
                <h4>مشخصات خریدار / دریافت‌کننده خدمت</h4>
                <p><strong>نام خریدار:</strong> <?= e($user['name']) ?></p>
                <p><strong>شماره تماس:</strong> <span dir="ltr"><?= e($user['phone']) ?></span></p>
                <p><strong>کد ملی:</strong> <?= e($user['national_id']) ?></p>
                <p><strong>محل نصب / تحویل:</strong> تهران، سعادت‌آباد، خیابان سرو غربی، پلاک ۱۸، واحد ۴</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 50px;">ردیف</th>
                    <th>شرح کالا یا خدمات</th>
                    <th>تعداد</th>
                    <th>واحد</th>
                    <th>مبلغ واحد (تومان)</th>
                    <th>تخفیف</th>
                    <th>مبلغ کل (تومان)</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td class="text-right">
                            <strong><?= e($item['title']) ?></strong>
                            <div style="font-size: 0.78rem; color: #059669;"><?= icon('shield', '', 13) ?> <?= e($order['guarantee']) ?></div>
                        </td>
                        <td><?= $item['quantity'] ?></td>
                        <td>دستگاه</td>
                        <td><?= e($item['price']) ?></td>
                        <td>رایگان</td>
                        <td><strong><?= e($item['price']) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><?= $i ?></td>
                    <td class="text-right">اجرت نصب استاندارد و فعال‌سازی رسمی ضمانت‌نامه ۲۴ ماهه</td>
                    <td>۱</td>
                    <td>مورد</td>
                    <td>رایگان (کد ۷۲۷۳)</td>
                    <td>۱۰۰٪</td>
                    <td><strong>۰ تومان</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Summary & Stamp -->
        <div class="invoice-summary-row">
            <div class="invoice-stamp">
                <img src="<?= asset('images/gold-shield-medal.png') ?>" alt="مهر تایید">
                <div>
                    <div>تایید و مهر رسمی نمایندگی کد ۷۲۷۳</div>
                    <div style="font-size: 0.78rem; color: #64748b;">تکنسین مسئول: <?= e($order['technician']) ?></div>
                </div>
            </div>

            <div class="invoice-totals">
                <div class="totals-row">
                    <span>جمع کل اقلام:</span>
                    <span><?= e($order['total_price']) ?> تومان</span>
                </div>
                <div class="totals-row">
                    <span>حمل و ارسال در تهران:</span>
                    <span style="color: #059669;">رایگان</span>
                </div>
                <div class="totals-row">
                    <span>اجرت نصب شرکتی:</span>
                    <span style="color: #059669;">رایگان</span>
                </div>
                <div class="totals-row final">
                    <span>مبلغ نهایی پرداختی:</span>
                    <span><?= e($order['total_price']) ?> تومان</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="invoice-actions">
        <a href="<?= url('profile') ?>" class="btn btn-outline">
            <?= icon('arrow-right', '', 14) ?> <span>بازگشت به حساب کاربری</span>
        </a>
        <button type="button" class="btn btn-primary" onclick="window.print();">
            <?= icon('print', '', 16) ?> <span>چاپ فاکتور رسمی</span>
        </button>
    </div>

</body>
</html>
