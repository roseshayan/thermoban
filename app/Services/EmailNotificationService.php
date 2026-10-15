<?php
namespace App\Services;

class EmailNotificationService {
    /**
     * Send email notification to user when a ticket is created or replied to
     */
    public static function sendTicketNotification(string $email, string $name, string $ticketNum, string $subject, string $action = 'replied'): bool {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $siteName = 'نمایندگی رسمی ترموبان (کد ۷۲۷۳)';
        $emailSubject = ($action === 'created') ? "ثبت تیکت پشتیبانی جدید: {$subject} - ترموبان" : "پاسخ جدید به تیکت: {$subject} - ترموبان";
        $actionDesc = ($action === 'created') 
            ? "یک تیکت پشتیبانی و درخواست خدمت جدید توسط کارشناس نمایندگی برای شما با موضوع «{$subject}» در سامانه ثبت گردید." 
            : "کارشناس پشتیبانی نمایندگی رسمی ترموبان پاسخ جدیدی برای تیکت شماره {$ticketNum} ثبت نمود.";

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' . SITE_NAME . ' <' . SITE_EMAIL . '>',
            'Reply-To: ' . SITE_EMAIL,
            'X-Mailer: PHP/' . phpversion()
        ];

        $profileUrl = url('profile');

        $html = "
        <div dir='rtl' style='font-family: Tahoma, Vazirmatn, sans-serif; background-color: #f1f5f9; padding: 25px; color: #1e293b;'>
            <div style='max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 14px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.06);'>
                <div style='text-align: center; border-bottom: 2px solid #EF5C16; padding-bottom: 16px; margin-bottom: 22px;'>
                    <h2 style='color: #011C39; margin: 0 0 4px 0; font-size: 1.3rem;'>{$siteName}</h2>
                    <span style='color: #EF5C16; font-weight: bold; font-size: 0.85rem;'>نمایندگی رسمی کد ۷۲۷۳ بوتان و ایران رادیاتور</span>
                </div>
                <p style='font-size: 1rem; line-height: 1.7;'>مشتری گرامی، <strong>{$name}</strong> عزیز،</p>
                <p style='line-height: 1.8; font-size: 0.92rem; color: #334155;'>{$actionDesc}</p>
                <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-right: 4px solid #EF5C16; padding: 16px 20px; border-radius: 8px; margin: 22px 0;'>
                    <p style='margin: 4px 0; font-size: 0.9rem;'><strong>شناسه تیکت:</strong> <span dir='ltr' style='font-family: monospace;'>{$ticketNum}</span></p>
                    <p style='margin: 4px 0; font-size: 0.9rem;'><strong>موضوع:</strong> {$subject}</p>
                    <p style='margin: 4px 0; font-size: 0.9rem;'><strong>تاریخ:</strong> " . date('Y/m/d H:i') . "</p>
                </div>
                <p style='font-size: 0.88rem; color: #64748b; line-height: 1.7;'>جهت مشاهده متن کامل پیام و پاسخ کارشناس، ارسال توضیحات تکمیلی یا پیگیری اعزام تکنسین، لطفاً وارد پنل کاربری خود شوید.</p>
                <div style='text-align: center; margin: 30px 0 15px 0;'>
                    <a href='{$profileUrl}' style='background: #EF5C16; color: #ffffff; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.95rem; display: inline-block; box-shadow: 0 4px 12px rgba(239, 92, 22, 0.3);'>ورود به حساب کاربری و مشاهده تیکت</a>
                </div>
                <div style='margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 15px; font-size: 0.78rem; color: #94a3b8; text-align: center;'>
                    این ایمیل به صورت خودکار از سامانه آنلاین نمایندگی ترموبان (کد ۷۲۷۳) ارسال گردیده است.
                </div>
            </div>
        </div>";

        // In CLI testing or local Windows environment, simulate email dispatch
        if (PHP_SAPI === 'cli' || PHP_OS_FAMILY === 'Windows') {
            return true;
        }

        return @mail($email, '=?UTF-8?B?' . base64_encode($emailSubject) . '?=', $html, implode("\r\n", $headers));
    }
}
