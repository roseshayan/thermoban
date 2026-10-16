<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<!-- Page Hero / Breadcrumbs Banner -->
<section class="page-banner services-banner" style="background: linear-gradient(135deg, #011C39 0%, #032d5a 100%); color: #fff; padding: 50px 0 45px 0;">
    <div class="site-container">
        <div class="page-banner-content" style="max-width: 820px;">
            <nav class="breadcrumbs" aria-label="مسیر راهنما" style="margin-bottom: 12px; font-size: 0.85rem; color: #94a3b8;">
                <a href="<?= url() ?>" style="color: #cbd5e1; text-decoration: none;">صفحه اصلی</a>
                <span class="sep" style="margin: 0 6px;">/</span>
                <a href="<?= url('services') ?>" style="color: #cbd5e1; text-decoration: none;">خدمات ما</a>
                <span class="sep" style="margin: 0 6px;">/</span>
                <span class="current" style="color: #EF5C16; font-weight: 700;">ثبت سفارش خدمات و اعزام تکنسین</span>
            </nav>
            <h1 class="page-title" style="font-size: 1.85rem; font-weight: 800; margin-bottom: 10px; line-height: 1.4;">
                <i class="fa-solid fa-screwdriver-wrench text-orange" style="margin-left: 8px;"></i>
                فرم جامع ثبت سفارش آنلاین تعمیرات و سرویس پکیج
            </h1>
            <p class="page-subtitle" style="font-size: 0.95rem; color: #cbd5e1; line-height: 1.6; margin: 0;">
                اعزام نزدیک‌ترین تکنسین مجاز در ۲۲ منطقه تهران کمتر از ۶۰ دقیقه | امکان پیوست عکس و ویدیو جهت عیب‌یابی اولیه دقیق
            </p>
        </div>
    </div>
</section>

<section class="service-order-section" style="padding: 45px 0 70px 0; background: #f8fafc;">
    <div class="site-container">

        <?php if (!empty($error)): ?>
            <div class="form-alert-msg alert-error" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 16px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; font-size: 0.92rem;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.2rem; color: #dc2626;"></i>
                <span><?= e($error) ?></span>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 30px; align-items: start;">
            
            <!-- Left: Main Booking Form -->
            <form action="<?= url('service-order') ?>" method="POST" enctype="multipart/form-data" id="serviceOrderForm" style="background: #fff; border-radius: 18px; padding: 35px 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                <?= csrf_field() ?>

                <!-- Step 1: مشخصات خدمت و پکیج -->
                <div class="form-section-block" style="margin-bottom: 35px; border-bottom: 1px solid #f1f5f9; padding-bottom: 25px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <span style="width: 32px; height: 32px; background: #ffedd5; color: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">۱</span>
                        <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">نوع خدمت و مشخصات دستگاه</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">
                                نوع خدمت درخواستی <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="service_type" id="serviceTypeSelect" class="form-control" required style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                                <option value="">-- انتخاب خدمت مورد نظر --</option>
                                <?php if (!empty($tariffs)): ?>
                                    <?php foreach ($tariffs as $t): ?>
                                        <option value="<?= e($t['title']) ?>" <?= ($selectedService === $t['title'] || $selectedService === $t['service_key']) ? 'selected' : '' ?>>
                                            <?= e($t['title']) ?> (<?= e($t['price']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="تعمیر تخصصی پکیج شوفاژ دیواری">تعمیر تخصصی پکیج شوفاژ دیواری</option>
                                    <option value="سرویس دوره‌ای و اسیدشویی مبدل‌ها">سرویس دوره‌ای و اسیدشویی مبدل‌ها</option>
                                    <option value="نصب اصولی و استاندارد پکیج دیواری">نصب اصولی و استاندارد پکیج دیواری</option>
                                    <option value="راه‌اندازی فصلی و تنظیمات هوشمند">راه‌اندازی فصلی و تنظیمات هوشمند</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">
                                برند پکیج <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="brand" class="form-control" required style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                                <option value="">-- برند دستگاه شما --</option>
                                <?php foreach ($brands as $b): ?>
                                    <option value="<?= e($b) ?>"><?= e($b) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">مدل پکیج (اختیاری)</label>
                            <input type="text" name="model" class="form-control" placeholder="مثال: L24FF، اکو، پرلا پرو، اپتیما..." style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">نوع مشکل یا کد خطا</label>
                            <input type="text" name="problem_type" id="problemTypeInput" class="form-control" placeholder="مثال: ارور E81، خاموش شدن ناگهانی، نشت آب..." style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                        </div>
                    </div>

                    <!-- Quick Issue Selection Tags -->
                    <div style="margin-top: 10px;">
                        <label style="font-size: 0.82rem; color: #64748b; font-weight: 600; margin-bottom: 8px; display: block;">انتخاب سریع شایع‌ترین مشکلات پکیج:</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php foreach ($commonProblems as $cp): ?>
                                <button type="button" class="issue-tag-chip" onclick="selectProblemTag('<?= e(addslashes($cp)) ?>')" style="background: #f1f5f9; border: 1px solid #e2e8f0; color: #334155; font-size: 0.78rem; padding: 5px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
                                    + <?= e($cp) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">توضیحات تکمیلی درباره رفتار دستگاه</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="توضیح دهید از چه زمانی مشکل به وجود آمده؟ آیا صدای غیرعادی می‌دهد یا آبگرم قطع است؟" style="border-radius: 10px; font-size: 0.88rem; padding: 12px;"></textarea>
                    </div>
                </div>

                <!-- Step 2: پیوست عکس و ویدیو (Media Uploads) -->
                <div class="form-section-block" style="margin-bottom: 35px; border-bottom: 1px solid #f1f5f9; padding-bottom: 25px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">۲</span>
                            <div>
                                <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">پیوست عکس و ویدیو از مشکل دستگاه (اختیاری ولی موثر)</h3>
                                <span style="font-size: 0.8rem; color: #64748b;">ارسال عکس از پنل ارور، لوله‌کشی یا ویدیوی صدای دستگاه به تکنسین کمک می‌کند قطعات لازم را از قبل همراه بیاورد</span>
                            </div>
                        </div>
                        <span style="font-size: 0.78rem; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                            <i class="fa-solid fa-shield-check"></i> عیب‌یابی سریع‌تر
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- Photos Dropzone -->
                        <div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 18px; text-align: center; background: #f8fafc; transition: border-color 0.2s;" id="photoDropArea">
                            <i class="fa-solid fa-camera-retro" style="font-size: 2rem; color: #0284c7; margin-bottom: 8px; display: block;"></i>
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; color: #011C39; font-weight: 700;">آپلود عکس‌های پکیج</h4>
                            <p style="font-size: 0.76rem; color: #64748b; margin: 0 0 12px 0;">فرمت‌های JPG, PNG, WEBP (حداکثر ۵ عکس - هر فایل تا ۱۰ مگابایت)</p>
                            
                            <label for="photosInput" class="btn btn-outline" style="font-size: 0.82rem; padding: 8px 16px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-image"></i>
                                <span>انتخاب عکس‌ها</span>
                            </label>
                            <input type="file" name="photos[]" id="photosInput" multiple accept="image/*" style="display: none;" onchange="handlePhotoPreviews(this)">

                            <!-- Photo Previews Container -->
                            <div id="photoPreviewsList" style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; justify-content: center;"></div>
                        </div>

                        <!-- Video Upload Box -->
                        <div style="border: 2px dashed #fed7aa; border-radius: 12px; padding: 18px; text-align: center; background: #fffaf5; transition: border-color 0.2s;" id="videoDropArea">
                            <i class="fa-solid fa-video" style="font-size: 2rem; color: #ea580c; margin-bottom: 8px; display: block;"></i>
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; color: #011C39; font-weight: 700;">آپلود ویدیوی کوتاه از نقص دستگاه</h4>
                            <p style="font-size: 0.76rem; color: #64748b; margin: 0 0 12px 0;">فرمت‌های MP4, WEBM, MOV (حداکثر تا ۵۰ مگابایت)</p>
                            
                            <label for="videoInput" class="btn btn-outline" style="font-size: 0.82rem; padding: 8px 16px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; border-color: #fdba74; color: #c2410c;">
                                <i class="fa-solid fa-file-video"></i>
                                <span>انتخاب فایل ویدیو</span>
                            </label>
                            <input type="file" name="video" id="videoInput" accept="video/mp4,video/webm,video/quicktime" style="display: none;" onchange="handleVideoPreview(this)">

                            <!-- Video Status Preview -->
                            <div id="videoFileInfo" style="margin-top: 10px; font-size: 0.8rem; color: #059669; font-weight: 700; display: none;"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: زمان‌بندی و فوریت اعزام تکنسین -->
                <div class="form-section-block" style="margin-bottom: 35px; border-bottom: 1px solid #f1f5f9; padding-bottom: 25px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <span style="width: 32px; height: 32px; background: #fef3c7; color: #d97706; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">۳</span>
                        <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">زمان و فوریت اعزام تکنسین</h3>
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 8px; display: block;">فوریت اعزام تکنسین:</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                            <label style="border: 2px solid #fdba74; background: #fffaf5; border-radius: 10px; padding: 12px 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="urgency" value="urgent" checked style="accent-color: #EF5C16; width: 18px; height: 18px;">
                                <div>
                                    <strong style="color: #c2410c; font-size: 0.9rem; display: block;">فوری (اعزام کمتر از ۲ ساعت)</strong>
                                    <span style="font-size: 0.74rem; color: #64748b;">مناسب در صورت قطعی کامل آب یا نشت شدید</span>
                                </div>
                            </label>

                            <label style="border: 1px solid #e2e8f0; background: #fff; border-radius: 10px; padding: 12px 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="urgency" value="normal" style="accent-color: #EF5C16; width: 18px; height: 18px;">
                                <div>
                                    <strong style="color: #011C39; font-size: 0.9rem; display: block;">عادی (در طول امروز)</strong>
                                    <span style="font-size: 0.74rem; color: #64748b;">اعزام در بازه زمانی تعیین شده شما</span>
                                </div>
                            </label>

                            <label style="border: 1px solid #e2e8f0; background: #fff; border-radius: 10px; padding: 12px 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="urgency" value="scheduled" style="accent-color: #EF5C16; width: 18px; height: 18px;">
                                <div>
                                    <strong style="color: #011C39; font-size: 0.9rem; display: block;">برنامه‌ریزی برای روزهای آتی</strong>
                                    <span style="font-size: 0.74rem; color: #64748b;">انتخاب تاریخ دلخواه جهت سرویس دوره‌ای</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">روز مراجعه تکنسین</label>
                            <select name="preferred_date" class="form-control" style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                                <option value="امروز">امروز (سریع‌ترین تکنسین کشیک)</option>
                                <option value="فردا">فردا</option>
                                <option value="پس‌فردا">پس‌فردا</option>
                                <option value="آخر هفته (پنجشنبه/جمعه)">آخر هفته (پنجشنبه یا جمعه)</option>
                                <option value="با هماهنگی تلفنی">با هماهنگی تلفنی قبل از حرکت</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">بازه ساعتی حضور در منزل/محل</label>
                            <select name="time_slot" class="form-control" style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                                <option value="سریع‌ترین زمان ممکن (اورژانسی)">سریع‌ترین زمان ممکن (اورژانسی)</option>
                                <?php foreach ($timeSlots as $slot): ?>
                                    <option value="<?= e($slot) ?>"><?= e($slot) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 4: نشانی و مشخصات تماس مشتری -->
                <div class="form-section-block" style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="width: 32px; height: 32px; background: #dcfce7; color: #15803d; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">۴</span>
                            <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">اطلاعات تماس و نشانی دقیق محل دستگاه</h3>
                        </div>

                        <?php if ($user && !empty($userAddresses)): ?>
                            <div style="font-size: 0.82rem; color: #0369a1; background: #e0f2fe; padding: 5px 12px; border-radius: 6px; font-weight: 600;">
                                <i class="fa-solid fa-address-book" style="margin-left: 4px;"></i>
                                آدرس‌های ذخیره‌شده شما در دسترس است
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($user && !empty($userAddresses)): ?>
                        <div style="margin-bottom: 18px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 14px 18px; border-radius: 12px;">
                            <label style="font-weight: 700; font-size: 0.86rem; color: #166534; display: block; margin-bottom: 6px;">
                                انتخاب از آدرس‌های قبلی ذخیره‌شده در حساب کاربری:
                            </label>
                            <select id="savedAddressSelector" class="form-control" style="border-radius: 8px; font-size: 0.88rem; height: 42px;" onchange="applySavedAddress(this)">
                                <option value="">-- نشانی جدید وارد می‌کنم --</option>
                                <?php foreach ($userAddresses as $ua): ?>
                                    <option value="<?= e(htmlspecialchars(json_encode($ua))) ?>">
                                        <?= e($ua['title']) ?> (<?= e($ua['district']) ?>) - <?= e($ua['address']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">نام و نام خانوادگی <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="customer_name" id="custNameInput" class="form-control" value="<?= e($user['name'] ?? '') ?>" placeholder="مثال: مهدی رحمانی" required style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                        </div>

                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">شماره همراه جهت هماهنگی <span style="color: #ef4444;">*</span></label>
                            <input type="tel" name="customer_phone" id="custPhoneInput" dir="ltr" class="form-control" value="<?= e($user['phone'] ?? '') ?>" placeholder="۰۹۱۲۳۴۵۶۷۸۹" required style="border-radius: 10px; height: 46px; font-size: 0.95rem; font-family: monospace;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 18px; margin-bottom: 18px;">
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">منطقه شهرداری تهران <span style="color: #ef4444;">*</span></label>
                            <select name="district" id="custDistrictSelect" class="form-control" required style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                                <option value="">-- انتخاب منطقه --</option>
                                <?php for ($i = 1; $i <= 22; $i++): ?>
                                    <option value="منطقه <?= fa_num($i) ?>">منطقه <?= fa_num($i) ?> تهران</option>
                                <?php endfor; ?>
                                <option value="پردیس و بومهن">پردیس و بومهن</option>
                                <option value="لواسانات و فشم">لواسانات و فشم</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-bottom: 6px; display: block;">نشانی دقیق (خیابان، کوچه، پلاک، زنگ/واحد) <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="address" id="custAddressInput" class="form-control" placeholder="مثال: تهرانپارس، فلکه اول، خ رشید، پلاک ۲۴، زنگ ۳" required style="border-radius: 10px; height: 46px; font-size: 0.9rem;">
                        </div>
                    </div>

                    <?php if ($user): ?>
                        <div style="margin-top: 10px;">
                            <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.86rem; color: #334155;">
                                <input type="checkbox" name="save_address" value="1" checked style="width: 17px; height: 17px; accent-color: #EF5C16;">
                                <span>ذخیره این نشانی در دفترچه آدرس‌های پروفایل من جهت سفارش‌های بعدی</span>
                            </label>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Anti-Spam Captcha & Submit -->
                <div style="background: #f8fafc; border-radius: 14px; padding: 20px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin: 0;">کد امنیتی ضد اسپم:</label>
                        <?= \App\Services\CaptchaService::render() ?>
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding: 14px 38px; font-size: 1.05rem; font-weight: 800; border-radius: 10px; box-shadow: 0 4px 14px rgba(239, 92, 22, 0.35);">
                        <i class="fa-solid fa-paper-plane" style="margin-left: 8px;"></i>
                        ثبت نهایی و دریافت کد رهگیری
                    </button>
                </div>
            </form>

            <!-- Right: Service Highlights & Guarantee Sidebar -->
            <div class="service-order-sidebar">
                
                <!-- Guarantee Box -->
                <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.03); margin-bottom: 24px;">
                    <div style="width: 50px; height: 50px; background: #fff7ed; color: #ea580c; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 16px;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h4 style="margin: 0 0 8px 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">ضمانت کتبی ۶ ماهه</h4>
                    <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0 0 16px 0;">
                        تمامی خدمات عیب‌یابی، تعمیرات و قطعات یدکی تعویض شده دارای ۶ ماه ضمانت کتبی شرکتی با فاکتور رسمی می‌باشند.
                    </p>
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; font-size: 0.82rem; color: #059669; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>نمایندگی رسمی کد مجاز </span>
                    </div>
                </div>

                <!-- Fast Dispatch Info -->
                <div class="admin-card" style="background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.03); margin-bottom: 24px;">
                    <h4 style="margin: 0 0 14px 0; font-size: 1rem; color: #011C39; font-weight: 800;">
                        <i class="fa-solid fa-clock-rotate-left text-orange" style="margin-left: 6px;"></i>
                        مراحل پس از ثبت سفارش
                    </h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.84rem; color: #475569; line-height: 1.9;">
                        <li style="margin-bottom: 10px; display: flex; gap: 8px;">
                            <span style="color: #EF5C16; font-weight: 800;">۱.</span>
                            <span>صدور فوری پیامک کد پیگیری یکتا به موبایل شما</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; gap: 8px;">
                            <span style="color: #EF5C16; font-weight: 800;">۲.</span>
                            <span>بررسی عکس‌ها و ویدیوی ارسالی توسط سرپرست فنی</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; gap: 8px;">
                            <span style="color: #EF5C16; font-weight: 800;">۳.</span>
                            <span>تماس تکنسین محلی منطقه جهت هماهنگی ساعت حرکت</span>
                        </li>
                        <li style="display: flex; gap: 8px;">
                            <span style="color: #EF5C16; font-weight: 800;">۴.</span>
                            <span>تعمیر در محل، تست عملکرد و ارائه فاکتور رسمی</span>
                        </li>
                    </ul>
                </div>

                <!-- Emergency Contact CTA -->
                <div class="admin-card" style="background: linear-gradient(135deg, #011C39 0%, #032d5a 100%); color: #fff; border-radius: 16px; padding: 24px; text-align: center;">
                    <i class="fa-solid fa-phone-volume" style="font-size: 2.2rem; color: #EF5C16; margin-bottom: 12px; display: block;"></i>
                    <h4 style="margin: 0 0 6px 0; font-size: 1.05rem; font-weight: 800;">نیاز به مشاوره تلفنی فوری دارید؟</h4>
                    <p style="font-size: 0.8rem; color: #cbd5e1; margin: 0 0 16px 0;">تیم کشیک ۲۴ ساعته آماده راهنمایی و اعزام اضطراری است</p>
                    <a href="tel:02177428596" class="btn btn-primary" style="display: block; width: 100%; padding: 10px 0; font-weight: 800; border-radius: 8px; text-decoration: none; font-size: 0.95rem;">
                        <i class="fa-solid fa-phone" style="margin-left: 6px;"></i>
                        تماس: ۰۲۱-۷۷۴۲۸۵۹۶
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

<script>
function selectProblemTag(text) {
    var inp = document.getElementById('problemTypeInput');
    if (inp) {
        if (inp.value.trim() === '') {
            inp.value = text;
        } else {
            inp.value += ' - ' + text;
        }
    }
}

function applySavedAddress(sel) {
    if (!sel.value) return;
    try {
        var addr = JSON.parse(sel.value);
        if (addr.district) document.getElementById('custDistrictSelect').value = addr.district;
        if (addr.address) document.getElementById('custAddressInput').value = addr.address;
        if (addr.phone) document.getElementById('custPhoneInput').value = addr.phone;
    } catch(e) {}
}

function handlePhotoPreviews(input) {
    var container = document.getElementById('photoPreviewsList');
    container.innerHTML = '';
    if (!input.files || input.files.length === 0) return;

    for (var i = 0; i < input.files.length; i++) {
        var file = input.files[i];
        if (i >= 5) break; // max 5

        var reader = new FileReader();
        reader.onload = (function(f) {
            return function(e) {
                var box = document.createElement('div');
                box.style.width = '64px';
                box.style.height = '64px';
                box.style.borderRadius = '8px';
                box.style.overflow = 'hidden';
                box.style.border = '2px solid #0284c7';
                box.style.boxShadow = '0 2px 6px rgba(0,0,0,0.1)';
                box.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: cover;" alt="پیش‌نمایش">';
                container.appendChild(box);
            };
        })(file);
        reader.readAsDataURL(file);
    }
}

function handleVideoPreview(input) {
    var info = document.getElementById('videoFileInfo');
    if (!input.files || input.files.length === 0) {
        info.style.display = 'none';
        return;
    }
    var file = input.files[0];
    var mb = (file.size / (1024 * 1024)).toFixed(1);
    if (file.size > 50 * 1024 * 1024) {
        info.style.display = 'block';
        info.style.color = '#dc2626';
        info.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> حجم ویدیو (' + mb + 'MB) بیشتر از سقف مجاز ۵۰ مگابایت است.';
        input.value = '';
    } else {
        info.style.display = 'block';
        info.style.color = '#059669';
        info.innerHTML = '<i class="fa-solid fa-circle-check"></i> ویدیو انتخاب شد: ' + file.name + ' (' + mb + 'MB)';
    }
}
</script>
