# =========================================================================
# Thermoban - cPanel Shared Hosting Deployment Packager
# Generates a standalone, clean zip package with the Web Installer ready
# Uses PHP ZipArchive to guarantee 100% Linux/cPanel forward-slash (/) compatibility
# =========================================================================

$projectRoot = Split-Path -Parent $PSScriptRoot
$packagerScript = Join-Path $projectRoot "scripts\build_package.php"

Write-Host "Invoking Universal PHP Packager..." -ForegroundColor Cyan
php $packagerScript
