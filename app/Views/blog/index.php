<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <span class="current">مقالات و راهنمای آموزشی پکیج</span>
            </nav>
            <h1 class="page-title">مقالات و آموزش‌های تخصصی پکیج و سیستم‌های گرمایشی</h1>
            <p class="page-subtitle">جدیدترین راهنماهای عیب‌یابی، رفع کدهای خطا و نکات افزایش طول عمر پکیج به قلم تکنسین‌های ارشد ترموبان</p>
        </div>
    </div>
</section>

<!-- Blog Main Section -->
<section class="blog-archive-section">
    <div class="site-container">
        
        <!-- Filter & Search Bar -->
        <div class="archive-filter-bar">
            <!-- Categories Pills -->
            <div class="category-pills">
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= url('blog' . ($cat['slug'] ? '?cat=' . $cat['slug'] : '')) ?>" 
                       class="cat-pill <?= ($currentCategory === $cat['slug']) ? 'active' : '' ?>">
                        <span><?= e($cat['title']) ?></span>
                        <span class="pill-badge"><?= $cat['count'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Search Form -->
            <form action="<?= url('blog') ?>" method="GET" class="archive-search-form">
                <?php if (!empty($currentCategory)): ?>
                    <input type="hidden" name="cat" value="<?= e($currentCategory) ?>">
                <?php endif; ?>
                <input type="text" name="q" value="<?= e($searchQuery) ?>" placeholder="جستجو در بین مقالات و ارورها..." class="search-input">
                <button type="submit" class="search-submit-btn" aria-label="جستجو">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
        </div>

        <div class="blog-layout-grid">
            
            <!-- Main Column: Articles Cards -->
            <div class="blog-articles-main">
                
                <?php if (empty($articles)): ?>
                    <div class="no-results-box">
                        <div class="no-results-icon"><?= icon('search', '', 40) ?></div>
                        <h3>مقاله‌ای با این مشخصات یافت نشد</h3>
                        <p>لطفاً عبارت دیگری را جستجو کنید یا فیلتر دسته‌بندی را تغییر دهید.</p>
                        <a href="<?= url('blog') ?>" class="btn btn-primary">مشاهده همه مقالات</a>
                    </div>
                <?php else: ?>
                    
                    <!-- Featured Article (if no search filter applied) -->
                    <?php if (empty($searchQuery) && empty($currentCategory) && !empty($featuredArticle)): ?>
                        <?php 
                        $fImg = $featuredArticle['image'];
                        $fImgUrl = (str_starts_with($fImg, 'http') || str_starts_with($fImg, '/uploads') || str_starts_with($fImg, 'uploads/'))
                            ? (str_starts_with($fImg, 'http') ? $fImg : url($fImg))
                            : asset('images/' . $fImg);
                        ?>
                        <article class="featured-article-card">
                            <div class="featured-card-badge">ویژه و برگزیده</div>
                            <div class="featured-card-image">
                                <img src="<?= $fImgUrl ?>" alt="<?= e($featuredArticle['title']) ?>">
                            </div>
                            <div class="featured-card-body">
                                <div class="article-meta">
                                    <span class="meta-item meta-category"><?= e($featuredArticle['category']) ?></span>
                                    <span class="meta-item meta-date"><?= icon('calendar', '', 14) ?> <?= e($featuredArticle['date']) ?></span>
                                    <span class="meta-item meta-read"><?= icon('clock', '', 14) ?> <?= e($featuredArticle['read_time']) ?> مطالعه</span>
                                </div>
                                <h2 class="featured-card-title">
                                    <a href="<?= url('blog/' . $featuredArticle['slug']) ?>"><?= e($featuredArticle['title']) ?></a>
                                </h2>
                                <p class="featured-card-excerpt"><?= e($featuredArticle['excerpt']) ?></p>
                                <div class="featured-card-footer">
                                    <div class="author-info">
                                        <div class="author-avatar"><?= icon('technician', '', 20) ?></div>
                                        <div class="author-text">
                                            <strong><?= e($featuredArticle['author']) ?></strong>
                                            <span><?= e($featuredArticle['author_role']) ?></span>
                                        </div>
                                    </div>
                                    <a href="<?= url('blog/' . $featuredArticle['slug']) ?>" class="btn btn-primary btn-read-more">
                                        <span>مطالعه کامل مقاله</span>
                                        <span class="arrow">←</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endif; ?>

                    <!-- Standard Grid of Articles -->
                    <div class="articles-grid">
                        <?php foreach ($articles as $art): ?>
                            <?php 
                            $aImg = $art['image'];
                            $aImgUrl = (str_starts_with($aImg, 'http') || str_starts_with($aImg, '/uploads') || str_starts_with($aImg, 'uploads/'))
                                ? (str_starts_with($aImg, 'http') ? $aImg : url($aImg))
                                : asset('images/' . $aImg);
                            ?>
                            <article class="article-grid-card">
                                <div class="article-card-image">
                                    <a href="<?= url('blog/' . $art['slug']) ?>">
                                        <img src="<?= $aImgUrl ?>" alt="<?= e($art['title']) ?>" loading="lazy">
                                    </a>
                                    <span class="article-badge-cat"><?= e($art['category']) ?></span>
                                </div>
                                <div class="article-card-body">
                                    <div class="article-card-meta">
                                        <span class="meta-date"><?= icon('calendar', '', 14) ?> <?= e($art['date']) ?></span>
                                        <span class="meta-time"><?= icon('clock', '', 14) ?> <?= e($art['read_time']) ?></span>
                                    </div>
                                    <h3 class="article-card-title">
                                        <a href="<?= url('blog/' . $art['slug']) ?>"><?= e($art['title']) ?></a>
                                    </h3>
                                    <p class="article-card-excerpt"><?= e($art['excerpt']) ?></p>
                                </div>
                                <div class="article-card-footer">
                                    <span class="author-name"><?= icon('user', '', 14) ?> <?= e($art['author']) ?></span>
                                    <a href="<?= url('blog/' . $art['slug']) ?>" class="card-link-more">
                                        <span>ادامه مطلب</span>
                                        <span class="arrow">←</span>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Dynamic Pagination -->
                    <?php if (!empty($totalPages) && $totalPages > 1): ?>
                        <div class="archive-pagination">
                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <?php
                                $pageQuery = http_build_query(array_merge($_GET, ['page' => $p]));
                                ?>
                                <?php if ($p == ($currentPageNum ?? 1)): ?>
                                    <span class="page-num active"><?= $p ?></span>
                                <?php else: ?>
                                    <a href="<?= url('blog?' . $pageQuery) ?>" class="page-num"><?= $p ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                
                <!-- Need Technician Box -->
                <div class="sidebar-widget widget-emergency-cta">
                    <div class="cta-shield-icon">
                        <img src="<?= asset('images/gold-shield-medal.png') ?>" alt="نمایندگی رسمی ایران رادیاتور">
                    </div>
                    <h3 class="widget-cta-title">پکیج شما ارور داده یا روشن نمی‌شود؟</h3>
                    <p class="widget-cta-desc">تکنسین‌های مجاز ترموبان در کمتر از ۱ ساعت با قطعات اصل شرکتی و ۶ ماه گارانتی در محل شما حاضر می‌شوند.</p>
                    <a href="tel:02177428596" class="btn btn-primary w-100 widget-cta-btn">
                        <?= icon('phone', '', 18) ?>
                        <span>تماس فوری: ۰۲۱-۷۷۴۲۸۵۹۶</span>
                    </a>
                    <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener" class="widget-bale-link">
                        <span>یا ارتباط آنلاین در پیام‌رسان بله</span>
                    </a>
                </div>

                <!-- Categories Widget -->
                <div class="sidebar-widget">
                    <h4 class="widget-title">دسته‌بندی‌های آموزشی</h4>
                    <ul class="widget-cat-list">
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="<?= url('blog' . ($cat['slug'] ? '?cat=' . $cat['slug'] : '')) ?>" class="cat-list-item <?= ($currentCategory === $cat['slug']) ? 'active' : '' ?>">
                                    <span><?= e($cat['title']) ?></span>
                                    <span class="count"><?= $cat['count'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Popular Articles Widget -->
                <div class="sidebar-widget">
                    <h4 class="widget-title">پربازدیدترین راهنماها</h4>
                    <ul class="widget-recent-list">
                        <?php foreach ($recentArticles as $recent): ?>
                            <li class="recent-article-item">
                                <a href="<?= url('blog/' . $recent['slug']) ?>" class="recent-thumb">
                                    <img src="<?= asset('images/' . $recent['image']) ?>" alt="<?= e($recent['title']) ?>">
                                </a>
                                <div class="recent-info">
                                    <a href="<?= url('blog/' . $recent['slug']) ?>" class="recent-title"><?= e($recent['title']) ?></a>
                                    <span class="recent-date"><?= e($recent['date']) ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </aside>

        </div>

    </div>
</section>
