# Copy the skills in this repo into your personal Claude Code skills folder.
$here = Split-Path -Parent $MyInvocation.MyCommand.Path
$dest = if ($env:CLAUDE_SKILLS_DIR) { $env:CLAUDE_SKILLS_DIR } else { Join-Path $HOME ".claude\skills" }
New-Item -ItemType Directory -Force $dest | Out-Null
Get-ChildItem (Join-Path $here "skills") -Directory | ForEach-Object {
    $target = Join-Path $dest $_.Name
    if (Test-Path $target) { Remove-Item $target -Recurse -Force }
    Copy-Item $_.FullName $target -Recurse
    Write-Host "Installed $($_.Name) -> $target"
}
Write-Host "Restart Claude Code to pick up the skills."
