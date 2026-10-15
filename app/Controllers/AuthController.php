<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Models\User;

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
            $_SESSION['auth_error'] = 'شماره موبایل یا کلمه عبور وارد شده نادرست است.';
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
            'role'          => 'user'
        ]);

        $user = User::find($userId);
        Auth::login($user);

        $this->redirect('profile');
    }

    /**
     * Logout
     */
    public function logout(): void {
        Auth::logout();
        $this->redirect('');
    }
}
