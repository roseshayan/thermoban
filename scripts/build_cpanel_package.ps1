# =========================================================================
# Thermoban - cPanel Shared Hosting Deployment Packager
# Generates a standalone, clean zip package with the Web Installer ready
# =========================================================================

$projectRoot = Split-Path -Parent $PSScriptRoot
$zipOutput = Join-Path $projectRoot "thermoban-cpanel-installer.zip"
$tempStaging = Join-Path $projectRoot "storage\cpanel_staging"

Write-Host "Creating cPanel Installer Package for Thermoban..." -ForegroundColor Cyan

# 1. Clean previous staging
if (Test-Path $tempStaging) {
    Remove-Item -Recurse -Force $tempStaging
}
if (Test-Path $zipOutput) {
    Remove-Item -Force $zipOutput
}

New-Item -ItemType Directory -Path $tempStaging -Force | Out-Null

# 2. Copy directories
$dirsToCopy = @("app", "database", "public")
foreach ($dir in $dirsToCopy) {
    $src = Join-Path $projectRoot $dir
    $dest = Join-Path $tempStaging $dir
    Copy-Item -Recurse -Path $src -Destination $dest
}

# 3. Create fresh storage directory structure (WITHOUT installed.lock or previous backup sql files)
$stagingStorage = Join-Path $tempStaging "storage"
New-Item -ItemType Directory -Path (Join-Path $stagingStorage "backups") -Force | Out-Null
New-Item -ItemType Directory -Path (Join-Path $stagingStorage "logs") -Force | Out-Null
New-Item -ItemType Directory -Path (Join-Path $stagingStorage "cache") -Force | Out-Null

# Add safety .htaccess to storage
Set-Content -Path (Join-Path $stagingStorage ".htaccess") -Value "Deny from all`n"
Set-Content -Path (Join-Path (Join-Path $stagingStorage "backups") ".htaccess") -Value "Deny from all`n"

# 4. Copy root files
$filesToCopy = @(".htaccess", "index.php", "README.md")
foreach ($file in $filesToCopy) {
    $src = Join-Path $projectRoot $file
    if (Test-Path $src) {
        Copy-Item -Path $src -Destination $tempStaging
    }
}

# 5. Compress into zip
Write-Host "Compressing files into $zipOutput..." -ForegroundColor Yellow
Compress-Archive -Path "$tempStaging\*" -DestinationPath $zipOutput -CompressionLevel Optimal

# 6. Cleanup staging
Remove-Item -Recurse -Force $tempStaging

$fileInfo = Get-Item $zipOutput
$sizeMb = [math]::Round($fileInfo.Length / 1MB, 2)
Write-Host "Successfully generated cPanel package!" -ForegroundColor Green
Write-Host "Package Path: $zipOutput" -ForegroundColor Green
Write-Host "File Size: $sizeMb MB" -ForegroundColor Green
Write-Host "To install on cPanel: Upload & Extract into public_html, then open your domain in browser!" -ForegroundColor Cyan
