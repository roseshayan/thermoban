<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.5rem; color: #011C39; font-weight: 800; margin: 0 0 6px 0;">
                <i class="fa-solid fa-wand-magic-sparkles" style="color: #EF5C16; margin-left: 8px;"></i>
                مدیریت ماژول‌ها و پلن‌های تجاری (ویژه برنامه‌نویس)
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.88rem;">
                کنترل دسترسی ماژول‌های سامانه، درآمدزایی از امکانات اختصاصی و سوئیچ حالت نگهداری و تعمیرات سایت
            </p>
        </div>
        <div style="background: #fef3c7; color: #92400e; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; border: 1px solid #fde68a;">
            <i class="fa-solid fa-crown" style="margin-left: 4px;"></i>
            سطح دسترسی: سوپر ادمین (شایان نماینده)
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="form-alert-msg alert-success" style="margin-bottom: 20px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-circle-check" style="margin-left: 8px;"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="form-alert-msg alert-error" style="margin-bottom: 20px; background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 12px 18px; border-radius: 10px;">
        <i class="fa-solid fa-triangle-exclamation" style="margin-left: 8px;"></i>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<form action="<?= url('admin/modules') ?>" method="POST">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px; margin-bottom: 30px;">
        
        <!-- Module Card: Shop & E-Commerce -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #ffedd5; color: #ea580c; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">فروشگاه آنلاین پکیج و قطعات</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">کاتالوگ آنلاین، سبد خرید، تسویه حساب</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_shop" value="1" <?= ($settings['module_shop'] ?? '1') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                در صورت غیرفعال بودن، دسترسی به صفحات فروشگاه (/shop, /cart, /checkout) مسدود شده و پیام تماس تلفنی جهت سفارش به مشتریان نمایش داده می‌شود. (جهت دریافت هزینه فعال‌سازی فروشگاه از کارفرما)
            </p>
        </div>

        <!-- Module Card: Blog & Articles -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #e0f2fe; color: #0284c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">بخش مقالات و آموزش‌ها</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">تولید محتوا، راهنمای خطا و عیب‌یابی</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_blog" value="1" <?= ($settings['module_blog'] ?? '1') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                کنترل نمایش عمومی دانشنامه پکیج و مقالات تخصصی در صفحات وب‌سایت.
            </p>
        </div>

        <!-- Module Card: Support Ticketing -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #f3e8ff; color: #9333ea; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">سامانه تیکتینگ و رزرو</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">گفتگوی پشتیبانی، پیگیری گارانتی</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_tickets" value="1" <?= ($settings['module_tickets'] ?? '1') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                امکان ثبت و تبادل پیام متنی تیکت در پنل کاربری مشترکین به همراه ضمیمه مشخصات دستگاه.
            </p>
        </div>

        <!-- Module Card: Online Gateways -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #dcfce7; color: #16a34a; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">درگاه‌های پرداخت شاپرک</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">زرین‌پال، زیبال و تسویه بانکی</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_gateways" value="1" <?= ($settings['module_gateways'] ?? '1') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                امکان پرداخت آنلاین هزینه پکیج و بیعانه سرویس از طریق شبکه پرداخت الکترونیک شتاب.
            </p>
        </div>

        <!-- Module Card: SMS Notifications -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #fee2e2; color: #dc2626; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-comment-sms"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">وب‌سرویس پیامکی ملی‌پیامک</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">پیامک احراز هویت، ثبت سفارش و نصاب</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_sms" value="1" <?= ($settings['module_sms'] ?? '1') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                سیستم ارسال خودکار پیامک‌های خدماتی بدون بلک‌لیست بر پایه الگوهای خدماتی صوتی و متنی.
            </p>
        </div>

        <!-- Module Card: User Direct Registration -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #fef9c3; color: #ca8a04; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">ثبت‌نام مستقیم کاربران</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">عضویت عمومی در سایت</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_registration" value="1" <?= ($settings['module_registration'] ?? '1') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                امکان ایجاد خودکار حساب کاربری توسط بازدیدکنندگان سایت یا محدود کردن ثبت‌نام تنها از طریق ادمین.
            </p>
        <!-- Module Card: Files.ir Cloud Storage -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #e0e7ff; color: #4338ca; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">هاست ابری و بک‌آپ files.ir</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">ذخیره‌سازی امن بک‌آپ‌ها در سرورهای ابری ایران</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_files_ir" value="1" <?= ($settings['module_files_ir'] ?? '0') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin-bottom: 12px;">
                طبق درخواست، این قابلیت به صورت پیش‌فرض غیرفعال است و به عنوان پلن سازمانی قابل فعال‌سازی است.
            </p>
            <div class="form-group" style="margin: 0;">
                <label style="font-weight: 600; font-size: 0.8rem; color: #334155; margin-bottom: 4px; display: block;">کلید دسترسی (API Token):</label>
                <input type="text" name="files_ir_api_key" class="form-control" dir="ltr" value="<?= e($settings['files_ir_api_key'] ?? '') ?>" placeholder="files_ir_secret_token_..." style="font-size: 0.82rem; padding: 8px 12px;">
            </div>
        </div>

        <!-- Module Card: Visitor Analytics -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #cffafe; color: #0891b2; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">آمار پیشرفته بازدیدکنندگان</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">رهگیری ترافیک، مرورگرها، موبایل و لندینگ‌ها</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_analytics" value="1" <?= ($settings['module_analytics'] ?? '1') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                تحلیل جامع ورودی‌های گوگل و بازدیدهای روزانه بدون نیاز به کوکی خارجی، کاملاً منطبق بر حریم خصوصی و بهینه.
            </p>
        </div>

        <!-- Module Card: Online Live Chat (Goftino / Raychat) -->
        <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); grid-column: span 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="display: flex; gap: 14px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #ecfdf5; color: #059669; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">چت آنلاین و پشتیبانی زنده</h3>
                        <span style="font-size: 0.78rem; color: #64748b;">یکپارچه‌سازی رسمی با گفتینو (Goftino) و رایچت (Raychat)</span>
                    </div>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="module_chat" value="1" <?= ($settings['module_chat'] ?? '0') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <p style="font-size: 0.84rem; color: #475569; line-height: 1.6; margin-bottom: 14px;">
                امکان فعال‌سازی ابزارک چت آنلاین اختصاصی. به صورت پیش‌فرض غیرفعال است. مشخصات کاربران لاگین شده (نام، موبایل و ایمیل) به صورت خودکار به پنل چت شما ارسال می‌گردد.
            </p>

            <div style="background: #f8fafc; border-radius: 10px; padding: 14px; border: 1px solid #e2e8f0;">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label style="font-weight: 700; font-size: 0.82rem; color: #1e293b; margin-bottom: 6px; display: block;">انتخاب ارائه‌دهنده چت آنلاین:</label>
                    <select name="chat_provider" class="form-control" style="font-size: 0.85rem; height: 38px; border-radius: 6px;">
                        <option value="goftino" <?= (($settings['chat_provider'] ?? 'goftino') === 'goftino') ? 'selected' : '' ?>>گفتینو (Goftino) - پیشنهاد شده</option>
                        <option value="raychat" <?= (($settings['chat_provider'] ?? '') === 'raychat') ? 'selected' : '' ?>>رایچت (Raychat)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <label style="font-weight: 600; font-size: 0.78rem; color: #334155; margin: 0;">کلید ویجت گفتینو (Goftino Token):</label>
                        <a href="https://www.goftino.com/docs" target="_blank" rel="noopener noreferrer" style="font-size: 0.72rem; color: #0284c7; text-decoration: none;">مستندات گفتینو</a>
                    </div>
                    <input type="text" name="chat_goftino_key" class="form-control" dir="ltr" value="<?= e($settings['chat_goftino_key'] ?? '') ?>" placeholder="مثال: abcdef123456" style="font-size: 0.82rem; padding: 7px 10px; font-family: monospace;">
                </div>

                <div class="form-group" style="margin: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <label style="font-weight: 600; font-size: 0.78rem; color: #334155; margin: 0;">توکن رایچت (Raychat Token):</label>
                        <a href="https://raychat.io/documentation" target="_blank" rel="noopener noreferrer" style="font-size: 0.72rem; color: #0284c7; text-decoration: none;">مستندات رایچت</a>
                    </div>
                    <input type="text" name="chat_raychat_key" class="form-control" dir="ltr" value="<?= e($settings['chat_raychat_key'] ?? '') ?>" placeholder="مثال: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" style="font-size: 0.82rem; padding: 7px 10px; font-family: monospace;">
                </div>
            </div>
        </div>

    </div>

    <!-- Maintenance Mode Settings Section -->
    <div class="admin-card" style="background: #fff; border-radius: 14px; padding: 28px; border: 2px solid #fed7aa; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ffedd5;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="width: 42px; height: 42px; background: #fff7ed; color: #ea580c; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-wrench"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; color: #011C39; font-weight: 800;">حالت تعمیرات و نگهداری سایت (Maintenance Mode 503)</h3>
                    <span style="font-size: 0.8rem; color: #64748b;">در صورت فعال بودن، کاربران عادی با صفحه ۵۰۳ حرفه‌ای مواجه می‌شوند و مدیران می‌توانند بدون مشکل وارد سایت شوند</span>
                </div>
            </div>
            <label class="switch" style="position: relative; display: inline-block; width: 56px; height: 30px;">
                <input type="checkbox" name="maintenance_mode" value="1" <?= ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;">
                <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
            </label>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 6px; display: block;">عنوان صفحه تعمیرات</label>
                <input type="text" name="maintenance_title" class="form-control" value="<?= e($settings['maintenance_title'] ?? '') ?>" placeholder="سامانه ترموبان در حال ارتقا است">
            </div>

            <div class="form-group">
                <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 6px; display: block;">تلفن پشتیبانی اضطراری</label>
                <input type="text" name="maintenance_phone" class="form-control" dir="ltr" value="<?= e($settings['maintenance_phone'] ?? '۰۲۱-۷۷۴۲۸۵۹۶') ?>">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="font-weight: 700; font-size: 0.88rem; color: #011C39; margin-bottom: 6px; display: block;">پیام به مشتریان و بازدیدکنندگان</label>
                <textarea name="maintenance_message" class="form-control" rows="3"><?= e($settings['maintenance_message'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div style="text-align: left;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1rem; font-weight: 800; border-radius: 8px;">
            <i class="fa-solid fa-floppy-disk" style="margin-left: 8px;"></i>
            <span>ذخیره تغییرات و پیکربندی ماژول‌ها</span>
        </button>
    </div>
</form>

<style>
.switch input:checked + .slider {
    background-color: #EF5C16 !important;
}
.switch input:focus + .slider {
    box-shadow: 0 0 1px #EF5C16;
}
.switch input:checked + .slider:before {
    transform: translateX(24px);
}
.slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
</style>
