/**
 * Thermoban CMS - Quill Persian & RTL Integration Helper
 * Fully localized Persian toolbar, RTL support, and Media Library integration.
 */

(function() {
    'use strict';

    var PERSIAN_TOOLTIPS = {
        'ql-bold': 'برجسته / پررنگ (Ctrl+B)',
        'ql-italic': 'کج / مورب (Ctrl+I)',
        'ql-underline': 'خط زیرین (Ctrl+U)',
        'ql-strike': 'خط خورده',
        'ql-blockquote': 'نقل قول / کادر ویژه',
        'ql-code-block': 'بلوک کد برنامه‌نویسی',
        'ql-header': 'سطح عنوان و تیتر',
        'ql-list': 'فهرست (شماره‌دار / نشانه‌دار)',
        'ql-direction': 'تغییر جهت متن (راست‌چین / چپ‌چین)',
        'ql-align': 'تراز و چینش متن (راست، وسط، چپ، هم‌تراز)',
        'ql-color': 'رنگ قلم متن',
        'ql-background': 'رنگ پس‌زمینه متن',
        'ql-link': 'افزودن لینک پیوند',
        'ql-image': 'درج تصویر از کتابخانه رسانه یا بارگذاری',
        'ql-clean': 'پاکسازی قالب‌بندی و فرمت'
    };

    function applyPersianTooltips(toolbarEl) {
        if (!toolbarEl) return;
        Object.keys(PERSIAN_TOOLTIPS).forEach(function(cls) {
            var btns = toolbarEl.querySelectorAll('.' + cls);
            btns.forEach(function(btn) {
                if (!btn.getAttribute('title')) {
                    btn.setAttribute('title', PERSIAN_TOOLTIPS[cls]);
                }
            });
        });
    }

    /**
     * Initialize Persian RTL Quill Editor
     * @param {string|HTMLElement} container - Selector or element for Quill
     * @param {string|HTMLElement} hiddenInput - Selector or element for form sync
     * @param {Object} options - Optional overrides
     * @returns {Quill|null}
     */
    window.initPersianQuill = function(container, hiddenInput, options) {
        if (typeof Quill === 'undefined') {
            console.warn('Quill library not loaded');
            return null;
        }

        var containerEl = typeof container === 'string' ? document.querySelector(container) : container;
        var hiddenEl = typeof hiddenInput === 'string' ? document.querySelector(hiddenInput) : hiddenInput;

        if (!containerEl) {
            return null;
        }

        options = options || {};

        var defaultToolbar = [
            [{ 'header': [1, 2, 3, 4, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'direction': 'rtl' }],
            [{ 'align': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            ['clean']
        ];

        var quill = new Quill(containerEl, {
            theme: options.theme || 'snow',
            placeholder: options.placeholder || 'متن خود را اینجا بنویسید... (پشتیبانی کامل از زبان فارسی و راست‌چین)',
            modules: {
                toolbar: options.toolbar || defaultToolbar
            }
        });

        // Set default direction to RTL and align right
        quill.format('direction', 'rtl');
        quill.format('align', 'right');

        // Apply Persian tooltips to toolbar
        var toolbar = quill.getModule('toolbar');
        if (toolbar && toolbar.container) {
            applyPersianTooltips(toolbar.container);

            // Connect Media Library to Image button
            toolbar.addHandler('image', function() {
                if (typeof window.openMediaPicker === 'function') {
                    window.openMediaPicker(function(selectedUrl) {
                        var range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', selectedUrl, Quill.sources.USER);
                        quill.setSelection(range.index + 1, Quill.sources.SILENT);
                    });
                } else {
                    var url = prompt('لطفاً آدرس اینترنتی تصویر را وارد فرمایید:');
                    if (url) {
                        var range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', url, Quill.sources.USER);
                    }
                }
            });
        }

        // Sync with hidden input
        if (hiddenEl) {
            quill.on('text-change', function() {
                hiddenEl.value = quill.root.innerHTML;
            });

            // Ensure value on parent form submission
            var form = containerEl.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    hiddenEl.value = quill.root.innerHTML;
                });
            }
        }

        return quill;
    };
})();
