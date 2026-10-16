<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<div class="admin-page-header">
    <div class="admin-header-title">
        <h1><i class="fa-solid fa-photo-film text-orange"></i> کتابخانه چندرسانه‌ای و فایل‌منیجر</h1>
        <p>مدیریت جامع، آپلود، مشاهده و دریافت لینک تصاویر محصولات، کاتالوگ‌ها، اسناد و مقالات</p>
    </div>
    <div class="admin-header-actions">
        <label for="directUploadInput" class="btn btn-primary" style="cursor: pointer; margin: 0;">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>بارگذاری فایل جدید</span>
            <input type="file" id="directUploadInput" style="display: none;" onchange="handleDirectUpload(this)">
        </label>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="admin-alert admin-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= e($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="admin-alert admin-alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?= e($error) ?></span>
    </div>
<?php endif; ?>

<!-- Dropzone Area -->
<div id="dropzoneBox" style="border: 2px dashed #cbd5e1; border-radius: 16px; padding: 32px 20px; text-align: center; background: #fff; margin-bottom: 24px; transition: all 0.2s ease; cursor: pointer;" onclick="document.getElementById('directUploadInput').click()">
    <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239, 92, 22, 0.1); color: #EF5C16; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 14px auto;">
        <i class="fa-solid fa-cloud-arrow-up"></i>
    </div>
    <h3 style="margin: 0 0 6px 0; font-size: 1.1rem; color: #011C39; font-weight: 800;">فایل‌ها را به این قسمت بکشید و رها کنید</h3>
    <p style="margin: 0; font-size: 0.88rem; color: #64748b;">یا برای انتخاب فایل از رایانه خود، اینجا کلیک کنید (فرمت‌های مجاز: JPG, PNG, WEBP, SVG, PDF - حداکثر ۱۰ مگابایت)</p>
    <div id="uploadProgressBox" style="display: none; max-width: 400px; margin: 16px auto 0 auto;">
        <div style="background: #e2e8f0; border-radius: 10px; height: 10px; overflow: hidden;">
            <div id="uploadProgressBar" style="background: #EF5C16; height: 100%; width: 0%; transition: width 0.2s ease;"></div>
        </div>
        <div id="uploadProgressText" style="font-size: 0.82rem; color: #64748b; margin-top: 6px;">در حال بارگذاری فایل...</div>
    </div>
</div>

<!-- Stats and Filter Row -->
<div class="admin-card mb-4" style="padding: 16px 20px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <span style="font-size: 0.92rem; font-weight: 700; color: #011C39;">
            <i class="fa-solid fa-folder-open text-orange" style="margin-left: 6px;"></i>
            مجموع فایل‌ها: <strong id="totalFilesCount"><?= count($mediaList) ?></strong> عدد
        </span>
        <?php
        $totalBytes = array_sum(array_column($mediaList, 'file_size'));
        $totalMB = round($totalBytes / (1024 * 1024), 2);
        ?>
        <span style="font-size: 0.88rem; color: #64748b; background: #f1f5f9; padding: 4px 12px; border-radius: 20px;">
            حجم کل مصرفی: <?= $totalMB ?> مگابایت
        </span>
    </div>

    <div style="display: flex; align-items: center; gap: 10px;">
        <div style="position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 12px; top: 12px; color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" id="mediaSearchInput" oninput="filterMediaGrid(this.value)" placeholder="جستجو در پرونده‌ها..." class="admin-input" style="padding-right: 34px; width: 240px; height: 38px; font-size: 0.85rem;">
        </div>
    </div>
</div>

<!-- Media Grid -->
<div id="mediaFilesGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; margin-bottom: 30px;">
    <?php if (empty($mediaList)): ?>
        <div style="grid-column: 1 / -1; background: #fff; border-radius: 12px; border: 1px dashed #cbd5e1; padding: 60px 20px; text-align: center; color: #94a3b8;">
            <i class="fa-solid fa-images" style="font-size: 3rem; margin-bottom: 12px; opacity: 0.5;"></i>
            <h4 style="margin: 0 0 6px 0; color: #475569;">کتابخانه رسانه خالی است</h4>
            <p style="margin: 0; font-size: 0.88rem;">نخستین فایل یا تصویر خود را در کادر بالا بارگذاری فرمایید.</p>
        </div>
    <?php else: ?>
        <?php foreach ($mediaList as $item): ?>
            <?php
            $fileUrl = upload_url($item['file_path']);
            $isImg = str_starts_with($item['mime_type'], 'image/');
            $fileSizeKb = fa_num(round($item['file_size'] / 1024, 1));
            ?>
            <div class="media-card-item" data-name="<?= strtolower(e($item['original_name'])) ?>" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: all 0.2s ease; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                <div style="height: 140px; background: #f8fafc; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; border-bottom: 1px solid #f1f5f9;">
                    <?php if ($isImg): ?>
                        <img src="<?= $fileUrl ?>" alt="<?= e($item['original_name']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.3s ease;" class="media-thumb" loading="lazy">
                    <?php else: ?>
                        <i class="fa-solid fa-file-pdf text-danger" style="font-size: 3.5rem;"></i>
                    <?php endif; ?>
                    <a href="<?= $fileUrl ?>" target="_blank" class="preview-badge" style="position: absolute; top: 8px; left: 8px; background: rgba(0,0,0,0.6); color: #fff; border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; text-decoration: none;" title="مشاهده فایل اصلی">
                        <i class="fa-solid fa-expand"></i>
                    </a>
                </div>

                <div style="padding: 12px 14px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.82rem; font-weight: 700; color: #011C39; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px;" title="<?= e($item['original_name']) ?>">
                            <?= e($item['original_name']) ?>
                        </div>
                        <div style="font-size: 0.75rem; color: #94a3b8; display: flex; justify-content: space-between;">
                            <span><?= $fileSizeKb ?> کیلوبایت</span>
                            <span><?= to_shamsi($item['created_at']) ?></span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 6px; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 8px;">
                        <button type="button" class="btn btn-sm btn-outline" onclick="copyFileUrl('<?= $fileUrl ?>')" style="flex-grow: 1; font-size: 0.75rem; padding: 4px 6px;" title="کپی آدرس مستقیم فایل">
                            <i class="fa-solid fa-copy"></i>
                            <span>کپی لینک</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline text-danger" onclick="confirmDeleteMedia(<?= $item['id'] ?>, '<?= e($item['original_name']) ?>')" style="padding: 4px 8px; font-size: 0.75rem;" title="حذف پرونده">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Hidden Delete Form -->
<form id="deleteMediaForm" method="POST" action="<?= url('admin/media/delete') ?>" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="media_id" id="deleteMediaId">
</form>

<script>
    // Drag & Drop
    var dropBox = document.getElementById('dropzoneBox');
    if (dropBox) {
        ['dragenter', 'dragover'].forEach(function(evt) {
            dropBox.addEventListener(evt, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropBox.style.borderColor = '#EF5C16';
                dropBox.style.background = 'rgba(239,92,22,0.03)';
            });
        });
        ['dragleave', 'drop'].forEach(function(evt) {
            dropBox.addEventListener(evt, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropBox.style.borderColor = '#cbd5e1';
                dropBox.style.background = '#fff';
            });
        });
        dropBox.addEventListener('drop', function(e) {
            var files = e.dataTransfer.files;
            if (files && files[0]) {
                uploadSingleFile(files[0]);
            }
        });
    }

    function handleDirectUpload(input) {
        if (input.files && input.files[0]) {
            uploadSingleFile(input.files[0]);
        }
    }

    function uploadSingleFile(file) {
        var progressBox = document.getElementById('uploadProgressBox');
        var progressBar = document.getElementById('uploadProgressBar');
        var progressText = document.getElementById('uploadProgressText');

        if (progressBox) progressBox.style.display = 'block';
        if (progressBar) progressBar.style.width = '20%';
        if (progressText) progressText.innerText = 'در حال ارسال فایل (' + file.name + ')...';

        var formData = new FormData();
        formData.append('file', file);
        formData.append('ajax', '1');

        fetch("<?= url('admin/media/upload') ?>", {
            method: 'POST',
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                if (progressBar) progressBar.style.width = '100%';
                if (progressText) progressText.innerText = 'آپلود با موفقیت تکمیل شد!';
                setTimeout(function() {
                    window.location.reload();
                }, 600);
            } else {
                alert(res.message || 'خطا در بارگذاری');
                if (progressBox) progressBox.style.display = 'none';
            }
        })
        .catch(function() {
            alert('خطای اتصال به سرور جهت آپلود فایل');
            if (progressBox) progressBox.style.display = 'none';
        });
    }

    function copyFileUrl(url) {
        navigator.clipboard.writeText(url).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'لینک فایل کپی شد!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    function confirmDeleteMedia(id, name) {
        Swal.fire({
            title: 'حذف پرونده',
            text: 'آیا از حذف پرونده «' + name + '» مطمئن هستید؟ این فایل از هاست پاک خواهد شد.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'بله، حذف کن',
            cancelButtonText: 'انصراف'
        }).then(function(res) {
            if (res.isConfirmed) {
                document.getElementById('deleteMediaId').value = id;
                document.getElementById('deleteMediaForm').submit();
            }
        });
    }

    function filterMediaGrid(q) {
        q = q.trim().toLowerCase();
        var items = document.querySelectorAll('.media-card-item');
        var visibleCount = 0;
        items.forEach(function(item) {
            var name = item.getAttribute('data-name') || '';
            if (!q || name.indexOf(q) !== -1) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        var countEl = document.getElementById('totalFilesCount');
        if (countEl) countEl.innerText = visibleCount;
    }
</script>
