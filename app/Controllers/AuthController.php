<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Database;
use App\Models\User;
use App\Models\Setting;
use App\Services\CaptchaService;
use App\Services\Sms\MeliPayamakService;

class AuthController extends Controller {
    /**
     * Show login form
     */
    public function showLogin(): void {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                $this->redirect('admin');
            } else {
                $this->redirect('profile');
            }
        }

        $this->view('auth/login', [
            'pageTitle' => 'ورود به حساب کاربری | ترموبان',
            'csrfToken' => Security::generateCsrf(),
            'error'     => $_SESSION['auth_error'] ?? null,
            'success'   => $_SESSION['auth_success'] ?? null
        ]);
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
    }

    /**
     * Process login
     */
    public function processLogin(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $_SESSION['auth_error'] = 'اعتبار نشست شما به پایان رسیده است. لطفاً دوباره تلاش فرمایید.';
            $this->redirect('login');
        }

        // Anti-bot Honeypot and Captcha
        $honeypot = $_POST['honeypot'] ?? '';
        $captcha = $_POST['captcha'] ?? '';
        if (!empty($honeypot) || !CaptchaService::verify($captcha)) {
            $_SESSION['auth_error'] = 'کد امنیتی ضد ربات نامعتبر است یا پاسخ نادرست بود.';
            $this->redirect('login');
        }

        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $_SESSION['auth_error'] = 'شماره تماس و کلمه عبور الزامی است.';
            $this->redirect('login');
        }

        if (Auth::attempt($identifier, $password)) {
            if (Auth::isAdmin()) {
                $this->redirect('admin');
            } else {
                $this->redirect('profile');
            }
        } else {
            $_SESSION['auth_error'] = 'شماره موبایل یا کلمه عبور وارد شده نادرست است (یا حساب مسدود می‌باشد).';
            $this->redirect('login');
        }
    }

    /**
     * Show register form
     */
    public function showRegister(): void {
        if (Auth::check()) {
            $this->redirect('profile');
        }

        $this->view('auth/register', [
            'pageTitle' => 'عضویت در ترموبان | ایجاد حساب کاربری',
            'csrfToken' => Security::generateCsrf(),
            'error'     => $_SESSION['auth_error'] ?? null
        ]);
        unset($_SESSION['auth_error']);
    }

    /**
     * Process registration
     */
    public function processRegister(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $_SESSION['auth_error'] = 'خطای امنیتی نشست. دوباره تلاش فرمایید.';
            $this->redirect('register');
        }

        // Anti-bot Honeypot and Captcha
        $honeypot = $_POST['honeypot'] ?? '';
        $captcha = $_POST['captcha'] ?? '';
        if (!empty($honeypot) || !CaptchaService::verify($captcha)) {
            $_SESSION['auth_error'] = 'پاسخ کد امنیتی ضد ربات نادرست است.';
            $this->redirect('register');
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($phone) || empty($password)) {
            $_SESSION['auth_error'] = 'لطفاً کلیه فیلدهای الزامی را تکمیل نمایید.';
            $this->redirect('register');
        }

        if (User::findByPhone($phone)) {
            $_SESSION['auth_error'] = 'حساب کاربری با این شماره همراه قبلاً ثبت شده است. لطفاً وارد شوید.';
            $this->redirect('login');
        }

        $userId = User::create([
            'name'          => $name,
            'phone'         => $phone,
            'password_hash' => Auth::hashPassword($password),
            'role'          => 'user',
            'status'        => 'active'
        ]);

        $user = User::find($userId);
        Auth::login($user);

        $this->redirect('profile');
    }

    /**
     * Show Forgot Password Form
     */
    public function showForgotPassword(): void {
        if (Auth::check()) {
            $this->redirect('profile');
        }

        $this->view('auth/forgot-password', [
            'pageTitle' => 'فراموشی و بازیابی رمز عبور | ترموبان',
            'csrfToken' => Security::generateCsrf(),
            'error'     => $_SESSION['auth_error'] ?? null,
            'success'   => $_SESSION['auth_success'] ?? null
        ]);
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
    }

    /**
     * Process Forgot Password Request
     */
    public function submitForgotPassword(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $_SESSION['auth_error'] = 'اعتبار نشست شما منقضی شده است.';
            $this->redirect('forgot-password');
        }

        $honeypot = $_POST['honeypot'] ?? '';
        $captcha = $_POST['captcha'] ?? '';
        if (!empty($honeypot) || !CaptchaService::verify($captcha)) {
            $_SESSION['auth_error'] = 'کد امنیتی ضد ربات وارد شده نادرست است.';
            $this->redirect('forgot-password');
        }

        $phone = trim($_POST['phone'] ?? '');
        if (empty($phone)) {
            $_SESSION['auth_error'] = 'شماره تلفن همراه الزامی است.';
            $this->redirect('forgot-password');
        }

        $user = User::findByPhone($phone);
        if (!$user) {
            $_SESSION['auth_error'] = 'کاربری با این شماره تلفن همراه در سامانه ترموبان یافت نشد.';
            $this->redirect('forgot-password');
        }

        // Generate 6-digit verification code and secure token
        $code = (string) rand(100000, 999999);
        $resetToken = bin2hex(random_bytes(24));

        Database::insert('password_resets', [
            'phone'      => $phone,
            'token'      => $resetToken,
            'code'       => $code,
            'expires_at' => date('Y-m-d H:i:s', time() + 900) // 15 mins validity
        ]);

        // Send OTP via SMS if configured
        if (Setting::get('sms_enabled', '0') === '1') {
            MeliPayamakService::sendOtp($phone, $code);
            $_SESSION['auth_success'] = 'کد تأیید ۶ رقمی بازیابی به شماره همراه شما پیامک گردید.';
        } else {
            $_SESSION['auth_demo_otp'] = $code;
            $_SESSION['auth_success'] = "کد تأیید بازیابی ایجاد شد (کد شبیه‌ساز پیامک: {$code})";
        }

        $this->redirect('reset-password?token=' . urlencode($resetToken));
    }

    /**
     * Show Reset Password Form
     */
    public function showResetPassword(): void {
        if (Auth::check()) {
            $this->redirect('profile');
        }

        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            $_SESSION['auth_error'] = 'لینک بازیابی نامعتبر است.';
            $this->redirect('forgot-password');
        }

        $demoCode = $_SESSION['auth_demo_otp'] ?? null;
        unset($_SESSION['auth_demo_otp']);

        $this->view('auth/reset-password', [
            'pageTitle' => 'تنظیم کلمه عبور جدید | ترموبان',
            'token'     => $token,
            'demoCode'  => $demoCode,
            'csrfToken' => Security::generateCsrf(),
            'error'     => $_SESSION['auth_error'] ?? null,
            'success'   => $_SESSION['auth_success'] ?? null
        ]);
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
    }

    /**
     * Submit Reset Password
     */
    public function submitResetPassword(): void {
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($csrf)) {
            $_SESSION['auth_error'] = 'اعتبار نشست شما منقضی شده است.';
            $this->redirect('forgot-password');
        }

        $token = trim($_POST['token'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        // Normalize Persian numbers in code
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        $cleanCode = str_replace($persian, $english, $code);

        if (empty($token) || empty($cleanCode) || empty($password)) {
            $_SESSION['auth_error'] = 'تکمیل تمامی فیلدها الزامی است.';
            $this->redirect('reset-password?token=' . urlencode($token));
        }

        if (strlen($password) < 6) {
            $_SESSION['auth_error'] = 'کلمه عبور جدید باید حداقل ۶ کاراکتر باشد.';
            $this->redirect('reset-password?token=' . urlencode($token));
        }

        if ($password !== $confirm) {
            $_SESSION['auth_error'] = 'کلمه عبور جدید با تکرار آن مطابقت ندارد.';
            $this->redirect('reset-password?token=' . urlencode($token));
        }

        $reset = Database::fetchOne(
            "SELECT * FROM `password_resets` WHERE `token` = :token AND `code` = :code AND `expires_at` > NOW() ORDER BY `id` DESC LIMIT 1",
            ['token' => $token, 'code' => $cleanCode]
        );

        if (!$reset) {
            $_SESSION['auth_error'] = 'کد تأیید وارد شده نامعتبر است یا زمان استفاده از آن منقضی شده است.';
            $this->redirect('reset-password?token=' . urlencode($token));
        }

        $user = User::findByPhone($reset['phone']);
        if (!$user) {
            $_SESSION['auth_error'] = 'کاربر مربوطه یافت نشد.';
            $this->redirect('login');
        }

        // Update user's password
        User::update($user['id'], [
            'password_hash' => Auth::hashPassword($password)
        ]);

        // Clean up resets for this phone
        Database::delete('password_resets', '`phone` = :phone', ['phone' => $reset['phone']]);

        $_SESSION['auth_success'] = 'کلمه عبور شما با موفقیت تغییر یافت. اکنون می‌توانید با رمز جدید وارد شوید.';
        $this->redirect('login');
    }

    /**
     * Logout
     */
    public function logout(): void {
        Auth::logout();
        $this->redirect('');
    }
}
