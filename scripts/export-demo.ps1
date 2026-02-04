$ErrorActionPreference = "Stop"

$projectRoot = Split-Path -Parent $PSScriptRoot
$docsPath = Join-Path $projectRoot "docs"
$baseUrl = "http://127.0.0.1:8000/"

Write-Host "Exporting demo from $baseUrl to $docsPath"

if (Test-Path $docsPath) {
    Write-Host "Cleaning existing docs folder..."
    Remove-Item -Recurse -Force $docsPath
}

New-Item -ItemType Directory -Force -Path $docsPath | Out-Null

Write-Host "Checking for wget.exe..."
$wgetExe = Get-Command "wget.exe" -ErrorAction SilentlyContinue
if (-not $wgetExe) {
    Write-Host "ERROR: wget.exe not found. PowerShell 'wget' is an alias for Invoke-WebRequest and cannot mirror sites."
    Write-Host "Install wget (recommended) or use HTTrack, then rerun this script."
    Write-Host "Example install via winget: winget install GnuWin32.Wget"
    exit 1
}

Write-Host "Mirroring site with wget.exe..."
& $wgetExe.Source --mirror --convert-links --page-requisites --adjust-extension --no-parent $baseUrl -P $docsPath

Write-Host "Fetching admin and user pages..."
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}admin" -P $docsPath
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}home" -P $docsPath
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}user/profile" -P $docsPath
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}user/monthly-report" -P $docsPath
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}user/final-report" -P $docsPath

Write-Host "Writing direct HTML pages..."
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}admin" -O (Join-Path $docsPath "admin.html")
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}home" -O (Join-Path $docsPath "home.html")
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}user/profile" -O (Join-Path $docsPath "profile.html")
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}user/monthly-report" -O (Join-Path $docsPath "monthly-report.html")
& $wgetExe.Source --convert-links --page-requisites --adjust-extension --no-parent "${baseUrl}user/final-report" -O (Join-Path $docsPath "final-report.html")

Write-Host "Normalizing docs output..."
$mirroredDir = Join-Path $docsPath "127.0.0.1+8000"
if (Test-Path $mirroredDir) {
    if (-not (Test-Path (Join-Path $docsPath "index.html"))) {
        Get-ChildItem -Path $mirroredDir -Force | ForEach-Object {
            Move-Item -Path $_.FullName -Destination $docsPath -Force
        }
    }
    Remove-Item -Recurse -Force $mirroredDir
}

Write-Host "Creating landing index..."
$adminPage = Join-Path $docsPath "admin.html"
$adminMirror = Join-Path $docsPath "admin\\index.html"
$homePage = Join-Path $docsPath "home.html"
$homeMirror = Join-Path $docsPath "home\\index.html"
$profilePage = Join-Path $docsPath "profile.html"
$profileMirror = Join-Path $docsPath "user\\profile\\index.html"
$monthlyPage = Join-Path $docsPath "monthly-report.html"
$monthlyMirror = Join-Path $docsPath "user\\monthly-report\\index.html"
$finalPage = Join-Path $docsPath "final-report.html"
$finalMirror = Join-Path $docsPath "user\\final-report\\index.html"
$indexPage = Join-Path $docsPath "index.html"

if (Test-Path $adminMirror) {
    Move-Item -Path $adminMirror -Destination $adminPage -Force
}
if (Test-Path $homeMirror) {
    Move-Item -Path $homeMirror -Destination $homePage -Force
}
if ((Test-Path $profileMirror) -and (-not (Test-Path $profilePage))) {
    Move-Item -Path $profileMirror -Destination $profilePage -Force
}
if ((Test-Path $monthlyMirror) -and (-not (Test-Path $monthlyPage))) {
    Move-Item -Path $monthlyMirror -Destination $monthlyPage -Force
}
if ((Test-Path $finalMirror) -and (-not (Test-Path $finalPage))) {
    Move-Item -Path $finalMirror -Destination $finalPage -Force
}
if (-not (Test-Path $adminPage) -and (Test-Path $indexPage)) {
    Move-Item -Path $indexPage -Destination $adminPage -Force
}

$landingHtml = @"
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Sistem Absensi</title>
    <meta name="robots" content="noindex">
    <style>
      body {
        font-family: Arial, sans-serif;
        background: #f6f7fb;
        color: #1f2430;
        margin: 0;
        padding: 24px;
      }
      .card {
        max-width: 680px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
      }
      h1 {
        margin: 0 0 8px;
        font-size: 22px;
      }
      p {
        margin: 0 0 16px;
        color: #5c6475;
      }
      a {
        display: inline-block;
        margin-right: 12px;
        margin-bottom: 8px;
        color: #ffffff;
        background: #1f6feb;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
      }
      a.secondary {
        background: #5c6475;
      }
      .small {
        font-size: 12px;
        color: #7b8498;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>Demo Sistem Absensi</h1>
      <p>Pilih dashboard yang ingin dilihat.</p>
      <div>
        <a href="./admin.html">Dashboard Admin</a>
        <a class="secondary" href="./home.html">Dashboard User</a>
      </div>
      <p class="small">Jika halaman tidak terbuka, pastikan file demo sudah di-export.</p>
    </div>
  </body>
</html>
"@

$landingHtml | Set-Content -Path $indexPage -Encoding UTF8

Write-Host "Ensuring user pages exist..."
$fallbackPages = @(
    @{ Path = $profilePage; Title = "Profil"; Target = "./home.html" },
    @{ Path = $monthlyPage; Title = "Monthly Report"; Target = "./home.html" },
    @{ Path = $finalPage; Title = "Final Report"; Target = "./home.html" }
)

foreach ($page in $fallbackPages) {
    if (-not (Test-Path $page.Path)) {
        @"
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>$($page.Title)</title>
    <style>
      body { font-family: Arial, sans-serif; background: #f6f7fb; color: #1f2430; padding: 24px; }
      .card { max-width: 640px; margin: 0 auto; background: #fff; border: 1px solid #e6e9f0; border-radius: 12px; padding: 20px; }
      a { color: #1f6feb; text-decoration: none; font-weight: 600; }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>$($page.Title)</h1>
      <p>Halaman ini belum berhasil diekspor. Silakan kembali ke dashboard user.</p>
      <a href="$($page.Target)">Kembali ke Dashboard User</a>
    </div>
  </body>
</html>
"@ | Set-Content -Path $page.Path -Encoding UTF8
    }
}

Write-Host "Creating redirect stubs..."
$redirects = @(
    @{ Path = (Join-Path $docsPath "admin\\index.html"); Target = "../admin.html" },
    @{ Path = (Join-Path $docsPath "home\\index.html"); Target = "../home.html" },
    @{ Path = (Join-Path $docsPath "user\\home\\index.html"); Target = "../../home.html" },
    @{ Path = (Join-Path $docsPath "user\\profile\\index.html"); Target = "../../profile.html" },
    @{ Path = (Join-Path $docsPath "user\\monthly-report\\index.html"); Target = "../../monthly-report.html" },
    @{ Path = (Join-Path $docsPath "user\\final-report\\index.html"); Target = "../../final-report.html" }
)

foreach ($redir in $redirects) {
    $dir = Split-Path -Parent $redir.Path
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Force -Path $dir | Out-Null
    }
    @"
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0; url=$($redir.Target)">
    <title>Redirect</title>
  </head>
  <body>
    <p>Redirecting...</p>
  </body>
</html>
"@ | Set-Content -Path $redir.Path -Encoding UTF8
}

Write-Host "Rewriting localhost links..."
$files = Get-ChildItem -Path $docsPath -Recurse -File -Include *.html,*.css,*.js
foreach ($file in $files) {
    $content = Get-Content -Path $file.FullName -Raw
    $content = $content -replace 'http://127\.0\.0\.1:8000/', './'
    $content = $content -replace 'http://127\.0\.0\.1:8000', './'
    $content = $content -replace '127\.0\.0\.1:8000/', './'
    $content = $content -replace '"/user/monthly-report"', '"./monthly-report.html"'
    $content = $content -replace '"/user/final-report"', '"./final-report.html"'
    $content = $content -replace '"/user/profile"', '"./profile.html"'
    $content = $content -replace '"/user/home"', '"./home.html"'
    $content = $content -replace '"user/monthly-report"', '"./monthly-report.html"'
    $content = $content -replace '"user/final-report"', '"./final-report.html"'
    $content = $content -replace '"user/profile"', '"./profile.html"'
    $content = $content -replace '"user/home"', '"./home.html"'
    $content = $content -replace '"/home"', '"./home.html"'
    $content = $content -replace '"/admin"', '"./admin.html"'
    Set-Content -Path $file.FullName -Value $content -Encoding UTF8
}

Write-Host "Copying public assets..."
Copy-Item (Join-Path $projectRoot "public\\assets") (Join-Path $docsPath "assets") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\plugins") (Join-Path $docsPath "plugins") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\js") (Join-Path $docsPath "js") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\svg") (Join-Path $docsPath "svg") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\favicon.ico") (Join-Path $docsPath "favicon.ico") -Force

Write-Host "Done. Open docs/index.html to preview."
