param(
    [string]$Agent = "unknown",
    [string]$Task = "no task specified"
)

$today = Get-Date -Format "yyyy-MM-dd"
$now = Get-Date -Format "HH:mm"

$agentDir = Join-Path $PSScriptRoot "daily-agent"
if (-not (Test-Path $agentDir)) { New-Item -ItemType Directory -Path $agentDir -Force | Out-Null }

$logFile = Join-Path $agentDir "$today.md"

if (-not (Test-Path $logFile)) {
    "# $today Zidooka Agent Log`n`n---`n" | Out-File -FilePath $logFile -Encoding UTF8
}

"`n### $Agent — $now`n**Task:** $Task`n" | Out-File -FilePath $logFile -Encoding UTF8 -Append

Write-Host "Logged: $Agent at $now - $Task"
Write-Host "File: $logFile"
