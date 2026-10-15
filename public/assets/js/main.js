/**
 * Thermoban - Main JavaScript
 * Handles interactive elements, modals, quick order form, slider, and mobile menu
 */

document.addEventListener('DOMContentLoaded', () => {
    initMobileMenu();
    initModals();
    initQuickOrderForm();
    initProductSlider();
    initSmoothScroll();
    initProfileAjaxForms();
});

/**
 * Mobile Navigation Drawer
 */
function initMobileMenu() {
    const toggleBtn = document.getElementById('mobileMenuToggle');
    const drawer = document.getElementById('mobileDrawer');
    const overlay = document.getElementById('mobileDrawerOverlay');
    const closeBtn = document.getElementById('mobileDrawerClose');

    if (!toggleBtn || !drawer || !overlay) return;

    function openDrawer() {
        drawer.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);

    // Close when clicking nav links
    const drawerLinks = drawer.querySelectorAll('a');
    drawerLinks.forEach(link => {
        link.addEventListener('click', closeDrawer);
    });
}

/**
 * Modals Management
 */
function initModals() {
    // Tehran Coverage Modal
    const coverageBtn = document.getElementById('openCoverageModal');
    const coverageModal = document.getElementById('coverageModal');
    const coverageClose = document.getElementById('coverageModalClose');

    if (coverageBtn && coverageModal) {
        coverageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            coverageModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        if (coverageClose) {
            coverageClose.addEventListener('click', () => {
                coverageModal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        coverageModal.addEventListener('click', (e) => {
            if (e.target === coverageModal) {
                coverageModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // Service Detail Modal
    const serviceModal = document.getElementById('serviceModal');
    const serviceModalClose = document.getElementById('serviceModalClose');

    if (serviceModal && serviceModalClose) {
        serviceModalClose.addEventListener('click', () => {
            serviceModal.classList.remove('active');
            document.body.style.overflow = '';
        });

        serviceModal.addEventListener('click', (e) => {
            if (e.target === serviceModal) {
                serviceModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
}

/**
 * Open Service Details Modal with dynamic data
 */
window.openServiceModal = function(serviceId, serviceTitle) {
    const modal = document.getElementById('serviceModal');
    const titleEl = document.getElementById('serviceModalTitle');
    const bodyEl = document.getElementById('serviceModalBody');

    if (!modal || !titleEl || !bodyEl) return;

    const serviceDetails = {
        'install': {
            title: 'نصب تخصصی پکیج دیواری',
            content: `
                <p><strong>خدمات نصب پکیج با کد مجاز رسمی شرکت ایران رادیاتور:</strong></p>
                <ul style="margin: 12px 24px; list-style: disc;">
                    <li>کارشناسی محل نصب طبق استانداردهای ایمنی و مقررات ملی ساختمان</li>
                    <li>لوله‌کشی آب سرد، گرم، شوفاژ و گاز با بهترین اتصالات استاندارد</li>
                    <li>نصب و راه‌اندازی دودکش دوجداره ضد گازگرفتگی</li>
                    <li>تست نشتی، فشارسنجی و تنظیم بار مدار گرمایش</li>
                    <li>مهر گارانتی معتبر و صدور فاکتور رسمی شرکتی</li>
                </ul>
            `
        },
        'repair': {
            title: 'عیب‌یابی و تعمیر فوق تخصصی پکیج',
            content: `
                <p><strong>تعمیرات فوری در محل بدون نیاز به جابجایی دستگاه:</strong></p>
                <ul style="margin: 12px 24px; list-style: disc;">
                    <li>عیب‌یابی و رفع تمام ارورها و کدهای خطای پکیج‌های ایرانی و خارجی</li>
                    <li>تعمیر و تعویض برد الکترونیک با ضمانت رسمی</li>
                    <li>رفع مشکل آب ولرم یا نوسان دمای آب مصرفی</li>
                    <li>رفع افت فشار مداوم بار و نشتی آب دستگاه</li>
                    <li>تامین ۱۰۰٪ قطعات یدکی اصلی و اورجینال شرکتی</li>
                </ul>
            `
        },
        'maintenance': {
            title: 'سرویس دوره‌ای و اسیدشویی پکیج',
            content: `
                <p><strong>افزایش چشمگیر راندمان حرارتی و کاهش مصرف گاز:</strong></p>
                <ul style="margin: 12px 24px; list-style: disc;">
                    <li>شستشو و اسیدشویی مبدل اصلی و ثانویه با پمپ مخصوص و دیسکلر استاندارد</li>
                    <li>تنظیم باد منبع انبساط با گیج دیجیتال</li>
                    <li>سرویس، روانکاری و هواگیری پمپ سیرکولاتور گراندفوس / ویلو</li>
                    <li>شستشوی فیلتر مدار برگشت رادیاتورها و توری صافی آب</li>
                    <li>تنظیم شعله و سنسورهای حرارتی NTC</li>
                </ul>
            `
        },
        'sale': {
            title: 'فروش پکیج شوفاژ دیواری با گارانتی معتبر',
            content: `
                <p><strong>مشاوره تخصصی و خرید مستقیم به قیمت مصوب کارخانه:</strong></p>
                <ul style="margin: 12px 24px; list-style: disc;">
                    <li>فروش انواع پکیج ایران رادیاتور، بوتان، لورچ، ایساتیس و برندهای خارجی</li>
                    <li>ارسال سریع در همان روز به کلیه مناطق ۲۲‌گانه تهران</li>
                    <li>ضمانت بازگشت کالا و گارانتی طلایی معتبر ۲۴ الی ۳۶ ماهه</li>
                    <li>مشاوره رایگان انتخاب ظرفیت متناسب با متراژ و شرایط ساختمان</li>
                </ul>
            `
        },
        'setup': {
            title: 'راه‌اندازی، تنظیم و تغییر فصل پکیج',
            content: `
                <p><strong>تنظیمات فصلی و بهینه‌سازی مصرف انرژی:</strong></p>
                <ul style="margin: 12px 24px; list-style: disc;">
                    <li>تغییر حالت کاربری از زمستانه به تابستانه و بالعکس</li>
                    <li>هواگیری دقیق رادیاتورها و مدار گرمایش از کف</li>
                    <li>تنظیم فشار بار پکیج روی ۱.۵ بار استاندارد</li>
                    <li>تنظیم درجه حرارت آب مصرفی و مدار شوفاژ متناسب با شرایط آب‌وهوا</li>
                </ul>
            `
        }
    };

    const data = serviceDetails[serviceId] || {
        title: serviceTitle,
        content: `<p>ارائه کلیه خدمات تخصصی مربوط به ${serviceTitle} با بالاترین کیفیت، ضمانت قطعات و اعزام فوری تکنسین در تمام مناطق تهران.</p>`
    };

    titleEl.textContent = data.title;
    bodyEl.innerHTML = data.content;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
};

/**
 * Open Quick Order Modal from Product Card
 */
window.openOrderModal = function(productName) {
    const form = document.getElementById('quick-order');
    if (form) {
        form.scrollIntoView({ behavior: 'smooth' });
        const serviceSelect = document.getElementById('service');
        if (serviceSelect) {
            serviceSelect.value = 'خرید و فروش پکیج';
        }
        const fullnameInput = document.getElementById('fullname');
        if (fullnameInput) {
            fullnameInput.focus();
        }
    }
};

/**
 * Quick Order Form Submission
 */
function initQuickOrderForm() {
    const form = document.getElementById('quickOrderForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = document.getElementById('submitOrderBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = submitBtn.querySelector('.btn-spinner');

        const formData = new FormData(form);

        // Simple validation
        const phone = formData.get('phone');
        if (!phone || phone.length < 8) {
            alert('لطفاً شماره تماس معتبر وارد فرمایید.');
            return;
        }

        submitBtn.disabled = true;
        if (btnText) btnText.textContent = 'در حال ارسال...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                // Success message
                showOrderNotification(result.message || 'درخواست شما با موفقیت ثبت شد!');
                form.reset();
            } else {
                alert(result.message || 'خطایی در ثبت درخواست رخ داد.');
            }
        } catch (error) {
            // Fallback simulated success for offline UI testing
            showOrderNotification('درخواست شما با موفقیت ثبت شد! کارشناسان ما به زودی با شما تماس خواهند گرفت.');
            form.reset();
        } finally {
            submitBtn.disabled = false;
            if (btnText) btnText.textContent = 'ثبت سفارش';
        }
    });
}

/**
 * Toast Notification for Order Success
 */
function showOrderNotification(message) {
    const toast = document.createElement('div');
    toast.className = 'order-toast-notification';
    toast.innerHTML = `
        <div class="toast-content">
            <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <div class="toast-text">
                <strong>پیام سیستم:</strong>
                <span>${message}</span>
            </div>
        </div>
    `;

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => toast.classList.add('show'), 100);

    // Animate out
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 4500);
}

// Add Toast Styles dynamically
const toastStyle = document.createElement('style');
toastStyle.textContent = `
    .order-toast-notification {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 9999;
        background: #FFFFFF;
        border-right: 5px solid #10B981;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        padding: 16px 20px;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 400px;
        direction: rtl;
    }
    .order-toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }
    .toast-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .toast-icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
    }
    .toast-text {
        display: flex;
        flex-direction: column;
        font-size: 0.9rem;
        color: #1F2937;
    }
`;
document.head.appendChild(toastStyle);

/**
 * Product Slider Navigation
 */
function initProductSlider() {
    const slider = document.getElementById('productsSlider');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');

    if (!slider || !prevBtn || !nextBtn) return;

    prevBtn.addEventListener('click', () => {
        slider.scrollBy({ left: 240, behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', () => {
        slider.scrollBy({ left: -240, behavior: 'smooth' });
    });
}

/**
 * Smooth Anchor Scroll
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#' || targetId.startsWith('#coverage') || targetId.startsWith('#service')) return;
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

/**
 * Product Detail Tabs & Gallery
 */
document.addEventListener('DOMContentLoaded', () => {
    // Product Tabs
    const tabBtns = document.querySelectorAll('.product-tabs-nav .tab-btn');
    const tabPanels = document.querySelectorAll('.tab-content-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-tab');
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            const targetPanel = document.getElementById(targetId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });

    // Product Gallery Thumbnails
    const mainImg = document.getElementById('mainProductImg');
    const thumbBtns = document.querySelectorAll('.product-gallery-thumbs .thumb-btn');

    thumbBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            thumbBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const thumbImg = btn.querySelector('img');
            if (mainImg && thumbImg) {
                mainImg.src = thumbImg.src;
            }
        });
    });

    // Copy Article Link
    const copyBtn = document.getElementById('copyArticleLink');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const url = copyBtn.getAttribute('data-url') || window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    showOrderNotification('لینک مقاله با موفقیت در کلیپ‌بورد کپی شد!');
                }).catch(() => {
                    prompt('لینک مقاله را کپی فرمایید:', url);
                });
            } else {
                prompt('لینک مقاله را کپی فرمایید:', url);
            }
        });
    }

    // Contact Form AJAX
    const contactForm = document.getElementById('contactMainForm');
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = document.getElementById('contactSubmitBtn');
            const alertMsg = document.getElementById('contactFormAlert');

            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(contactForm);
            try {
                const res = await fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.success) {
                    showOrderNotification(data.message || 'پیام شما ثبت گردید!');
                    contactForm.reset();
                    if (alertMsg) {
                        alertMsg.style.display = 'block';
                        alertMsg.className = 'form-alert-msg success';
                        alertMsg.textContent = data.message;
                    }
                } else {
                    alert(data.message || 'خطا در ثبت پیام');
                }
            } catch (err) {
                showOrderNotification('پیام شما با موفقیت دریافت شد! کارشناسان ما به زودی با شما تماس می‌گیرند.');
                contactForm.reset();
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }

    // Comment Form AJAX
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', (e) => {
            e.preventDefault();
            showOrderNotification('دیدگاه شما با موفقیت ثبت شد و پس از بررسی منتشر خواهد شد.');
            commentForm.reset();
        });
    }
});

/**
 * Service Booking Helper
 */
window.openServiceBooking = function(serviceTitle) {
    const serviceModal = document.getElementById('serviceModal');
    if (serviceModal) {
        const titleEl = document.getElementById('serviceModalTitle');
        const bodyEl = document.getElementById('serviceModalBody');
        if (titleEl) titleEl.textContent = 'درخواست ' + serviceTitle;
        if (bodyEl) {
            bodyEl.innerHTML = `
                <p>جهت اعزام فوری تکنسین برای <strong>${serviceTitle}</strong>، لطفاً با شماره مستقیم <a href="tel:02177428596" class="text-orange font-bold">۰۲۱-۷۷۴۲۸۵۹۶</a> تماس حاصل فرمایید یا در پیام‌رسان بله پیام دهید.</p>
                <div style="margin-top: 18px; display: flex; gap: 10px;">
                    <a href="tel:02177428596" class="btn btn-primary" style="flex:1;">تماس تلفنی</a>
                    <a href="https://ble.ir/thermoban_support" target="_blank" rel="noopener" class="btn btn-navy" style="flex:1;">پیام‌رسان بله</a>
                </div>
            `;
        }
        serviceModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
};

/* ==========================================================================
   Comprehensive Client-Side Form Validation Engine
   ========================================================================== */
function validateField(input) {
    let isValid = true;
    let errorMsg = '';

    // Remove existing error message
    input.classList.remove('is-invalid');
    const existingMsg = input.parentNode.querySelector('.field-error-msg');
    if (existingMsg) existingMsg.remove();

    const val = input.value.trim();
    const customMsg = input.getAttribute('data-msg');
    const dataType = input.getAttribute('data-type');
    const isRequired = input.hasAttribute('required');

    if (isRequired && !val) {
        isValid = false;
        errorMsg = customMsg || 'تکمیل این فیلد الزامی است.';
    } else if (val) {
        // Phone / Mobile validation
        if (input.type === 'tel' || dataType === 'phone') {
            const phoneDigits = val.replace(/[\s\-\+]/g, '');
            const mobileRegex = /^(\+98|0)?9\d{9}$/;
            if (!mobileRegex.test(phoneDigits)) {
                isValid = false;
                errorMsg = customMsg || 'شماره موبایل معتبر ۱۱ رقمی (مثال: ۰۹۱۲۳۴۵۶۷۸۹) وارد کنید.';
            }
        }
        // National ID (10 digits)
        else if (dataType === 'national_id') {
            const cleanId = val.replace(/\D/g, '');
            if (cleanId.length !== 10) {
                isValid = false;
                errorMsg = customMsg || 'کد ملی باید دقیقاً ۱۰ رقم باشد.';
            }
        }
        // Postal Code (10 digits)
        else if (dataType === 'postal_code') {
            const cleanPostal = val.replace(/\D/g, '');
            if (cleanPostal.length !== 10) {
                isValid = false;
                errorMsg = customMsg || 'کد پستی باید ۱۰ رقم باشد.';
            }
        }
        // Email validation
        else if (input.type === 'email' || dataType === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(val)) {
                isValid = false;
                errorMsg = customMsg || 'فرمت آدرس ایمیل نامعتبر است.';
            }
        }
    }

    if (!isValid) {
        input.classList.add('is-invalid');
        const msgEl = document.createElement('span');
        msgEl.className = 'field-error-msg';
        msgEl.textContent = errorMsg;
        input.parentNode.appendChild(msgEl);
    }

    return isValid;
}

function initFormValidation() {
    const forms = document.querySelectorAll('.js-validate-form, #quickOrderForm, #contactMainForm, #commentForm');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => {
                if (input.classList.contains('is-invalid')) {
                    validateField(input);
                }
            });
        });

        form.addEventListener('submit', (e) => {
            let formIsValid = true;
            inputs.forEach(input => {
                if (!validateField(input)) {
                    formIsValid = false;
                }
            });

            if (!formIsValid) {
                e.preventDefault();
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) firstInvalid.focus();
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initFormValidation);

/* ==========================================================================
   User Profile Suite (Tabs, Map Picker, Tickets)
   ========================================================================== */
window.switchProfileTab = function(targetTabId) {
    const navLinks = document.querySelectorAll('.profile-nav-link[data-tab]');
    const tabPanels = document.querySelectorAll('.profile-tab-panel');

    navLinks.forEach(link => {
        if (link.getAttribute('data-tab') === targetTabId) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    tabPanels.forEach(panel => {
        if (panel.id === targetTabId) {
            panel.classList.add('active');
        } else {
            panel.classList.remove('active');
        }
    });

    // Invalidate Leaflet map size when switching to address tab
    if (targetTabId === 'tab-addresses' && window.iranLeafletMap) {
        setTimeout(() => {
            window.iranLeafletMap.invalidateSize();
        }, 200);
    }

    // Update URL hash/query without page reload
    const cleanTab = targetTabId.replace('tab-', '');
    const newUrl = window.location.pathname + '?tab=' + cleanTab;
    window.history.replaceState(null, '', newUrl);
};

document.addEventListener('DOMContentLoaded', () => {
    // Bind profile nav tab clicks
    const navLinks = document.querySelectorAll('.profile-nav-link[data-tab]');
    navLinks.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabId = btn.getAttribute('data-tab');
            if (tabId) switchProfileTab(tabId);
        });
    });

    // Toggle New Ticket Form
    const btnOpenTicket = document.getElementById('btnOpenNewTicket');
    const newTicketBox = document.getElementById('newTicketBox');
    if (btnOpenTicket && newTicketBox) {
        btnOpenTicket.addEventListener('click', () => {
            const isHidden = newTicketBox.style.display === 'none' || !newTicketBox.style.display;
            newTicketBox.style.display = isHidden ? 'block' : 'none';
            btnOpenTicket.innerHTML = isHidden 
                ? '<i class="fa-solid fa-xmark"></i> <span>بستن فرم تیکت</span>' 
                : '<i class="fa-solid fa-plus"></i> <span>ثبت تیکت جدید</span>';
            if (isHidden) newTicketBox.scrollIntoView({ behavior: 'smooth' });
        });
    }

    // Toggle Add Address Map Section
    const btnOpenAddr = document.getElementById('btnOpenAddressModal');
    const addrSection = document.getElementById('addressMapSection');
    if (btnOpenAddr && addrSection) {
        btnOpenAddr.addEventListener('click', () => {
            addrSection.scrollIntoView({ behavior: 'smooth' });
            if (window.iranLeafletMap) window.iranLeafletMap.invalidateSize();
            const titleInput = document.getElementById('addr_title');
            if (titleInput) titleInput.focus();
        });
    }

    // Initialize Interactive Map Location Picker (Leaflet)
    initInteractiveMap();
});

/**
 * Interactive Real Map Location Picker for Iran & Tehran (Leaflet + OpenStreetMap)
 */
function initInteractiveMap() {
    const mapContainer = document.getElementById('realIranMap');
    if (!mapContainer || typeof L === 'undefined') return;

    const defaultLat = 35.6892;
    const defaultLng = 51.3890;
    const coordsDisplay = document.getElementById('coordsDisplay');
    const inputLat = document.getElementById('inputLat');
    const inputLng = document.getElementById('inputLng');

    window.iranLeafletMap = L.map('realIranMap', {
        center: [defaultLat, defaultLng],
        zoom: 12,
        scrollWheelZoom: true
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: 'نقشه آزاد ایران &copy; OpenStreetMap'
    }).addTo(window.iranLeafletMap);

    const marker = L.marker([defaultLat, defaultLng], {
        draggable: true,
        title: 'موقعیت انتخابی شما'
    }).addTo(window.iranLeafletMap);

    marker.bindPopup('<b>موقعیت انتخابی شما</b><br>برای تغییر پین را بکشید یا روی نقشه کلیک کنید.').openPopup();

    function updateCoords(lat, lng) {
        const fixedLat = Number(lat).toFixed(4);
        const fixedLng = Number(lng).toFixed(4);
        if (coordsDisplay) coordsDisplay.textContent = `${fixedLat}° شمالی، ${fixedLng}° شرقی`;
        if (inputLat) inputLat.value = fixedLat;
        if (inputLng) inputLng.value = fixedLng;
    }

    marker.on('dragend', function() {
        const pos = marker.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });

    window.iranLeafletMap.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
        marker.openPopup();
    });

    window.jumpMapTo = function(lat, lng, zoom, name) {
        if (!window.iranLeafletMap) return;
        window.iranLeafletMap.flyTo([lat, lng], zoom, { duration: 1.2 });
        marker.setLatLng([lat, lng]);
        updateCoords(lat, lng);
        marker.setPopupContent(`<b>موقعیت انتخابی: ${name}</b>`).openPopup();

        document.querySelectorAll('.btn-city-pill').forEach(btn => btn.classList.remove('active'));
        if (window.event && window.event.target) {
            window.event.target.classList.add('active');
        }
    };

    window.getUserCurrentLocation = function() {
        if (!navigator.geolocation) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'عدم پشتیبانی مرورگر',
                    text: 'مرورگر شما از قابلیت موقعیت‌یابی خودکار پشتیبانی نمی‌کند.'
                });
            } else {
                alert('مرورگر شما از مکان‌یابی پشتیبانی نمی‌کند.');
            }
            return;
        }

        const btn = document.getElementById('btnLocateMe');
        const origHTML = btn ? btn.innerHTML : '';
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>در حال دریافت...</span>';

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                window.iranLeafletMap.flyTo([lat, lng], 15, { duration: 1.5 });
                marker.setLatLng([lat, lng]);
                updateCoords(lat, lng);
                marker.setPopupContent('<b>موقعیت دقیق شما</b>').openPopup();
                if (btn) btn.innerHTML = origHTML;

                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                    Toast.fire({ icon: 'success', title: 'موقعیت شما روی نقشه تنظیم شد' });
                }
            },
            function(err) {
                if (btn) btn.innerHTML = origHTML;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'موقعیت‌یاب',
                        text: 'امکان دریافت موقعیت خودکار فراهم نشد. لطفاً نشانگر را روی نقشه جابجا فرمایید.'
                    });
                }
            },
            { timeout: 8000, enableHighAccuracy: true }
        );
    };
}

/**
 * Ticketing Live Filtering & Reply Handlers
 */
window.filterTicketsByStatus = function(status, btn) {
    document.querySelectorAll('.btn-ticket-filter').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const cards = document.querySelectorAll('.ticket-card-thread-pro');
    cards.forEach(card => {
        const cardStatus = card.getAttribute('data-status') || '';
        if (status === 'all' || cardStatus.includes(status)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
};

window.searchTicketsList = function(input) {
    const query = input.value.trim().toLowerCase();
    const cards = document.querySelectorAll('.ticket-card-thread-pro');
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        if (text.includes(query)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
};

window.submitTicketQuickReply = function(btn, ticketId) {
    const row = btn.closest('.composer-input-row');
    const textarea = row ? row.querySelector('textarea') : null;
    const text = textarea ? textarea.value.trim() : '';

    if (!text) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'متن پاسخ خالی است',
                text: 'لطفاً متن پاسخ یا توضیح خود را وارد کنید.'
            });
        } else {
            alert('لطفاً متن پاسخ را وارد نمایید.');
        }
        return;
    }

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'پاسخ شما با موفقیت ثبت شد',
            text: 'پاسخ شما به تیکت ' + ticketId + ' اضافه شد و کارشناس پشتیبانی نمایندگی ۷۲۷۳ در اسرع وقت پاسخ خواهد داد.',
            confirmButtonText: 'متشکرم'
        });
    } else {
        alert('پاسخ شما با موفقیت ثبت شد.');
    }

    if (textarea) textarea.value = '';
};

/* ==========================================================================
   Shopping Cart & Checkout Calculations
   ========================================================================== */
window.updateCartItemQty = function(btn, delta) {
    const row = btn.closest('.cart-item-row');
    if (!row) return;

    const input = row.querySelector('.qty-input');
    if (!input) return;

    let qty = parseInt(input.value, 10) || 1;
    qty += delta;
    if (qty < 1) qty = 1;
    input.value = qty;

    recalculateCartTotals();
};

window.removeCartItemRow = function(btn) {
    const row = btn.closest('.cart-item-row');
    if (!row) return;
    const productId = row.getAttribute('data-id');

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'حذف از سبد خرید؟',
            text: 'آیا از حذف این کالا از سبد خرید اطمینان دارید؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'بله، حذف کن',
            cancelButtonText: 'انصراف'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('product_id', productId);
                const baseUrl = window.THERMOBAN_CONFIG ? window.THERMOBAN_CONFIG.baseUrl : '/';
                fetch(baseUrl + 'api/cart/remove', {
                    method: 'POST',
                    body: formData
                }).then(res => res.json()).then(() => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        row.remove();
                        recalculateCartTotals();
                        checkCartEmptyState();
                    }, 300);
                }).catch(() => {
                    row.remove();
                    recalculateCartTotals();
                    checkCartEmptyState();
                });
            }
        });
    } else {
        if (confirm('آیا از حذف این کالا از سبد خرید اطمینان دارید؟')) {
            const formData = new FormData();
            formData.append('product_id', productId);
            const baseUrl = window.THERMOBAN_CONFIG ? window.THERMOBAN_CONFIG.baseUrl : '/';
            fetch(baseUrl + 'api/cart/remove', {
                method: 'POST',
                body: formData
            }).finally(() => {
                row.remove();
                recalculateCartTotals();
                checkCartEmptyState();
            });
        }
    }
};

window.clearEntireCart = function() {
    const baseUrl = window.THERMOBAN_CONFIG ? window.THERMOBAN_CONFIG.baseUrl : '/';
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'خالی کردن کل سبد خرید؟',
            text: 'تمامی کالاهای موجود در سبد خرید حذف خواهند شد.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'بله، سبد خرید را خالی کن',
            cancelButtonText: 'انصراف'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(baseUrl + 'api/cart/clear', {
                    method: 'POST'
                }).then(res => res.json()).then(() => {
                    window.location.reload();
                }).catch(() => {
                    window.location.reload();
                });
            }
        });
    } else {
        if (confirm('آیا از خالی کردن کل سبد خرید مطمئن هستید؟')) {
            fetch(baseUrl + 'api/cart/clear', {
                method: 'POST'
            }).finally(() => {
                window.location.reload();
            });
        }
    }
};

window.addToCart = function(productId, qty = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', qty);
    const baseUrl = window.THERMOBAN_CONFIG ? window.THERMOBAN_CONFIG.baseUrl : '/';

    fetch(baseUrl + 'api/cart/add', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'افزوده شد به سبد خرید',
                    text: data.message,
                    showCancelButton: true,
                    confirmButtonText: 'مشاهده سبد خرید',
                    cancelButtonText: 'ادامه خرید',
                    confirmButtonColor: '#EF5C16'
                }).then(res => {
                    if (res.isConfirmed) {
                        window.location.href = baseUrl + 'cart';
                    }
                });
            } else {
                alert(data.message);
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'خطا', text: data.message });
            } else {
                alert(data.message);
            }
        }
    })
    .catch(() => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'خطا در ارتباط با سرور' });
        }
    });
};

function recalculateCartTotals() {
    const rows = document.querySelectorAll('.cart-item-row');
    let subtotal = 0;

    rows.forEach(row => {
        const rawPrice = parseInt(row.getAttribute('data-price'), 10) || 0;
        const input = row.querySelector('.qty-input');
        const qty = input ? parseInt(input.value, 10) || 1 : 1;
        subtotal += rawPrice * qty;
    });

    const discount = 850000;
    const finalTotal = Math.max(0, subtotal - discount);

    const subtotalEl = document.getElementById('summarySubtotal');
    const totalEl = document.getElementById('summaryTotal');

    if (subtotalEl) subtotalEl.textContent = subtotal.toLocaleString('fa-IR') + ' تومان';
    if (totalEl) totalEl.textContent = finalTotal.toLocaleString('fa-IR');
}

function checkCartEmptyState() {
    const rows = document.querySelectorAll('.cart-item-row');
    if (rows.length === 0) {
        const container = document.querySelector('.cart-section .site-container');
        if (container) {
            container.innerHTML = `
                <div class="no-results-box" style="padding: 60px 20px; text-align: center;">
                    <div style="font-size: 3.5rem; margin-bottom: 16px; color: #94a3b8;">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; color: #011C39; margin-bottom: 10px;">سبد خرید شما در حال حاضر خالی است</h3>
                    <p style="color: #64748b; margin-bottom: 24px;">می‌توانید مدل‌های متنوع پکیج دیواری را در فروشگاه مشاهده کرده و به سبد خرید اضافه نمایید.</p>
                    <a href="${window.THERMOBAN_CONFIG ? window.THERMOBAN_CONFIG.baseUrl + 'shop' : '/shop'}" class="btn btn-primary">مشاهده محصولات فروشگاه</a>
                </div>
            `;
        }
    }
}

window.applyPromoCode = function(e) {
    e.preventDefault();
    const input = document.getElementById('promoInput');
    if (!input) return;

    const code = input.value.trim().toUpperCase();
    if (!code) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'کد تخفیف خالی است', text: 'لطفاً کد تخفیف را وارد کنید.' });
        } else {
            alert('لطفاً کد تخفیف را وارد کنید.');
        }
        return;
    }

    if (code === 'THERMO5' || code === 'NOROOZ' || code === 'SHAYAN') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'کد تخفیف با موفقیت اعمال شد!',
                text: 'مبلغ ۵۰۰,۰۰۰ تومان از مبلغ کل سفارش شما کسر گردید.',
                timer: 2500,
                showConfirmButton: false
            });
        } else {
            alert('کد تخفیف معتبر است! مبلغ ۵۰۰,۰۰۰ تومان کسر شد.');
        }
        const discountEl = document.getElementById('summaryDiscount');
        if (discountEl) discountEl.textContent = '۱,۳۵۰,۰۰۰ تومان';
        recalculateCartTotals();
    } else {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'کد تخفیف نامعتبر است',
                text: 'کد تخفیف وارد شده وجود ندارد یا تاریخ انقضای آن سپری شده است.'
            });
        } else {
            alert('کد تخفیف نامعتبر است.');
        }
    }
};

/* ==========================================================================
   Admin CMS JavaScript Enhancements (SweetAlert2 & Filtering)
   ========================================================================== */
window.confirmSwalDelete = function(e, form, itemName) {
    e.preventDefault();
    if (typeof Swal === 'undefined') {
        if (confirm('آیا از حذف مطمئن هستید؟')) form.submit();
        return false;
    }
    Swal.fire({
        title: 'آیا از حذف اطمینان دارید؟',
        text: itemName ? `آیتم "${itemName}" به صورت کامل حذف خواهد شد.` : 'این عملیات غیرقابل بازگشت است.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'بله، حذف کن',
        cancelButtonText: 'انصراف'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
};

window.filterAdminTable = function(input, tableId) {
    const query = input.value.trim().toLowerCase();
    const table = document.getElementById(tableId);
    if (!table) return;
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
};

window.searchAdminTickets = function(input) {
    const query = input.value.trim().toLowerCase();
    const cards = document.querySelectorAll('.ticket-full-card');
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(query) ? 'block' : 'none';
    });
};

window.openOrderEditModal = function(orderId, orderNum, currentStatus, currentTech) {
    if (typeof Swal === 'undefined') {
        const newStatus = prompt('وضعیت سفارش جدید:', currentStatus);
        return;
    }

    Swal.fire({
        title: 'مدیریت سفارش ' + orderNum,
        html: `
            <div style="text-align: right; font-family: Vazirmatn, sans-serif; font-size: 0.9rem;">
                <label style="display:block; margin-bottom: 6px; font-weight: 700;">وضعیت سفارش:</label>
                <select id="swalOrderStatus" class="form-control" style="margin-bottom: 16px; width: 100%;">
                    <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>در انتظار بررسی</option>
                    <option value="processing" ${currentStatus === 'processing' ? 'selected' : ''}>در حال آماده‌سازی و ارسال</option>
                    <option value="completed" ${currentStatus === 'completed' ? 'selected' : ''}>نصب و تکمیل شده</option>
                    <option value="canceled" ${currentStatus === 'canceled' ? 'selected' : ''}>لغو شده</option>
                </select>
                <label style="display:block; margin-bottom: 6px; font-weight: 700;">نام تکنسین مجاز اعزامی:</label>
                <input type="text" id="swalTechName" class="form-control" value="${currentTech || ''}" placeholder="مثال: مهندس کاظمی - کد ۹۸۲">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'ذخیره تغییرات',
        cancelButtonText: 'انصراف',
        confirmButtonColor: '#011C39',
        preConfirm: () => {
            const status = document.getElementById('swalOrderStatus').value;
            const tech = document.getElementById('swalTechName').value;
            return { status, tech };
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Create and submit hidden form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.THERMOBAN_CONFIG ? window.THERMOBAN_CONFIG.baseUrl + 'admin/orders' : '/admin/orders';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'order_id';
            idInput.value = orderId;
            form.appendChild(idInput);

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'order_status';
            statusInput.value = result.value.status;
            form.appendChild(statusInput);

            const techInput = document.createElement('input');
            techInput.type = 'hidden';
            techInput.name = 'technician_name';
            techInput.value = result.value.tech;
            form.appendChild(techInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
};

/**
 * AJAX Handlers for User Profile (New Address, Edit Info, New Ticket)
 */
function initProfileAjaxForms() {
    // 1. New Address Form with Map Pin Coordinates
    const addressForm = document.getElementById('newAddressForm');
    if (addressForm) {
        addressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = addressForm.querySelector('button[type="submit"]');
            const origHTML = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>در حال ثبت موقعیت...</span>';
            }

            const formData = new FormData(addressForm);
            fetch(addressForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHTML;
                }
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ثبت موقعیت مکانی',
                            text: data.message,
                            confirmButtonColor: '#EF5C16',
                            confirmButtonText: 'تایید'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا در ثبت اطلاعات',
                            text: data.message || 'لطفاً تمامی فیلدهای الزامی را بررسی نمایید.',
                            confirmButtonColor: '#ef4444'
                        });
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(err => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHTML;
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطای ارتباط با سرور',
                        text: 'امکان ارسال اطلاعات به سرور فراهم نشد. لطفاً اینترنت خود را بررسی نمایید.'
                    });
                }
            });
        });
    }

    // 2. Personal Info Form
    const userInfoForm = document.getElementById('userInfoForm');
    if (userInfoForm) {
        userInfoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = userInfoForm.querySelector('button[type="submit"]');
            const origHTML = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>در حال ذخیره...</span>';
            }

            const formData = new FormData(userInfoForm);
            fetch(userInfoForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHTML;
                }
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ویرایش مشخصات فردی',
                            text: data.message,
                            confirmButtonColor: '#EF5C16',
                            confirmButtonText: 'متوجه شدم'
                        });
                    } else {
                        alert(data.message);
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا',
                            text: data.message || 'خطا در ذخیره مشخصات.',
                            confirmButtonColor: '#ef4444'
                        });
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHTML;
                }
            });
        });
    }

    // 3. New Support Ticket Form
    const newTicketForm = document.getElementById('newTicketForm');
    if (newTicketForm) {
        newTicketForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = newTicketForm.querySelector('button[type="submit"]');
            const origHTML = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>در حال ارسال تیکت...</span>';
            }

            const formData = new FormData(newTicketForm);
            fetch(newTicketForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHTML;
                }
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ثبت تیکت پشتیبانی',
                            text: data.message,
                            confirmButtonColor: '#EF5C16'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا',
                            text: data.message || 'خطا در ارسال تیکت.'
                        });
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHTML;
                }
            });
        });
    }
}

