<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Services\DatabaseMigrationService;

class Faq extends Model {
    protected static string $table = 'faqs';

    /**
     * Get FAQs by section and optional target ID
     */
    public static function getBySection(string $section = 'home', ?int $targetId = null): array {
        DatabaseMigrationService::ensureTable('faqs');
        
        $where = "`section` = :sec AND `is_active` = 1";
        $params = ['sec' => $section];

        if ($targetId !== null) {
            $where .= " AND `target_id` = :tid";
            $params['tid'] = $targetId;
        }

        return static::where($where, $params, "`sort_order` ASC, `id` ASC");
    }

    /**
     * Build Schema.org FAQPage JSON-LD structure
     */
    public static function buildSchemaJsonLd(array $faqs): string {
        if (empty($faqs)) return '';
        $mainEntity = [];
        foreach ($faqs as $f) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $f['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($f['answer'])
                ]
            ];
        }
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity
        ];
        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }

    public static function getSchemaJsonLd(array $faqs): string {
        return static::buildSchemaJsonLd($faqs);
    }
}
