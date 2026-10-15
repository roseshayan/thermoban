<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class HomeController extends Controller {
    public function index(): void {
        $shopEnabled = (\App\Models\Setting::get('module_shop', '0') === '1');
        $blogEnabled = (\App\Models\Setting::get('module_blog', '1') === '1');

        // Dynamic Brands
        $brandsList = [];
        try {
            $brandsList = \App\Models\Brand::getActive();
        } catch (\Throwable $e) {}
        if (empty($brandsList)) {
            $brandsList = [
                ['name' => 'ایران رادیاتور', 'logo' => 'brand-iranradiator-logo.png', 'badge' => 'کد مجاز نصب'],
                ['name' => 'بوتان', 'logo' => 'brand-butane.png', 'badge' => null],
                ['name' => 'لورچ', 'logo' => 'brand-lorch.png', 'badge' => null],
                ['name' => 'ایساتیس', 'logo' => 'brand-isatis.png', 'badge' => null],
                ['name' => 'بوش', 'logo' => 'brand-bosch.png', 'badge' => null],
                ['name' => 'آریستون', 'logo' => 'brand-ariston.png', 'badge' => null],
            ];
        }

        // Dynamic Products (If shop enabled)
        $productsList = [];
        if ($shopEnabled) {
            try {
                $rawProds = \App\Models\Product::where("status = 'active' OR status IS NULL", [], 'id DESC', 6);
                foreach ($rawProds as $p) {
                    $productsList[] = [
                        'id' => $p['id'],
                        'title' => $p['title'],
                        'model' => $p['model'] ?? '',
                        'price' => number_format((float)$p['price']),
                        'image' => $p['image'] ?? 'package-bita-24.png'
                    ];
                }
            } catch (\Throwable $e) {}
        }

        // Dynamic Articles (If blog enabled)
        $articlesList = [];
        if ($blogEnabled) {
            try {
                $rawArts = \App\Models\Article::where("is_published = 1", [], 'id DESC', 3);
                foreach ($rawArts as $a) {
                    $articlesList[] = [
                        'id' => $a['id'],
                        'title' => $a['title'],
                        'slug' => $a['slug'] ?? 'post-' . $a['id'],
                        'excerpt' => $a['excerpt'] ?? '',
                        'date' => !empty($a['created_at']) ? date('Y/m/d', strtotime($a['created_at'])) : '۱۴۰۵/۰۸/۰۶',
                        'image' => $a['image'] ?? 'technician-thumb.jpg'
                    ];
                }
            } catch (\Throwable $e) {}
        }

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
            'brands' => $brandsList,
            'products' => $productsList,
            'articles' => $articlesList,
            'shopEnabled' => $shopEnabled,
            'blogEnabled' => $blogEnabled,
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

        try {
            \App\Models\QuickOrder::create([
                'fullname'   => $fullname,
                'phone'      => $phone,
                'service'    => $service,
                'district'   => $district,
                'status'     => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            error_log("Failed to insert quick order: " . $e->getMessage());
        }

        // Return successful order response
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
