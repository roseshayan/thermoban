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
            'total_revenue'     => Database::fetchOne("SELECT SUM(payable_amount) as total FROM `orders` WHERE `payment_status` = 'paid'")['total'] ?? 0
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
     * Manage Products
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
     * Manage Orders
     */
    public function orders(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int) ($_POST['order_id'] ?? 0);
            $orderStatus = $_POST['order_status'] ?? 'registered';
            $technician = trim($_POST['technician_name'] ?? '');

            Database::update('orders', [
                'order_status'    => $orderStatus,
                'technician_name' => $technician
            ], '`id` = :id', ['id' => $orderId]);

            $_SESSION['admin_success'] = 'وضعیت سفارش و تکنسین اعزامی بروزرسانی شد.';
            $this->redirect('admin/orders');
        }

        $orders = Database::fetchAll(
            "SELECT o.*, u.name as customer_name, u.phone as customer_phone, ua.district as customer_district 
             FROM `orders` o 
             LEFT JOIN `users` u ON o.user_id = u.id 
             LEFT JOIN `user_addresses` ua ON o.address_id = ua.id
             ORDER BY o.id DESC"
        );

        $this->view('admin/orders', [
            'pageTitle' => 'مدیریت سفارش‌ها و اعزام نصاب | CMS ترموبان',
            'orders'    => $orders,
            'activeNav' => 'orders',
            'success'   => $_SESSION['admin_success'] ?? null
        ], 'layouts/admin');
        unset($_SESSION['admin_success']);
    }

    /**
     * Manage Support Tickets
     */
    public function tickets(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = (int) ($_POST['ticket_id'] ?? 0);
            $replyMessage = trim($_POST['reply_message'] ?? '');
            $status = $_POST['status'] ?? 'answered';

            if (!empty($replyMessage)) {
                Ticket::addMessage($ticketId, 'admin', 'پشتیبانی فنی ترموبان', $replyMessage);
                Database::update('tickets', ['status' => $status], '`id` = :id', ['id' => $ticketId]);
                $_SESSION['admin_success'] = 'پاسخ تیکت با موفقیت ثبت و ارسال گردید.';
            }

            $this->redirect('admin/tickets');
        }

        $tickets = Database::fetchAll(
            "SELECT t.*, u.name as user_name, u.phone as user_phone 
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

        $this->view('admin/tickets', [
            'pageTitle' => 'تیکت‌های پشتیبانی و رزرو | CMS ترموبان',
            'tickets'   => $tickets,
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
     * Manage Articles
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
}
