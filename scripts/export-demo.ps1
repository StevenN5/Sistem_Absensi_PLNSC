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

Write-Host "Normalizing docs output..."
$mirroredDir = Join-Path $docsPath "127.0.0.1+8000"
if (Test-Path $mirroredDir) {
    Get-ChildItem -Path $mirroredDir -Force | ForEach-Object {
        Move-Item -Path $_.FullName -Destination $docsPath -Force
    }
    Remove-Item -Recurse -Force $mirroredDir
}

Write-Host "Creating landing index..."
$indexPage = Join-Path $docsPath "index.html"

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
        <a href="./employees.html">Dashboard Admin</a>
        <a class="secondary" href="./home.html">Dashboard User</a>
      </div>
      <p class="small">Jika halaman tidak terbuka, pastikan file demo sudah di-export.</p>
    </div>
  </body>
</html>
"@

$landingHtml | Set-Content -Path $indexPage -Encoding UTF8

Write-Host "Rewriting localhost links..."
$files = Get-ChildItem -Path $docsPath -Recurse -File -Include *.html,*.css,*.js
foreach ($file in $files) {
    $content = Get-Content -Path $file.FullName -Raw
    $content = $content.Replace("http://127.0.0.1:8000/", "./")
    $content = $content.Replace("http://127.0.0.1:8000", "./")
    $content = $content.Replace("127.0.0.1:8000/", "./")
    Set-Content -Path $file.FullName -Value $content -Encoding UTF8
}

Write-Host "Copying public assets..."
Copy-Item (Join-Path $projectRoot "public\\assets") (Join-Path $docsPath "assets") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\plugins") (Join-Path $docsPath "plugins") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\js") (Join-Path $docsPath "js") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\svg") (Join-Path $docsPath "svg") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\favicon.ico") (Join-Path $docsPath "favicon.ico") -Force

Write-Host "Done. Open docs/index.html to preview."
