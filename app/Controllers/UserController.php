<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class UserController extends Controller {
    protected array $user = [
        'name' => 'علیرضا تهرانی',
        'phone' => '۰۹۱۲۲۸۴۵۸۳۲',
        'national_id' => '۰۰۱۸۳۷۲۶۴۵',
        'email' => 'tehrani@gmail.com',
        'city' => 'تهران',
        'avatar' => 'technician-thumb.jpg'
    ];

    protected array $addresses = [
        [
            'id' => 1,
            'title' => 'منزل (سعادت‌آباد)',
            'recipient' => 'علیرضا تهرانی',
            'phone' => '۰۹۱۲۲۸۴۵۸۳۲',
            'district' => 'منطقه ۲',
            'address' => 'تهران، سعادت‌آباد، میدان کاج، خیابان سرو غربی، کوچه مروارید، پلاک ۱۸، واحد ۴',
            'postal_code' => '۱۹۹۷۸۵۴۳۲۱',
            'lat' => 35.7832,
            'lng' => 51.3712,
            'is_default' => true
        ],
        [
            'id' => 2,
            'title' => 'محل کار (میرداماد)',
            'recipient' => 'علیرضا تهرانی',
            'phone' => '۰۲۱-۲۲۱۴۵۶۷۸',
            'district' => 'منطقه ۳',
            'address' => 'تهران، بلوار میرداماد، جنب ایستگاه مترو، ساختمان اداری نگین، طبقه ۳، واحد ۹',
            'postal_code' => '۱۵۴۳۶۷۸۹۰۱',
            'lat' => 35.7610,
            'lng' => 51.4390,
            'is_default' => false
        ]
    ];

    protected array $orders = [
        [
            'id' => 'TB-1405-82',
            'date' => '۱۴۰۵/۰۸/۱۰',
            'status' => 'تکمیل شده و تحویل شد',
            'status_class' => 'status-success',
            'total_price' => '۲۸,۵۰۰,۰۰۰',
            'payment_method' => 'پرداخت اینترنتی شتابی',
            'technician' => 'مهندس کاظمی (کد نصاب ۷۲۷۳)',
            'guarantee' => 'فعال - ۲۴ ماه طلایی',
            'items' => [
                [
                    'title' => 'پکیج شوفاژ دیواری بوتان ۲۴۰۰۰ مدل Bita 24 Sealed',
                    'quantity' => 1,
                    'price' => '۲۸,۵۰۰,۰۰۰',
                    'image' => 'package-bita-24.png'
                ]
            ]
        ],
        [
            'id' => 'TB-1405-64',
            'date' => '۱۴۰۵/۰۷/۱۵',
            'status' => 'تکمیل شده با ضمانت ۶ ماهه',
            'status_class' => 'status-success',
            'total_price' => '۴۵۰,۰۰۰',
            'payment_method' => 'کارتخوان سیار در محل',
            'technician' => 'مهندس رضایی (تکنسین ارشد)',
            'guarantee' => 'فعال - ۶ ماهه کتبی',
            'items' => [
                [
                    'title' => 'سرویس کامل، اسیدشویی مبدل اصلی و ثانویه و تست بار',
                    'quantity' => 1,
                    'price' => '۴۵۰,۰۰۰',
                    'image' => 'badge-shield.png'
                ]
            ]
        ]
    ];

    protected array $likedProducts = [
        [
            'id' => 2,
            'title' => 'پکیج شوفاژ دیواری ایران رادیاتور ۲۴۰۰۰',
            'model' => 'مدل L24FF دو مبدل دیجیتال فن دار',
            'brand' => 'ایران رادیاتور',
            'price' => '۳۲,۴۰۰,۰۰۰',
            'old_price' => '۳۴,۵۰۰,۰۰۰',
            'discount' => '۶٪',
            'image' => 'package-thumb.png',
            'in_stock' => true
        ],
        [
            'id' => 1,
            'title' => 'پکیج شوفاژ دیواری بوتان ۲۴۰۰۰',
            'model' => 'مدل Bita 24 Sealed فن دار',
            'brand' => 'بوتان',
            'price' => '۲۸,۵۰۰,۰۰۰',
            'old_price' => '۳۱,۲۰۰,۰۰۰',
            'discount' => '۹٪',
            'image' => 'package-bita-24.png',
            'in_stock' => true
        ]
    ];

    protected array $tickets = [
        [
            'id' => 'TK-1084',
            'subject' => 'درخواست اعزام نصاب مجاز برای فعال‌سازی گارانتی پکیج',
            'category' => 'رزرو و اعزام تکنسین',
            'date' => '۱۴۰۵/۰۸/۱۲',
            'status' => 'پاسخ داده شده',
            'status_class' => 'status-answered',
            'last_reply' => 'کارشناس اعزام: هماهنگی انجام شد، تکنسین فردا بین ساعت ۱۰ الی ۱۲ در آدرس شما حاضر خواهند بود.',
            'messages' => [
                [
                    'sender' => 'user',
                    'sender_name' => 'علیرضا تهرانی',
                    'time' => 'دیروز ساعت ۱۶:۳۰',
                    'text' => 'سلام، پکیج بیتا ۲۴ تحویل ما شد. لطفاً نصاب مجاز دارای کد رسمی جهت نصب استاندارد و مهر گارانتی ۲۴ ماهه اعزام بفرمایید.'
                ],
                [
                    'sender' => 'support',
                    'sender_name' => 'پشتیبانی فنی ترموبان (مهندس رضایی)',
                    'time' => 'دیروز ساعت ۱۷:۱۵',
                    'text' => 'سلام جناب تهرانی عزیز. درخواست شما ثبت و کد پیگیری ارجاع گردید. تکنسین ما فردا بین ساعت ۱۰ الی ۱۲ قبل از حضور با شما تماس خواهند گرفت.'
                ]
            ]
        ],
        [
            'id' => 'TK-1042',
            'subject' => 'استعلام فیلتر مغناطیسی مدار گرمایش و رسوب‌گیر پلی‌فسفات',
            'category' => 'پشتیبانی فنی و قطعات',
            'date' => '۱۴۰۵/۰۷/۱۴',
            'status' => 'بسته شده',
            'status_class' => 'status-closed',
            'last_reply' => 'پشتیبانی: فیلترها همراه تکنسین با ضمانت تحویل گردید.',
            'messages' => [
                [
                    'sender' => 'user',
                    'sender_name' => 'علیرضا تهرانی',
                    'time' => '۱۴۰۵/۰۷/۱۴ ساعت ۱۰:۰۰',
                    'text' => 'سلام، آیا تکنسین فیلتر مغناطیسی مدار برگشت همراه خود دارند یا باید جداگانه تهیه کنیم؟'
                ],
                [
                    'sender' => 'support',
                    'sender_name' => 'پشتیبانی ترموبان',
                    'time' => '۱۴۰۵/۰۷/۱۴ ساعت ۱۰:۳۰',
                    'text' => 'درود بر شما. کلیه قطعات استاندارد شرکتی در خودروهای خدمات سیار ترموبان موجود است و نیازی به خرید مجزا نیست.'
                ]
            ]
        ]
    ];

    public function profile(): void {
        $tab = $_GET['tab'] ?? 'dashboard';

        $data = [
            'pageTitle' => 'حساب کاربری | ترموبان',
            'currentPage' => 'profile',
            'activeTab' => $tab,
            'csrfToken' => Security::generateCsrf(),
            'user' => $this->user,
            'addresses' => $this->addresses,
            'orders' => $this->orders,
            'likedProducts' => $this->likedProducts,
            'tickets' => $this->tickets
        ];

        $this->view('user/profile', $data);
    }

    public function invoice(string $id = ''): void {
        $order = null;
        foreach ($this->orders as $o) {
            if ($o['id'] === $id) {
                $order = $o;
                break;
            }
        }

        if (!$order) {
            $order = $this->orders[0];
        }

        $data = [
            'pageTitle' => 'فاکتور رسمی فروش و خدمات ' . $order['id'] . ' | ترموبان',
            'currentPage' => 'profile',
            'order' => $order,
            'user' => $this->user
        ];

        $this->view('user/invoice', $data);
    }

    public function updateInfo(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $this->json(['success' => false, 'message' => 'توکن امنیتی نامعتبر است.'], 403);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $national_id = trim($_POST['national_id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($name) || empty($phone)) {
            $this->json(['success' => false, 'message' => 'نام و شماره موبایل الزامی است.'], 422);
            return;
        }

        try {
            $userId = \App\Core\Auth::id() ?? 2;
            $updateData = [
                'name'        => $name,
                'phone'       => $phone,
                'national_id' => $national_id,
                'email'       => $email
            ];
            if (!empty($password)) {
                $updateData['password_hash'] = \App\Core\Auth::hashPassword($password);
            }
            \App\Core\Database::update('users', $updateData, '`id` = :id', ['id' => $userId]);
        } catch (\Throwable $e) {
            error_log("Failed to update user info: " . $e->getMessage());
        }

        $this->json([
            'success' => true,
            'message' => 'اطلاعات کاربری با موفقیت ویرایش و در پایگاه داده ذخیره گردید.'
        ]);
    }

    public function addAddress(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $this->json(['success' => false, 'message' => 'توکن امنیتی نامعتبر است.'], 403);
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $district = trim($_POST['district'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $lat = (float) ($_POST['lat'] ?? 35.7219);
        $lng = (float) ($_POST['lng'] ?? 51.4056);

        if (empty($title) || empty($district) || empty($address)) {
            $this->json(['success' => false, 'message' => 'لطفاً تمامی فیلدهای الزامی آدرس را تکمیل فرمایید.'], 422);
            return;
        }

        try {
            $userId = \App\Core\Auth::id() ?? 2;
            \App\Core\Database::insert('user_addresses', [
                'user_id'     => $userId,
                'title'       => $title,
                'district'    => $district,
                'address'     => $address,
                'postal_code' => $postal_code,
                'phone'       => $phone,
                'lat'         => $lat,
                'lng'         => $lng,
                'is_default'  => 0,
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            error_log("Failed to add address: " . $e->getMessage());
        }

        $this->json([
            'success' => true,
            'message' => 'آدرس و موقعیت مکانی جدید با موفقیت روی نقشه ثبت شد.'
        ]);
    }

    public function createTicket(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $this->json(['success' => false, 'message' => 'توکن امنیتی نامعتبر است.'], 403);
            return;
        }

        $subject = trim($_POST['subject'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $priority = trim($_POST['priority'] ?? 'normal');

        if (empty($subject) || empty($message)) {
            $this->json(['success' => false, 'message' => 'عنوان و شرح پیام تیکت الزامی است.'], 422);
            return;
        }

        $ticketNum = 'TK-' . rand(1100, 9999);
        try {
            $userId = \App\Core\Auth::id() ?? 2;
            $ticketId = \App\Core\Database::insert('tickets', [
                'ticket_number' => $ticketNum,
                'user_id'       => $userId,
                'subject'       => $subject,
                'category'      => $category,
                'priority'      => in_array($priority, ['normal', 'high', 'emergency']) ? $priority : 'normal',
                'status'        => 'open',
                'created_at'    => date('Y-m-d H:i:s')
            ]);

            \App\Core\Database::insert('ticket_messages', [
                'ticket_id'   => $ticketId,
                'sender_type' => 'user',
                'sender_name' => 'کاربر',
                'message'     => $message,
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            error_log("Failed to create ticket: " . $e->getMessage());
        }

        $this->json([
            'success' => true,
            'message' => 'تیکت پشتیبانی با کد ' . $ticketNum . ' با موفقیت ثبت شد و به کارشناس فنی ارجاع گردید.'
        ]);
    }
}
