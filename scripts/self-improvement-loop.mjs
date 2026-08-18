import 'dotenv/config';
import { mkdir, writeFile, readFile } from 'fs/promises';
import path from 'path';

const PH_HOST = process.env.POSTHOG_HOST || 'https://us.posthog.com';
const PH_KEY  = process.env.POSTHOG_PERSONAL_API_KEY;
const PH_PROJ = process.env.POSTHOG_PROJECT_ID;

async function run(cmd) {
  const { execSync } = await import('child_process');
  try {
    const out = execSync(cmd, { encoding: 'utf8', maxBuffer: 10 * 1024 * 1024 });
    return { ok: true, stdout: out };
  } catch (e) {
    return { ok: false, stdout: e.stdout || '', stderr: e.stderr || e.message };
  }
}

function todayDate() {
  const d = new Date();
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

function parseTableLines(text, colCount) {
  const lines = text.split('\n').filter(l => l.includes('|') && !l.startsWith('---'));
  return lines.map(l => l.split('|').map(c => c.trim()).filter(Boolean)).filter(r => r.length >= colCount);
}

function parseGscPages(text) {
  const rows = parseTableLines(text, 5);
  const opportunities = [];
  for (const r of rows) {
    const page = r[0];
    const clicks = Number(r[1]) || 0;
    const impressions = Number(r[2]) || 0;
    const ctr = parseFloat(r[3]) || 0;
    const position = Number(r[4]) || 0;
    if (impressions >= 50 && ctr < 3.0) {
      opportunities.push({ type: 'low_ctr', page, impressions, clicks, ctr, position, severity: impressions >= 300 ? 'high' : 'medium' });
    }
    if (position <= 10 && clicks < 5 && impressions >= 200) {
      opportunities.push({ type: 'high_pos_low_clicks', page, impressions, clicks, ctr, position, severity: 'medium' });
    }
  }
  return opportunities;
}

function parseGa4LandingPages(text) {
  const rows = parseTableLines(text, 6);
  const opportunities = [];
  for (const r of rows) {
    const page = r[0];
    const sessions = Number(r[1]) || 0;
    const engaged = Number(r[4]) || 0;
    if (sessions < 20) continue;
    const engagementRate = engaged / sessions;
    if (engagementRate < 0.25 && sessions >= 40) {
      opportunities.push({ type: 'low_engagement', page, sessions, engagementRate: `${(engagementRate * 100).toFixed(0)}%`, severity: 'high' });
    }
  }
  return opportunities;
}

function parseBingCrawl(text) {
  const lines = text.split('\n').filter(l => /^\d{4}-\d{2}/.test(l.trim()));
  if (!lines.length) return [];
  const latest = lines[lines.length - 1].split('|').map(c => c.trim()).filter(Boolean);
  if (latest.length < 3) return [];
  const total = Number(latest[1]) + Number(latest[2]);
  const errRate = total > 0 ? (Number(latest[2]) / total * 100).toFixed(1) : 0;
  if (Number(errRate) > 25) {
    return [{ type: 'high_crawl_error', errRate: `${errRate}%`, errors: latest[2], total, severity: Number(errRate) > 50 ? 'high' : 'medium' }];
  }
  return [];
}

function parseAdsense(text) {
  const rows = parseTableLines(text, 6);
  if (rows.length < 2) return [];
  const rpms = rows.map(r => Number(r[4])).filter(v => !isNaN(v));
  if (rpms.length < 2) return [];
  const first = rpms[0];
  const last = rpms[rpms.length - 1];
  const drop = ((first - last) / first * 100).toFixed(0);
  if (Number(drop) > 20) {
    return [{ type: 'rpm_drop', dropPct: `${drop}%`, first, last, severity: 'high' }];
  }
  return [];
}

async function readDecisionOverdue() {
  try {
    const { execSync } = await import('child_process');
    const out = execSync('node scripts/check-decisions.mjs', { encoding: 'utf8' });
    const lines = out.split('\n').filter(l => l.includes('overdue') || l.includes('OVERDUE') || l.includes('expired'));
    return lines.length > 0 ? lines : [];
  } catch {
    return [];
  }
}

async function main() {
  const date = todayDate();
  const outputDir = path.join('daily', 'self-improvement');
  await mkdir(outputDir, { recursive: true });

  const lines = [];
  lines.push(`# 自己改善ループ分析 (${date})`);
  lines.push('');

  // 1. Collect data
  lines.push('## 1. データ収集');
  lines.push('');

  const endDate = date;
  const startDate = new Date();
  startDate.setDate(startDate.getDate() - 28);
  const sd = `${startDate.getFullYear()}-${String(startDate.getMonth() + 1).padStart(2, '0')}-${String(startDate.getDate()).padStart(2, '0')}`;

  console.log('Fetching GSC top pages...');
  const gscPages = await run(`node scripts/gsc-query.mjs --preset top-pages --limit 100 --start-date ${sd} --end-date ${endDate}`);
  lines.push(`- GSC Top Pages: ${gscPages.ok ? 'OK' : 'FAIL'}`);

  console.log('Fetching GA4 landing pages...');
  const ga4Landing = await run('node scripts/ga4-report.mjs --preset landing-pages --limit 50');
  lines.push(`- GA4 Landing Pages: ${ga4Landing.ok ? 'OK' : 'FAIL'}`);

  console.log('Fetching GA4 events...');
  const ga4Events = await run('node scripts/ga4-report.mjs --preset events --limit 30');
  lines.push(`- GA4 Events: ${ga4Events.ok ? 'OK' : 'FAIL'}`);

  console.log('Fetching AdSense...');
  const adsense = await run('node scripts/adsense-report.mjs');
  lines.push(`- AdSense: ${adsense.ok ? 'OK' : 'FAIL'}`);

  console.log('Fetching Bing crawl...');
  const bingCrawl = await run('node scripts/bing-webmaster-report.mjs --preset crawl-stats');
  lines.push(`- Bing Crawl: ${bingCrawl.ok ? 'OK' : 'FAIL'}`);

  console.log('Fetching PostHog status...');
  const posthog = await run('node scripts/posthog-check.mjs');
  lines.push(`- PostHog A/B: ${posthog.ok ? 'OK' : 'FAIL'}`);

  console.log('Checking decisions...');
  const overdueDecisions = await readDecisionOverdue();
  lines.push(`- Overdue decisions: ${overdueDecisions.length > 0 ? `${overdueDecisions.length} found` : 'none'}`);

  lines.push('');

  // 2. Analyze
  lines.push('## 2. 分析結果');
  lines.push('');

  const gscOpportunities = gscPages.ok ? parseGscPages(gscPages.stdout) : [];
  const ga4Opportunities = ga4Landing.ok ? parseGa4LandingPages(ga4Landing.stdout) : [];
  const bingIssues = bingCrawl.ok ? parseBingCrawl(bingCrawl.stdout) : [];
  const adsenseIssues = adsense.ok ? parseAdsense(adsense.stdout) : [];

  if (gscOpportunities.length > 0) {
    lines.push('### GSC: CTR改善機会（低CTR・高順位ページ）');
    lines.push('');
    lines.push('| ページ | インプレッション | クリック | CTR | 順位 |');
    lines.push('|-------|----------------|---------|-----|------|');
    for (const opp of gscOpportunities.sort((a, b) => a.ctr - b.ctr).slice(0, 15)) {
      lines.push(`| ${opp.page} | ${opp.impressions} | ${opp.clicks} | ${opp.ctr}% | ${opp.position}位 |`);
    }
    lines.push('');
  }

  if (ga4Opportunities.length > 0) {
    lines.push('### GA4: エンゲージメント改善機会');
    lines.push('');
    lines.push('| ページ | セッション | エンゲージ率 |');
    lines.push('|-------|-----------|-------------|');
    for (const opp of ga4Opportunities.sort((a, b) => parseFloat(a.engagementRate) - parseFloat(b.engagementRate))) {
      lines.push(`| ${opp.page} | ${opp.sessions} | ${opp.engagementRate} |`);
    }
    lines.push('');
  }

  if (bingIssues.length > 0) {
    lines.push('### Bing: クロールエラー');
    for (const issue of bingIssues) {
      lines.push(`- エラー率: ${issue.errRate} (${issue.errors} errors / ${issue.total} total) — severity: ${issue.severity}`);
    }
    lines.push('');
  }

  if (adsenseIssues.length > 0) {
    lines.push('### AdSense: RPM低下');
    for (const issue of adsenseIssues) {
      lines.push(`- RPMが ¥${issue.first} → ¥${issue.last} (${issue.dropPct}低下) — severity: ${issue.severity}`);
    }
    lines.push('');
  }

  if (posthog.ok) {
    lines.push('### PostHog A/B');
    const phOut = posthog.stdout;
    const activeMatch = /Active:\s*(.+)/.exec(phOut);
    const nullMatch = /Null rate:\s*([\d.]+)%/.exec(phOut);
    const recMatch = /Rec:\s*(\w+)\s*[—–-]\s*(.+)/s.exec(phOut);
    if (activeMatch) lines.push(`- Active flag: ${activeMatch[1]}`);
    if (nullMatch) lines.push(`- Null rate: ${nullMatch[1]}%`);
    if (recMatch) lines.push(`- Recommendation: ${recMatch[1]} — ${recMatch[2]}`);
    lines.push('');
  }

  if (overdueDecisions.length > 0) {
    lines.push('### 判断記録の期限超過');
    for (const d of overdueDecisions) {
      lines.push(`- ${d}`);
    }
    lines.push('');
  }

  if (!gscOpportunities.length && !ga4Opportunities.length && !bingIssues.length && !adsenseIssues.length && !overdueDecisions.length) {
    lines.push('特筆すべき機会・問題は見つかりませんでした。全ての指標が正常範囲内です。');
    lines.push('');
  }

  // 3. TODO generation
  lines.push('## 3. 推奨TODO');
  lines.push('');

  let todoCount = 0;

  // Low CTR articles
  for (const opp of gscOpportunities.filter(o => o.severity === 'high').slice(0, 3)) {
    todoCount++;
    lines.push(`### TODO-Z${String(todoCount).padStart(2, '0')}: CTR改善 — ${opp.page}`);
    lines.push(`Status: pending`);
    lines.push(`根拠: ${opp.impressions} impressions / CTR ${opp.ctr}% / 順位 ${opp.position}位`);
    lines.push(`候補施策: タイトル・メタディスクリプションの最適化、H1の見直し、リッチスニペット対応`);
    lines.push('');
  }

  // Low engagement articles
  for (const opp of ga4Opportunities.slice(0, 2)) {
    todoCount++;
    lines.push(`### TODO-Z${String(todoCount).padStart(2, '0')}: エンゲージメント改善 — ${opp.page}`);
    lines.push(`Status: pending`);
    lines.push(`根拠: ${opp.sessions} sessions / エンゲージ率 ${opp.engagementRate}`);
    lines.push(`候補施策: 内部リンクの追加、目次の導入、関連記事の推薦、CTAの最適化`);
    lines.push('');
  }

  // Bing crawl errors
  if (bingIssues.length > 0) {
    todoCount++;
    lines.push(`### TODO-Z${String(todoCount).padStart(2, '0')}: Bingクロールエラー対応`);
    lines.push(`Status: pending`);
    lines.push(`根拠: エラー率 ${bingIssues[0].errRate}`);
    lines.push(`候補施策: 404/5xxの原因ページ特定、robots.txt確認、サイトマップ再送信`);
    lines.push('');
  }

  // RPM drop
  if (adsenseIssues.length > 0) {
    todoCount++;
    lines.push(`### TODO-Z${String(todoCount).padStart(2, '0')}: AdSense RPM低下調査`);
    lines.push(`Status: pending`);
    lines.push(`根拠: RPM ${adsenseIssues[0].dropPct}低下`);
    lines.push(`候補施策: 広告スロット配置の確認、フィラーレートの確認、デバイス別RPM比較`);
    lines.push('');
  }

  // Overdue decisions
  if (overdueDecisions.length > 0) {
    todoCount++;
    lines.push(`### TODO-Z${String(todoCount).padStart(2, '0')}: 期限超過の判断記録を検証`);
    lines.push(`Status: pending`);
    lines.push(`根拠: ${overdueDecisions.length}件の判断記録が期限超過`);
    lines.push(`候補施策: 各判断記録を読み、PostHog/GA4データで効果を検証し結果を追記`);
    lines.push('');
  }

  if (todoCount === 0) {
    lines.push('現時点で生成すべきTODOはありません。');
    lines.push('');
  }

  lines.push('---');
  lines.push('');
  lines.push('## 4. Raw Data');
  lines.push('');

  if (gscPages.ok) {
    lines.push('### GSC Top Pages');
    lines.push('```');
    lines.push(gscPages.stdout.trim());
    lines.push('```');
    lines.push('');
  }

  if (ga4Landing.ok) {
    lines.push('### GA4 Landing Pages');
    lines.push('```');
    lines.push(ga4Landing.stdout.trim());
    lines.push('```');
    lines.push('');
  }

  if (ga4Events.ok) {
    lines.push('### GA4 Events');
    lines.push('```');
    lines.push(ga4Events.stdout.trim());
    lines.push('```');
    lines.push('');
  }

  if (adsense.ok) {
    lines.push('### AdSense');
    lines.push('```');
    lines.push(adsense.stdout.trim());
    lines.push('```');
    lines.push('');
  }

  if (bingCrawl.ok) {
    lines.push('### Bing Crawl');
    lines.push('```');
    lines.push(bingCrawl.stdout.trim());
    lines.push('```');
    lines.push('');
  }

  const filePath = path.join(outputDir, `${date}-self-improvement.md`);
  await writeFile(filePath, lines.join('\n'), 'utf8');
  console.log(`\nReport saved: ${filePath}`);
  console.log(`Generated ${todoCount} TODO recommendations.`);

  // Summary for stdout
  console.log('');
  console.log('=== Self-Improvement Summary ===');
  console.log(`GSC opportunities: ${gscOpportunities.length}`);
  console.log(`GA4 opportunities: ${ga4Opportunities.length}`);
  console.log(`Bing issues: ${bingIssues.length}`);
  console.log(`AdSense issues: ${adsenseIssues.length}`);
  if (posthog.ok) {
    const activeMatch = /Active:\s*(.+)/.exec(posthog.stdout);
    if (activeMatch) console.log(`PostHog active: ${activeMatch[1]}`);
  }
  console.log(`TODO recommendations: ${todoCount}`);
  console.log(`Report: ${filePath}`);
}

main().catch((error) => {
  console.error('Self-improvement loop failed:', error.message);
  process.exitCode = 1;
});
