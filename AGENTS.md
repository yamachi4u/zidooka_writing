# AGENTS Guidelines

Scope: zidooka_writing repository.

## Privacy First

Never export information from conversations unless the user explicitly instructs you to create or update an external artifact (Issue, PR, article, documentation, etc.).

When exporting information, include only what is necessary for that artifact.

If there is any uncertainty about whether information should be exported, ask the user before proceeding.

外部成果物（Issue / PR / 記事 / ドキュメント）に書く前に、会話中の個人情報・研究メモ・仕事上の機微・過去の文脈を無断で転記しない。判断に迷う場合は確認を優先する。

## Quick Index

| 知りたいこと | 見るセクション |
|---|---|
| 作業を始める前に | [Start-of-Work](#start-of-work) |
| ナレッジベース | [Knowledge Base](#knowledge-base) |
| PostHog A/B実験の状況 | [PostHog A/B Operations](#posthog-ab-operations) |
| 記事を公開する | [Publishing Pipeline](#publishing-pipeline) |
| SEO/分析を回す | [Analytics & SEO](#analytics--seo) |
| 広告(AdSense/A8)を触る | [Ads Management](#ads-management) |
| 自己改善ループ | [Self-Improvement](#self-improvement) |
| テーマをデプロイする | [Remote Theme Pipeline](#remote-theme-pipeline) |
| 文体・表記ルール | [Conventions](#conventions) |
| スクリーンショット | [Screenshot Capture](#screenshot-capture) |
| テーマの視覚改善 | `docs/THEME_VISUAL_QA.md` |
| サムネイル生成 | [Thumbnail Generator](#thumbnail-generator) |

---

## Start-of-Work

1. Read today's agent coordination log: `daily-agent/YYYYMMDD.md`
2. Read current PostHog status: `drat/posthog-status.md`
3. Check decision records: `docs/decisions/` (verification dates)
4. If working on theme files: `docs/operations/README.md` and `docs/THEME_VISUAL_QA.md`
5. Read self-improvement docs: `docs/SELF-IMPROVEMENT.md`, `docs/AGENT-TOOL-IMPROVEMENT.md`
6. Read knowledge base: `knowledge/index.md`

```powershell
.\daily-agent.cmd --agent Codex --task "<task description>"
```

Status words: `start`, `claim`, `doing`, `blocked`, `handoff`, `done`, `memo`.
Append-only. Do not rewrite other agents' entries.

---

## PostHog A/B Operations

### Quick Commands

```powershell
npm run posthog:check    # Full data pull → daily/posthog/YYYY-MM-DD.md + auto-update status
npm run posthog:status   # Read current status from drat/posthog-status.md
```

### Status File

**`drat/posthog-status.md`** is the single source of truth. It shows:
- Active experiment + health (null rate with trend arrows ↑↓→)
- Outcome comparison (control vs treatment + lift)
- Next action with closeout checklist
- Pipeline priority
- Meta alerts

Updated automatically by `npm run posthog:check`.

### Key Rules

- One experiment at a time. At most two if same purpose.
- Never run ad/CTA experiments with readability/navigation experiments.
- CTA/consultation banner experiments are prohibited.
- Judge winners by outcome events (not impression counts).
- Before changing flags or deploying: check `daily-agent/YYYYMMDD.md` for active claims.

### Reference Files

| File | Purpose |
|---|---|
| `drat/posthog-status.md` | **Primary** — current state + next action |
| `docs/operations/posthog-ab-operations.md` | Policy, thresholds, troubleshooting |
| `drat/posthog-experiments.md` | Registry, pipeline, action log |
| `daily/posthog/YYYY-MM-DD.md` | Detailed per-check report |
| `downloads/zidooka-tw/assets/posthog-experiments.js` | Client-side experiment JS |

### Decision Thresholds

| Threshold | Value |
|---|---|
| Min data days | 5 |
| Min impressions/variant | 200 |
| Min outcomes/variant | 100 |
| Meaningful lift | 15% |
| Max null rate | 30% |

---

## Agent Coordination Log

zidooka_writing uses a shared daily coordination log (`daily-agent/YYYYMMDD.md`) to prevent multi-agent conflicts, especially for PostHog experiment operations and theme deployments.

File format and rules: `daily-agent/README.md`.

---

## Knowledge Base

`knowledge/index.md` に OKF 形式で構造化されたナレッジベースあり。
詳細は `C:\Users\user\Documents\knowledge-base-okf\bundles\personal-ops\` の横断KBも参照。

- analytics/ — GA4/GSC/AdSense/Bing/PostHog
- operations/ — improvement-cycle, decision-records, agent-coordination
- conventions/ — writing-style
- reference/ — troubleshooting, glossary, links

---

## Self-Improvement

```powershell
npm run improve          # Auto data collection → TODO generation
npm run improve:report   # Show latest improvement report
npm run decisions        # Check overdue decision records
```

サイクル: `docs/SELF-IMPROVEMENT.md` 参照。
学習ログ: `docs/AGENT-TOOL-IMPROVEMENT.md` 参照。

---

## Publishing Pipeline

### CLI Commands

```powershell
node src/index.js post drafts/file.md          # Publish (with validation)
node src/index.js post drafts/file.md --force   # Skip validation
node src/index.js post-pair drafts/file-ja.md   # Publish ja+en pair
node src/index.js schedule drafts/file.md       # Schedule next free 09:00 JST
node src/index.js thumbnail --title "..." --output path.png
```

### Rules

- Japanese + English paired publishing is the default unless user says single-language.
- Omit `date` from frontmatter for immediate publish (timezone safety).
- `--validate` checks: title length, slug presence, content length (300+ chars), no `【】` brackets.

### Post-Publish

```powershell
node src/index.js post drafts/xxx.md && node scripts/ping-indexnow.mjs <url>
```

### Must-Read

- `PIPELINE_MANUAL.md`
- `docs/snippets/emphasis.md`

---

## Analytics & SEO

### Available Commands

```powershell
npm run weekly          # All 5 channels: GA4 + GSC + AdSense + Bing + PostHog
npm run seo:weekly      # GA4 + GSC 7d digest
npm run seo:monthly     # GA4 + GSC 30d deep dive
npm run seo:errors      # 19 tracked error pages followup
npm run ga4 -- --preset overview|acquisition|landing-pages|events
npm run gsc -- --preset top-queries|top-pages --limit N
npm run adsense
npm run bing -- --preset crawl-stats|top-queries|rank-traffic
```

### Weekly Rhythm

| Frequency | Task | Command |
|---|---|---|
| Mon/Thu | PostHog A/B check | `npm run posthog:check` |
| Weekly | Integrated report | `npm run weekly` |
| Weekly | Low-CTR article check | `npm run gsc -- --preset top-queries --limit 30` |
| Biweekly | GSC gap analysis | `npm run seo:errors` |
| Monthly | Article performance review | `npm run seo:monthly` |

### Monitoring Thresholds

| Metric | Healthy | Warning | Command |
|---|---|---|---|
| RPM | ¥170+ | <¥100 | `npm run adsense` |
| Desktop fill rate | 70%+ | <60% | `npm run adsense -- --dimensions PLATFORM_TYPE_CODE` |
| Bing crawl error | <30% | >50% | `npm run bing -- --preset crawl-stats` |
| GSC low-CTR article | — | CTR<5% & 100+imp | `npm run gsc -- --preset top-queries --limit 30` |

### API Stack

| Channel | Command | Auth |
|---|---|---|
| GA4 | `npm run ga4` | Service Account |
| GSC | `npm run gsc` | Service Account |
| AdSense | `npm run adsense` | OAuth Desktop |
| Bing | `npm run bing` | API Key |
| PostHog | `npm run posthog:check` | Personal API Key |

---

## Ads Management

広告(AdSense + A8.net)は台帳で一元管理。触る前に `docs/ADS_MANAGEMENT.md` を読むこと。

- **台帳実装**: テーマ `downloads/zidooka-tw/inc/ads.php`(`zidooka_ads_default_config()`)。
  WP管理画面 [外観 > Ads Settings] のJSONで上書き可能(デプロイ不要でON/OFF・差し替え)
- **共通レンダラ**: `zidooka_render_ad('<placement_id>')` — ラベル・PostHog計測・CLS対策を統一適用
- **計測イベント**: PostHog `ad_impression` / `ad_click` / `ad_unfilled`
- **死活チェック**: `npm run ads:check`(A8リンク。月次)
- **A8成果CSV**: `data/ads/a8/YYYY-MM.csv` に月次で配置
- 記事に `affiliate` タグを付けるとそのページのAdSenseが無効化
- 判断記録: `docs/decisions/2026-07-07-ads-registry.md`

---

## Remote Theme Pipeline

Push/pull WordPress theme files via WebDAV (configured in `.env`).

```powershell
# Pull for local editing
$env:REMOTE_PROTOCOL='WEBDAV'
$env:WEBDAV_URL='https://ciao-yamakazu.webdav-lolipop.jp/'
$env:WEBDAV_USER='ciao.jp-yamakazu'
$env:WEBDAV_PASS='...'
$env:REMOTE_BASES='zidooka/wp-content/themes/zidooka-tw/'
node scripts/remote-agent/index.js check

# Pull → edit → push
node scripts/remote-agent/index.js pull --file="zidooka/wp-content/themes/zidooka-tw/single.php" --out="tmp_remote_agent/zidooka-tw/single.php"
# ... edit locally ...
node scripts/remote-agent/index.js push --file="zidooka/wp-content/themes/zidooka-tw/single.php" --src="downloads/zidooka-tw/single.php"
```

### Verification

```powershell
node --check downloads/zidooka-tw/assets/posthog-experiments.js
node scripts/remote-agent/index.js push ...
node scripts/remote-agent/index.js pull --file=... --out=tmp_remote_agent/verify.js
# Compare: local == pulled
```

---

## Conventions

### Zidooka Blocks (use these, not 【】)

```
:::conclusion  — 結論・まとめ
:::note        — 補足・メモ・ポイント
:::warning     — 注意・警告
:::step        — 手順・ステップ・対処
:::example     — 具体例
```

### Writing Style

- **参照**: `docs/ZIDOOKA-STYLE.md` — AI臭さを消すための詳細ルール
- 日本語: ですます調
- 英語: standard technical writing, contractions OK
- URLs: wrap in `<...>` or use Markdown links to avoid WordPress auto-link bugs
- **必須**: 公開前に `docs/ZIDOOKA-STYLE.md` のセルフチェックをパスすること

### AdSense Control

Add `affiliate` tag to a post to disable AdSense on that page.

---

## Screenshot Capture

```powershell
node scripts/agent-browser-screenshot.mjs "https://..." "images-agent-browser/output.png"
# Default: 1920x900 PNG
# Resize: $env:SCREEN_WIDTH="1280"; $env:SCREEN_HEIGHT="720"; node scripts/...
# Gallery: node scripts/agent-browser-gallery.mjs  (PC + mobile + tablet)
```

---

## Thumbnail Generator

```powershell
node src/index.js thumbnail --title "..." --output images/2026/thumb.png [--subtitle "..." --accent cyan --category "..." --icon link]
```

Icons: `link` | `plus` | `qr` | `search` | `chart` | `code` | `gear` | `book` | `pen` | `globe`
Accents: `cyan` | `green` | `purple` | `amber` | `red` | `blue` | `pink` | `orange` | `teal` | `indigo`

---

## Key Files Map

| File | Purpose |
|---|---|
| `drat/posthog-status.md` | PostHog current state + next action |
| `drat/posthog-experiments.md` | Experiment registry + pipeline |
| `docs/operations/posthog-ab-operations.md` | Policy + thresholds + troubleshooting |
| `docs/operations/README.md` | Operations registry |
| `daily-agent/YYYYMMDD.md` | Daily coordination log |
| `docs/ADS_MANAGEMENT.md` | Ads design + inventory (AdSense/A8) |
| `drat/seo-todo-zidooka-tw.md` | SEO master TODO |
| `downloads/zidooka-tw/` | Local theme copy (edit here) |
| `tmp_remote_agent/zidooka-tw/` | Remote theme pull (compare) |
