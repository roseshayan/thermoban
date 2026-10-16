<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Database;
use App\Models\Product;

class ShopController extends Controller {

    /**
     * Products List / Shop Archive
     */
    public function index(): void {
        $brandSlug = trim($_GET['brand'] ?? '');
        $capacity = (int) ($_GET['capacity'] ?? 0);
        $sort = $_GET['sort'] ?? 'newest';

        $where = ["p.`is_active` = 1"];
        $params = [];

        if (!empty($brandSlug)) {
            $brand = Database::fetchOne("SELECT id FROM `brands` WHERE `slug` = :b", ['b' => $brandSlug]);
            if ($brand) {
                $where[] = "p.`brand_id` = :bid";
                $params['bid'] = $brand['id'];
            }
        }

        if ($capacity > 0) {
            $where[] = "p.`capacity` = :cap";
            $params['cap'] = $capacity;
        }

        $orderBy = match ($sort) {
            'price_asc'  => 'p.`price` ASC',
            'price_desc' => 'p.`price` DESC',
            default      => 'p.`id` DESC'
        };

        $whereSql = implode(' AND ', $where);
        $products = Database::fetchAll(
            "SELECT p.*, b.name as brand_name, b.slug as brand_slug, b.logo as brand_logo 
             FROM `products` p 
             LEFT JOIN `brands` b ON p.brand_id = b.id 
             WHERE {$whereSql} 
             ORDER BY {$orderBy}",
            $params
        );

        foreach ($products as &$p) {
            $p['brand'] = $p['brand_name'] ?? 'ایران رادیاتور';
            $p['category'] = 'پکیج شوفاژ دیواری';
            $p['brand_logo'] = !empty($p['brand_logo']) ? $p['brand_logo'] : 'logo-icon.png';
            $p['price_raw'] = (int) $p['price'];
            $p['price'] = number_format($p['price_raw']);
            $p['old_price'] = !empty($p['old_price']) ? number_format((int)$p['old_price']) : null;
            $p['in_stock'] = ($p['stock'] ?? 0) > 0;
            $p['rating'] = 4.8;
            $p['reviews_count'] = 24;
        }

        $brands = Database::fetchAll("SELECT * FROM `brands`");
        $brandList = [
            ['slug' => '', 'name' => 'همه برندها', 'count' => Product::count("`is_active` = 1")]
        ];
        foreach ($brands as $b) {
            $cnt = Product::count("`is_active` = 1 AND `brand_id` = " . (int)$b['id']);
            $brandList[] = [
                'slug'  => $b['slug'],
                'name'  => $b['name'],
                'count' => $cnt
            ];
        }

        $this->view('shop/index', [
            'pageTitle'        => 'فروشگاه پکیج شوفاژ دیواری | نمایندگی کد ۷۲۷۳ | ترموبان',
            'currentPage'      => 'shop',
            'csrfToken'        => Security::generateCsrf(),
            'products'         => $products,
            'brands'           => $brandList,
            'selectedBrand'    => $brandSlug,
            'selectedCapacity' => $capacity,
            'selectedSort'     => $sort,
            'totalCount'       => count($products)
        ]);
    }

    /**
     * Single Product Detail
     */
    public function detail(string $id = ''): void {
        $product = Database::fetchOne(
            "SELECT p.*, b.name as brand_name, b.slug as brand_slug, b.logo as brand_logo 
             FROM `products` p 
             LEFT JOIN `brands` b ON p.brand_id = b.id 
             WHERE (p.`id` = :id1 OR p.`slug` = :id2) AND p.`is_active` = 1 LIMIT 1",
            ['id1' => $id, 'id2' => $id]
        );

        if (!$product) {
            $product = Database::fetchOne("SELECT p.*, b.name as brand_name, b.slug as brand_slug, b.logo as brand_logo FROM `products` p LEFT JOIN `brands` b ON p.brand_id = b.id LIMIT 1");
        }

        if ($product) {
            $product['price_raw'] = (int) $product['price'];
            $product['price'] = number_format($product['price_raw']);
            $product['old_price'] = !empty($product['old_price']) ? number_format((int)$product['old_price']) : null;
            $product['in_stock'] = ($product['stock'] ?? 0) > 0;
            $product['specs'] = json_decode($product['specs'] ?? '[]', true) ?: [];
            $product['features'] = json_decode($product['features'] ?? '[]', true) ?: [];
            $product['rating'] = 4.9;
            $product['reviews_count'] = 32;
        }

        $related = Database::fetchAll(
            "SELECT p.*, b.name as brand_name FROM `products` p LEFT JOIN `brands` b ON p.brand_id = b.id WHERE p.`id` != :pid LIMIT 3",
            ['pid' => $product['id'] ?? 0]
        );

        foreach ($related as &$rel) {
            $rel['price_raw'] = (int) $rel['price'];
            $rel['price'] = number_format($rel['price_raw']);
            $rel['in_stock'] = ($rel['stock'] ?? 0) > 0;
        }

        $this->view('shop/detail', [
            'pageTitle'       => ($product['title'] ?? 'پکیج') . ' | نمایندگی رسمی ترموبان',
            'currentPage'     => 'shop',
            'csrfToken'       => Security::generateCsrf(),
            'product'         => $product,
            'relatedProducts' => $related
        ]);
    }

    /**
     * Shopping Cart View (100% Dynamic Session Cart)
     */
    public function cart(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $rawCart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $subtotal = 0;

        if (!empty($rawCart)) {
            $ids = array_keys($rawCart);
            $inClause = implode(',', array_map('intval', $ids));
            $products = Database::fetchAll("SELECT * FROM `products` WHERE `id` IN ({$inClause})");

            foreach ($products as $p) {
                $qty = (int) ($rawCart[$p['id']] ?? 1);
                $pPrice = (int) $p['price'];
                $itemTotal = $pPrice * $qty;
                $subtotal += $itemTotal;

                $cartItems[] = [
                    'id'        => $p['id'],
                    'title'     => $p['title'],
                    'price_raw' => $pPrice,
                    'price'     => number_format($pPrice),
                    'old_price' => !empty($p['old_price']) ? number_format((int)$p['old_price']) : null,
                    'quantity'  => $qty,
                    'image'     => $p['image'] ?: 'package-thumb.png',
                    'warranty'  => $p['warranty'] ?: '۲۴ ماه ضمانت طلایی شرکتی + نصب رایگان'
                ];
            }
        }

        $total = $subtotal;

        $this->view('shop/cart', [
            'pageTitle'   => 'سبد خرید | ترموبان',
            'currentPage' => 'cart',
            'csrfToken'   => Security::generateCsrf(),
            'cartItems'   => $cartItems,
            'subtotal'    => number_format($subtotal),
            'discount'    => '۰',
            'shipping'    => 'رایگان',
            'install'     => 'رایگان (کد ۷۲۷۳)',
            'total'       => number_format($total)
        ]);
    }

    /**
     * AJAX: Add Item to Cart
     */
    public function addToCart(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = max(1, (int) ($_POST['quantity'] ?? 1));

        if ($productId <= 0) {
            $this->json(['success' => false, 'message' => 'شناسه محصول نامعتبر است.'], 400);
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $qty;
        } else {
            $_SESSION['cart'][$productId] = $qty;
        }

        $this->json([
            'success'    => true,
            'message'    => 'محصول انتخابی با موفقیت به سبد خرید شما افزوده شد.',
            'cart_count' => array_sum($_SESSION['cart'])
        ]);
    }

    /**
     * AJAX: Remove Item from Cart
     */
    public function removeFromCart(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $productId = (int) ($_POST['product_id'] ?? 0);
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        $this->json([
            'success'    => true,
            'message'    => 'کالای مورد نظر از سبد خرید حذف گردید.',
            'cart_count' => isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0
        ]);
    }

    /**
     * AJAX: Clear / Empty Cart
     */
    public function clearCart(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION['cart'] = [];

        $this->json([
            'success'    => true,
            'message'    => 'سبد خرید شما کاملاً خالی شد.',
            'cart_count' => 0
        ]);
    }

    /**
     * Checkout View
     */
    public function checkout(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $rawCart = $_SESSION['cart'] ?? [];
        if (empty($rawCart)) {
            $this->redirect('shop');
            return;
        }

        $productIds = array_keys($rawCart);
        $cartItems = [];
        $subtotal = 0;
        $totalDiscount = 0;

        if (!empty($productIds)) {
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $products = Database::fetchAll("SELECT * FROM `products` WHERE `id` IN ($placeholders)", $productIds);
            foreach ($products as $p) {
                $qty = $rawCart[$p['id']] ?? 1;
                $priceRaw = (int) $p['price'];
                $oldPriceRaw = !empty($p['old_price']) ? (int) $p['old_price'] : $priceRaw;
                $rowTotal = $priceRaw * $qty;
                $subtotal += $oldPriceRaw * $qty;
                $totalDiscount += ($oldPriceRaw - $priceRaw) * $qty;

                $cartItems[] = [
                    'id'          => $p['id'],
                    'title'       => $p['title'],
                    'image'       => $p['image'],
                    'warranty'    => $p['warranty'],
                    'quantity'    => $qty,
                    'price_raw'   => $priceRaw,
                    'price'       => number_format($priceRaw),
                    'total_price' => number_format($rowTotal)
                ];
            }
        }

        $totalPayable = max(0, $subtotal - $totalDiscount);
        $itemsCount = array_sum($rawCart);

        $addresses = [];
        if (\App\Core\Auth::check()) {
            $addresses = Database::fetchAll(
                "SELECT * FROM `user_addresses` WHERE `user_id` = :uid ORDER BY `is_default` DESC",
                ['uid' => \App\Core\Auth::id()]
            );
        }

        $this->view('shop/checkout', [
            'pageTitle'    => 'تسویه حساب و پرداخت نهایی | ترموبان',
            'currentPage'  => 'checkout',
            'csrfToken'    => Security::generateCsrf(),
            'addresses'    => $addresses,
            'cartItems'    => $cartItems,
            'itemsCount'   => $itemsCount,
            'subtotal'     => number_format($subtotal),
            'discount'     => number_format($totalDiscount),
            'totalAmount'  => number_format($totalPayable)
        ]);
    }

    /**
     * Process Order Placement (Checkout Submit)
     */
    public function processCheckout(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $this->redirect('checkout');
            return;
        }

        $rawCart = $_SESSION['cart'] ?? [];
        if (empty($rawCart)) {
            $this->redirect('shop');
            return;
        }

        // Calculate cart total
        $productIds = array_keys($rawCart);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $products = Database::fetchAll("SELECT * FROM `products` WHERE `id` IN ($placeholders)", $productIds);

        $totalPayable = 0;
        $orderItemsData = [];
        foreach ($products as $p) {
            $qty = $rawCart[$p['id']] ?? 1;
            $unitPrice = (int) $p['price'];
            $lineTotal = $unitPrice * $qty;
            $totalPayable += $lineTotal;

            $orderItemsData[] = [
                'product_id'  => $p['id'],
                'quantity'    => $qty,
                'unit_price'  => $unitPrice,
                'total_price' => $lineTotal
            ];
        }

        // Customer & Address
        $userId = \App\Core\Auth::id();
        $selectedAddressId = !empty($_POST['selected_address']) ? (int) $_POST['selected_address'] : null;
        $deliverySlot = trim($_POST['delivery_slot'] ?? 'امروز عصر');
        $paymentMethod = ($_POST['payment_method'] ?? 'cod') === 'online' ? 'online' : 'cod';
        $notes = trim($_POST['notes'] ?? '');

        // Generate official order number
        $orderNumber = 'TB-' . date('Y') . '-' . rand(10000, 99999);

        // Insert into orders
        $orderId = Database::insert('orders', [
            'order_number'    => $orderNumber,
            'user_id'         => $userId ?: null,
            'address_id'      => $selectedAddressId,
            'total_amount'    => $totalPayable,
            'payable_amount'  => $totalPayable,
            'order_status'    => 'registered',
            'payment_status'  => $paymentMethod === 'online' ? 'paid' : 'pending',
            'payment_method'  => $paymentMethod,
            'technician_name' => 'در حال تخصیص تکنسین مجاز'
        ]);

        // Insert order items
        if ($orderId) {
            foreach ($orderItemsData as $item) {
                Database::insert('order_items', [
                    'order_id'    => $orderId,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total_price' => $item['total_price']
                ]);
            }
        }

        // Empty Cart
        $_SESSION['cart'] = [];

        // Send SMS notification if customer has phone
        if ($userId) {
            $user = Database::fetchOne("SELECT * FROM `users` WHERE `id` = :id", ['id' => $userId]);
            if ($user && !empty($user['phone'])) {
                \App\Services\Sms\MeliPayamakService::sendOrderSuccess($user['phone'], $orderNumber, number_format($totalPayable));
            }
        }

        $refId = 'REF-' . rand(100000, 999999);
        $this->redirect('payment/success?order=' . urlencode($orderNumber) . '&ref=' . urlencode($refId) . '&amount=' . urlencode(number_format($totalPayable)) . '&slot=' . urlencode($deliverySlot) . '&method=' . urlencode($paymentMethod));
    }

    public function paymentSuccess(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['cart'] = []; // Clear cart on successful order

        $orderId = trim($_GET['order'] ?? ('TB-' . date('Y') . '-' . rand(10000, 99999)));
        $refId = trim($_GET['ref'] ?? ('REF-' . rand(100000, 999999)));
        $amount = trim($_GET['amount'] ?? '۰');
        $delivery_time = trim($_GET['slot'] ?? 'هماهنگی تلفنی');
        $method = trim($_GET['method'] ?? 'cod');
        $gateway = ($method === 'online') ? 'درگاه شاپرک (بانک سامان / ملت)' : 'پرداخت در محل (کارتخوان سیار)';
        $date = to_shamsi(null, 'Y/m/d - H:i');

        $this->view('shop/payment-success', [
            'pageTitle'     => 'پرداخت موفق و ثبت سفارش | ترموبان',
            'currentPage'   => 'payment-success',
            'orderId'       => $orderId,
            'refId'         => $refId,
            'gateway'       => $gateway,
            'date'          => $date,
            'amount'        => $amount,
            'delivery_time' => $delivery_time
        ]);
    }

    public function paymentFailed(): void {
        $this->view('shop/payment-failed', [
            'pageTitle'   => 'خطا در پرداخت | ترموبان',
            'currentPage' => 'payment-failed',
            'errorCode'   => trim($_GET['code'] ?? 'ERR-1004'),
            'reason'      => trim($_GET['msg'] ?? 'لغو تراکنش توسط کاربر یا اختلال موقت در ارتباط با شبکه شاپرک'),
            'date'        => to_shamsi(null, 'Y/m/d - H:i')
        ]);
    }
}
