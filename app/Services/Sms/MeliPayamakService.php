<?php
namespace App\Services\Sms;

use App\Models\Setting;

class MeliPayamakService {
    protected string $username;
    protected string $password;
    protected string $fromNumber;
    protected bool $enabled;

    public function __construct() {
        $this->username = Setting::get('sms_username', '') ?: '';
        $this->password = Setting::get('sms_password', '') ?: '';
        $this->fromNumber = Setting::get('sms_from_number', '500040001234') ?: '500040001234';
        $this->enabled = Setting::get('sms_enabled', '0') === '1';
    }

    /**
     * Send OTP verification code using MeliPayamak Shared Pattern Service
     */
    public static function sendOtp(string $phone, string $code): array {
        $service = new self();
        $bodyId = (int) Setting::get('sms_pattern_otp', '14520');
        return $service->sendPattern($phone, $bodyId, [$code]);
    }

    /**
     * Send Order Notification to customer
     */
    public static function sendOrderSuccess(string $phone, string $orderNum, string $amount): array {
        $service = new self();
        $bodyId = (int) Setting::get('sms_pattern_order', '14521');
        return $service->sendPattern($phone, $bodyId, [$orderNum, $amount]);
    }

    /**
     * Send Ticket Created or Replied Notification to customer
     */
    public static function sendTicketNotification(string $phone, string $ticketNum, string $title, string $action = 'replied'): array {
        $service = new self();
        $bodyId = (int) Setting::get('sms_pattern_ticket', '0');

        if ($bodyId > 0) {
            return $service->sendPattern($phone, $bodyId, [$ticketNum, $title]);
        }

        $actionText = ($action === 'created') ? 'تیکت پشتیبانی جدیدی برای شما ثبت شد.' : 'پاسخ جدیدی برای تیکت پشتیبانی شما ثبت گردید.';
        $msg = "ترموبان (کد ۷۲۷۳)\nمشتری گرامی، {$actionText}\nشناسه تیکت: {$ticketNum}\nجهت بررسی وارد پنل کاربری شوید.";
        return self::sendDirect($phone, $msg);
    }


    /**
     * Send pattern SMS via MeliPayamak BaseServiceNumber API
     */
    public function sendPattern(string $to, int $bodyId, array $args = []): array {
        if (!$this->enabled) {
            return [
                'success' => true,
                'status'  => 'simulated',
                'message' => 'پیامک شبیه‌سازی شد (پنل ملی پیامک در حالت غیرفعال/تست است). کد پیامک: ' . implode(' - ', $args)
            ];
        }

        $url = 'https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber';
        $text = implode(';', $args);

        $data = [
            'username' => $this->username,
            'password' => $this->password,
            'text'     => $text,
            'to'       => $to,
            'bodyId'   => $bodyId
        ];

        $res = $this->sendPost($url, $data);

        // MeliPayamak returns numeric RetStatus > 1000 on success, or negative on error
        if (isset($res['Value']) && (int)$res['Value'] > 1000) {
            return [
                'success' => true,
                'status'  => 'delivered',
                'message' => 'پیامک خدماتی با شناسه رهگیری ' . $res['Value'] . ' با موفقیت ارسال شد.',
                'recId'   => $res['Value'],
                'response'=> $res
            ];
        }

        return [
            'success' => false,
            'status'  => 'failed',
            'message' => 'خطای ملی پیامک: ' . ($res['StrRetStatus'] ?? 'عدم اتصال به سرور پیامک'),
            'error'   => $res['StrRetStatus'] ?? 'عدم اتصال به سرور پیامک'
        ];
    }

    /**
     * Send regular single SMS
     */
    public static function sendDirect(string $to, string $text): array {
        $service = new self();
        if (!$service->enabled) {
            return [
                'success' => true,
                'status'  => 'simulated',
                'message' => 'پیامک در حالت آزمایشی با موفقیت لاگ شد.'
            ];
        }

        $url = 'https://rest.payamak-panel.com/api/SendSMS/SendSMS';
        $data = [
            'username' => $service->username,
            'password' => $service->password,
            'to'       => $to,
            'from'     => $service->fromNumber,
            'text'     => $text,
            'isFlash'  => false
        ];

        $res = $service->sendPost($url, $data);

        if (isset($res['Value']) && (int)$res['Value'] > 1000) {
            return [
                'success' => true,
                'status'  => 'sent',
                'message' => 'پیامک با موفقیت ارسال گردید.',
                'recId'   => $res['Value'],
                'response'=> $res
            ];
        }

        return [
            'success' => false,
            'status'  => 'failed',
            'message' => 'خطا در ارسال پیامک: ' . ($res['StrRetStatus'] ?? 'پاسخ نامعتبر'),
            'error'   => $res['StrRetStatus'] ?? 'پاسخ نامعتبر'
        ];
    }

    protected function sendPost(string $url, array $params): array {
        $json = json_encode($params);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $out = curl_exec($ch);
        curl_close($ch);

        return $out ? json_decode($out, true) ?? [] : [];
    }
}
