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

Write-Host "Checking for wget..."
$wget = Get-Command "wget" -ErrorAction SilentlyContinue
if (-not $wget) {
    Write-Host "ERROR: wget not found. Install wget or use HTTrack to mirror $baseUrl to $docsPath"
    exit 1
}

Write-Host "Mirroring site..."
wget --mirror --convert-links --page-requisites --adjust-extension --no-parent $baseUrl -P $docsPath

Write-Host "Copying public assets..."
Copy-Item (Join-Path $projectRoot "public\\assets") (Join-Path $docsPath "assets") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\plugins") (Join-Path $docsPath "plugins") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\js") (Join-Path $docsPath "js") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\svg") (Join-Path $docsPath "svg") -Recurse -Force
Copy-Item (Join-Path $projectRoot "public\\favicon.ico") (Join-Path $docsPath "favicon.ico") -Force

Write-Host "Done. Open docs/index.html to preview."
