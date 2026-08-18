import 'dotenv/config';
import { mkdir, writeFile } from 'fs/promises';
import path from 'path';

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

function parseAdsenseTable(text) {
  const lines = text.split('\n').filter(l => l.trim() && !l.startsWith('---') && !l.startsWith('AdSense') && !l.startsWith('Date'));
  const data = [];
  for (const line of lines) {
    const cols = line.split('|').map(c => c.trim()).filter(Boolean);
    if (cols.length >= 6 && /^\d{4}-\d{2}/.test(cols[0])) {
      data.push({ date: cols[0], earnings: cols[1], impressions: cols[2], rpm: cols[4] });
    }
  }
  return data;
}

function parseGa4Acquisition(text) {
  const lines = text.split('\n');
  let started = false;
  const data = [];
  for (const line of lines) {
    if (line.includes('sessionSourceMedium')) { started = true; continue; }
    if (!started || !line.includes('|')) continue;
    if (line.startsWith('---')) continue;
    const cols = line.split('|').map(c => c.trim()).filter(Boolean);
    if (cols.length >= 3) {
      data.push({ source: cols[0], sessions: cols[1] });
    }
  }
  return data;
}

function parseGscTable(text) {
  const lines = text.split('\n');
  let started = false;
  const data = [];
  for (const line of lines) {
    if (line.includes('query')) { started = true; continue; }
    if (!started || !line.includes('|')) continue;
    if (line.startsWith('---')) continue;
    const cols = line.split('|').map(c => c.trim()).filter(Boolean);
    if (cols.length >= 6) {
      data.push({ query: cols[0].slice(0, 60), clicks: cols[2], impressions: cols[3], ctr: cols[4], pos: cols[5] });
    }
  }
  return data;
}

function parseBingTable(text) {
  const lines = text.split('\n');
  let started = false;
  const data = [];
  for (const line of lines) {
    if (line.includes('Impressions | Clicks')) { started = true; continue; }
    if (!started || !line.includes('|')) continue;
    if (line.startsWith('---')) continue;
    const cols = line.split('|').map(c => c.trim()).filter(Boolean);
    if (cols.length >= 3 && /^\d{4}/.test(cols[0])) {
      data.push({ date: cols[0], impressions: cols[1], clicks: cols[2] });
    }
  }
  return data;
}

async function main() {
  const date = todayDate();
  const outputDir = path.join('daily', 'weekly');
  await mkdir(outputDir, { recursive: true });

  const report = [`# 週次統合レポート (${date})`, '', '## データソース', '', '| チャネル | ステータス |', '|---------|----------|'];

  // --- GA4 Overview ---
  console.log('Fetching GA4 overview...');
  const ga4Overview = await run('node scripts/ga4-report.mjs --preset overview --limit 10');
  report.push(`| GA4 Overview | ${ga4Overview.ok ? 'OK' : 'FAIL'} |`);

  // --- GA4 Acquisition ---
  console.log('Fetching GA4 acquisition...');
  const ga4Acq = await run('node scripts/ga4-report.mjs --preset acquisition --limit 15');
  report.push(`| GA4 Acquisition | ${ga4Acq.ok ? 'OK' : 'FAIL'} |`);

  // --- GA4 Countries ---
  console.log('Fetching GA4 countries...');
  const ga4Countries = await run('node scripts/ga4-report.mjs --preset countries --limit 10');
  report.push(`| GA4 Countries | ${ga4Countries.ok ? 'OK' : 'FAIL'} |`);

  // --- GSC ---
  console.log('Fetching GSC top queries...');
  const endDate = date;
  const startDate = new Date();
  startDate.setDate(startDate.getDate() - 28);
  const sd = `${startDate.getFullYear()}-${String(startDate.getMonth()+1).padStart(2,'0')}-${String(startDate.getDate()).padStart(2,'0')}`;
  const gsc = await run(`node scripts/gsc-query.mjs --preset top-queries --limit 30 --start-date ${sd} --end-date ${endDate}`);
  report.push(`| GSC Top Queries | ${gsc.ok ? 'OK' : 'FAIL'} |`);

  // --- AdSense ---
  console.log('Fetching AdSense daily...');
  const adsense = await run('node scripts/adsense-report.mjs');
  report.push(`| AdSense Daily | ${adsense.ok ? 'OK' : 'FAIL'} |`);

  // --- AdSense RPM by Platform ---
  console.log('Fetching AdSense by platform...');
  const adsensePlatform = await run('node scripts/adsense-report.mjs --dimensions PLATFORM_TYPE_CODE --metrics ESTIMATED_EARNINGS,IMPRESSIONS,PAGE_VIEWS_RPM,AD_REQUESTS_COVERAGE --limit 10');
  report.push(`| AdSense RPM by Platform | ${adsensePlatform.ok ? 'OK' : 'FAIL'} |`);

  // --- Bing ---
  console.log('Fetching Bing rank-traffic...');
  const bing = await run('node scripts/bing-webmaster-report.mjs --preset rank-traffic');
  report.push(`| Bing Rank & Traffic | ${bing.ok ? 'OK' : 'FAIL'} |`);

  // --- Bing Crawl Stats ---
  console.log('Fetching Bing crawl stats...');
  const bingCrawl = await run('node scripts/bing-webmaster-report.mjs --preset crawl-stats');
  report.push(`| Bing Crawl Stats | ${bingCrawl.ok ? 'OK' : 'FAIL'} |`);

  // --- PostHog A/B ---
  console.log('Fetching PostHog A/B status...');
  const posthog = await run('node scripts/posthog-check.mjs');
  report.push(`| PostHog A/B | ${posthog.ok ? 'OK' : 'FAIL'} |`);

  report.push('');

  // --- Summary Section ---
  report.push('## サマリー');
  report.push('');

  // Parse AdSense totals
  const adsenseRows = parseAdsenseTable(adsense.stdout);
  if (adsenseRows.length) {
    const total = adsenseRows.reduce((s, r) => ({ earnings: s.earnings + Number(r.earnings), impressions: s.impressions + Number(r.impressions) }), { earnings: 0, impressions: 0 });
    const avgRpm = adsenseRows.length ? (adsenseRows.reduce((s, r) => s + Number(r.rpm), 0) / adsenseRows.length).toFixed(0) : 0;
    const latestRpm = adsenseRows.length ? adsenseRows[adsenseRows.length - 1].rpm : 0;
    const firstRpm = adsenseRows.length ? adsenseRows[0].rpm : 0;
    report.push(`### AdSense`);
    report.push(`- **期間収益**: ¥${total.earnings}`);
    report.push(`- **総インプレッション**: ${total.impressions}`);
    report.push(`- **平均RPM**: ¥${avgRpm}`);
    report.push(`- **RPM推移**: ¥${firstRpm} → ¥${latestRpm} ${Number(latestRpm) < Number(firstRpm) ? '📉低下' : '📈上昇'}`);
    report.push('');
  }

  // GA4 traffic summary
  const acqRows = parseGa4Acquisition(ga4Acq.stdout);
  const bingSessions = acqRows.find(r => r.source.includes('bing'));
  const googleSessions = acqRows.find(r => r.source.includes('google'));
  if (bingSessions || googleSessions) {
    report.push(`### 検索エンジントラフィック`);
    if (bingSessions) report.push(`- **Bing**: ${bingSessions.sessions} sessions`);
    if (googleSessions) report.push(`- **Google**: ${googleSessions.sessions} sessions`);
    report.push('');
  }

  // Bing crawl summary
  const crawlLines = bingCrawl.stdout.split('\n').filter(l => /^\d{4}-\d{2}/.test(l.trim()));
  if (crawlLines.length) {
    const latest = crawlLines[crawlLines.length - 1].split('|').map(c => c.trim()).filter(Boolean);
    if (latest.length >= 3) {
      const errRate = (Number(latest[2]) / (Number(latest[1]) + Number(latest[2])) * 100).toFixed(1);
      report.push(`### Bing クロール`);
      report.push(`- **最終日**: ${latest[0]}`);
      report.push(`- **クロール数**: ${latest[1]}`);
      report.push(`- **エラー数**: ${latest[2]} (${errRate}%)`);
      report.push('');
    }
  }

  // RPM by Platform
  const platformLines = (adsensePlatform.stdout || '').split('\n').filter(l => /Desktop|HighEndMobile/.test(l));
  if (platformLines.length) {
    const desktop = platformLines.filter(l => l.includes('Desktop'));
    const mobile = platformLines.filter(l => l.includes('HighEndMobile'));
    if (desktop.length) {
      const avgDesktopRpm = (desktop.reduce((s, l) => {
        const cols = l.split('|').map(c => c.trim()).filter(Boolean);
        return s + (Number(cols[3]) || 0);
      }, 0) / desktop.length).toFixed(0);
      const avgMobileRpm = mobile.length ? (mobile.reduce((s, l) => {
        const cols = l.split('|').map(c => c.trim()).filter(Boolean);
        return s + (Number(cols[3]) || 0);
      }, 0) / mobile.length).toFixed(0) : 0;
      report.push(`### RPM（デバイス別28日平均）`);
      report.push(`- **Desktop**: ¥${avgDesktopRpm}`);
      report.push(`- **Mobile**: ¥${avgMobileRpm}`);
      report.push('');
    }
  }

  // PostHog A/B summary
  if (posthog.ok && posthog.stdout) {
    const phSummary = posthog.stdout;
    const activeMatch = /Active:\s*(.+)/.exec(phSummary);
    const nullMatch = /Null rate:\s*([\d.]+)%/.exec(phSummary);
    const recMatch = /Rec:\s*(\w+)\s*[—–-]\s*(.+)/s.exec(phSummary);
    report.push('### PostHog A/B');
    if (activeMatch) report.push(`- **Active flag**: ${activeMatch[1]}`);
    if (nullMatch) {
      const nRate = parseFloat(nullMatch[1]);
      const icon = nRate < 20 ? '🟢' : nRate < 30 ? '🟡' : '🔴';
      report.push(`- **Null rate**: ${icon} ${nullMatch[1]}%`);
    }
    if (recMatch) report.push(`- **Recommendation**: \`${recMatch[1]}\` — ${recMatch[2]}`);
    report.push('');
    report.push(`Full report: \`daily/posthog/${date}.md\``);
    report.push('');
  }

  // --- Raw Data Sections ---
  report.push('---');
  report.push('');
  report.push('## GA4 Overview');
  report.push('```');
  report.push(ga4Overview.stdout.trim());
  report.push('```');
  report.push('');

  report.push('## GA4 Acquisition');
  report.push('```');
  report.push(ga4Acq.stdout.trim());
  report.push('```');
  report.push('');

  report.push('## GSC Top Queries');
  report.push('```');
  report.push(gsc.stdout.trim());
  report.push('```');
  report.push('');

  report.push('## AdSense Daily');
  report.push('```');
  report.push(adsense.stdout.trim());
  report.push('```');
  report.push('');

  report.push('## AdSense RPM by Platform');
  report.push('```');
  report.push(adsensePlatform.stdout.trim());
  report.push('```');
  report.push('');

  report.push('## Bing Rank & Traffic');
  report.push('```');
  report.push(bing.stdout.trim());
  report.push('```');
  report.push('');

  report.push('## Bing Crawl Stats');
  report.push('```');
  report.push(bingCrawl.stdout.trim());
  report.push('```');
  report.push('');

  report.push('---');
  report.push('');
  report.push('**Self-Improvement**: `npm run improve` で GA4/GSC/AdSense/Bing/PostHog データを統合し、TODO を自動生成できます。');
  report.push('');

  const filePath = path.join(outputDir, `${date}-weekly-report.md`);
  await writeFile(filePath, report.join('\n'), 'utf8');
  console.log(`Report saved: ${filePath}`);
}

main().catch((error) => {
  console.error('Weekly report failed:', error.message);
  process.exitCode = 1;
});
