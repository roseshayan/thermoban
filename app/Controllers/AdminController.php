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

        try {
            \App\Services\DatabaseMigrationService::run();
        } catch (\Throwable $e) {
            error_log("Admin migration run error: " . $e->getMessage());
        }
    }

    /**
     * Check module authorization for store managers
     */
    protected function requireModule(string $moduleKey, string $moduleName): void {
        if (!Auth::isSuperAdmin()) {
            $enabled = Setting::get($moduleKey, '1');
            if ($enabled === '0' || $enabled === false) {
                $_SESSION['admin_error'] = "ماژول «{$moduleName}» در حال حاضر غیرفعال است و تنها توسط سوپر ادمین (برنامه‌نویس) قابل فعال‌سازی می‌باشد.";
                header('Location: ' . url('admin'));
                exit;
            }
        }
    }

    /**
     * Ensure only super admin can access developer tools
     */
    protected function requireSuperAdmin(): void {
        if (!Auth::isSuperAdmin()) {
            $_SESSION['admin_error'] = 'این بخش فقط برای سوپر ادمین (برنامه‌نویس سامانه) در دسترس است.';
            header('Location: ' . url('admin'));
            exit;
        }
    }

    /**
     * AJAX: Mark all notifications as read
     */
    public function markNotificationsRead(): void {
        \App\Services\NotificationService::markAllAsRead();
        $this->json([
            'success' => true,
            'message' => 'تمامی اعلان‌ها با موفقیت علامت‌گذاری و خوانده شدند.'
        ]);
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

        // 1. Real Monthly Sales Trend (Last 7 Months)
        $monthNames = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        $currentJalali = \App\Core\Jalali::toJalali(time());
        $curMonth = $currentJalali['m'];
        $labels = [];
        $salesData = [];
        $ordersCountData = [];

        for ($i = 6; $i >= 0; $i--) {
            $mIdx = $curMonth - $i;
            if ($mIdx <= 0) $mIdx += 12;
            $labels[] = $monthNames[$mIdx - 1];
            $salesData[] = 0;
            $ordersCountData[] = 0;
        }

        $realOrders = Database::fetchAll("SELECT created_at, payable_amount, payment_status FROM `orders` ORDER BY id ASC");
        if (!empty($realOrders)) {
            foreach ($realOrders as $ro) {
                $ts = strtotime($ro['created_at']);
                $j = \App\Core\Jalali::toJalali($ts);
                $mName = $monthNames[$j['m'] - 1];
                $idx = array_search($mName, $labels, true);
                if ($idx !== false) {
                    $ordersCountData[$idx]++;
                    if (($ro['payment_status'] ?? '') === 'paid') {
                        $salesData[$idx] += round(((float)$ro['payable_amount']) / 1000000, 2);
                    }
                }
            }
        }

        $salesChart = [
            'labels' => $labels,
            'sales'  => $salesData,
            'counts' => $ordersCountData
        ];

        // 2. Real Brands Distribution from Database
        $brandRows = Database::fetchAll(
            "SELECT b.name, COUNT(p.id) as cnt 
             FROM `brands` b 
             LEFT JOIN `products` p ON p.brand_id = b.id 
             GROUP BY b.id, b.name 
             ORDER BY cnt DESC"
        );
        $brandLabels = [];
        $brandCounts = [];
        $palette = ['#EF5C16', '#011C39', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];
        
        foreach ($brandRows as $br) {
            $brandLabels[] = $br['name'];
            $brandCounts[] = (int) $br['cnt'];
        }

        if (empty($brandLabels)) {
            $brandLabels = ['ایران رادیاتور', 'بوتان'];
            $brandCounts = [0, 0];
        }

        $brandsChart = [
            'labels' => $brandLabels,
            'data'   => $brandCounts,
            'colors' => array_slice($palette, 0, count($brandLabels))
        ];

        $this->view('admin/dashboard', [
            'pageTitle'    => 'داشبورد مدیریت CMS ترموبان',
            'stats'        => $stats,
            'recentOrders' => $recentOrders,
            'recentLeads'  => $recentLeads,
            'salesChart'   => $salesChart,
            'brandsChart'  => $brandsChart,
            'activeNav'    => 'dashboard'
        ], 'layouts/admin');
    }

    /**
     * Manage Products (List, Create, Delete)
     */
    public function products(): void {
        $this->requireModule('module_shop', 'فروشگاه آنلاین و محصولات');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'create';

            if ($action === 'create') {
                $image = $this->handleUpload('image_file', 'uploads/products');
                if (!$image) {
                    $image = trim($_POST['image'] ?? '') ?: 'package-thumb.png';
                }

                $gallery = [];
                if (!empty($_POST['gallery']) && is_array($_POST['gallery'])) {
                    $gallery = array_values(array_filter($_POST['gallery']));
                }
                if (!empty($_FILES['gallery_files']['name']) && is_array($_FILES['gallery_files']['name'])) {
                    $destDir = ROOT_PATH . '/public/uploads/products';
                    if (!is_dir($destDir)) @mkdir($destDir, 0755, true);
                    foreach ($_FILES['gallery_files']['name'] as $idx => $fname) {
                        if ($_FILES['gallery_files']['error'][$idx] === UPLOAD_ERR_OK) {
                            $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
                                $newName = time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                                if (move_uploaded_file($_FILES['gallery_files']['tmp_name'][$idx], $destDir . '/' . $newName)) {
                                    $gallery[] = 'uploads/products/' . $newName;
                                }
                            }
                        }
                    }
                }

                $features = [];
                if (!empty($_POST['features']) && is_array($_POST['features'])) {
                    $features = array_values(array_filter(array_map('trim', $_POST['features'])));
                } elseif (!empty($_POST['features']) && is_string($_POST['features'])) {
                    $features = array_values(array_filter(array_map('trim', explode("\n", $_POST['features']))));
                }

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
                    'image'          => $image,
                    'gallery'        => !empty($gallery) ? json_encode($gallery, JSON_UNESCAPED_UNICODE) : null,
                    'description'    => trim($_POST['description']),
                    'features'       => json_encode($features, JSON_UNESCAPED_UNICODE),
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
        $this->requireModule('module_shop', 'فروشگاه آنلاین و محصولات');
        $product = Product::find($id);
        if (!$product) {
            $_SESSION['admin_error'] = 'محصول یافت نشد.';
            $this->redirect('admin/products');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = $this->handleUpload('image_file', 'uploads/products');
            if (!$image) {
                $image = trim($_POST['image'] ?? '') ?: ($product['image'] ?? 'package-thumb.png');
            }

            $gallery = [];
            if (!empty($_POST['existing_gallery']) && is_array($_POST['existing_gallery'])) {
                $gallery = array_values(array_filter($_POST['existing_gallery']));
            } elseif (!empty($product['gallery'])) {
                $decoded = json_decode($product['gallery'], true);
                if (is_array($decoded) && empty($_POST['gallery_cleared'])) {
                    $gallery = $decoded;
                }
            }
            if (!empty($_FILES['gallery_files']['name']) && is_array($_FILES['gallery_files']['name'])) {
                $destDir = ROOT_PATH . '/public/uploads/products';
                if (!is_dir($destDir)) @mkdir($destDir, 0755, true);
                foreach ($_FILES['gallery_files']['name'] as $idx => $fname) {
                    if ($_FILES['gallery_files']['error'][$idx] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
                            $newName = time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                            if (move_uploaded_file($_FILES['gallery_files']['tmp_name'][$idx], $destDir . '/' . $newName)) {
                                $gallery[] = 'uploads/products/' . $newName;
                            }
                        }
                    }
                }
            }

            $features = [];
            if (!empty($_POST['features']) && is_array($_POST['features'])) {
                $features = array_values(array_filter(array_map('trim', $_POST['features'])));
            } elseif (!empty($_POST['features']) && is_string($_POST['features'])) {
                $features = array_values(array_filter(array_map('trim', explode("\n", $_POST['features']))));
            }

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
                'image'          => $image,
                'gallery'        => !empty($gallery) ? json_encode($gallery, JSON_UNESCAPED_UNICODE) : null,
                'description'    => trim($_POST['description']),
                'features'       => json_encode($features, JSON_UNESCAPED_UNICODE),
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
        $this->requireModule('module_shop', 'فروشگاه آنلاین و سفارش‌ها');
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
        $this->requireModule('module_tickets', 'تیکت‌های پشتیبانی');
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
        $this->requireModule('module_blog', 'مقالات و آموزش‌ها');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'create';

            if ($action === 'create') {
                $image = $this->handleUpload('image_file', 'uploads/media');
                if (!$image) {
                    $image = trim($_POST['image'] ?: 'hero-technician.jpg');
                }

                $catTitle = trim($_POST['category'] ?? 'راهنمای پکیج') ?: 'راهنمای پکیج';
                $catSlug = trim($_POST['category_slug'] ?? '') ?: 'guide';

                Article::create([
                    'title'            => trim($_POST['title']),
                    'slug'             => trim($_POST['slug'] ?? ('article-' . time())),
                    'category'         => $catTitle,
                    'category_slug'    => $catSlug,
                    'excerpt'          => trim($_POST['excerpt']),
                    'content'          => trim($_POST['content']),
                    'image'            => $image,
                    'author_name'      => trim($_POST['author_name'] ?? 'تیم فنی ترموبان') ?: 'تیم فنی ترموبان',
                    'reading_time'     => trim($_POST['reading_time'] ?: '۵ دقیقه'),
                    'is_published'     => 1,
                    'meta_title'       => trim($_POST['meta_title'] ?? '') ?: null,
                    'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
                    'focus_keyword'    => trim($_POST['focus_keyword'] ?? '') ?: null,
                    'canonical_url'    => trim($_POST['canonical_url'] ?? '') ?: null
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
        $this->requireModule('module_blog', 'مقالات و آموزش‌ها');
        $article = Article::find($id);
        if (!$article) {
            $_SESSION['admin_error'] = 'مقاله یافت نشد.';
            $this->redirect('admin/articles');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = $this->handleUpload('image_file', 'uploads/media');
            if (!$image) {
                $image = trim($_POST['image'] ?? '') ?: ($article['image'] ?? 'hero-technician.jpg');
            }

            $catTitle = trim($_POST['category'] ?? ($article['category'] ?? 'راهنمای پکیج')) ?: 'راهنمای پکیج';
            $catSlug = trim($_POST['category_slug'] ?? ($article['category_slug'] ?? 'guide')) ?: 'guide';

            Article::update($id, [
                'title'            => trim($_POST['title']),
                'slug'             => trim($_POST['slug']),
                'category'         => $catTitle,
                'category_slug'    => $catSlug,
                'excerpt'          => trim($_POST['excerpt']),
                'content'          => trim($_POST['content']),
                'image'            => $image,
                'author_name'      => trim($_POST['author_name'] ?? ($article['author_name'] ?? 'تیم فنی ترموبان')) ?: 'تیم فنی ترموبان',
                'reading_time'     => trim($_POST['reading_time'] ?: '۵ دقیقه'),
                'is_published'     => isset($_POST['is_published']) ? 1 : 0,
                'meta_title'       => trim($_POST['meta_title'] ?? '') ?: null,
                'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
                'focus_keyword'    => trim($_POST['focus_keyword'] ?? '') ?: null,
                'canonical_url'    => trim($_POST['canonical_url'] ?? '') ?: null
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
                'maintenance_phone'   => trim($_POST['maintenance_phone'] ?? '۰۲۱-۷۷۴۲۸۵۹۶'),
                'module_chat'         => isset($_POST['module_chat']) ? '1' : '0',
                'chat_provider'       => in_array($_POST['chat_provider'] ?? '', ['goftino', 'raychat'], true) ? $_POST['chat_provider'] : 'goftino',
                'chat_goftino_key'    => trim($_POST['chat_goftino_key'] ?? ''),
                'chat_raychat_key'    => trim($_POST['chat_raychat_key'] ?? '')
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
        $this->requireModule('module_gateways', 'درگاه‌های پرداخت آنلاین');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireSuperAdmin();
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
        $this->requireModule('module_sms', 'سامانه پیامک');
        $testResult = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireSuperAdmin();
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
    /**
     * Manage Users (CRUD, Roles, Block/Active) - Strict Super Admin Immutability
     */
    public function users(): void {
        \App\Services\DatabaseMigrationService::ensureTable('user_addresses');
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

                if ($role === 'super_admin' && !Auth::isSuperAdmin()) {
                    $role = 'user';
                }

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

                $target = User::find($userId);
                if (!$target) {
                    $_SESSION['admin_error'] = 'کاربر مورد نظر یافت نشد.';
                } elseif ($target['role'] === 'super_admin' && !Auth::isSuperAdmin()) {
                    $_SESSION['admin_error'] = 'دسترسی غیرمجاز: مدیر سیستم اجازه تغییر نقش مدیر ارشد (سوپر ادمین) را ندارد.';
                } elseif ($role === 'super_admin' && !Auth::isSuperAdmin()) {
                    $_SESSION['admin_error'] = 'دسترسی غیرمجاز: تنها سوپر ادمین می‌تواند نقش سوپر ادمین را تعیین یا اعطا کند.';
                } else {
                    User::update($userId, [
                        'role'   => $role,
                        'status' => $status
                    ]);
                    $_SESSION['admin_success'] = 'نقش و وضعیت کاربر بروزرسانی شد.';
                }
            } elseif ($action === 'update_info') {
                $userId = (int) $_POST['user_id'];
                $target = User::find($userId);
                if (!$target) {
                    $_SESSION['admin_error'] = 'کاربر مورد نظر یافت نشد.';
                } elseif ($target['role'] === 'super_admin' && !Auth::isSuperAdmin()) {
                    $_SESSION['admin_error'] = 'دسترسی غیرمجاز: مدیر سیستم اجازه ویرایش اطلاعات مدیر ارشد (سوپر ادمین) را ندارد.';
                } else {
                    $name = trim($_POST['name'] ?? $target['name']);
                    $phone = trim($_POST['phone'] ?? $target['phone']);
                    $nationalId = trim($_POST['national_id'] ?? ($target['national_id'] ?? ''));
                    $email = trim($_POST['email'] ?? ($target['email'] ?? ''));
                    $role = $_POST['role'] ?? $target['role'];
                    $status = $_POST['status'] ?? $target['status'];

                    if ($role === 'super_admin' && !Auth::isSuperAdmin()) {
                        $role = $target['role'];
                    }

                    User::update($userId, [
                        'name'        => $name,
                        'phone'       => $phone,
                        'national_id' => $nationalId ?: null,
                        'email'       => $email ?: null,
                        'role'        => $role,
                        'status'      => $status
                    ]);
                    $_SESSION['admin_success'] = "اطلاعات کاربر {$name} با موفقیت ویرایش شد.";
                }
            } elseif ($action === 'reset_password') {
                $userId = (int) $_POST['user_id'];
                $target = User::find($userId);
                if (!$target) {
                    $_SESSION['admin_error'] = 'کاربر مورد نظر یافت نشد.';
                } elseif ($target['role'] === 'super_admin' && !Auth::isSuperAdmin()) {
                    $_SESSION['admin_error'] = 'دسترسی غیرمجاز: مدیر سیستم اجازه تغییر کلمه عبور مدیر ارشد (سوپر ادمین) را ندارد.';
                } else {
                    $newPassword = $_POST['new_password'] ?? '';
                    if (!empty($newPassword) && strlen($newPassword) >= 6) {
                        User::update($userId, [
                            'password_hash' => Auth::hashPassword($newPassword)
                        ]);
                        $_SESSION['admin_success'] = 'کلمه عبور کاربر با موفقیت تغییر یافت.';
                    } else {
                        $_SESSION['admin_error'] = 'کلمه عبور باید حداقل ۶ رقم باشد.';
                    }
                }
            } elseif ($action === 'delete') {
                $userId = (int) $_POST['user_id'];
                $target = User::find($userId);
                if (!$target) {
                    $_SESSION['admin_error'] = 'کاربر مورد نظر یافت نشد.';
                } elseif ($userId === Auth::id()) {
                    $_SESSION['admin_error'] = 'امکان حذف حساب کاربری جاری خودتان وجود ندارد.';
                } elseif ($target['role'] === 'super_admin') {
                    $_SESSION['admin_error'] = 'دسترسی غیرمجاز: حساب مدیر ارشد (سوپر ادمین) تحت هیچ شرایطی قابل حذف نمی‌باشد.';
                } else {
                    User::delete($userId);
                    $_SESSION['admin_success'] = 'کاربر با موفقیت حذف گردید.';
                }
            }

            $this->redirect('admin/users');
            return;
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

        // Attach saved addresses to each user
        $addresses = Database::fetchAll("SELECT * FROM `user_addresses` ORDER BY `is_default` DESC, `id` DESC");
        $userAddressesMap = [];
        foreach ($addresses as $addr) {
            $userAddressesMap[$addr['user_id']][] = $addr;
        }

        foreach ($users as &$u) {
            $u['addresses'] = $userAddressesMap[$u['id']] ?? [];
            $u['addresses_count'] = count($u['addresses']);
            $u['default_address'] = !empty($u['addresses']) ? $u['addresses'][0] : null;
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
     * User Addresses Management
     */
    public function addresses(): void {
        \App\Services\DatabaseMigrationService::ensureTable('user_addresses');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['address_id'] ?? 0);

            if ($action === 'delete') {
                Database::query("DELETE FROM `user_addresses` WHERE `id` = :id", ['id' => $id]);
                $_SESSION['admin_success'] = 'آدرس با موفقیت حذف گردید.';
            } elseif ($action === 'set_default') {
                $addr = Database::fetchOne("SELECT * FROM `user_addresses` WHERE `id` = :id", ['id' => $id]);
                if ($addr) {
                    Database::query("UPDATE `user_addresses` SET `is_default` = 0 WHERE `user_id` = :uid", ['uid' => $addr['user_id']]);
                    Database::query("UPDATE `user_addresses` SET `is_default` = 1 WHERE `id` = :id", ['id' => $id]);
                    $_SESSION['admin_success'] = 'آدرس به عنوان پیش‌فرض این کاربر تنظیم شد.';
                }
            }

            $this->redirect('admin/addresses');
        }

        $search = trim($_GET['q'] ?? '');
        $userIdFilter = !empty($_GET['user_id']) ? (int) $_GET['user_id'] : null;

        $addresses = \App\Models\UserAddress::getAllWithUser($search, $userIdFilter);
        $users = User::all('name ASC');

        $this->view('admin/addresses', [
            'pageTitle'    => 'مدیریت آدرس‌های ذخیره شده کاربران | CMS ترموبان',
            'addresses'    => $addresses,
            'users'        => $users,
            'search'       => $search,
            'userIdFilter' => $userIdFilter,
            'activeNav'    => 'addresses',
            'success'      => $_SESSION['admin_success'] ?? null,
            'error'        => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Official Approved Service Tariffs (نرخ مصوب اتحادیه)
     */
    public function tariffs(): void {
        \App\Services\DatabaseMigrationService::ensureTable('service_tariffs');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'update';

            if ($action === 'create') {
                $key = trim($_POST['service_key'] ?? '') ?: ('srv_' . time());
                $title = trim($_POST['title'] ?? '');
                $subtitle = trim($_POST['subtitle'] ?? '');
                $price = trim($_POST['price'] ?? '');
                $guarantee = trim($_POST['guarantee'] ?? '');
                $sort = (int) ($_POST['sort_order'] ?? 0);
                $isActive = isset($_POST['is_active']) ? 1 : 0;

                $featuresRaw = trim($_POST['features'] ?? '');
                $featuresLines = array_values(array_filter(array_map('trim', explode("\n", $featuresRaw))));
                $featuresJson = json_encode($featuresLines, JSON_UNESCAPED_UNICODE);

                if (!empty($title) && !empty($price)) {
                    \App\Models\ServiceTariff::create([
                        'service_key' => $key,
                        'title'       => $title,
                        'subtitle'    => $subtitle ?: null,
                        'price'       => $price,
                        'guarantee'   => $guarantee,
                        'features'    => $featuresJson,
                        'sort_order'  => $sort,
                        'is_active'   => $isActive
                    ]);
                    $_SESSION['admin_success'] = "تعرفه «{$title}» با موفقیت افزوده شد.";
                } else {
                    $_SESSION['admin_error'] = 'عنوان خدمت و نرخ مصوب الزامی هستند.';
                }
            } elseif ($action === 'update') {
                $id = (int) ($_POST['tariff_id'] ?? 0);
                $tariff = \App\Models\ServiceTariff::find($id);
                if ($tariff) {
                    $title = trim($_POST['title'] ?? $tariff['title']);
                    $subtitle = trim($_POST['subtitle'] ?? $tariff['subtitle']);
                    $price = trim($_POST['price'] ?? $tariff['price']);
                    $guarantee = trim($_POST['guarantee'] ?? $tariff['guarantee']);
                    $sort = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : $tariff['sort_order'];
                    $isActive = isset($_POST['is_active']) ? 1 : 0;

                    $featuresRaw = trim($_POST['features'] ?? '');
                    $featuresLines = array_values(array_filter(array_map('trim', explode("\n", $featuresRaw))));
                    $featuresJson = json_encode($featuresLines, JSON_UNESCAPED_UNICODE);

                    \App\Models\ServiceTariff::update($id, [
                        'title'      => $title,
                        'subtitle'   => $subtitle ?: null,
                        'price'      => $price,
                        'guarantee'  => $guarantee,
                        'features'   => $featuresJson,
                        'sort_order' => $sort,
                        'is_active'  => $isActive
                    ]);
                    $_SESSION['admin_success'] = "نرخ مصوب «{$title}» با موفقیت بروزرسانی شد.";
                }
            } elseif ($action === 'toggle_status') {
                $id = (int) ($_POST['tariff_id'] ?? 0);
                $tariff = \App\Models\ServiceTariff::find($id);
                if ($tariff) {
                    $newStatus = ($tariff['is_active'] ?? 1) ? 0 : 1;
                    \App\Models\ServiceTariff::update($id, ['is_active' => $newStatus]);
                    $_SESSION['admin_success'] = 'وضعیت نمایش تعرفه تغییر یافت.';
                }
            } elseif ($action === 'delete') {
                $id = (int) ($_POST['tariff_id'] ?? 0);
                \App\Models\ServiceTariff::delete($id);
                $_SESSION['admin_success'] = 'تعرفه با موفقیت حذف گردید.';
            }

            $this->redirect('admin/tariffs');
        }

        $tariffs = Database::fetchAll("SELECT * FROM `service_tariffs` ORDER BY `sort_order` ASC, `id` ASC");
        foreach ($tariffs as &$t) {
            $dec = json_decode($t['features'] ?? '', true);
            $t['features_list'] = is_array($dec) ? $dec : [];
        }

        $this->view('admin/tariffs', [
            'pageTitle' => 'مدیریت نرخ‌های مصوب اتحادیه و تعرفه خدمات | CMS ترموبان',
            'tariffs'   => $tariffs,
            'activeNav' => 'tariffs',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Comprehensive Service Requests & Technician Dispatch Management
     */
    public function serviceRequests(): void {
        \App\Services\DatabaseMigrationService::ensureTable('service_requests');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['request_id'] ?? 0);

            if ($action === 'update_status') {
                $status = $_POST['status'] ?? 'pending';
                $techId = !empty($_POST['technician_id']) ? (int)$_POST['technician_id'] : null;
                $techName = null;
                if ($techId) {
                    $tech = User::find($techId);
                    $techName = $tech['name'] ?? null;
                }

                $adminNotes = trim($_POST['admin_notes'] ?? '');
                $estCost = !empty($_POST['estimated_cost']) ? (int)$_POST['estimated_cost'] : null;
                $finCost = !empty($_POST['final_cost']) ? (int)$_POST['final_cost'] : null;

                $updateData = [
                    'status'         => $status,
                    'technician_id'  => $techId,
                    'technician_name'=> $techName,
                    'admin_notes'    => $adminNotes ?: null,
                ];
                if ($estCost !== null) $updateData['estimated_cost'] = $estCost;
                if ($finCost !== null) $updateData['final_cost'] = $finCost;

                Database::update('service_requests', $updateData, '`id` = :id', ['id' => $id]);
                $_SESSION['admin_success'] = 'وضعیت سفارش و اعزام تکنسین با موفقیت بروزرسانی شد.';
            } elseif ($action === 'delete') {
                $req = Database::fetchOne("SELECT * FROM `service_requests` WHERE `id` = :id", ['id' => $id]);
                if ($req) {
                    // Remove attached video if exists
                    if (!empty($req['video_path'])) {
                        $vPath = ROOT_PATH . '/public/' . ltrim($req['video_path'], '/');
                        if (file_exists($vPath)) @unlink($vPath);
                    }
                    // Remove attached photos
                    if (!empty($req['photos'])) {
                        $photos = json_decode($req['photos'], true);
                        if (is_array($photos)) {
                            foreach ($photos as $p) {
                                $pPath = ROOT_PATH . '/public/' . ltrim($p, '/');
                                if (file_exists($pPath)) @unlink($pPath);
                            }
                        }
                    }
                    Database::query("DELETE FROM `service_requests` WHERE `id` = :id", ['id' => $id]);
                    $_SESSION['admin_success'] = 'درخواست خدمات و فایل‌های پیوست آن حذف گردید.';
                }
            }

            $this->redirect('admin/service-requests');
        }

        $statusFilter = trim($_GET['status'] ?? '');
        $search = trim($_GET['q'] ?? '');

        $requests = \App\Models\ServiceRequest::getAllWithDetails($statusFilter, $search);
        $technicians = Database::fetchAll("SELECT * FROM `users` WHERE `role` IN ('technician', 'admin', 'super_admin') AND `status` = 'active' ORDER BY `name` ASC");

        $this->view('admin/service-requests', [
            'pageTitle'    => 'مدیریت سفارشات خدمات و اعزام تکنسین | CMS ترموبان',
            'requests'     => $requests,
            'technicians'  => $technicians,
            'statusFilter' => $statusFilter,
            'search'       => $search,
            'activeNav'    => 'service-requests',
            'success'      => $_SESSION['admin_success'] ?? null,
            'error'        => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Run all pending database migrations (Super Admin only)
     */
    public function migrateDb(): void {
        $this->requireSuperAdmin();
        try {
            $executed = \App\Services\DatabaseMigrationService::run();
            $_SESSION['admin_success'] = 'همگام‌سازی و ارتقای ساختار پایگاه داده با موفقیت انجام شد: ' . implode('، ', $executed);
        } catch (\Throwable $e) {
            $_SESSION['admin_error'] = 'خطا در ارتقای دیتابیس: ' . $e->getMessage();
        }
        $this->redirect('admin/settings');
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
        $this->requireSuperAdmin();
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
        $this->requireSuperAdmin();
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
        $this->requireModule('module_analytics', 'آمار بازدیدکنندگان');
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

    /**
     * Brand Management under coverage
     */
    public function brands(): void {
        $this->requireModule('module_shop', 'برندهای تحت پوشش فروشگاه');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'create';

            if ($action === 'create') {
                $name = trim($_POST['name'] ?? '');
                $slug = trim($_POST['slug'] ?? '') ?: ('brand-' . time());
                $badge = trim($_POST['badge'] ?? '') ?: null;
                $description = trim($_POST['description'] ?? '') ?: null;
                $isActive = isset($_POST['is_active']) ? 1 : 0;

                $logo = $this->handleUpload('logo_file', 'uploads/brands');
                if (!$logo) {
                    $logo = trim($_POST['logo'] ?? '') ?: 'brand-iranradiator-logo.png';
                }

                if (!empty($name)) {
                    \App\Models\Brand::create([
                        'name'        => $name,
                        'slug'        => $slug,
                        'logo'        => $logo,
                        'badge'       => $badge,
                        'description' => $description,
                        'is_active'   => $isActive
                    ]);
                    $_SESSION['admin_success'] = "برند «{$name}» با موفقیت افزوده شد.";
                } else {
                    $_SESSION['admin_error'] = 'نام برند الزامی است.';
                }
            } elseif ($action === 'update') {
                $id = (int) ($_POST['brand_id'] ?? 0);
                $brand = \App\Models\Brand::find($id);
                if ($brand) {
                    $name = trim($_POST['name'] ?? $brand['name']);
                    $slug = trim($_POST['slug'] ?? $brand['slug']);
                    $badge = trim($_POST['badge'] ?? '') ?: null;
                    $description = trim($_POST['description'] ?? '') ?: null;
                    $isActive = isset($_POST['is_active']) ? 1 : 0;

                    $logo = $this->handleUpload('logo_file', 'uploads/brands');
                    if (!$logo) {
                        $logo = trim($_POST['logo'] ?? '') ?: $brand['logo'];
                    }

                    \App\Models\Brand::update($id, [
                        'name'        => $name,
                        'slug'        => $slug,
                        'logo'        => $logo,
                        'badge'       => $badge,
                        'description' => $description,
                        'is_active'   => $isActive
                    ]);
                    $_SESSION['admin_success'] = "اطلاعات برند «{$name}» بروزرسانی گردید.";
                }
            } elseif ($action === 'toggle_status') {
                $id = (int) ($_POST['brand_id'] ?? 0);
                $brand = \App\Models\Brand::find($id);
                if ($brand) {
                    $newStatus = ($brand['is_active'] ?? 1) ? 0 : 1;
                    \App\Models\Brand::update($id, ['is_active' => $newStatus]);
                    $_SESSION['admin_success'] = 'وضعیت نمایش برند تغییر یافت.';
                }
            } elseif ($action === 'delete') {
                $id = (int) ($_POST['brand_id'] ?? 0);
                \App\Models\Brand::delete($id);
                $_SESSION['admin_success'] = 'برند با موفقیت حذف گردید.';
            }

            $this->redirect('admin/brands');
        }

        $brands = \App\Models\Brand::all('id DESC');

        $this->view('admin/brands', [
            'pageTitle' => 'مدیریت برندهای تحت پوشش | CMS ترموبان',
            'brands'    => $brands,
            'activeNav' => 'brands',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Media Manager Library
     */
    public function media(): void {
        if (isset($_GET['format']) && $_GET['format'] === 'json') {
            $mediaItems = \App\Models\Media::getRecent(60);
            foreach ($mediaItems as &$item) {
                $item['url'] = upload_url($item['file_path']);
            }
            $this->json(['success' => true, 'media' => $mediaItems]);
            return;
        }

        $mediaList = \App\Models\Media::all('id DESC');

        $this->view('admin/media', [
            'pageTitle' => 'کتابخانه پرونده‌های چندرسانه‌ای و فایل منیجر | CMS ترموبان',
            'mediaList' => $mediaList,
            'activeNav' => 'media',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * AJAX/Form Upload to Media Manager
     */
    public function uploadMedia(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/media');
        }

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_POST['ajax']);

        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'فایلی برای آپلود انتخاب نشده یا خطایی رخ داده است.'], 400);
                return;
            }
            $_SESSION['admin_error'] = 'فایلی برای آپلود انتخاب نشده است.';
            $this->redirect('admin/media');
        }

        $file = $_FILES['file'];
        $origName = basename($file['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'pdf'];

        if (!in_array($ext, $allowed, true)) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'فرمت فایل مجاز نیست. فرمت‌های مجاز: ' . implode(', ', $allowed)], 400);
                return;
            }
            $_SESSION['admin_error'] = 'فرمت فایل انتخابی مجاز نمی‌باشد.';
            $this->redirect('admin/media');
        }

        $newFilename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destDir = ROOT_PATH . '/public/uploads/media';
        if (!is_dir($destDir)) @mkdir($destDir, 0755, true);
        $destPath = $destDir . '/' . $newFilename;
        $relPath = 'uploads/media/' . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            $mediaId = \App\Models\Media::create([
                'filename'      => $newFilename,
                'original_name' => $origName,
                'file_path'     => $relPath,
                'file_size'     => (int) $file['size'],
                'mime_type'     => $file['type'] ?? 'image/' . $ext,
                'uploader_id'   => Auth::id()
            ]);

            if ($isAjax) {
                $this->json([
                    'success'  => true,
                    'message'  => 'فایل با موفقیت آپلود شد.',
                    'media'    => [
                        'id'            => $mediaId,
                        'filename'      => $newFilename,
                        'original_name' => $origName,
                        'file_path'     => $relPath,
                        'url'           => upload_url($relPath)
                    ]
                ]);
                return;
            }

            $_SESSION['admin_success'] = 'فایل با موفقیت در کتابخانه چندرسانه‌ای ذخیره شد.';
        } else {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'خطا در ذخیره‌سازی فایل در سرور.'], 500);
                return;
            }
            $_SESSION['admin_error'] = 'خطا در آپلود فایل روی سرور.';
        }

        $this->redirect('admin/media');
    }

    /**
     * Delete Media item
     */
    public function deleteMedia(): void {
        $id = (int) ($_POST['media_id'] ?? 0);
        $media = \App\Models\Media::find($id);

        if ($media) {
            $filePath = ROOT_PATH . '/public/' . ltrim($media['file_path'], '/');
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            \App\Models\Media::delete($id);
            $_SESSION['admin_success'] = 'پرونده با موفقیت حذف گردید.';
        }

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_POST['ajax']);
        if ($isAjax) {
            $this->json(['success' => true]);
            return;
        }

        $this->redirect('admin/media');
    }

    /**
     * Admin Profile Settings
     */
    public function profile(): void {
        $user = Auth::user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? $user['name']);
            $email = trim($_POST['email'] ?? $user['email']);
            $phone = trim($_POST['phone'] ?? $user['phone']);
            $nationalId = trim($_POST['national_id'] ?? ($user['national_id'] ?? ''));

            $updateData = [
                'name'        => $name,
                'email'       => $email ?: null,
                'phone'       => $phone,
                'national_id' => $nationalId ?: null,
            ];

            $avatar = $this->handleUpload('avatar_file', 'uploads/avatars');
            if ($avatar) {
                $updateData['avatar'] = $avatar;
            }

            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (!empty($newPassword)) {
                if (strlen($newPassword) < 6) {
                    $_SESSION['admin_error'] = 'رمز عبور جدید باید حداقل ۶ کاراکتر باشد.';
                    $this->redirect('admin/profile');
                }
                if ($newPassword !== $confirmPassword) {
                    $_SESSION['admin_error'] = 'تکرار کلمه عبور جدید مطابقت ندارد.';
                    $this->redirect('admin/profile');
                }
                if (!password_verify($currentPassword, $user['password_hash'] ?? '')) {
                    $_SESSION['admin_error'] = 'کلمه عبور فعلی نادرست است.';
                    $this->redirect('admin/profile');
                }
                $updateData['password_hash'] = Auth::hashPassword($newPassword);
            }

            User::update($user['id'], $updateData);
            $_SESSION['user'] = User::find($user['id']);

            $_SESSION['admin_success'] = 'مشخصات حساب کاربری مدیریت با موفقیت بروزرسانی شد.';
            $this->redirect('admin/profile');
        }

        $this->view('admin/profile', [
            'pageTitle' => 'ویرایش مشخصات و پروفایل مدیر سامانه | CMS ترموبان',
            'user'      => $user,
            'activeNav' => 'profile',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Technician Dispatch Schedule & Calendar View
     */
    public function schedule(): void {
        \App\Services\DatabaseMigrationService::ensureTable('service_requests');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['request_id'] ?? 0);

            if ($action === 'update_dispatch' && $id > 0) {
                $status = $_POST['status'] ?? 'scheduled';
                $techId = !empty($_POST['technician_id']) ? (int) $_POST['technician_id'] : null;
                $preferredDate = trim($_POST['preferred_date'] ?? '');
                $timeSlot = trim($_POST['time_slot'] ?? '');
                $techNotes = trim($_POST['technician_notes'] ?? '');

                Database::query(
                    "UPDATE `service_requests` 
                     SET `status` = :st, `technician_id` = :tech, `preferred_date` = :pdate, `time_slot` = :ts, `technician_notes` = :notes, `updated_at` = NOW() 
                     WHERE `id` = :id",
                    [
                        'st'    => $status,
                        'tech'  => $techId,
                        'pdate' => $preferredDate,
                        'ts'    => $timeSlot,
                        'notes' => $techNotes,
                        'id'    => $id
                    ]
                );

                $_SESSION['admin_success'] = 'وضعیت نوبت و تکنسین اعزامی بروزرسانی شد.';
            }

            $this->redirect('admin/schedule');
        }

        $technicians = Database::fetchAll("SELECT id, name, phone, role FROM `users` WHERE `role` IN ('technician', 'admin', 'super_admin') AND `status` = 'active' ORDER BY `name` ASC");

        $techFilter = !empty($_GET['technician_id']) ? (int) $_GET['technician_id'] : null;
        $statusFilter = trim($_GET['status'] ?? '');
        $dateFilter = trim($_GET['date'] ?? '');

        $where = ["1=1"];
        $params = [];

        if ($techFilter) {
            $where[] = "sr.technician_id = :tech";
            $params['tech'] = $techFilter;
        }

        if (!empty($statusFilter)) {
            $where[] = "sr.status = :status";
            $params['status'] = $statusFilter;
        }

        if (!empty($dateFilter)) {
            $where[] = "sr.preferred_date LIKE :dt";
            $params['dt'] = "%$dateFilter%";
        }

        $whereSql = implode(" AND ", $where);

        $requests = Database::fetchAll(
            "SELECT sr.*, u.name as customer_name, u.phone as customer_phone, t.name as technician_name, t.phone as technician_phone 
             FROM `service_requests` sr 
             LEFT JOIN `users` u ON sr.user_id = u.id 
             LEFT JOIN `users` t ON sr.technician_id = t.id 
             WHERE {$whereSql} 
             ORDER BY sr.preferred_date ASC, sr.created_at DESC",
            $params
        );

        $this->view('admin/schedule', [
            'pageTitle'    => 'تقویم کاری و زمان‌بندی اعزام تکنسین‌ها | CMS ترموبان',
            'requests'     => $requests,
            'technicians'  => $technicians,
            'techFilter'   => $techFilter,
            'statusFilter' => $statusFilter,
            'dateFilter'   => $dateFilter,
            'activeNav'    => 'schedule',
            'success'      => $_SESSION['admin_success'] ?? null,
            'error'        => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Menu & Mega Menu Manager
     */
    public function menus(): void {
        \App\Services\DatabaseMigrationService::ensureTable('menus');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['menu_id'] ?? 0);

            if ($action === 'create' || $action === 'update') {
                $title = trim($_POST['title'] ?? '');
                $url = trim($_POST['url'] ?? '#');
                $location = in_array($_POST['location'] ?? '', ['header', 'footer']) ? $_POST['location'] : 'header';
                $parentId = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
                $type = in_array($_POST['type'] ?? '', ['simple', 'dropdown', 'megamenu']) ? $_POST['type'] : 'simple';
                $icon = trim($_POST['icon'] ?? '');
                $badge = trim($_POST['badge'] ?? '');
                $target = trim($_POST['target'] ?? '_self');
                $sort = (int) ($_POST['sort_order'] ?? 0);
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                $megamenuData = trim($_POST['megamenu_data'] ?? '');

                if (!empty($megamenuData)) {
                    // Test json validity
                    $test = json_decode($megamenuData, true);
                    if ($test === null && json_last_error() !== JSON_ERROR_NONE) {
                        $megamenuData = null;
                    }
                } else {
                    $megamenuData = null;
                }

                $data = [
                    'title'         => $title,
                    'url'           => $url,
                    'location'      => $location,
                    'parent_id'     => $parentId,
                    'type'          => $type,
                    'icon'          => $icon ?: null,
                    'badge'         => $badge ?: null,
                    'target'        => $target ?: '_self',
                    'sort_order'    => $sort,
                    'is_active'     => $isActive,
                    'megamenu_data' => $megamenuData
                ];

                if (!empty($title)) {
                    if ($action === 'create') {
                        \App\Models\Menu::create($data);
                        $_SESSION['admin_success'] = 'آیتم منو با موفقیت ایجاد گردید.';
                    } else {
                        \App\Models\Menu::update($id, $data);
                        $_SESSION['admin_success'] = 'آیتم منو با موفقیت ویرایش گردید.';
                    }
                }
            } elseif ($action === 'delete' && $id > 0) {
                // Delete children first
                Database::query("DELETE FROM `menus` WHERE `parent_id` = :id", ['id' => $id]);
                \App\Models\Menu::delete($id);
                $_SESSION['admin_success'] = 'آیتم منو و زیرمجموعه‌های آن با موفقیت حذف گردید.';
            } elseif ($action === 'toggle' && $id > 0) {
                $cur = \App\Models\Menu::find($id);
                if ($cur) {
                    $newVal = ((int)$cur['is_active'] === 1) ? 0 : 1;
                    \App\Models\Menu::update($id, ['is_active' => $newVal]);
                    $_SESSION['admin_success'] = 'وضعیت انتشار آیتم منو تغییر یافت.';
                }
            }

            $this->redirect('admin/menus');
        }

        $locationFilter = in_array($_GET['location'] ?? '', ['header', 'footer']) ? $_GET['location'] : 'header';
        $items = \App\Models\Menu::getAllFlat($locationFilter);
        $parents = \App\Models\Menu::getParents($locationFilter);

        $this->view('admin/menus', [
            'pageTitle'      => 'مدیریت منوهای سایت و مگامنو | CMS ترموبان',
            'items'          => $items,
            'parents'        => $parents,
            'locationFilter' => $locationFilter,
            'activeNav'      => 'menus',
            'success'        => $_SESSION['admin_success'] ?? null,
            'error'          => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Frequently Asked Questions (FAQ) Manager
     */
    public function faqs(): void {
        \App\Services\DatabaseMigrationService::ensureTable('faqs');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['faq_id'] ?? 0);

            if ($action === 'create' || $action === 'update') {
                $section = trim($_POST['section'] ?? 'home');
                $targetId = !empty($_POST['target_id']) ? (int) $_POST['target_id'] : null;
                $question = trim($_POST['question'] ?? '');
                $answer = trim($_POST['answer'] ?? '');
                $sort = (int) ($_POST['sort_order'] ?? 0);
                $isActive = isset($_POST['is_active']) ? 1 : 0;

                $data = [
                    'section'   => $section,
                    'target_id' => $targetId,
                    'question'  => $question,
                    'answer'    => $answer,
                    'sort_order'=> $sort,
                    'is_active' => $isActive
                ];

                if (!empty($question) && !empty($answer)) {
                    if ($action === 'create') {
                        \App\Models\Faq::create($data);
                        $_SESSION['admin_success'] = 'پرسش و پاسخ جدید با موفقیت ذخیره شد.';
                    } else {
                        \App\Models\Faq::update($id, $data);
                        $_SESSION['admin_success'] = 'پرسش متداول با موفقیت ویرایش شد.';
                    }
                }
            } elseif ($action === 'delete' && $id > 0) {
                \App\Models\Faq::delete($id);
                $_SESSION['admin_success'] = 'پرسش متداول حذف گردید.';
            } elseif ($action === 'toggle' && $id > 0) {
                $faq = \App\Models\Faq::find($id);
                if ($faq) {
                    $newVal = ((int)$faq['is_active'] === 1) ? 0 : 1;
                    \App\Models\Faq::update($id, ['is_active' => $newVal]);
                    $_SESSION['admin_success'] = 'وضعیت نمایش سوال متداول بروز شد.';
                }
            }

            $this->redirect('admin/faqs');
        }

        $sectionFilter = trim($_GET['section'] ?? '');
        $where = "1=1";
        $params = [];
        if (!empty($sectionFilter)) {
            $where = "`section` = :sec";
            $params['sec'] = $sectionFilter;
        }

        $faqs = \App\Models\Faq::where($where, $params, "`section` ASC, `sort_order` ASC, `id` ASC");

        $this->view('admin/faqs', [
            'pageTitle'     => 'مدیریت سوالات متداول (FAQ) و اسکیما | CMS ترموبان',
            'faqs'          => $faqs,
            'sectionFilter' => $sectionFilter,
            'activeNav'     => 'faqs',
            'success'       => $_SESSION['admin_success'] ?? null,
            'error'         => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Customer Testimonials & Reviews Manager
     */
    public function testimonials(): void {
        \App\Services\DatabaseMigrationService::ensureTable('testimonials');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['testimonial_id'] ?? 0);

            if ($action === 'create' || $action === 'update') {
                $name = trim($_POST['customer_name'] ?? '');
                $city = trim($_POST['customer_city'] ?? '');
                $serviceType = trim($_POST['service_type'] ?? '');
                $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
                $content = trim($_POST['content'] ?? '');
                $avatar = trim($_POST['avatar'] ?? '');
                $sort = (int) ($_POST['sort_order'] ?? 0);
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

                $data = [
                    'customer_name' => $name,
                    'customer_city' => $city,
                    'service_type'  => $serviceType,
                    'rating'        => $rating,
                    'content'       => $content,
                    'avatar'        => $avatar ?: null,
                    'sort_order'    => $sort,
                    'is_active'     => $isActive,
                    'is_featured'   => $isFeatured
                ];

                if (!empty($name) && !empty($content)) {
                    if ($action === 'create') {
                        \App\Models\Testimonial::create($data);
                        $_SESSION['admin_success'] = 'نظر و رضایت مشتری با موفقیت افزوده شد.';
                    } else {
                        \App\Models\Testimonial::update($id, $data);
                        $_SESSION['admin_success'] = 'نظر مشتری با موفقیت ویرایش شد.';
                    }
                }
            } elseif ($action === 'delete' && $id > 0) {
                \App\Models\Testimonial::delete($id);
                $_SESSION['admin_success'] = 'نظر مشتری حذف شد.';
            } elseif ($action === 'toggle' && $id > 0) {
                $test = \App\Models\Testimonial::find($id);
                if ($test) {
                    $newVal = ((int)$test['is_active'] === 1) ? 0 : 1;
                    \App\Models\Testimonial::update($id, ['is_active' => $newVal]);
                    $_SESSION['admin_success'] = 'وضعیت نمایش نظر تغییر یافت.';
                }
            }

            $this->redirect('admin/testimonials');
        }

        $testimonials = \App\Models\Testimonial::all("`sort_order` ASC, `id` DESC");

        $this->view('admin/testimonials', [
            'pageTitle'    => 'مدیریت نظرات و رضایت مشتریان | CMS ترموبان',
            'testimonials' => $testimonials,
            'activeNav'    => 'testimonials',
            'success'      => $_SESSION['admin_success'] ?? null,
            'error'        => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Dealership Branches Manager
     */
    public function branches(): void {
        \App\Services\DatabaseMigrationService::ensureTable('branches');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['branch_id'] ?? 0);

            if ($action === 'create' || $action === 'update') {
                $name = trim($_POST['name'] ?? '');
                $city = trim($_POST['city'] ?? 'تهران');
                $district = trim($_POST['district'] ?? '');
                $address = trim($_POST['address'] ?? '');
                $phoneLandline = trim($_POST['phone_landline'] ?? '');
                $phoneMobile = trim($_POST['phone_mobile'] ?? '');
                $workingHours = trim($_POST['working_hours'] ?? '');
                $lat = !empty($_POST['lat']) ? trim($_POST['lat']) : null;
                $lng = !empty($_POST['lng']) ? trim($_POST['lng']) : null;
                $sort = (int) ($_POST['sort_order'] ?? 0);
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                $isMain = isset($_POST['is_main']) ? 1 : 0;

                $data = [
                    'name'           => $name,
                    'city'           => $city,
                    'district'       => $district,
                    'address'        => $address,
                    'phone_landline' => $phoneLandline ?: null,
                    'phone_mobile'   => $phoneMobile ?: null,
                    'working_hours'  => $workingHours ?: null,
                    'lat'            => $lat,
                    'lng'            => $lng,
                    'sort_order'     => $sort,
                    'is_active'      => $isActive,
                    'is_main'        => $isMain
                ];

                if (!empty($name) && !empty($district)) {
                    if ($action === 'create') {
                        \App\Models\Branch::create($data);
                        $_SESSION['admin_success'] = 'شعبه نمایندگی با موفقیت افزوده شد.';
                    } else {
                        \App\Models\Branch::update($id, $data);
                        $_SESSION['admin_success'] = 'مشخصات شعبه با موفقیت ویرایش شد.';
                    }
                }
            } elseif ($action === 'delete' && $id > 0) {
                \App\Models\Branch::delete($id);
                $_SESSION['admin_success'] = 'شعبه مورد نظر با موفقیت حذف شد.';
            } elseif ($action === 'toggle' && $id > 0) {
                $branch = \App\Models\Branch::find($id);
                if ($branch) {
                    $newVal = ((int)$branch['is_active'] === 1) ? 0 : 1;
                    \App\Models\Branch::update($id, ['is_active' => $newVal]);
                    $_SESSION['admin_success'] = 'وضعیت شعبه بروز شد.';
                }
            }

            $this->redirect('admin/branches');
        }

        $branches = \App\Models\Branch::all("`sort_order` ASC, `id` ASC");

        $this->view('admin/branches', [
            'pageTitle' => 'مدیریت شعب و دفاتر نمایندگی | CMS ترموبان',
            'branches'  => $branches,
            'activeNav' => 'branches',
            'success'   => $_SESSION['admin_success'] ?? null,
            'error'     => $_SESSION['admin_error'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
    }

    /**
     * Helper for single file upload
     */
    private function handleUpload(string $inputName, string $targetFolder = 'uploads/media'): ?string {
        if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$inputName];
        $origName = basename($file['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'pdf'];

        if (!in_array($ext, $allowed, true)) {
            return null;
        }

        $destDir = ROOT_PATH . '/public/' . trim($targetFolder, '/');
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }

        $newFilename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $destDir . '/' . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return trim($targetFolder, '/') . '/' . $newFilename;
        }

        return null;
    }
}
