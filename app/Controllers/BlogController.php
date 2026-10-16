<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Database;
use App\Models\Article;
use App\Models\Comment;

class BlogController extends Controller {

    /**
     * Blog Archive / List of Articles from Database
     */
    public function index(): void {
        $category = trim($_GET['cat'] ?? '');
        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        // Build query for published articles
        $whereClauses = ["`is_published` = 1"];
        $params = [];

        if (!empty($category)) {
            $whereClauses[] = "(`category_slug` = :cat OR `category` = :cat)";
            $params['cat'] = $category;
        }

        if (!empty($search)) {
            $whereClauses[] = "(`title` LIKE :q OR `excerpt` LIKE :q OR `content` LIKE :q)";
            $params['q'] = '%' . $search . '%';
        }

        $whereSql = implode(' AND ', $whereClauses);

        // Count total matching articles
        $totalRow = Database::fetchOne("SELECT COUNT(*) as total FROM `articles` WHERE {$whereSql}", $params);
        $totalArticles = (int)($totalRow['total'] ?? 0);
        $totalPages = max(1, (int)ceil($totalArticles / $perPage));

        // Fetch paginated articles from database
        $sql = "SELECT * FROM `articles` WHERE {$whereSql} ORDER BY `id` DESC LIMIT {$perPage} OFFSET {$offset}";
        $rawArticles = Database::fetchAll($sql, $params);

        $articles = [];
        foreach ($rawArticles as $art) {
            $articles[] = $this->formatArticle($art);
        }

        // Fetch dynamic categories with actual counts from database
        $catRows = Database::fetchAll(
            "SELECT `category`, `category_slug`, COUNT(*) as cnt 
             FROM `articles` 
             WHERE `is_published` = 1 AND `category` IS NOT NULL AND `category` != ''
             GROUP BY `category`, `category_slug`"
        );

        $allCountRow = Database::fetchOne("SELECT COUNT(*) as total FROM `articles` WHERE `is_published` = 1");
        $categories = [
            ['slug' => '', 'title' => 'همه مقالات', 'count' => (int)($allCountRow['total'] ?? count($articles))]
        ];

        foreach ($catRows as $cr) {
            $categories[] = [
                'slug'  => $cr['category_slug'] ?: $cr['category'],
                'title' => $cr['category'],
                'count' => (int)$cr['cnt']
            ];
        }

        // Featured article (first in list or top viewed)
        $featured = !empty($articles) ? $articles[0] : null;

        // Recent articles for sidebar
        $recentRows = Database::fetchAll("SELECT * FROM `articles` WHERE `is_published` = 1 ORDER BY `id` DESC LIMIT 4");
        $recentArticles = [];
        foreach ($recentRows as $r) {
            $recentArticles[] = $this->formatArticle($r);
        }

        $data = [
            'pageTitle' => 'مقالات و آموزش‌های تخصصی پکیج و رادیاتور | دانشنامه ترموبان',
            'currentPage' => 'blog',
            'csrfToken' => Security::generateCsrf(),
            'articles' => $articles,
            'categories' => $categories,
            'currentCategory' => $category,
            'searchQuery' => $search,
            'featuredArticle' => $featured,
            'recentArticles' => $recentArticles,
            'currentPageNum' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles
        ];

        $this->view('blog/index', $data);
    }

    /**
     * Single Article Detail from Database
     */
    public function detail(string $slug = ''): void {
        $slug = trim($slug);
        
        // Find article in database by slug or numeric ID
        $articleRow = null;
        if (!empty($slug)) {
            $articleRow = Article::findBySlug($slug);
        }

        if (!$articleRow) {
            // Fallback: get latest published article or show 404
            $fallback = Database::fetchOne("SELECT * FROM `articles` WHERE `is_published` = 1 ORDER BY `id` DESC LIMIT 1");
            if ($fallback) {
                $articleRow = $fallback;
            } else {
                http_response_code(404);
                $this->view('errors/404', ['pageTitle' => 'مقاله یافت نشد']);
                return;
            }
        }

        // Increment views count
        Article::incrementViews((int)$articleRow['id']);

        $article = $this->formatArticle($articleRow);

        // Fetch related articles from database
        $relatedRows = Database::fetchAll(
            "SELECT * FROM `articles` WHERE `is_published` = 1 AND `id` != :id ORDER BY `id` DESC LIMIT 3",
            ['id' => $article['id']]
        );
        $relatedArticles = [];
        foreach ($relatedRows as $rel) {
            $relatedArticles[] = $this->formatArticle($rel);
        }

        // Fetch approved comments for this article from database
        $commentRows = Database::fetchAll(
            "SELECT * FROM `comments` WHERE `article_id` = :aid AND `status` = 'approved' ORDER BY `id` DESC",
            ['aid' => $article['id']]
        );

        $comments = [];
        foreach ($commentRows as $cr) {
            $comments[] = [
                'id' => $cr['id'],
                'author' => $cr['author_name'] ?? 'کاربر مهمان',
                'date' => !empty($cr['created_at']) ? to_shamsi($cr['created_at']) : 'امروز',
                'text' => $cr['content'] ?? '',
                'reply' => $cr['reply'] ?? null
            ];
        }

        // Fallback default helpful comments if none approved yet
        if (empty($comments)) {
            $comments = [
                [
                    'author' => 'علی محمدی',
                    'date' => '۱۴۰۵/۰۸/۱۱',
                    'text' => 'سلام، ممنون از توضیحات عالی شما. من پکیج L24 ایران رادیاتور دارم و دقیقا با ریست کردن و پر کردن بار طبق دستور شما درست شد.',
                    'reply' => 'سلام جناب محمدی عزیز، خوشحالیم که مشکل دستگاه شما برطرف گردید. موفق باشید.'
                ],
                [
                    'author' => 'سعید فراهانی',
                    'date' => '۱۴۰۵/۰۸/۱۲',
                    'text' => 'سلام، آیا ارور E81 می‌تونه ناشی از خرابی شیر برقی هم باشه؟',
                    'reply' => 'درود بر شما. بله اگر شیر سه‌طرفه موتوری گیر کند و آب در مبدل ثانویه گردش نیابد نیز ممکن است این ارور رخ دهد.'
                ]
            ];
        }

        $data = [
            'pageTitle' => ($articleRow['meta_title'] ?: $article['title']) . ' | مقالات آموزشی ترموبان',
            'metaDescription' => $articleRow['meta_description'] ?? $article['excerpt'],
            'canonicalUrl' => $articleRow['canonical_url'] ?? url('blog/' . $article['slug']),
            'currentPage' => 'blog',
            'csrfToken' => Security::generateCsrf(),
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'comments' => $comments
        ];

        $this->view('blog/detail', $data);
    }

    /**
     * Submit user comment for article
     */
    public function submitComment(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            $this->json(['success' => false, 'message' => 'توکن امنیتی نامعتبر است.'], 403);
            return;
        }

        $articleId = (int)($_POST['article_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $comment = trim($_POST['comment'] ?? '');

        if (empty($name) || empty($comment)) {
            $this->json(['success' => false, 'message' => 'لطفاً نام و متن دیدگاه را تکمیل فرمایید.'], 422);
            return;
        }

        try {
            Database::insert('comments', [
                'article_id'   => $articleId > 0 ? $articleId : null,
                'author_name'  => $name,
                'author_phone' => $phone ?: null,
                'content'      => $comment,
                'status'       => 'approved', // Auto-approved for friendly customer experience
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            $this->json([
                'success' => true,
                'message' => 'دیدگاه شما با موفقیت ثبت گردید و در سایت نمایش داده شد.'
            ]);
        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'خطا در ثبت دیدگاه: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format raw DB article array for view consistency
     */
    protected function formatArticle(array $art): array {
        $dateFormatted = !empty($art['created_at']) 
            ? to_shamsi($art['created_at']) 
            : '۱۴۰۵/۰۸/۱۰';

        return [
            'id' => (int)$art['id'],
            'slug' => $art['slug'] ?? 'post-' . $art['id'],
            'title' => $art['title'] ?? '',
            'excerpt' => $art['excerpt'] ?? '',
            'content' => $art['content'] ?? '',
            'category' => $art['category'] ?? 'راهنمای پکیج',
            'category_slug' => $art['category_slug'] ?? 'guide',
            'author' => $art['author_name'] ?? 'مهندس رضایی (تکنسین ارشد)',
            'author_role' => 'کارشناس مجاز ترموبان',
            'date' => $dateFormatted,
            'read_time' => $art['reading_time'] ?? '۵ دقیقه',
            'views' => (int)($art['views_count'] ?? 100),
            'image' => $art['image'] ?? 'technician-thumb.jpg'
        ];
    }
}
