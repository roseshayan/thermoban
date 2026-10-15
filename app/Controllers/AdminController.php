<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Security;
use App\Models\Product;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Article;
use App\Models\QuickOrder;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\User;
use App\Services\Payment\PaymentManager;
use App\Services\Sms\MeliPayamakService;

class AdminController extends Controller {
    public function __construct() {
        if (!Auth::isAdmin()) {
            $_SESSION['auth_error'] = 'دسترسی غیرمجاز. لطفاً با حساب مدیریت وارد شوید.';
            header('Location: ' . url('login'));
            exit;
        }
    }

    /**
     * Admin Dashboard Overview
     */
    public function dashboard(): void {
        $stats = [
            'products_count'    => Product::count(),
            'orders_count'      => Order::count(),
            'open_tickets'      => Ticket::count("`status` != 'closed'"),
            'pending_leads'     => QuickOrder::count("`status` = 'pending'"),
            'total_revenue'     => Database::fetchOne("SELECT SUM(payable_amount) as total FROM `orders` WHERE `payment_status` = 'paid'")['total'] ?? 0,
            'users_count'       => User::count()
        ];

        $recentOrders = Database::fetchAll(
            "SELECT o.*, u.name as customer_name, u.phone as customer_phone 
             FROM `orders` o 
             LEFT JOIN `users` u ON o.user_id = u.id 
             ORDER BY o.id DESC LIMIT 5"
        );

        $recentLeads = QuickOrder::where("1=1", [], "id DESC", 5);

        $this->view('admin/dashboard', [
            'pageTitle'    => 'داشبورد مدیریت CMS ترموبان',
            'stats'        => $stats,
            'recentOrders' => $recentOrders,
            'recentLeads'  => $recentLeads,
            'activeNav'    => 'dashboard'
        ], 'layouts/admin');
    }

    /**
     * Manage Products (List, Create, Delete)
     */
    public function products(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'create';

            if ($action === 'create') {
                Product::create([
                    'brand_id'       => (int) ($_POST['brand_id'] ?? 1),
                    'title'          => trim($_POST['title']),
                    'slug'           => trim($_POST['slug'] ?? ('product-' . time())),
                    'model'          => trim($_POST['model']),
                    'capacity'       => (int) $_POST['capacity'],
                    'area'           => trim($_POST['area']),
                    'heat_exchanger' => trim($_POST['heat_exchanger']),
                    'flue_type'      => trim($_POST['flue_type']),
                    'price'          => (int) str_replace(',', '', $_POST['price']),
                    'old_price'      => !empty($_POST['old_price']) ? (int) str_replace(',', '', $_POST['old_price']) : null,
                    'warranty'       => trim($_POST['warranty']),
                    'image'          => trim($_POST['image'] ?: 'package-thumb.png'),
                    'description'    => trim($_POST['description']),
                    'features'       => json_encode(array_filter(array_map('trim', explode("\n", $_POST['features'] ?? ''))), JSON_UNESCAPED_UNICODE),
                    'specs'          => json_encode(['ظرفیت' => $_POST['capacity'], 'متراژ' => $_POST['area']], JSON_UNESCAPED_UNICODE),
                    'stock'          => (int) ($_POST['stock'] ?? 10),
                    'is_active'      => 1
                ]);
                $_SESSION['admin_success'] = 'محصول جدید با موفقیت به کاتالوگ فروشگاه اضافه شد.';
            } elseif ($action === 'delete') {
                $id = (int) $_POST['id'];
                Product::delete($id);
                $_SESSION['admin_success'] = 'محصول مورد نظر با موفقیت حذف گردید.';
            }

            $this->redirect('admin/products');
        }

        $products = Database::fetchAll(
            "SELECT p.*, b.name as brand_name FROM `products` p LEFT JOIN `brands` b ON p.brand_id = b.id ORDER BY p.id DESC"
        );
        $brands = Database::fetchAll("SELECT * FROM `brands`");

        $this->view('admin/products', [
            'pageTitle' => 'مدیریت محصولات فروشگاه | CMS ترموبان',
            'products'  => $products,
            'brands'    => $brands,
            'activeNav' => 'products',
            'success'   => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Edit Single Product
     */
    public function editProduct(int|string $id): void {
        $product = Product::find($id);
        if (!$product) {
            $_SESSION['admin_error'] = 'محصول یافت نشد.';
            $this->redirect('admin/products');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Product::update($id, [
                'brand_id'       => (int) ($_POST['brand_id'] ?? 1),
                'title'          => trim($_POST['title']),
                'model'          => trim($_POST['model']),
                'capacity'       => (int) $_POST['capacity'],
                'area'           => trim($_POST['area']),
                'heat_exchanger' => trim($_POST['heat_exchanger']),
                'flue_type'      => trim($_POST['flue_type']),
                'price'          => (int) str_replace(',', '', $_POST['price']),
                'old_price'      => !empty($_POST['old_price']) ? (int) str_replace(',', '', $_POST['old_price']) : null,
                'warranty'       => trim($_POST['warranty']),
                'image'          => trim($_POST['image'] ?: 'package-thumb.png'),
                'description'    => trim($_POST['description']),
                'features'       => json_encode(array_filter(array_map('trim', explode("\n", $_POST['features'] ?? ''))), JSON_UNESCAPED_UNICODE),
                'stock'          => (int) ($_POST['stock'] ?? 10),
                'is_active'      => isset($_POST['is_active']) ? 1 : 0
            ]);

            $_SESSION['admin_success'] = 'مشخصات و قیمت محصول بروزرسانی شد.';
            $this->redirect('admin/products');
        }

        $brands = Database::fetchAll("SELECT * FROM `brands`");

        $this->view('admin/product-edit', [
            'pageTitle' => 'ویرایش محصول: ' . $product['title'],
            'product'   => $product,
            'brands'    => $brands,
            'activeNav' => 'products'
        ], 'layouts/admin');
    }

    /**
     * Manage Orders & Manual Order Creation
     */
    public function orders(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'update_status';

            if ($action === 'create_order') {
                $userId = (int) ($_POST['user_id'] ?? 0);
                $productId = (int) ($_POST['product_id'] ?? 0);
                $qty = max(1, (int) ($_POST['quantity'] ?? 1));
                $product = Product::find($productId);
                
                $unitPrice = $product ? (int) $product['price'] : 0;
                $totalPrice = $unitPrice * $qty;
                $payable = !empty($_POST['payable_amount']) ? (int) str_replace(',', '', $_POST['payable_amount']) : $totalPrice;
                $orderNum = 'TB-' . date('Y') . '-' . rand(1000, 9999);

                $orderId = Database::insert('orders', [
                    'order_number'    => $orderNum,
                    'user_id'         => $userId ?: null,
                    'address_id'      => null,
                    'total_amount'    => $totalPrice,
                    'payable_amount'  => $payable,
                    'order_status'    => $_POST['order_status'] ?? 'registered',
                    'payment_status'  => $_POST['payment_status'] ?? 'paid',
                    'payment_method'  => $_POST['payment_method'] ?? 'cod',
                    'technician_name' => trim($_POST['technician_name'] ?? '')
                ]);

                if ($orderId && $productId) {
                    Database::insert('order_items', [
                        'order_id'    => $orderId,
                        'product_id'  => $productId,
                        'quantity'    => $qty,
                        'unit_price'  => $unitPrice,
                        'total_price' => $totalPrice
                    ]);
                }

                $_SESSION['admin_success'] = "سفارش جدید به شماره {$orderNum} با موفقیت ثبت گردید.";
            } else {
                $orderId = (int) ($_POST['order_id'] ?? 0);
                $orderStatus = $_POST['order_status'] ?? 'registered';
                $technician = trim($_POST['technician_name'] ?? '');

                Database::update('orders', [
                    'order_status'    => $orderStatus,
                    'technician_name' => $technician
                ], '`id` = :id', ['id' => $orderId]);

                $_SESSION['admin_success'] = 'وضعیت سفارش و تکنسین اعزامی بروزرسانی شد.';
            }

            $this->redirect('admin/orders');
        }

        $orders = Database::fetchAll(
            "SELECT o.*, u.name as customer_name, u.phone as customer_phone, ua.district as customer_district 
             FROM `orders` o 
             LEFT JOIN `users` u ON o.user_id = u.id 
             LEFT JOIN `user_addresses` ua ON o.address_id = ua.id
             ORDER BY o.id DESC"
        );

        $users = User::all('name ASC');
        $products = Product::where("is_active = 1", [], 'title ASC');

        $this->view('admin/orders', [
            'pageTitle' => 'مدیریت سفارش‌ها و ثبت سفارش دستی | CMS ترموبان',
            'orders'    => $orders,
            'users'     => $users,
            'products'  => $products,
            'activeNav' => 'orders',
            'success'   => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Manage Support Tickets & Customer Ticket Creation with Notifications
     */
    public function tickets(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'reply';

            if ($action === 'create_ticket') {
                $userId = (int) ($_POST['user_id'] ?? 0);
                $user = User::find($userId);
                $subject = trim($_POST['subject'] ?? 'درخواست خدمات پکیج');
                $category = trim($_POST['category'] ?? 'سرویس و عیب‌یابی');
                $message = trim($_POST['message'] ?? '');
                $sendEmail = isset($_POST['send_email']) && $_POST['send_email'] === '1';
                $sendSms = isset($_POST['send_sms']) && $_POST['send_sms'] === '1';
                $ticketNum = 'TK-' . rand(10000, 99999);

                $ticketId = Database::insert('tickets', [
                    'ticket_number' => $ticketNum,
                    'user_id'       => $userId ?: null,
                    'subject'       => $subject,
                    'category'      => $category,
                    'status'        => 'open',
                    'priority'      => $_POST['priority'] ?? 'normal'
                ]);

                if ($ticketId && !empty($message)) {
                    Ticket::addMessage($ticketId, 'admin', 'پشتیبانی فنی ترموبان', $message);
                }

                // Send Email Notification if requested & available
                if ($user && $sendEmail && !empty($user['email'])) {
                    \App\Services\EmailNotificationService::sendTicketNotification($user['email'], $user['name'], $ticketNum, $subject, 'created');
                }

                // Send SMS Notification if requested & enabled
                if ($user && $sendSms && !empty($user['phone'])) {
                    \App\Services\Sms\MeliPayamakService::sendTicketNotification($user['phone'], $ticketNum, $subject, 'created');
                }

                $customerName = $user['name'] ?? 'مشتری';
                $_SESSION['admin_success'] = "تیکت جدید با شماره {$ticketNum} برای {$customerName} با موفقیت ثبت شد.";
            } else {
                $ticketId = (int) ($_POST['ticket_id'] ?? 0);
                $replyMessage = trim($_POST['reply_message'] ?? '');
                $status = $_POST['status'] ?? 'answered';
                $sendEmail = isset($_POST['send_email']) && $_POST['send_email'] === '1';
                $sendSms = isset($_POST['send_sms']) && $_POST['send_sms'] === '1';

                if (!empty($replyMessage)) {
                    Ticket::addMessage($ticketId, 'admin', 'پشتیبانی فنی ترموبان', $replyMessage);
                    Database::update('tickets', ['status' => $status], '`id` = :id', ['id' => $ticketId]);

                    $ticket = Ticket::find($ticketId);
                    $user = $ticket && !empty($ticket['user_id']) ? User::find($ticket['user_id']) : null;

                    // Send Email Notification if requested & available
                    if ($user && $sendEmail && !empty($user['email'])) {
                        \App\Services\EmailNotificationService::sendTicketNotification($user['email'], $user['name'], $ticket['ticket_number'] ?? (string)$ticketId, $ticket['subject'] ?? '', 'replied');
                    }

                    // Send SMS Notification if requested & enabled
                    if ($user && $sendSms && !empty($user['phone'])) {
                        \App\Services\Sms\MeliPayamakService::sendTicketNotification($user['phone'], $ticket['ticket_number'] ?? (string)$ticketId, $ticket['subject'] ?? '', 'replied');
                    }

                    $_SESSION['admin_success'] = 'پاسخ تیکت با موفقیت ثبت و اعلانات مربوطه ارسال گردید.';
                }
            }

            $this->redirect('admin/tickets');
        }

        $tickets = Database::fetchAll(
            "SELECT t.*, u.name as user_name, u.phone as user_phone, u.email as user_email 
             FROM `tickets` t 
             LEFT JOIN `users` u ON t.user_id = u.id 
             ORDER BY t.id DESC"
        );

        foreach ($tickets as &$t) {
            $t['messages'] = Database::fetchAll(
                "SELECT * FROM `ticket_messages` WHERE `ticket_id` = :t_id ORDER BY `id` ASC",
                ['t_id' => $t['id']]
            );
        }

        $users = User::all('name ASC');

        $this->view('admin/tickets', [
            'pageTitle' => 'تیکت‌های پشتیبانی و ثبت تیکت جدید | CMS ترموبان',
            'tickets'   => $tickets,
            'users'     => $users,
            'activeNav' => 'tickets',
            'success'   => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Manage Quick Orders & Contact Leads
     */
    public function leads(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $leadId = (int) ($_POST['lead_id'] ?? 0);
            $status = $_POST['status'] ?? 'called';

            Database::update('quick_orders', ['status' => $status], '`id` = :id', ['id' => $leadId]);
            $_SESSION['admin_success'] = 'وضعیت تماس با مشتری بروزرسانی شد.';
            $this->redirect('admin/leads');
        }

        $quickOrders = QuickOrder::all('id DESC');
        $contacts = ContactMessage::all('id DESC');

        $this->view('admin/leads', [
            'pageTitle'   => 'درخواست‌های تماس و سفارش سریع | CMS ترموبان',
            'quickOrders' => $quickOrders,
            'contacts'    => $contacts,
            'activeNav'   => 'leads',
            'success'     => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Manage Articles (List, Create, Delete)
     */
    public function articles(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'create';

            if ($action === 'create') {
                Article::create([
                    'title'        => trim($_POST['title']),
                    'slug'         => trim($_POST['slug'] ?? ('article-' . time())),
                    'excerpt'      => trim($_POST['excerpt']),
                    'content'      => trim($_POST['content']),
                    'image'        => trim($_POST['image'] ?: 'hero-technician.jpg'),
                    'author_name'  => 'تیم فنی ترموبان',
                    'reading_time' => trim($_POST['reading_time'] ?: '۵ دقیقه'),
                    'is_published' => 1
                ]);
                $_SESSION['admin_success'] = 'مقاله جدید با موفقیت منتشر گردید.';
            } elseif ($action === 'delete') {
                $id = (int) $_POST['id'];
                Article::delete($id);
                $_SESSION['admin_success'] = 'مقاله حذف گردید.';
            }

            $this->redirect('admin/articles');
        }

        $articles = Article::all('id DESC');

        $this->view('admin/articles', [
            'pageTitle' => 'مدیریت مقالات آموزشی | CMS ترموبان',
            'articles'  => $articles,
            'activeNav' => 'articles',
            'success'   => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Edit Single Article
     */
    public function editArticle(int|string $id): void {
        $article = Article::find($id);
        if (!$article) {
            $_SESSION['admin_error'] = 'مقاله یافت نشد.';
            $this->redirect('admin/articles');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Article::update($id, [
                'title'        => trim($_POST['title']),
                'slug'         => trim($_POST['slug']),
                'excerpt'      => trim($_POST['excerpt']),
                'content'      => trim($_POST['content']),
                'image'        => trim($_POST['image'] ?: 'hero-technician.jpg'),
                'reading_time' => trim($_POST['reading_time'] ?: '۵ دقیقه'),
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);

            $_SESSION['admin_success'] = 'محتوای مقاله آموزشی با موفقیت بروزرسانی شد.';
            $this->redirect('admin/articles');
        }

        $this->view('admin/article-edit', [
            'pageTitle' => 'ویرایش مقاله: ' . $article['title'],
            'article'   => $article,
            'activeNav' => 'articles'
        ], 'layouts/admin');
    }

    /**
     * Super Admin Feature Flags & Monetization Control
     */
    public function modules(): void {
        if (!Auth::isSuperAdmin()) {
            $_SESSION['admin_error'] = 'این بخش فقط مخصوص برنامه‌نویس و سوپر ادمین (شایان نماینده) است.';
            $this->redirect('admin');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modules = [
                'module_shop'         => isset($_POST['module_shop']) ? '1' : '0',
                'module_blog'         => isset($_POST['module_blog']) ? '1' : '0',
                'module_tickets'      => isset($_POST['module_tickets']) ? '1' : '0',
                'module_gateways'     => isset($_POST['module_gateways']) ? '1' : '0',
                'module_sms'          => isset($_POST['module_sms']) ? '1' : '0',
                'module_registration' => isset($_POST['module_registration']) ? '1' : '0',
                'module_files_ir'     => isset($_POST['module_files_ir']) ? '1' : '0',
                'files_ir_api_key'    => trim($_POST['files_ir_api_key'] ?? ''),
                'module_analytics'    => isset($_POST['module_analytics']) ? '1' : '0',
                'maintenance_mode'    => isset($_POST['maintenance_mode']) ? '1' : '0',
                'maintenance_title'   => trim($_POST['maintenance_title'] ?? ''),
                'maintenance_message' => trim($_POST['maintenance_message'] ?? ''),
                'maintenance_phone'   => trim($_POST['maintenance_phone'] ?? '۰۲۱-۷۷۴۲۸۵۹۶')
            ];

            foreach ($modules as $k => $v) {
                Setting::set($k, $v);
            }

            $_SESSION['admin_success'] = 'وضعیت ماژول‌های سامانه و پلن‌های تجاری ذخیره گردید.';
            $this->redirect('admin/modules');
        }

        $settings = Setting::allKeyVal();

        $this->view('admin/modules', [
            'pageTitle' => 'مدیریت ماژول‌ها و پلن‌های تجاری (سوپر ادمین)',
            'settings'  => $settings,
            'activeNav' => 'modules',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Rank Math Pro Style SEO Manager
     */
    public function seo(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $seoKeys = [
                'seo_meta_title_template',
                'seo_meta_description',
                'seo_meta_keywords',
                'seo_canonical_url',
                'seo_og_image',
                'seo_robots_txt',
                'seo_google_analytics',
                'seo_search_console',
                'seo_schema_type'
            ];

            foreach ($seoKeys as $key) {
                if (isset($_POST[$key])) {
                    Setting::set($key, trim($_POST[$key]));
                }
            }

            $_SESSION['admin_success'] = 'تنظیمات حرفه‌ای سئو (Rank Math Pro) ذخیره گردید.';
            $this->redirect('admin/seo');
        }

        $settings = Setting::allKeyVal();

        $this->view('admin/seo', [
            'pageTitle' => 'مدیریت جامع سئو و بهینه‌سازی موتورهای جستجو (Rank Math)',
            'settings'  => $settings,
            'activeNav' => 'seo',
            'success'   => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Payment Gateways Configuration
     */
    public function gateways(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $keys = [
                'gateway_default'           => trim($_POST['gateway_default'] ?? 'zarinpal'),
                'gateway_zarinpal_merchant' => trim($_POST['gateway_zarinpal_merchant'] ?? ''),
                'gateway_zarinpal_sandbox'  => isset($_POST['gateway_zarinpal_sandbox']) ? '1' : '0',
                'gateway_zibal_merchant'    => trim($_POST['gateway_zibal_merchant'] ?? ''),
                'gateway_zibal_sandbox'     => isset($_POST['gateway_zibal_sandbox']) ? '1' : '0',
                'gateway_cod_enabled'       => isset($_POST['gateway_cod_enabled']) ? '1' : '0'
            ];

            foreach ($keys as $k => $v) {
                Setting::set($k, $v);
            }

            $_SESSION['admin_success'] = 'تنظیمات درگاه‌های پرداخت با موفقیت بروزرسانی شد.';
            $this->redirect('admin/gateways');
        }

        $settings = Setting::allKeyVal();

        $this->view('admin/gateways', [
            'pageTitle' => 'پیکربندی درگاه‌های پرداخت آنلاین (زرین‌پال و زیبال)',
            'settings'  => $settings,
            'activeNav' => 'gateways',
            'success'   => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * SMS Panel Configuration & Testing
     */
    public function sms(): void {
        $testResult = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'save_settings';

            if ($action === 'save_settings') {
                $keys = [
                    'sms_enabled'       => isset($_POST['sms_enabled']) ? '1' : '0',
                    'sms_username'      => trim($_POST['sms_username'] ?? ''),
                    'sms_password'      => trim($_POST['sms_password'] ?? ''),
                    'sms_from_number'   => trim($_POST['sms_from_number'] ?? ''),
                    'sms_pattern_otp'   => trim($_POST['sms_pattern_otp'] ?? ''),
                    'sms_pattern_order' => trim($_POST['sms_pattern_order'] ?? ''),
                    'sms_pattern_ticket'=> trim($_POST['sms_pattern_ticket'] ?? '')
                ];

                foreach ($keys as $k => $v) {
                    Setting::set($k, $v);
                }

                $_SESSION['admin_success'] = 'تنظیمات وب‌سرویس ملی‌پیامک ذخیره گردید.';
                $this->redirect('admin/sms');
            } elseif ($action === 'test_sms') {
                $testPhone = trim($_POST['test_phone'] ?? '');
                $testMsg = trim($_POST['test_message'] ?? 'تست ارتباط با پنل پیامکی ترموبان');

                if (!empty($testPhone)) {
                    $res = MeliPayamakService::sendDirect($testPhone, $testMsg);
                    if ($res['success']) {
                        $_SESSION['admin_success'] = 'پیامک آزمایشی با موفقیت به شماره ' . $testPhone . ' ارسال گردید. (پاسخ ملی‌پیامک: ' . ($res['response']['Value'] ?? 'OK') . ')';
                    } else {
                        $_SESSION['admin_error'] = 'خطا در ارسال پیامک: ' . ($res['error'] ?? 'خطای ناشناخته');
                    }
                }
                $this->redirect('admin/sms');
            }
        }

        $settings = Setting::allKeyVal();

        $this->view('admin/sms', [
            'pageTitle' => 'تنظیمات پنل پیامک (ملی‌پیامک REST)',
            'settings'  => $settings,
            'activeNav' => 'sms',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Manage Users (CRUD, Roles, Block/Active)
     */
    public function users(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'create') {
                $name = trim($_POST['name'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                $nationalId = trim($_POST['national_id'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '123456';
                $role = $_POST['role'] ?? 'user';
                $status = $_POST['status'] ?? 'active';

                if (!empty($name) && !empty($phone)) {
                    if (User::findByPhone($phone)) {
                        $_SESSION['admin_error'] = 'کاربری با این شماره همراه قبلاً ثبت شده است.';
                    } else {
                        User::create([
                            'name'          => $name,
                            'phone'         => $phone,
                            'national_id'   => $nationalId ?: null,
                            'email'         => $email ?: null,
                            'password_hash' => Auth::hashPassword($password),
                            'role'          => $role,
                            'status'        => $status
                        ]);
                        $_SESSION['admin_success'] = "کاربر {$name} با موفقیت افزوده شد.";
                    }
                }
            } elseif ($action === 'update_role') {
                $userId = (int) $_POST['user_id'];
                $role = $_POST['role'] ?? 'user';
                $status = $_POST['status'] ?? 'active';

                // Prevent changing super_admin role if not super_admin
                $target = User::find($userId);
                if ($target && $target['role'] === 'super_admin' && !Auth::isSuperAdmin()) {
                    $_SESSION['admin_error'] = 'شما دسترسی لازم برای تغییر نقش سوپر ادمین را ندارید.';
                } else {
                    User::update($userId, [
                        'role'   => $role,
                        'status' => $status
                    ]);
                    $_SESSION['admin_success'] = 'نقش و وضعیت کاربر بروزرسانی شد.';
                }
            } elseif ($action === 'reset_password') {
                $userId = (int) $_POST['user_id'];
                $newPassword = $_POST['new_password'] ?? '';
                if (!empty($newPassword) && strlen($newPassword) >= 6) {
                    User::update($userId, [
                        'password_hash' => Auth::hashPassword($newPassword)
                    ]);
                    $_SESSION['admin_success'] = 'کلمه عبور کاربر با موفقیت تغییر یافت.';
                } else {
                    $_SESSION['admin_error'] = 'کلمه عبور باید حداقل ۶ رقم باشد.';
                }
            } elseif ($action === 'delete') {
                $userId = (int) $_POST['user_id'];
                if ($userId === Auth::id()) {
                    $_SESSION['admin_error'] = 'امکان حذف حساب کاربری جاری خودتان وجود ندارد.';
                } else {
                    User::delete($userId);
                    $_SESSION['admin_success'] = 'کاربر با موفقیت حذف گردید.';
                }
            }

            $this->redirect('admin/users');
        }

        $search = trim($_GET['q'] ?? '');
        if (!empty($search)) {
            $users = Database::fetchAll(
                "SELECT * FROM `users` WHERE `name` LIKE :q OR `phone` LIKE :q OR `email` LIKE :q ORDER BY `id` DESC",
                ['q' => "%{$search}%"]
            );
        } else {
            $users = User::all('id DESC');
        }

        $this->view('admin/users', [
            'pageTitle' => 'مدیریت جامع کاربران و سطوح دسترسی | CMS ترموبان',
            'users'     => $users,
            'search'    => $search,
            'activeNav' => 'users',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Site Settings
     */
    public function settings(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['settings'] as $key => $val) {
                Setting::set($key, trim($val));
            }
            $_SESSION['admin_success'] = 'تنظیمات کلی وب‌سایت ذخیره گردید.';
            $this->redirect('admin/settings');
        }

        $settings = Database::fetchAll("SELECT * FROM `settings`");
        $settingsMap = [];
        foreach ($settings as $s) {
            $settingsMap[$s['setting_key']] = $s['setting_value'];
        }

        $this->view('admin/settings', [
            'pageTitle'   => 'تنظیمات سامانه ترموبان | CMS اختصاصی',
            'settings'    => $settingsMap,
            'activeNav'   => 'settings',
            'success'     => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Database Backups, Retention & Restore (cPanel Safe & Files.ir Cloud)
     */
    public function backups(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'create') {
                $uploadCloud = isset($_POST['upload_cloud']) && $_POST['upload_cloud'] === '1';
                $result = \App\Services\BackupService::createBackup($uploadCloud);
                if ($result['success']) {
                    $_SESSION['admin_success'] = $result['message'];
                } else {
                    $_SESSION['admin_error'] = $result['message'];
                }
            } elseif ($action === 'restore') {
                $filename = $_POST['filename'] ?? '';
                $result = \App\Services\BackupService::restoreBackup($filename);
                if ($result['success']) {
                    $_SESSION['admin_success'] = $result['message'];
                } else {
                    $_SESSION['admin_error'] = $result['message'];
                }
            } elseif ($action === 'delete') {
                $filename = $_POST['filename'] ?? '';
                if (\App\Services\BackupService::deleteBackup($filename)) {
                    $_SESSION['admin_success'] = "فایل پشتیبان {$filename} با موفقیت حذف گردید.";
                } else {
                    $_SESSION['admin_error'] = "خطا در حذف فایل پشتیبان.";
                }
            } elseif ($action === 'upload_cloud') {
                $filename = $_POST['filename'] ?? '';
                $path = \App\Services\BackupService::getBackupPath($filename);
                if ($path) {
                    $cloud = new \App\Services\Storage\FilesIrService();
                    $res = $cloud->uploadFile($path);
                    if ($res['success']) {
                        $_SESSION['admin_success'] = $res['message'];
                    } else {
                        $_SESSION['admin_error'] = $res['message'];
                    }
                } else {
                    $_SESSION['admin_error'] = 'فایل پشتیبان یافت نشد.';
                }
            } elseif ($action === 'save_schedule') {
                $schedule = $_POST['backup_schedule'] ?? 'daily';
                Setting::set('backup_schedule', $schedule);
                $_SESSION['admin_success'] = 'زمان‌بندی خودکار پشتیبان‌گیری بروزرسانی گردید.';
            }

            $this->redirect('admin/backups');
        }

        $backups = \App\Services\BackupService::getBackups();
        $schedule = Setting::get('backup_schedule', 'daily');
        $lastRun = Setting::get('backup_last_run', 'ثبت نشده');
        $filesIrEnabled = Setting::get('module_files_ir', '0') === '1';

        $this->view('admin/backups', [
            'pageTitle'      => 'مدیریت و بازیابی فایل‌های پشتیبان دیتابیس (Backup & Restore)',
            'backups'        => $backups,
            'schedule'       => $schedule,
            'lastRun'        => $lastRun,
            'filesIrEnabled' => $filesIrEnabled,
            'activeNav'      => 'backups',
            'success'        => $_SESSION['admin_success'] ?? null,
            'error'          => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Download Backup File
     */
    public function downloadBackup(string $file = ''): void {
        $path = \App\Services\BackupService::getBackupPath($file);
        if (!$path) {
            $_SESSION['admin_error'] = 'فایل مورد نظر یافت نشد.';
            $this->redirect('admin/backups');
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    /**
     * Visitor Analytics Dashboard
     */
    public function analytics(): void {
        $analyticsEnabled = Setting::get('module_analytics', '1') === '1';
        $stats = \App\Models\VisitorLog::getStats();
        $recentVisitors = Database::fetchAll("SELECT * FROM `visitor_logs` ORDER BY id DESC LIMIT 25");

        $this->view('admin/analytics', [
            'pageTitle'        => 'آمار و تحلیل بازدیدکنندگان سایت | CMS ترموبان',
            'analyticsEnabled' => $analyticsEnabled,
            'stats'            => $stats,
            'recentVisitors'   => $recentVisitors,
            'activeNav'        => 'analytics'
        ], 'layouts/admin');
    }

    /**
     * Comments & Reviews Moderation
     */
    public function comments(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['comment_id'] ?? 0);

            if ($action === 'approve') {
                Database::query("UPDATE `comments` SET `status` = 'approved' WHERE `id` = :id", ['id' => $id]);
                $_SESSION['admin_success'] = 'دیدگاه تایید شد و در سایت نمایش داده می‌شود.';
            } elseif ($action === 'reject') {
                Database::query("UPDATE `comments` SET `status` = 'rejected' WHERE `id` = :id", ['id' => $id]);
                $_SESSION['admin_success'] = 'دیدگاه رد شد.';
            } elseif ($action === 'reply') {
                $reply = trim($_POST['reply'] ?? '');
                Database::query("UPDATE `comments` SET `reply` = :reply, `status` = 'approved' WHERE `id` = :id", [
                    'reply' => $reply,
                    'id'    => $id
                ]);
                $_SESSION['admin_success'] = 'پاسخ رسمی شما ثبت و دیدگاه تایید گردید.';
            } elseif ($action === 'delete') {
                Database::query("DELETE FROM `comments` WHERE `id` = :id", ['id' => $id]);
                $_SESSION['admin_success'] = 'دیدگاه با موفقیت حذف گردید.';
            }

            $this->redirect('admin/comments');
        }

        $comments = Database::fetchAll(
            "SELECT c.*, p.title as product_title, a.title as article_title 
             FROM `comments` c 
             LEFT JOIN `products` p ON c.product_id = p.id 
             LEFT JOIN `articles` a ON c.article_id = a.id 
             ORDER BY c.id DESC"
        );

        $this->view('admin/comments', [
            'pageTitle' => 'مدیریت و پاسخ‌گویی به نظرات و دیدگاه‌ها | CMS ترموبان',
            'comments'  => $comments,
            'activeNav' => 'comments',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }
}
