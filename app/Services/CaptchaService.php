<?php
namespace App\Services;

class CaptchaService {
    /**
     * Generate a new mathematical security question
     */
    public static function generate(): array {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $num1 = rand(2, 9);
        $num2 = rand(1, 8);
        $op = '+';
        $answer = $num1 + $num2;

        $_SESSION['captcha_answer'] = (string) $answer;
        $_SESSION['captcha_time'] = time();

        $persianNums = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $englishNums = ['0','1','2','3','4','5','6','7','8','9'];
        $pNum1 = str_replace($englishNums, $persianNums, (string) $num1);
        $pNum2 = str_replace($englishNums, $persianNums, (string) $num2);

        return [
            'question' => "حاصل جمع {$pNum1} + {$pNum2} چند است؟",
            'raw_answer' => $answer
        ];
    }

    /**
     * Verify user submitted captcha answer
     */
    public static function verify(?string $userAnswer): bool {
        if (defined('TESTING') && TESTING === true) {
            return true;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($userAnswer) || empty($_SESSION['captcha_answer'])) {
            return false;
        }

        // Convert Persian/Arabic digits to English
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9','0','1','2','3','4','5','6','7','8','9'];
        $cleanUser = trim(str_replace($persian, $english, $userAnswer));

        $valid = ($cleanUser === (string) $_SESSION['captcha_answer']);

        // Invalidate answer after verification
        unset($_SESSION['captcha_answer']);

        return $valid;
    }

    /**
     * Render Captcha HTML Box for Forms
     */
    public static function renderHtml(): string {
        $captcha = self::generate();
        return '
        <div class="security-captcha-box" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 12px 16px; margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label for="captcha_input" style="font-size: 0.84rem; font-weight: 700; color: #011C39; margin: 0; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-shield-halved" style="color: #EF5C16;"></i>
                    <span>کد امنیتی ضد ربات:</span>
                    <strong style="color: #0369a1; background: #e0f2fe; padding: 2px 8px; border-radius: 6px; font-size: 0.95rem;">' . htmlspecialchars($captcha['question']) . '</strong>
                </label>
            </div>
            <input type="text" id="captcha_input" name="captcha" class="form-control" placeholder="پاسخ به عدد..." required style="height: 42px; font-size: 0.9rem; text-align: center;" autocomplete="off">
            <input type="text" name="honeypot" style="display:none !important;" tabindex="-1" autocomplete="off">
        </div>';
    }

    public static function render(): string {
        return self::renderHtml();
    }
}
