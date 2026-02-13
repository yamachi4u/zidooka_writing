# AGENTS Guidelines

Scope: Applies to the entire repository.

## Must-Read
- `PIPELINE_MANUAL.md` (overall workflow/pipeline manual)
- `docs/snippets/emphasis.md` (standard emphasis patterns to use in outputs)

## Workflow
- Read both Must-Read files before answering or editing.
- Implement changes, then restate which sections you followed.
- Self-check: confirm output conforms to emphasis snippet patterns.

## Conventions
- Use only the emphasis patterns defined in `docs/snippets/emphasis.md` for highlighting key takeaways, cautions, or conclusions.
- Prefer concise, single-line emphasis where possible; avoid decorative emojis.
- For code, use inline backticks for identifiers and fenced blocks for multi-line code.
- **DO NOT use 【】bracket emphasis** (e.g., 【結論】, 【ポイント】, 【注意】, 【対処】).
- **ALWAYS use Zidooka blocks instead** for key takeaways, cautions, steps, examples, and conclusions.
- 【対応ブロック一覧】
  - `:::conclusion` (結論・まとめ) - Use for conclusions and key takeaways
  - `:::note` (補足・メモ・ポイント) - Use for notes and key points
  - `:::warning` (注意・警告) - Use for cautions and warnings
  - `:::step` (手順・ステップ・対処) - Use for action items and procedures
  - `:::example` (具体例) - Use for specific examples

## URL Formatting (WordPress Auto-Link Safety)

WordPress (and some Markdown-to-block pipelines) may auto-link bare URLs. When a URL is immediately followed by Japanese punctuation/text (e.g. `）を開きます。`), the auto-linker can accidentally include that trailing text in the URL.

Rules:
- Prefer Markdown links: `[表示テキスト](https://example.com/path)` when you have link text.
- If you must show a bare URL, wrap it in angle brackets: `<https://example.com/path>`.
- Avoid writing `https://...` directly followed by `）` / `、` / `。` / Japanese text. Put the URL in `<...>` or use a Markdown link.

Examples:
- Bad: `UTM生成ツール（https://tools.zidooka.com/jp/utmtools/generator）を開く`
- Good: `UTM生成ツール（<https://tools.zidooka.com/jp/utmtools/generator>）を開く`
- Good: `[UTM生成ツール](https://tools.zidooka.com/jp/utmtools/generator)を開く`

## Writing Style (記事の文体)
- 日本語記事は原則「ですます調」で統一する。
- 「である調」は避け、読者に親しみやすい文体を維持する。
- 英語記事は standard technical writing style を使用する。

## Conflict Resolution
- If guidance conflicts, follow the more specific rule.
- Ask a clarifying question if uncertainty remains after reading the Must-Read files.

---

## Screenshot Capture (Playwright)

記事用にWebサイトのスクリーンショットが必要な場合、以下のスクリプトを使用する。

### 基本コマンド

```powershell
node scripts/agent-browser-screenshot.mjs "<URL>" "<出力パス>"
```

**例:**
```powershell
node scripts/agent-browser-screenshot.mjs "https://example.com" "images-agent-browser/example-homepage.png"
```

### 仕様
- **デフォルトサイズ**: 1920x900（横長、記事に最適）
- **出力先**: `images-agent-browser/` ディレクトリ
- **形式**: PNG
- **fullPage**: false（ビューポートのみ、縦長にならない）

### サイズ変更（環境変数）
```powershell
$env:SCREEN_WIDTH = "1280"; $env:SCREEN_HEIGHT = "720"; node scripts/agent-browser-screenshot.mjs "<URL>" "<出力パス>"
```

### ギャラリー撮影（複数デバイス）
```powershell
node scripts/agent-browser-gallery.mjs
```
→ PC全体・モバイル・タブレットの3種類を `images-agent-browser/` に保存

### 記事への組み込み
撮影後、Markdownで以下のように参照:
```markdown
![サイトのスクリーンショット](../images-agent-browser/example-homepage.png)
```

### 注意事項
- URLは必ず `https://` から始める
- 出力ファイル名にスペースを含めない
- 撮影前にサイトが存在するか確認する
- 記事用には縦長（fullPage: true）を避け、ビューポートのみを撮影する

---

# AGENTS.md

**Rule:** In each command, **define → use**. Do **not** escape `$`. Use generic `'path/to/file.ext'`.

---

## 1) READ (UTF‑8 no BOM, line‑numbered)

```bash
bash -lc 'powershell -NoLogo -Command "
$OutputEncoding = [Console]::OutputEncoding = [Text.UTF8Encoding]::new($false);
Set-Location -LiteralPath (Convert-Path .);
function Get-Lines { param([string]$Path,[int]$Skip=0,[int]$First=40)
  $enc=[Text.UTF8Encoding]::new($false)
  $text=[IO.File]::ReadAllText($Path,$enc)
  if($text.Length -gt 0 -and $text[0] -eq [char]0xFEFF){ $text=$text.Substring(1) }
  $ls=$text -split \"`r?`n\"
  for($i=$Skip; $i -lt [Math]::Min($Skip+$First,$ls.Length); $i++){ \"{0:D4}: {1}\" -f ($i+1), $ls[$i] }
}
Get-Lines -Path \"path/to/file.ext\" -First 120 -Skip 0
"'
```

---

## 2) WRITE (UTF‑8 no BOM, atomic replace, backup)

```bash
bash -lc 'powershell -NoLogo -Command "
$OutputEncoding = [Console]::OutputEncoding = [Text.UTF8Encoding]::new($false);
Set-Location -LiteralPath (Convert-Path .);
function Write-Utf8NoBom { param([string]$Path,[string]$Content)
  $dir = Split-Path -Parent $Path
  if (-not (Test-Path $dir)) {
    New-Item -ItemType Directory -Path $dir -Force | Out-Null
  }
  $tmp = [IO.Path]::GetTempFileName()
  try {
    $enc = [Text.UTF8Encoding]::new($false)
    [IO.File]::WriteAllText($tmp,$Content,$enc)
    Move-Item $tmp $Path -Force
  }
  finally {
    if (Test-Path $tmp) {
      Remove-Item $tmp -Force -ErrorAction SilentlyContinue
    }
  }
}
$file = "path/to/your_file.ext"
$enc  = [Text.UTF8Encoding]::new($false)
$old  = (Test-Path $file) ? ([IO.File]::ReadAllText($file,$enc)) : ''
Write-Utf8NoBom -Path $file -Content ($old+"`nYOUR_TEXT_HERE`n")
"'
```

---

## Remote Theme Pipeline (SFTP/FTPS/WebDAV)

Minimal, safe pipeline to pull/push WordPress theme files using the bundled Remote Template Agent.

### Prerequisites
- Node.js installed; run `npm install` once.
- `.env` contains remote credentials and whitelist path(s).

### Environment (define → use)

PowerShell example (pick one protocol and define required vars):

```powershell
# SFTP
$env:REMOTE_PROTOCOL = "SFTP"
$env:SFTP_HOST = "example.host"
$env:SFTP_PORT = "22"
$env:SFTP_USER = "username"
$env:SFTP_PASS = "password"

# FTPS (optional)
$env:REMOTE_PROTOCOL = "FTPS"
$env:FTPS_HOST = "example.host"
$env:FTPS_PORT = "21"
$env:FTPS_USER = "username"
$env:FTPS_PASS = "password"

# WebDAV (optional; often works on shared hosts)
$env:REMOTE_PROTOCOL = "WEBDAV"
$env:WEBDAV_URL  = "https://example.webdav-host.tld/"
$env:WEBDAV_USER = "username"
$env:WEBDAV_PASS = "password"

# Whitelist: allowed remote prefixes (comma-separated)
$env:REMOTE_BASES = "site/wp-content/themes/your-theme/,site/wp-content/themes/your-child-theme/"

# Use after defining the above
node scripts/remote-agent/index.js check
```

### Common Commands (define → use)

- List a directory
```powershell
$dir = "site/wp-content/themes/your-theme/"
node scripts/remote-agent/index.js ls --dir="$dir"
```

- Pull a remote file
```powershell
$remote = "site/wp-content/themes/your-theme/path/to/file.php"
$out    = "tmp_remote_agent/your-theme/file.php"
node scripts/remote-agent/index.js pull --file="$remote" --out="$out"
```

- Push a local file (creates `<file>.bak.<timestamp>` on remote)
```powershell
$remote = "site/wp-content/themes/your-theme/path/to/file.php"
$src    = "path/to/file.php"
node scripts/remote-agent/index.js push --file="$remote" --src="$src"
```

### WEBDAV Example (ZIDOOKA)

```powershell
$env:REMOTE_PROTOCOL = "WEBDAV"
$env:WEBDAV_URL  = "https://example.webdav-host.tld/"
$env:WEBDAV_USER = "username"
$env:WEBDAV_PASS = "password"
$env:REMOTE_BASES = "zidooka/wp-content/themes/zidooka-tw/"
node scripts/remote-agent/index.js check

$remote = "zidooka/wp-content/themes/zidooka-tw/single.php"
$out    = "tmp_remote_agent/zidooka-tw/single.php"
node scripts/remote-agent/index.js pull --file="$remote" --out="$out"

$src = "tmp_remote_agent/zidooka-tw/single.php"
node scripts/remote-agent/index.js push --file="$remote" --src="$src"
```

- Safe in-place replace (preview or apply)
```powershell
$file = "site/wp-content/themes/your-theme/path/to/file.php"
node scripts/remote-agent/index.js replace --file="$file" --from="old" --to="new" --dry-run
node scripts/remote-agent/index.js replace --file="$file" --from="old" --to="new"
```

### Notes
- Operations are refused outside `REMOTE_BASES` for safety.
- Local backups are stored under `tmp_remote_agent/`.
- Prefer editing locally (pull → edit → push). Use `replace` for small, surgical changes.

---

## Thumbnail Generator

Generate branded ZIDOOKA thumbnail images (SVG → PNG via sharp) for blog articles.

### Usage

```bash
node src/index.js thumbnail --title "..." --output path/to/file.png [options]
```

Or directly:
```bash
node scripts/generate-thumbnail.cjs --title "..." --output path/to/file.png [options]
```

### Required Options

| Option     | Description           |
|------------|-----------------------|
| `--title`  | Main title text       |
| `--output` | Output file path (.png) |

### Optional Options

| Option       | Default              | Description                          |
|--------------|----------------------|--------------------------------------|
| `--subtitle` | (none)               | Subtitle text                        |
| `--icon`     | `link`               | Icon type (see list below)           |
| `--accent`   | `cyan` (#06b6d4)     | Accent color — name or hex           |
| `--category` | `便利ツール`          | Category pill text                   |
| `--badge`    | `tools.zidooka.com`  | Bottom badge text                    |
| `--width`    | `1920`               | Image width in px                    |
| `--height`   | `900`                | Image height in px                   |

### Available Icons

`link` | `plus` | `qr` | `search` | `chart` | `code` | `gear` | `book` | `pen` | `globe`

### Accent Color Presets

`cyan` | `green` | `purple` | `amber` | `red` | `blue` | `pink` | `orange` | `teal` | `indigo`

Any hex color (e.g. `#ff6600`) is also accepted.

### Examples

```bash
# UTM tool article thumbnail
node src/index.js thumbnail \
  --title "UTM生成ツールの使い方" \
  --subtitle "入力候補・短縮URL・QRコードまで" \
  --icon plus --accent green \
  --output images/2026/utm-generator-thumbnail.png

# GAS article with custom category and badge
node src/index.js thumbnail \
  --title "GASでメール自動送信" \
  --icon code --accent indigo \
  --category "GAS" --badge "www.zidooka.com" \
  --output images/2026/gas-mail-thumbnail.png
```

### Output Spec
- Format: PNG
- Default size: 1920x900
- Design: gradient background, accent bar top/bottom, decorative circles, icon area, ZIDOOKA.COM label, category pill, title/subtitle, badge
- Output directories are created automatically if they don't exist

### Frontmatter Integration

Reference the generated thumbnail in article frontmatter:
```yaml
featured_image: ../images/2026/my-article-thumbnail.png
```

The CLI's `post` command will upload and attach it as the featured image automatically.
