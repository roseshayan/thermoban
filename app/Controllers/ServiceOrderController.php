<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Security;
use App\Models\ServiceRequest;
use App\Models\ServiceTariff;
use App\Models\UserAddress;
use App\Services\CaptchaService;
use App\Services\NotificationService;
use App\Services\Sms\MeliPayamakService;

class ServiceOrderController extends Controller {

    /**
     * Display Comprehensive Service Booking Form
     */
    public function index(): void {
        $user = Auth::user();
        $userAddresses = [];
        if ($user) {
            $userAddresses = UserAddress::getByUser($user['id']);
        }

        $tariffs = [];
        try {
            $tariffs = ServiceTariff::getActive();
        } catch (\Throwable $e) {}

        $selectedService = trim($_GET['service'] ?? '');

        $commonProblems = [
            'افت مکرر فشار بار دستگاه و خاموش شدن',
            'آب گرم مصرفی سرد و گرم می‌شود',
            'رادیاتورها گرم نمی‌شوند یا نیمی سرد هستند',
            'نمایش کد خطا و ارور دیجیتال روی پنل',
            'شنیده شدن صدای غیرعادی، تق‌تق یا سوت',
            'نشتی آب از زیر دستگاه یا اتصالات',
            'روشن شدن انفجاری مشعل یا استشمام بوی گاز',
            'نیاز به سرویس کامل دوره‌ای و اسیدشویی مبدل',
            'نصب دستگاه نو، راه‌اندازی و مهر گارانتی'
        ];

        $brands = [
            'ایران رادیاتور',
            'بوتان',
            'لورچ',
            'ایساتیس',
            'آریستون',
            'بوش',
            'تاچی',
            'فرولی',
            'مرکوری',
            'سایر برندها'
        ];

        $timeSlots = [
            '۹:۰۰ الی ۱۲:۰۰ (صبح)',
            '۱۲:۰۰ الی ۱۵:۰۰ (ظهر)',
            '۱۵:۰۰ الی ۱۸:۰۰ (عصر)',
            '۱۸:۰۰ الی ۲۱:۰۰ (شب)'
        ];

        $this->view('service/order', [
            'pageTitle'        => 'ثبت آنلاین سفارش خدمات و اعزام فوری تکنسین پکیج | ترموبان',
            'user'             => $user,
            'userAddresses'    => $userAddresses,
            'tariffs'          => $tariffs,
            'selectedService'  => $selectedService,
            'commonProblems'   => $commonProblems,
            'brands'           => $brands,
            'timeSlots'        => $timeSlots,
            'csrfToken'        => Security::generateCsrf(),
            'error'            => $_SESSION['service_order_error'] ?? null
        ]);
        unset($_SESSION['service_order_error']);
    }

    /**
     * Process Service Booking Form with Media Attachments
     */
    public function submit(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('service-order');
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $_SESSION['service_order_error'] = 'خطای امنیتی: نشست شما منقضی شده است. لطفاً مجدداً تلاش نمایید.';
            $this->redirect('service-order');
        }

        // Validate Captcha
        $captchaInput = trim($_POST['captcha'] ?? '');
        if (!CaptchaService::verify($captchaInput)) {
            $_SESSION['service_order_error'] = 'کد امنیتی ضد اسپم نادرست وارد شده است.';
            $this->redirect('service-order');
        }

        $customerName = trim($_POST['customer_name'] ?? '');
        $customerPhone = trim($_POST['customer_phone'] ?? '');
        $serviceType = trim($_POST['service_type'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $district = trim($_POST['district'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $preferredDate = trim($_POST['preferred_date'] ?? 'امروز');
        $timeSlot = trim($_POST['time_slot'] ?? 'سریع‌ترین زمان ممکن');
        $urgency = in_array($_POST['urgency'] ?? '', ['urgent', 'normal', 'scheduled'], true) ? $_POST['urgency'] : 'normal';
        $problemType = trim($_POST['problem_type'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validation
        if (empty($customerName) || empty($customerPhone) || empty($serviceType) || empty($brand) || empty($district) || empty($address)) {
            $_SESSION['service_order_error'] = 'لطفاً تمامی فیلدهای ستاره‌دار الزامی (نام، شماره تماس، نوع خدمت، برند و نشانی) را تکمیل فرمایید.';
            $this->redirect('service-order');
        }

        // Clean phone
        $customerPhone = en_num($customerPhone);
        if (!preg_match('/^09[0-9]{9}$/', $customerPhone)) {
            $_SESSION['service_order_error'] = 'شماره تلفن همراه باید ۱۱ رقم و با ۰۹ آغاز شود.';
            $this->redirect('service-order');
        }

        // Handle Photo Uploads (Max 5 photos, jpg, jpeg, png, webp, max 10MB each)
        $photoPaths = [];
        if (!empty($_FILES['photos']['name']) && is_array($_FILES['photos']['name'])) {
            $photoDir = ROOT_PATH . '/public/uploads/service_requests/photos';
            if (!is_dir($photoDir)) @mkdir($photoDir, 0755, true);

            $allowedPhotoExts = ['jpg', 'jpeg', 'png', 'webp'];
            $maxPhotos = 5;
            $count = 0;

            foreach ($_FILES['photos']['name'] as $idx => $fname) {
                if ($count >= $maxPhotos) break;
                if ($_FILES['photos']['error'][$idx] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                    $size = $_FILES['photos']['size'][$idx];
                    if (in_array($ext, $allowedPhotoExts, true) && $size <= 10 * 1024 * 1024) {
                        $newName = 'img_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                        if (move_uploaded_file($_FILES['photos']['tmp_name'][$idx], $photoDir . '/' . $newName)) {
                            $photoPaths[] = 'uploads/service_requests/photos/' . $newName;
                            $count++;
                        }
                    }
                }
            }
        }

        // Handle Video Upload (mp4, webm, mov, max 50MB)
        $videoPath = null;
        if (!empty($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
            $vFile = $_FILES['video'];
            $vExt = strtolower(pathinfo($vFile['name'], PATHINFO_EXTENSION));
            $allowedVideoExts = ['mp4', 'webm', 'mov', 'avi'];
            $maxVideoSize = 50 * 1024 * 1024; // 50MB

            if (in_array($vExt, $allowedVideoExts, true) && $vFile['size'] <= $maxVideoSize) {
                $videoDir = ROOT_PATH . '/public/uploads/service_requests/videos';
                if (!is_dir($videoDir)) @mkdir($videoDir, 0755, true);
                $newVideoName = 'vid_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $vExt;
                if (move_uploaded_file($vFile['tmp_name'], $videoDir . '/' . $newVideoName)) {
                    $videoPath = 'uploads/service_requests/videos/' . $newVideoName;
                }
            }
        }

        // Generate Tracking Code
        $trackingCode = ServiceRequest::generateTrackingCode();
        $userId = Auth::id() ?: null;

        // Coordinates (from Map Picker)
        $lat = !empty($_POST['lat']) ? (float)$_POST['lat'] : null;
        $lng = !empty($_POST['lng']) ? (float)$_POST['lng'] : null;

        // Save order to database
        $requestId = ServiceRequest::create([
            'tracking_code'  => $trackingCode,
            'user_id'        => $userId,
            'customer_name'  => $customerName,
            'customer_phone' => $customerPhone,
            'service_type'   => $serviceType,
            'brand'          => $brand,
            'model'          => $model ?: null,
            'district'       => $district,
            'address'        => $address,
            'lat'            => $lat,
            'lng'            => $lng,
            'preferred_date' => $preferredDate,
            'time_slot'      => $timeSlot,
            'urgency'        => $urgency,
            'problem_type'   => $problemType ?: null,
            'description'    => $description ?: null,
            'photos'         => !empty($photoPaths) ? json_encode($photoPaths, JSON_UNESCAPED_UNICODE) : null,
            'video_path'     => $videoPath,
            'status'         => 'pending'
        ]);

        // Auto-save address if logged in and requested
        if ($userId && !empty($_POST['save_address']) && $_POST['save_address'] === '1') {
            UserAddress::create([
                'user_id'     => $userId,
                'title'       => trim($_POST['address_title'] ?? 'محل دستگاه'),
                'district'    => $district,
                'address'     => $address,
                'postal_code' => trim($_POST['postal_code'] ?? '') ?: null,
                'phone'       => $customerPhone,
                'lat'         => $lat ?: 35.7219,
                'lng'         => $lng ?: 51.4056,
                'is_default'  => 0
            ]);
        }

        // Send SMS to customer with tracking code (if enabled)
        try {
            $smsPattern = \App\Models\Setting::get('sms_pattern_order', '');
            if (!empty($smsPattern)) {
                MeliPayamakService::sendPattern($customerPhone, $smsPattern, [
                    'tracking' => $trackingCode,
                    'service'  => $serviceType
                ]);
            }
        } catch (\Throwable $e) {}

        $_SESSION['service_order_success_code'] = $trackingCode;
        $this->redirect('service-order/success?tracking=' . $trackingCode);
    }

    /**
     * Confirmation Page with Tracking Code
     */
    public function success(): void {
        $trackingCode = trim($_GET['tracking'] ?? ($_SESSION['service_order_success_code'] ?? ''));
        $request = null;
        if (!empty($trackingCode)) {
            $request = ServiceRequest::findByTrackingCode($trackingCode);
        }

        $this->view('service/success', [
            'pageTitle'    => 'ثبت موفق سفارش خدمات | کد رهگیری: ' . $trackingCode,
            'trackingCode' => $trackingCode,
            'request'      => $request
        ]);
    }

    /**
     * Customer Tracking Page (Status, Assigned Tech, Schedule)
     */
    public function tracking(): void {
        $trackingCode = trim($_GET['code'] ?? '');
        $phone = trim($_GET['phone'] ?? '');
        $request = null;
        $searched = false;

        if (!empty($trackingCode)) {
            $searched = true;
            $request = ServiceRequest::findByTrackingCode($trackingCode);
        } elseif (!empty($phone)) {
            $searched = true;
            $phoneClean = en_num($phone);
            $request = Database::fetchOne(
                "SELECT sr.*, tech.name as assigned_tech_name, tech.phone as assigned_tech_phone 
                 FROM `service_requests` sr 
                 LEFT JOIN `users` tech ON sr.technician_id = tech.id 
                 WHERE sr.customer_phone = :p ORDER BY sr.id DESC LIMIT 1",
                ['p' => $phoneClean]
            );
        }

        $this->view('service/tracking', [
            'pageTitle'    => 'پیگیری وضعیت سفارش و اعزام تکنسین | سامانه هوشمند ترموبان',
            'trackingCode' => $trackingCode,
            'phone'        => $phone,
            'request'      => $request,
            'searched'     => $searched
        ]);
    }
}
