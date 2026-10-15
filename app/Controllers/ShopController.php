<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class ShopController extends Controller {
    protected array $products = [
        [
            'id' => 1,
            'slug' => 'butane-bita-24-sealed',
            'title' => 'پکیج شوفاژ دیواری بوتان ۲۴۰۰۰',
            'model' => 'مدل Bita 24 Sealed فن دار',
            'brand' => 'بوتان',
            'brand_slug' => 'butane',
            'brand_logo' => 'brand-butane.png',
            'category' => 'پکیج دیواری',
            'price_raw' => 28500000,
            'price' => '۲۸,۵۰۰,۰۰۰',
            'old_price' => '۳۱,۲۰۰,۰۰۰',
            'discount' => '۹٪',
            'image' => 'package-bita-24.png',
            'capacity' => 24000,
            'area' => 'تا ۱۴۰ متر مربع',
            'heat_exchanger' => 'دو مبدل حرارتی',
            'flue_type' => 'فن‌دار (محفظه احتراق بسته)',
            'efficiency' => '۹۰٪ راندمان واقعی',
            'warranty' => '۲۴ ماه گارانتی طلایی شرکت بوتان + نصب رایگان',
            'in_stock' => true,
            'rating' => 4.8,
            'reviews_count' => 38,
            'description' => 'پکیج دیواری بوتان مدل Bita 24 Sealed یکی از پرفروش‌ترین و اقتصادی‌ترین پکیج‌های بازار ایران است. این پکیج با دارا بودن محفظه احتراق بسته و فن قدرتمند دودکش، قابلیت نصب ایمن در تراس یا فضای داخلی آشپزخانه را داراست. با راندمان بالا و قطعات استاندارد، آب گرم بهداشتی فوری و مداوم و گرمایش مطبوع شوفاژ را تضمین می‌کند.',
            'specs' => [
                'ظرفیت حرارتی خروجی' => '۲۴ کیلووات (۲۴,۰۰۰ کیلوکالری)',
                'نوع مبدل حرارتی' => 'دو مبدل مجزا (مبدل اصلی مسی + مبدل ثانویه صفحه‌ای استیل)',
                'نوع محفظه احتراق' => 'بسته (فن دار)',
                'حداکثر متراژ تحت پوشش' => '۱۴۰ متر مربع در شرایط آب و هوایی تهران',
                'دبی آب گرم مصرفی' => '۱۰.۸ لیتر در دقیقه (با اختلاف دمای ۳۰ درجه)',
                'حداکثر دمای مدار گرمایش' => '۸۵ درجه سانتی‌گراد',
                'حداکثر دمای مدار آب گرم مصرفی' => '۶۰ درجه سانتی‌گراد',
                'فشار کاری مجاز' => '۰.۳ الی ۳ بار',
                'قطر لوله دودکش' => '۶ سانتی‌متر لوله تخلیه دود / ۱۰ سانتی‌متر مکش هوا',
                'ابعاد (ارتفاع × عرض × عمق)' => '۷۴۰ × ۴۰۰ × ۳۳۰ میلی‌متر',
                'وزن خالص' => '۲۹.۵ کیلوگرم',
                'کشور سازنده' => 'ایران (شرکت صنعتی بوتان)'
            ],
            'features' => [
                'مجهز به سیستم ضد یخ‌زدگی (Anti-Freeze)',
                'سیستم ضد گریپاژ پمپ و شیر سه طرفه',
                'سیستم کنترل هوشمند میکروپروسسوری',
                'قابلیت اتصال به ترموستات اتاقی و حسگر دمای محیط بیرونی',
                'نمایشگر عیب‌یاب دیجیتال با کدهای خطای دقیق'
            ]
        ],
        [
            'id' => 2,
            'slug' => 'iran-radiator-l24ff',
            'title' => 'پکیج شوفاژ دیواری ایران رادیاتور ۲۴۰۰۰',
            'model' => 'مدل L24FF دو مبدل دیجیتال فن دار',
            'brand' => 'ایران رادیاتور',
            'brand_slug' => 'iran-radiator',
            'brand_logo' => 'brand-iranradiator-logo.png',
            'category' => 'پکیج دیواری',
            'price_raw' => 32400000,
            'price' => '۳۲,۴۰۰,۰۰۰',
            'old_price' => '۳۴,۵۰۰,۰۰۰',
            'discount' => '۶٪',
            'image' => 'package-thumb.png',
            'capacity' => 24000,
            'area' => 'تا ۱۴۰ متر مربع',
            'heat_exchanger' => 'دو مبدل حرارتی منوژنیک',
            'flue_type' => 'فن‌دار (FF)',
            'efficiency' => '۹۳٪ راندمان حرارتی',
            'warranty' => '۲۴ ماه گارانتی رسمی شرکت ایران رادیاتور (کد مجاز ۷۲۷۳) + نصب رایگان',
            'in_stock' => true,
            'rating' => 4.9,
            'reviews_count' => 64,
            'description' => 'پکیج شوفاژ دیواری ایران رادیاتور سری L مدل L24FF برترین پرچمدار دیجیتال ایران رادیاتور است. این مدل با صفحه نمایشگر بزرگ دیجیتال، منبع انبساط بزرگتر، هیدرولیک برنجی مقاوم و کم‌صداترین پمپ ویلو، انتخابی بی‌نظیر برای سال‌ها آرامش و آسایش گرمایشی خانه شماست.',
            'specs' => [
                'ظرفیت حرارتی خروجی' => '۲۴ کیلووات (۲۴,۰۰۰ کیلوکالری)',
                'نوع مبدل حرارتی' => 'دو مبدل مجزا با آلیاژ ضد رسوب',
                'نوع محفظه احتراق' => 'بسته مجهز به فن دودکش FF',
                'صفحه نمایشگر' => 'LCD دیجیتال با قابلیت نمایش دقیق دما و عیب‌یابی هوشمند',
                'حداکثر متراژ تحت پوشش' => '۱۴۰ متر مربع',
                'دبی آب گرم مصرفی' => '۱۱.۴ لیتر بر دقیقه',
                'پمپ سیرکولاتور' => 'سه سرعته پرقدرت Wilo آلمان',
                'ابعاد (ارتفاع × عرض × عمق)' => '۷۶۳ × ۴۵۰ × ۳۵۰ میلی‌متر',
                'وزن خالص' => '۳۷ کیلوگرم',
                'گارانتی و خدمات' => '۲۴ ماه گارانتی تعویض قطعات شرکتی ایران رادیاتور'
            ],
            'features' => [
                'هیدرولیک تمام برنجی با طول عمر فوق‌العاده بالا',
                'صفحه نمایشگر دیجیتال با وضوح بالا',
                'سیستم هواگیری خودکار پمپ ایرونت (Air Vent)',
                'سیستم ایمنی تخلیه گازهای احتراق و پرشر سوییچ دود',
                'گارانتی رسمی با نصب رایگان توسط نصاب مجاز کد ۷۲۷۳'
            ]
        ],
        [
            'id' => 3,
            'slug' => 'iran-radiator-l28ff',
            'title' => 'پکیج دیواری ایران رادیاتور ۲۸۰۰۰ دیجیتال',
            'model' => 'مدل L28FF دو مبدل فن دار',
            'brand' => 'ایران رادیاتور',
            'brand_slug' => 'iran-radiator',
            'brand_logo' => 'brand-iranradiator-logo.png',
            'category' => 'پکیج دیواری',
            'price_raw' => 38900000,
            'price' => '۳۸,۹۰۰,۰۰۰',
            'old_price' => '۴۱,۰۰۰,۰۰۰',
            'discount' => '۵٪',
            'image' => 'package-thumb.png',
            'capacity' => 28000,
            'area' => 'تا ۲۱۰ متر مربع',
            'heat_exchanger' => 'دو مبدل حرارتی',
            'flue_type' => 'فن‌دار (FF)',
            'efficiency' => '۹۳٪ راندمان حرارتی',
            'warranty' => '۲۴ ماه گارانتی طلایی ایران رادیاتور + نصب رایگان',
            'in_stock' => true,
            'rating' => 4.9,
            'reviews_count' => 42,
            'description' => 'پکیج ۲۸۰۰۰ ایران رادیاتور مدل L28FF برای واحدهای مسکونی تا متراژ ۲۱۰ متر مربع و همچنین واحدهایی که دارای دو حمام هستند ایده‌آل است. ظرفیت آب گرم دهی عالی و توان حرارتی بالا پاسخگوی سخت‌ترین سرمای زمستان است.',
            'specs' => [
                'ظرفیت حرارتی' => '۲۸,۰۰۰ کیلوکالری',
                'نوع مبدل' => 'دو مبدل حرارتی',
                'نوع احتراق' => 'فن دار',
                'متراژ مناسب' => '۱۴۰ الی ۲۱۰ متر مربع',
                'ابعاد' => '۷۶۳ × ۴۵۰ × ۳۵۰ میلی‌متر',
                'وزن' => '۳۸.۵ کیلوگرم'
            ],
            'features' => [
                'مناسب برای منازل دارای ۲ سرویس بهداشتی و حمام',
                'نمایشگر هوشمند دیجیتال',
                'حداکثر راندمان با کمترین میزان مصرف گاز'
            ]
        ],
        [
            'id' => 4,
            'slug' => 'lorch-hero-24',
            'title' => 'پکیج شوفاژ دیواری لورچ ۲۴۰۰۰',
            'model' => 'مدل هیرو (Hero 24) فن دار',
            'brand' => 'لورچ',
            'brand_slug' => 'lorch',
            'brand_logo' => 'brand-lorch.png',
            'category' => 'پکیج دیواری',
            'price_raw' => 29800000,
            'price' => '۲۹,۸۰۰,۰۰۰',
            'old_price' => '۳۲,۰۰۰,۰۰۰',
            'discount' => '۷٪',
            'image' => 'package-bita-24.png',
            'capacity' => 24000,
            'area' => 'تا ۱۴۰ متر مربع',
            'heat_exchanger' => 'دو مبدل حرارتی صفحه‌ای',
            'flue_type' => 'فن‌دار',
            'efficiency' => '۹۳.۵٪ راندمان',
            'warranty' => '۲۴ ماه ضمانت لورچ + نصب رایگان',
            'in_stock' => true,
            'rating' => 4.7,
            'reviews_count' => 29,
            'description' => 'پکیج دیواری لورچ ۲۴۰۰۰ مدل هیرو با قطعات باکیفیت اروپایی، سیستم مدولاسیون خطی مشعل و سنسور دمای پیشرفته، آب گرم مطلوب و بدون نوسان دما را برای خانه شما فراهم می‌کند.',
            'specs' => [
                'ظرفیت حرارتی' => '۲۴ کیلووات',
                'نوع مبدل' => 'دو مبدل مونوترمیک',
                'پمپ' => 'گراندفوس دانمارک ۳ سرعته',
                'راندمان' => '۹۳.۵ درصد'
            ],
            'features' => [
                'بدون کوچک‌ترین نوسان دمای آب گرم',
                'عایق صوتی پیشرفته و کارکرد کاملاً بی‌صدا'
            ]
        ]
    ];

    public function index(): void {
        $brand = $_GET['brand'] ?? '';
        $capacity = (int)($_GET['capacity'] ?? 0);
        $sort = $_GET['sort'] ?? 'newest';

        $filtered = $this->products;

        if (!empty($brand)) {
            $filtered = array_filter($filtered, function($item) use ($brand) {
                return $item['brand_slug'] === $brand;
            });
        }

        if ($capacity > 0) {
            $filtered = array_filter($filtered, function($item) use ($capacity) {
                return $item['capacity'] === $capacity;
            });
        }

        // Sort
        if ($sort === 'cheap') {
            usort($filtered, fn($a, $b) => $a['price_raw'] <=> $b['price_raw']);
        } elseif ($sort === 'expensive') {
            usort($filtered, fn($a, $b) => $b['price_raw'] <=> $a['price_raw']);
        }

        $brands = [
            ['slug' => '', 'name' => 'همه برندها', 'count' => count($this->products)],
            ['slug' => 'iran-radiator', 'name' => 'ایران رادیاتور (کد مجاز)', 'count' => 2],
            ['slug' => 'butane', 'name' => 'بوتان', 'count' => 1],
            ['slug' => 'lorch', 'name' => 'لورچ', 'count' => 1],
        ];

        $data = [
            'pageTitle' => 'فروشگاه پکیج و رادیاتور | نمایندگی رسمی با نصب رایگان | ترموبان',
            'currentPage' => 'shop',
            'csrfToken' => Security::generateCsrf(),
            'products' => array_values($filtered),
            'brands' => $brands,
            'selectedBrand' => $brand,
            'selectedCapacity' => $capacity,
            'selectedSort' => $sort,
            'totalCount' => count($filtered)
        ];

        $this->view('shop/index', $data);
    }

    public function detail(string $id = ''): void {
        $product = null;
        foreach ($this->products as $item) {
            if ((string)$item['id'] === $id || $item['slug'] === $id) {
                $product = $item;
                break;
            }
        }

        if (!$product) {
            $product = $this->products[0];
        }

        $related = array_filter($this->products, fn($p) => $p['id'] !== $product['id']);

        $data = [
            'pageTitle' => $product['title'] . ' ' . $product['model'] . ' | ترموبان',
            'currentPage' => 'shop',
            'csrfToken' => Security::generateCsrf(),
            'product' => $product,
            'relatedProducts' => array_slice($related, 0, 3)
        ];

        $this->view('shop/detail', $data);
    }
}
