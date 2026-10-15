<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class HomeController extends Controller {
    public function index(): void {
        $data = [
            'pageTitle' => 'ترموبان | مرجع تخصصی نصب، تعمیر و فروش پکیج',
            'csrfToken' => Security::generateCsrf(),
            'heroStats' => [
                [
                    'icon' => 'icon-hero-support.png',
                    'title' => 'پشتیبانی ۲۴ ساعته',
                    'subtitle' => 'در ۲۲ نقطه تهران'
                ],
                [
                    'icon' => 'icon-hero-guarantee.png',
                    'title' => 'گارانتی خدمات',
                    'subtitle' => 'و قطعات'
                ],
                [
                    'icon' => 'icon-hero-license.png',
                    'title' => 'کد مجاز نصب',
                    'subtitle' => 'ایران رادیاتور'
                ]
            ],
            'services' => [
                [
                    'id' => 'setup',
                    'icon' => 'icon-service-setup.png',
                    'iconWhite' => 'icon-service-setup-white.png',
                    'title' => 'راه‌اندازی و تنظیم پکیج',
                    'desc' => 'تنظیم و راه‌اندازی اصولی پکیج برای عملکرد بهتر و مصرف بهینه'
                ],
                [
                    'id' => 'sale',
                    'icon' => 'icon-service-sale.png',
                    'iconWhite' => 'icon-service-sale-white.png',
                    'title' => 'فروش پکیج',
                    'desc' => 'فروش انواع پکیج های ایرانی و خارجی با ضمانت اصالت کالا'
                ],
                [
                    'id' => 'maintenance',
                    'icon' => 'icon-service-maintenance.png',
                    'iconWhite' => 'icon-service-maintenance-white.png',
                    'title' => 'سرویس دوره ای پکیج',
                    'desc' => 'افزایش رادمان و کاهش مصرف با سرویس های دوره‌ای استاندارد'
                ],
                [
                    'id' => 'repair',
                    'icon' => 'icon-service-repair.png',
                    'iconWhite' => 'icon-service-repair-white.png',
                    'title' => 'تعمیر پکیج',
                    'desc' => 'عیب یابی و تعمیر تخصصی انواع پکیج ایرانی و خارجی'
                ],
                [
                    'id' => 'install',
                    'icon' => 'icon-service-install.png',
                    'iconWhite' => 'icon-service-install-white.png',
                    'title' => 'نصب پکیج',
                    'desc' => 'نصب انواع پکیج دیواری با کد مجاز نصب'
                ],
            ],
            'stats' => [
                ['value' => 'کد مجاز', 'label' => 'ایران رادیاتور'],
                ['value' => '۲۲', 'label' => 'منطقه تحت پوشش'],
                ['value' => '۱,۰۰۰', 'label' => 'مشتری راضی'],
                ['value' => '۱۰+', 'label' => 'سال تجربه']
            ],
            'brands' => [
                [
                    'name' => 'ایران رادیاتور',
                    'logo' => 'brand-iranradiator-logo.png',
                    'badge' => 'کد مجاز نصب'
                ],
                [
                    'name' => 'بوتان',
                    'logo' => 'brand-butane.png',
                    'badge' => null
                ],
                [
                    'name' => 'لورچ',
                    'logo' => 'brand-lorch.png',
                    'badge' => null
                ],
                [
                    'name' => 'ایساتیس',
                    'logo' => 'brand-isatis.png',
                    'badge' => null
                ],
                [
                    'name' => 'بوش',
                    'logo' => 'brand-bosch.png',
                    'badge' => null
                ],
                [
                    'name' => 'ال جی',
                    'logo' => 'brand-lg.png',
                    'badge' => null
                ],
                [
                    'name' => 'آریستون',
                    'logo' => 'brand-ariston.png',
                    'badge' => null
                ]
            ],
            'products' => [
                [
                    'id' => 1,
                    'title' => 'پکیج شوفاژ دیواری بوتان',
                    'model' => 'مدل Bita 24 sealed',
                    'price' => '۷۰,۳۷۵,۰۰۰',
                    'image' => 'package-bita-24.png'
                ],
                [
                    'id' => 2,
                    'title' => 'پکیج شوفاژ دیواری بوتان',
                    'model' => 'مدل Bita 24 sealed',
                    'price' => '۷۰,۳۷۵,۰۰۰',
                    'image' => 'package-bita-24.png'
                ],
                [
                    'id' => 3,
                    'title' => 'پکیج شوفاژ دیواری بوتان',
                    'model' => 'مدل Bita 24 sealed',
                    'price' => '۷۰,۳۷۵,۰۰۰',
                    'image' => 'package-bita-24.png'
                ]
            ],
            'articles' => [
                [
                    'id' => 1,
                    'title' => 'علت روشن نشدن پکیج چیست؟',
                    'excerpt' => 'بررسی رایج ترین دلایل و راه حل های روشن نشدن پکیج',
                    'date' => '۱۴۰۵/۰۸/۰۶',
                    'image' => 'technician-thumb.jpg'
                ],
                [
                    'id' => 2,
                    'title' => 'زمان مناسب سرویس پکیج',
                    'excerpt' => 'هر چند وقت یک بار باید پکیج را سرویس کنیم؟',
                    'date' => '۱۴۰۵/۰۸/۰۶',
                    'image' => 'technician-thumb.jpg'
                ],
                [
                    'id' => 3,
                    'title' => 'راهنمای تنظیم دمای پکیج',
                    'excerpt' => 'دمای مناسب برای فصل های مختلف و صرفه جویی در مصرف گاز',
                    'date' => '۱۴۰۵/۰۸/۰۶',
                    'image' => 'technician-thumb.jpg'
                ]
            ]
        ];

        $this->view('home/index', $data);
    }

    public function submitQuickOrder(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $this->json(['success' => false, 'message' => 'توکن امنیتی نامعتبر است.'], 403);
            return;
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $service = trim($_POST['service'] ?? '');
        $district = trim($_POST['district'] ?? '');

        if (empty($fullname) || empty($phone)) {
            $this->json(['success' => false, 'message' => 'لطفاً نام و شماره تماس را وارد فرمایید.'], 422);
            return;
        }

        // Return successful simulated order response for UI
        $this->json([
            'success' => true,
            'message' => 'درخواست شما با موفقیت ثبت شد! کارشناسان ترموبان به زودی با شما تماس خواهند گرفت.',
            'data' => [
                'fullname' => e($fullname),
                'phone' => e($phone),
                'service' => e($service),
                'district' => e($district)
            ]
        ]);
    }
}
