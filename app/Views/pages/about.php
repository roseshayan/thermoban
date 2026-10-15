<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner about-banner">
    <div class="site-container">
        <div class="page-banner-content">
            <nav class="breadcrumbs" aria-label="مسیر راهنما">
                <a href="<?= url() ?>">صفحه اصلی</a>
                <span class="sep">/</span>
                <span class="current">درباره ما</span>
            </nav>
            <h1 class="page-title">درباره مرکز تخصصی پکیج و رادیاتور ترموبان</h1>
            <p class="page-subtitle">نمایندگی رسمی و کد مجاز ۷۲۷۳ ایران رادیاتور و بوتان با بیش از یک دهه خدمت‌رسانی صادقانه در تهران</p>
        </div>
    </div>
</section>

<!-- About Story & Introduction Section -->
<section class="about-story-section">
    <div class="site-container">
        <div class="about-story-grid">
            
            <!-- Right: Text & Philosophy -->
            <div class="about-text-col">
                <div class="section-tag-badge">داستان ترموبان</div>
                <h2 class="about-heading">بیش از ۱۵ سال تعهد به گرمای خانه‌های شما با بالاترین استانداردهای فنی</h2>
                
                <p class="about-paragraph">
                    مجموعه تخصصی <strong>ترموبان</strong> از سال ۱۳۸۹ با هدف ارائه خدمات مهندسی، استاندارد و باکیفیت در زمینه نصب، سرویس، عیب‌یابی و تعمیرات انواع پکیج شوفاژ دیواری و سیستم‌های حرارتی در تهران آغاز به کار نمود.
                </p>
                <p class="about-paragraph">
                    ما با کسب <strong>کد مجاز نمایندگی ۷۲۷۳ ایران رادیاتور</strong> و گواهینامه‌های رسمی از سازمان آموزش فنی و حرفه‌ای کشور، توانسته‌ایم تیمی از برترین تکنسین‌های آموزش‌دیده را گرد هم آوریم تا دغدغه خانواده‌ها در خصوص سرمای ناگهانی، هزینه‌های غیرمنصفانه و قطعات تقلبی را برای همیشه برطرف کنیم.
                </p>

                <!-- Core Pillars Checklist -->
                <div class="about-pillars">
                    <div class="pillar-item">
                        <span class="pillar-icon"><?= icon('medal', 'text-orange', 22) ?></span>
                        <div>
                            <strong>کد مجاز نمایندگی ۷۲۷۳</strong>
                            <p>فعالیت رسمی تحت نظارت اتحادیه شوفاژکاران و تأسیسات مکانیکی تهران</p>
                        </div>
                    </div>
                    <div class="pillar-item">
                        <span class="pillar-icon"><?= icon('shield', 'text-orange', 22) ?></span>
                        <div>
                            <strong>۶ ماه ضمانت کتبی خدمات</strong>
                            <p>تضمین بی‌قیدوشرط کارکرد قطعات تعویض شده و خدمات انجام‌شده</p>
                        </div>
                    </div>
                    <div class="pillar-item">
                        <span class="pillar-icon"><?= icon('clock', 'text-orange', 22) ?></span>
                        <div>
                            <strong>حضور در محل در کمتر از ۱ ساعت</strong>
                            <p>بهره‌گیری از شبکه تکنسین‌های سیار مستقر در ۲۲ منطقه پایتخت</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Left: License & Badge Visual -->
            <div class="about-visual-col">
                <div class="about-visual-card">
                    <img src="<?= asset('images/gold-shield-medal.png') ?>" alt="نمایندگی رسمی ایران رادیاتور" class="about-gold-medal">
                    <div class="about-card-info">
                        <h3>نمایندگی مجاز کد ۷۲۷۳</h3>
                        <p class="text-muted">ایران رادیاتور و بوتان</p>
                        <div class="badge-pill">دارای پروانه کسب معتبر</div>
                    </div>
                </div>
                <div class="about-sub-image">
                    <img src="<?= asset('images/hero-technician.jpg') ?>" alt="تکنسین‌های مجرب ترموبان" class="about-tech-photo">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Stats Counters Section -->
<section class="about-stats-section">
    <div class="site-container">
        <div class="about-stats-grid">
            <?php foreach ($stats as $s): ?>
                <div class="about-stat-card">
                    <div class="stat-number"><?= e($s['value']) ?></div>
                    <div class="stat-title"><?= e($s['label']) ?></div>
                    <div class="stat-desc"><?= e($s['desc']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Values & Guarantees Grid -->
<section class="about-values-section">
    <div class="site-container">
        <div class="section-header text-center">
            <span class="section-tag-badge">چرا ترموبان؟</span>
            <h2 class="section-title">اصول و استانداردهایی که به آن پایبندیم</h2>
            <p class="section-subtitle">رضایت و آرامش شما در روزهای سرد سال، مهم‌ترین سرمایه و اولویت ماست</p>
        </div>

        <div class="values-grid">
            <?php foreach ($values as $v): ?>
                <div class="value-card">
                    <div class="value-icon-box">
                        <img src="<?= asset('images/' . $v['icon']) ?>" alt="<?= e($v['title']) ?>">
                    </div>
                    <h3 class="value-card-title"><?= e($v['title']) ?></h3>
                    <p class="value-card-desc"><?= e($v['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="about-team-section">
    <div class="site-container">
        <div class="section-header text-center">
            <span class="section-tag-badge">کادر فنی</span>
            <h2 class="section-title">تکنسین‌های ارشد و کادر مدیریتی ترموبان</h2>
            <p class="section-subtitle">متخصصانی با مدارک بین‌المللی و تعهد کامل به اخلاق حرفه‌ای</p>
        </div>

        <div class="team-grid">
            <?php foreach ($team as $member): ?>
                <div class="team-card">
                    <div class="team-avatar-box">
                        <img src="<?= asset('images/' . $member['image']) ?>" alt="<?= e($member['name']) ?>" class="team-photo">
                        <div class="team-exp-badge"><?= e($member['exp']) ?></div>
                    </div>
                    <div class="team-card-info">
                        <h4 class="team-name"><?= e($member['name']) ?></h4>
                        <span class="team-role"><?= e($member['role']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Bar -->
<section class="about-cta-bar">
    <div class="site-container">
        <div class="about-cta-box">
            <div class="cta-content">
                <h3>همین حالا پکیج خود را با خیالی آسوده به کارشناسان بسپارید</h3>
                <p>مشاوره رایگان تلفنی و اعزام فوری تکنسین به کلیه مناطق تهران</p>
            </div>
            <div class="cta-actions">
                <a href="tel:02177428596" class="btn btn-primary btn-lg">
                    <span><?= icon('phone', '', 18) ?> ۰۲۱-۷۷۴۲۸۵۹۶</span>
                </a>
                <a href="<?= url('contact') ?>" class="btn btn-outline btn-lg">
                    <span>ارتباط با شعب</span>
                </a>
            </div>
        </div>
    </div>
</section>
