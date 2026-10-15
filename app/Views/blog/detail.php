<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner article-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <a href="<?= url('blog') ?>">مقالات آموزشی</a>
                <span class="sep">/</span>
                <span class="current"><?= e($article['title']) ?></span>
            </nav>
            <div class="article-header-meta">
                <span class="article-cat-tag"><?= e($article['category']) ?></span>
                <span class="meta-dot">•</span>
                <span><?= icon('calendar', '', 14) ?> انتشار: <?= e($article['date']) ?></span>
                <span class="meta-dot">•</span>
                <span><?= icon('clock', '', 14) ?> زمان مطالعه: <?= e($article['read_time']) ?></span>
                <span class="meta-dot">•</span>
                <span><?= icon('eye', '', 14) ?> بازدید: <?= e(number_format($article['views'])) ?> مرتبه</span>
            </div>
            <h1 class="page-title article-single-title"><?= e($article['title']) ?></h1>
        </div>
    </div>
</section>

<!-- Single Article Main Section -->
<section class="article-single-section">
    <div class="site-container">
        <div class="blog-layout-grid">
            
            <!-- Article Body Main -->
            <main class="article-content-main">
                
                <!-- Featured Image -->
                <div class="article-main-image">
                    <img src="<?= asset('images/' . $article['image']) ?>" alt="<?= e($article['title']) ?>">
                </div>

                <!-- Rich Content -->
                <div class="article-rich-body">
                    <?= $article['content'] ?>
                </div>

                <!-- Author Bio Card -->
                <div class="article-author-card">
                    <div class="author-avatar-lg"><?= icon('technician', '', 32) ?></div>
                    <div class="author-card-content">
                        <div class="author-header">
                            <h4 class="author-name"><?= e($article['author']) ?></h4>
                            <span class="author-role-badge"><?= e($article['author_role']) ?></span>
                        </div>
                        <p class="author-desc">
                            متخصص ارشد سیستم‌های حرارتی و برودتی با بیش از یک دهه تجربه عملی در نصب، عیب‌یابی و سرویس انواع پکیج‌های دیواری ایران رادیاتور و بوتان در ۲۲ منطقه تهران.
                        </p>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="article-share-bar">
                    <span class="share-title">اشتراک‌گذاری این راهنما:</span>
                    <div class="share-links">
                        <a href="https://ble.ir/share/url?url=<?= urlencode(url('blog/' . $article['slug'])) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener" class="share-btn share-bale" title="اشتراک در بله">
                            <span>پیام‌رسان بله</span>
                        </a>
                        <a href="https://t.me/share/url?url=<?= urlencode(url('blog/' . $article['slug'])) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener" class="share-btn share-telegram" title="اشتراک در تلگرام">
                            <span>تلگرام</span>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' ' . url('blog/' . $article['slug'])) ?>" target="_blank" rel="noopener" class="share-btn share-whatsapp" title="اشتراک در واتساپ">
                            <span>واتساپ</span>
                        </a>
                        <button type="button" class="share-btn share-copy" id="copyArticleLink" data-url="<?= url('blog/' . $article['slug']) ?>" title="کپی لینک مقاله">
                            <?= icon('copy', '', 14) ?>
                            <span>کپی لینک</span>
                        </button>
                    </div>
                </div>

                <!-- Related Articles Section -->
                <?php if (!empty($relatedArticles)): ?>
                    <div class="related-articles-section">
                        <h3 class="related-title">مقالات و راهنماهای مرتبط</h3>
                        <div class="related-grid">
                            <?php foreach ($relatedArticles as $rel): ?>
                                <div class="related-card">
                                    <a href="<?= url('blog/' . $rel['slug']) ?>" class="related-thumb">
                                        <img src="<?= asset('images/' . $rel['image']) ?>" alt="<?= e($rel['title']) ?>">
                                    </a>
                                    <div class="related-body">
                                        <span class="related-cat"><?= e($rel['category']) ?></span>
                                        <h4 class="related-card-title">
                                            <a href="<?= url('blog/' . $rel['slug']) ?>"><?= e($rel['title']) ?></a>
                                        </h4>
                                        <span class="related-date"><?= icon('calendar', '', 12) ?> <?= e($rel['date']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Comments Section -->
                <div class="article-comments-section" id="comments">
                    <h3 class="comments-title">نظرات و پرسش‌های کاربران (<?= count($comments) ?>)</h3>
                    
                    <!-- Comments List -->
                    <div class="comments-list">
                        <?php foreach ($comments as $c): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <div class="comment-user">
                                        <div class="user-avatar-circle"><?= icon('user', '', 18) ?></div>
                                        <div class="user-meta">
                                            <strong class="user-name"><?= e($c['author']) ?></strong>
                                            <span class="comment-date"><?= e($c['date']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="comment-text">
                                    <p><?= e($c['text']) ?></p>
                                </div>

                                <?php if (!empty($c['reply'])): ?>
                                    <div class="comment-reply">
                                        <div class="reply-header">
                                            <div class="reply-badge">پاسخ کارشناس ترموبان</div>
                                        </div>
                                        <p class="reply-text"><?= e($c['reply']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- New Comment Form -->
                    <div class="comment-form-wrapper">
                        <h4 class="form-title">ثبت پرسش یا دیدگاه شما درباره این ارور</h4>
                        <p class="form-desc">اگر پکیج شما دچار مشکل شده یا در مورد این خطا سوالی دارید، بپرسید تا کارشناسان ما پاسخ دهند.</p>
                        
                        <form action="<?= url('api/comment') ?>" method="POST" class="comment-form js-validate-form" id="commentForm">
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                            <input type="hidden" name="article_id" value="<?= e($article['id']) ?>">
                            
                            <div class="form-row-2">
                                <div class="form-group">
                                    <label for="c_name">نام و نام خانوادگی <span class="required">*</span></label>
                                    <input type="text" id="c_name" name="name" class="form-control" placeholder="مثال: رضا کریمی" required data-msg="نام الزامی است">
                                </div>
                                <div class="form-group">
                                    <label for="c_phone">شماره همراه (جهت هماهنگی تکنسین)</label>
                                    <input type="tel" id="c_phone" name="phone" class="form-control" placeholder="۰۹۱۲۳۴۵۶۷۸۹" dir="ltr" data-type="phone">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="c_comment">متن دیدگاه یا شرح مشکل پکیج <span class="required">*</span></label>
                                <textarea id="c_comment" name="comment" rows="4" class="form-control" placeholder="مدل پکیج و شرح دقیق مشکل یا ارور را بنویسید..." required data-msg="متن دیدگاه الزامی است"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-submit-comment">
                                <?= icon('check', '', 18) ?>
                                <span>ارسال دیدگاه</span>
                            </button>
                        </form>
                    </div>

                </div>

            </main>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                
                <!-- Quick Emergency Request Widget -->
                <div class="sidebar-widget widget-emergency-cta">
                    <div class="cta-shield-icon">
                        <img src="<?= asset('images/gold-shield-medal.png') ?>" alt="نمایندگی رسمی ایران رادیاتور">
                    </div>
                    <h3 class="widget-cta-title">نیاز به اعزام فوری تکنسین در تهران دارید؟</h3>
                    <p class="widget-cta-desc">اعزام تکنسین مجرب با قطعات اورجینال در کمتر از ۶۰ دقیقه در ۲۲ منطقه تهران با ضمانت ۶ ماهه.</p>
                    <a href="tel:02177428596" class="btn btn-primary w-100 widget-cta-btn">
                        <?= icon('phone', '', 18) ?>
                        <span>تماس مستقیم: ۰۲۱-۷۷۴۲۸۵۹۶</span>
                    </a>
                    <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener" class="widget-bale-link">
                        <span>چت آنلاین در پیام‌رسان بله</span>
                    </a>
                </div>

                <!-- Price Transparency Widget -->
                <div class="sidebar-widget widget-transparent-price">
                    <h4 class="widget-title">مزایای سرویس توسط ترموبان</h4>
                    <ul class="widget-benefits-list">
                        <li>✔️ دارای کد مجاز نمایندگی ۷۲۷۳</li>
                        <li>✔️ صدور فاکتور رسمی و شرکتی</li>
                        <li>✔️ ۶ ماه ضمانت کتبی کلیه تعمیرات</li>
                        <li>✔️ قطعات ۱۰۰٪ اصل با هولوگرام</li>
                        <li>✔️ نرخ مصوب اتحادیه بدون هزینه پنهان</li>
                    </ul>
                </div>

            </aside>

        </div>
    </div>
</section>
