<?php
namespace App\Services;

use App\Models\Setting;
use App\Models\Product;
use App\Models\Article;

class SeoService {
    /**
     * Render full SEO tags in <head>
     */
    public static function renderHeadTags(array $pageSeo = []): string {
        $siteName = Setting::get('site_name', 'ترموبان | نمایندگی کد  ایران رادیاتور و بوتان');
        $rawTitle = $pageSeo['title'] ?? 'فروش، نصب و تعمیر پکیج دیواری در تهران';
        $titleTemplate = Setting::get('seo_meta_title_template', '%title% | %sitename%');

        $finalTitle = str_replace(
            ['%title%', '%sitename%', '%sep%'],
            [$rawTitle, $siteName, '-'],
            $titleTemplate
        );

        $description = $pageSeo['description'] ?? Setting::get('seo_meta_description', 'مرکز تخصصی فروش، نصب و تعمیرات پکیج دیواری ایران رادیاتور و بوتان در ۲۲ منطقه تهران با کد مجاز ');
        $keywords = $pageSeo['keywords'] ?? Setting::get('seo_meta_keywords', 'تعمیر پکیج, نصب پکیج, ایران رادیاتور, بوتان, نمایندگی کد ');
        $canonical = $pageSeo['canonical'] ?? (rtrim(Setting::get('seo_canonical_url', url()), '/') . '/' . ltrim($_SERVER['REQUEST_URI'] ?? '', '/'));
        $image = !empty($pageSeo['image']) ? asset($pageSeo['image']) : asset(Setting::get('seo_og_image', 'images/hero-technician.jpg'));
        $type = $pageSeo['type'] ?? 'website';

        $analyticsId = Setting::get('seo_google_analytics', '');
        $searchConsole = Setting::get('seo_search_console', '');

        $html = "\n    <!-- Rank Math Pro Advanced SEO Meta -->\n";
        $html .= '    <title>' . htmlspecialchars($finalTitle, ENT_QUOTES, 'UTF-8') . "</title>\n";
        $html .= '    <meta name="description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta name="keywords" content="' . htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <link rel="canonical" href="' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">\n';

        if (!empty($searchConsole)) {
            $html .= '    <meta name="google-site-verification" content="' . htmlspecialchars($searchConsole, ENT_QUOTES, 'UTF-8') . "\">\n";
        }

        // Open Graph
        $html .= "    <!-- Open Graph (Facebook, WhatsApp, Bale) -->\n";
        $html .= '    <meta property="og:locale" content="fa_IR">\n';
        $html .= '    <meta property="og:type" content="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta property="og:title" content="' . htmlspecialchars($finalTitle, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta property="og:description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta property="og:url" content="' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta property="og:site_name" content="' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta property="og:image" content="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "\">\n";

        // Twitter Card
        $html .= "    <!-- Twitter Cards -->\n";
        $html .= '    <meta name="twitter:card" content="summary_large_image">\n';
        $html .= '    <meta name="twitter:title" content="' . htmlspecialchars($finalTitle, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta name="twitter:description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '    <meta name="twitter:image" content="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "\">\n";

        // Schema.org Structured Data (JSON-LD)
        $html .= self::renderSchemaJsonLd($pageSeo);

        return $html;
    }

    /**
     * Generate Schema.org JSON-LD Rich Snippet
     */
    public static function renderSchemaJsonLd(array $pageSeo = []): string {
        $schemas = [];

        // 1. Organization / HVAC LocalBusiness
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'HVACBusiness',
            'name' => 'نمایندگی رسمی ترموبان - کد ',
            'image' => asset('images/logo-thermoban.png'),
            'telephone' => Setting::get('site_phone_2', '۰۲۱-۷۷۴۲۸۵۹۶'),
            'priceRange' => '$$',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('site_address', 'تهران، خیابان دماوند، پلاک ۳۴'),
                'addressLocality' => 'تهران',
                'addressRegion' => 'تهران',
                'postalCode' => '1653812345',
                'addressCountry' => 'IR'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 35.6892,
                'longitude' => 51.3890
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'],
                'opens' => '08:00',
                'closes' => '21:00'
            ]
        ];

        // 2. Product Schema
        if (!empty($pageSeo['product'])) {
            $prod = $pageSeo['product'];
            $schemas[] = [
                '@context' => 'https://schema.org/',
                '@type' => 'Product',
                'name' => $prod['title'],
                'image' => asset('images/' . ($prod['image'] ?? 'product-iranradiator-l24ff.png')),
                'description' => 'پکیج دیواری با ظرفیت ' . ($prod['capacity'] ?? 24000) . ' کیلوکالری، گارانتی ۲۴ ماهه و نصب رایگان نمایندگی ',
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $prod['brand_name'] ?? 'ایران رادیاتور'
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'IRT',
                    'price' => $prod['price'] ?? 24500000,
                    'availability' => 'https://schema.org/InStock',
                    'seller' => [
                        '@type' => 'Organization',
                        'name' => 'ترموبان'
                    ]
                ]
            ];
        }

        // 3. Article Schema
        if (!empty($pageSeo['article'])) {
            $art = $pageSeo['article'];
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $art['title'],
                'image' => asset('images/' . ($art['image'] ?? 'blog-error-e81.jpg')),
                'datePublished' => $art['created_at'] ?? date('Y-m-d'),
                'author' => [
                    '@type' => 'Person',
                    'name' => 'مهندس شایان نماینده'
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'ترموبان',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/logo-thermoban.png')
                    ]
                ]
            ];
        }

        $json = json_encode($schemas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        return "\n    <!-- Rank Math Pro Schema.org JSON-LD -->\n    <script type=\"application/ld+json\">\n" . $json . "\n    </script>\n";
    }

    /**
     * Generate dynamic XML Sitemap
     */
    public static function generateSitemapXml(): string {
        $baseUrl = rtrim(url(), '/');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static routes
        $staticRoutes = [
            ['loc' => $baseUrl, 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/shop', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/services', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl . '/blog', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/about', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($staticRoutes as $r) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($r['loc']) . "</loc>\n";
            $xml .= "    <priority>" . $r['priority'] . "</priority>\n";
            $xml .= "    <changefreq>" . $r['changefreq'] . "</changefreq>\n";
            $xml .= "  </url>\n";
        }

        // Dynamic Products
        $products = Product::all();
        foreach ($products as $p) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/product/' . $p['id']) . "</loc>\n";
            $xml .= "    <priority>0.85</priority>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "  </url>\n";
        }

        // Dynamic Articles
        $articles = Article::where("`is_published` = 1");
        foreach ($articles as $a) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/blog/' . ($a['slug'] ?: $a['id'])) . "</loc>\n";
            $xml .= "    <priority>0.75</priority>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';
        return $xml;
    }
}
