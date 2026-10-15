<?php
namespace App\Services\Storage;

use App\Models\Setting;
use CURLFile;

class FilesIrService {
    protected string $apiKey;
    protected string $endpoint;
    protected bool $enabled;

    public function __construct() {
        $this->apiKey = Setting::get('files_ir_api_key', '') ?: '';
        $this->endpoint = Setting::get('files_ir_endpoint', 'https://api.files.ir/v1/upload') ?: 'https://api.files.ir/v1/upload';
        $this->enabled = Setting::get('module_files_ir', '0') === '1';
    }

    /**
     * Upload a backup file to Files.ir Cloud Storage
     */
    public function uploadFile(string $filePath): array {
        if (!$this->enabled) {
            return [
                'success' => false,
                'message' => 'ماژول هاست ابری files.ir توسط سوپر ادمین غیرفعال شده است.'
            ];
        }

        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'کلید API وب‌سرویس files.ir در تنظیمات سامانه وارد نشده است.'
            ];
        }

        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'message' => 'فایل بک‌آپ جهت ارسال به فضای ابری یافت نشد.'
            ];
        }

        $cfile = new CURLFile($filePath, 'application/sql', basename($filePath));
        $postData = [
            'file' => $cfile
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            return [
                'success' => false,
                'message' => 'خطای cURL در ارتباط با files.ir: ' . $curlErr
            ];
        }

        $json = json_decode($response, true);
        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'message' => 'فایل پشتیبان با موفقیت در فضای ابری files.ir ذخیره گردید.',
                'data'    => $json
            ];
        }

        return [
            'success' => false,
            'message' => 'پاسخ سرور files.ir با کد خطای ' . $httpCode . ': ' . ($json['message'] ?? $response)
        ];
    }
}
