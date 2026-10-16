<?php
/**
 * Thermoban - Universal Cross-Platform cPanel Deployment Packager
 * Generates a clean, 100% Linux/cPanel-compatible ZIP archive with strictly normalized forward slashes (/).
 */

$rootDir = dirname(__DIR__);
$zipFile = $rootDir . DIRECTORY_SEPARATOR . 'thermoban-cpanel-installer.zip';

echo "=== Building Universal cPanel Package for Thermoban ===\n";

if (file_exists($zipFile)) {
    unlink($zipFile);
}

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Error: Cannot create zip file at $zipFile\n");
}

/**
 * Add directory recursively to zip with normalized forward slashes
 */
function addDirToZip(ZipArchive $zip, string $dirPath, string $baseInZip = ''): void {
    $files = scandir($dirPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        // Skip git, node_modules, temp files
        if (in_array($file, ['.git', '.github', 'node_modules', 'cpanel_staging'])) continue;

        $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
        $inZipPath = $baseInZip === '' ? $file : $baseInZip . '/' . $file;
        $inZipPath = str_replace('\\', '/', $inZipPath);

        if (is_dir($filePath)) {
            $zip->addEmptyDir($inZipPath);
            $zip->setExternalAttributesName($inZipPath, ZipArchive::OPSYS_UNIX, (0040755 << 16));
            addDirToZip($zip, $filePath, $inZipPath);
        } else {
            $zip->addFile($filePath, $inZipPath);
            $zip->setExternalAttributesName($inZipPath, ZipArchive::OPSYS_UNIX, (0100644 << 16));
        }
    }
}

// 1. Add app, database, public
echo "1. Adding application, database, and public assets...\n";
addDirToZip($zip, $rootDir . '/app', 'app');
addDirToZip($zip, $rootDir . '/database', 'database');
addDirToZip($zip, $rootDir . '/public', 'public');

// Ensure public/uploads/settings directory exists
$zip->addEmptyDir('public/uploads/settings');
$zip->setExternalAttributesName('public/uploads/settings', ZipArchive::OPSYS_UNIX, (0040755 << 16));

// 2. Add fresh storage structure (No installed.lock, no logs, no previous backup files)
echo "2. Adding clean storage directory structure...\n";
foreach (['storage', 'storage/logs', 'storage/cache', 'storage/backups'] as $sDir) {
    $zip->addEmptyDir($sDir);
    $zip->setExternalAttributesName($sDir, ZipArchive::OPSYS_UNIX, (0040755 << 16));
}

$htaccessDeny = "Deny from all\n";
$zip->addFromString('storage/.htaccess', $htaccessDeny);
$zip->setExternalAttributesName('storage/.htaccess', ZipArchive::OPSYS_UNIX, (0100644 << 16));

$zip->addFromString('storage/backups/.htaccess', $htaccessDeny);
$zip->setExternalAttributesName('storage/backups/.htaccess', ZipArchive::OPSYS_UNIX, (0100644 << 16));

// 3. Add root files
echo "3. Adding root entry files...\n";
$rootFiles = ['.htaccess', 'index.php', 'README.md'];
foreach ($rootFiles as $rf) {
    $fullPath = $rootDir . DIRECTORY_SEPARATOR . $rf;
    if (file_exists($fullPath)) {
        $zip->addFile($fullPath, $rf);
        $zip->setExternalAttributesName($rf, ZipArchive::OPSYS_UNIX, (0100644 << 16));
    }
}

$zip->close();

// 4. Verify that ALL entries use strictly forward slashes (/)
echo "4. Verifying Linux / cPanel path compatibility...\n";
$checkZip = new ZipArchive();
if ($checkZip->open($zipFile) === true) {
    $hasBackslash = false;
    $totalFiles = $checkZip->numFiles;
    for ($i = 0; $i < $totalFiles; $i++) {
        $name = $checkZip->getNameIndex($i);
        if (str_contains($name, '\\')) {
            $hasBackslash = true;
            echo " [FAIL] Illegal backslash found in entry: $name\n";
            break;
        }
    }

    $sizeMb = round(filesize($zipFile) / (1024 * 1024), 2);
    $checkZip->close();

    if ($hasBackslash) {
        die("FATAL: Zip archive contains backslashes!\n");
    }

    echo "\n=======================================================\n";
    echo "SUCCESS: cPanel installer zip package successfully created!\n";
    echo "Package Path: $zipFile\n";
    echo "Total Files in Archive: $totalFiles\n";
    echo "Archive Size: {$sizeMb} MB\n";
    echo "Compatibility: 100% Linux / cPanel / DirectAdmin compatible\n";
    echo "=======================================================\n";
} else {
    die("Error opening generated zip file.\n");
}
