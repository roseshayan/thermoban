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

        $where = ["`is_active` = 1"];
        $params = [];

        if (!empty($brandSlug)) {
            $brand = Database::fetchOne("SELECT id FROM `brands` WHERE `slug` = :b", ['b' => $brandSlug]);
            if ($brand) {
                $where[] = "`brand_id` = :bid";
                $params['bid'] = $brand['id'];
            }
        }

        if ($capacity > 0) {
            $where[] = "`capacity` = :cap";
            $params['cap'] = $capacity;
        }

        $orderBy = match ($sort) {
            'price_asc'  => '`price` ASC',
            'price_desc' => '`price` DESC',
            default      => '`id` DESC'
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
        }

        $addresses = [];
        if (\App\Core\Auth::check()) {
            $addresses = Database::fetchAll(
                "SELECT * FROM `user_addresses` WHERE `user_id` = :uid ORDER BY `is_default` DESC",
                ['uid' => \App\Core\Auth::id()]
            );
        }

        $this->view('shop/checkout', [
            'pageTitle'   => 'تسویه حساب و پرداخت نهایی | ترموبان',
            'currentPage' => 'checkout',
            'csrfToken'   => Security::generateCsrf(),
            'addresses'   => $addresses
        ]);
    }

    public function paymentSuccess(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['cart'] = []; // Clear cart on successful order
        $this->view('shop/payment-success', [
            'pageTitle'   => 'پرداخت موفق و ثبت سفارش | ترموبان',
            'currentPage' => 'payment-success'
        ]);
    }

    public function paymentFailed(): void {
        $this->view('shop/payment-failed', [
            'pageTitle'   => 'خطا در پرداخت | ترموبان',
            'currentPage' => 'payment-failed'
        ]);
    }
}
