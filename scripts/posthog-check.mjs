import 'dotenv/config';
import { mkdir, writeFile, readdir } from 'fs/promises';
import path from 'path';
import { existsSync, readFileSync } from 'fs';

const PH_HOST = process.env.POSTHOG_HOST || 'https://us.posthog.com';
const PH_KEY  = process.env.POSTHOG_PERSONAL_API_KEY;
const PH_PROJ = process.env.POSTHOG_PROJECT_ID;

const FLAGS = [
  { key:'zdk_code_fold',     label:'code_fold',     ui:'Code Block Fold',            startedAt:'2026-06-22', deadline:'2026-06-29' },
  { key:'zdk_header_image',  label:'header_image',  ui:'Header Image Size',          startedAt:'2026-06-23', deadline:'2026-07-07' },
  { key:'zdk_author_pos',    label:'author_pos',    ui:'Author Position',            startedAt:'2026-06-23', deadline:'2026-07-07' },
  { key:'zdk_header_density',label:'header_density',ui:'Header Density',             startedAt:null,         deadline:null },
];

const OUTCOMES = [
  { event:'zdk_read_depth',   label:'Read Depth (25/50/75/90%)' },
  { event:'zdk_engaged_60s',  label:'Engaged 60s' },
  { event:'zdk_toc_click',    label:'TOC Click' },
  { event:'zdk_related_click',label:'Related Click' },
];

const DECISION = {
  minDays:         5,
  minImpressionsPerVariant: 200,
  minOutcomesPerVariant:    100,
  meaningfulLift:           0.15,
  maxNullRate:              0.30,
};

const META = {
  maxExperimentDays:         14,
  stalePipelineDays:         14,
  checkComplianceWindowDays: 10,
  lowOutcomeEventMin:        5,
};

function todayDate() {
  const d = new Date();
  return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

function daysAgoDate(days) {
  const d = new Date();
  d.setDate(d.getDate() - days);
  return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

function dateFromSlug(slug) {
  const m = /^(\d{4}-\d{2}-\d{2})/.exec(slug);
  return m ? m[1] : null;
}

function daysBetween(a, b) {
  const da = new Date(a);
  const db = new Date(b);
  return Math.round((db - da) / (1000 * 60 * 60 * 24));
}

async function phQuery(queryStr) {
  if (!PH_KEY || !PH_PROJ) throw new Error('POSTHOG_PERSONAL_API_KEY or POSTHOG_PROJECT_ID missing in .env');
  const url = `${PH_HOST}/api/projects/${PH_PROJ}/query/`;
  const body = JSON.stringify({ query: { kind:'HogQLQuery', query: queryStr } });
  const res = await fetch(url, {
    method:'POST',
    headers:{ Authorization:`Bearer ${PH_KEY}`, 'Content-Type':'application/json' },
    body,
  });
  if (!res.ok) {
    const txt = await res.text();
    throw new Error(`PH query failed (${res.status}): ${txt.slice(0,300)}`);
  }
  const json = await res.json();
  return (json.results || []).map(row => ({ _cols: row }));
}

async function phGet(path) {
  if (!PH_KEY || !PH_PROJ) throw new Error('POSTHOG_PERSONAL_API_KEY or POSTHOG_PROJECT_ID missing in .env');
  const url = `${PH_HOST}/api/projects/${PH_PROJ}/${path}`;
  const res = await fetch(url, { headers:{ Authorization:`Bearer ${PH_KEY}` } });
  if (!res.ok) throw new Error(`PH GET ${path} failed (${res.status})`);
  return res.json();
}

function formatPct(n) {
  if (n === null || n === undefined) return '-';
  return (n * 100).toFixed(1) + '%';
}

function formatLift(controlCount, treatmentCount, controlTotal, treatmentTotal) {
  if (!controlTotal || !treatmentTotal) return '-';
  const cRate = controlCount / controlTotal;
  const tRate = treatmentCount / treatmentTotal;
  if (cRate === 0 && tRate === 0) return '-';
  if (cRate === 0) return tRate > 0 ? '+∞' : '-∞';
  const lift = (tRate - cRate) / cRate;
  const sign = lift >= 0 ? '+' : '';
  return `${sign}${(lift*100).toFixed(1)}%`;
}

async function fetchFlagStates() {
  const data = await phGet('feature_flags/?limit=50');
  const flags = (data.results || []).filter(f => f.key.startsWith('zdk_'));
  return flags.map(f => ({ key:f.key, name:f.name, active:f.active, id:f.id }));
}

async function fetchImpressions7d() {
  const q = `
    SELECT
      JSONExtractString(properties, 'experiment') as experiment,
      JSONExtractString(properties, 'variant') as variant,
      count() as cnt
    FROM events
    WHERE event = 'zdk_experiment_impression'
      AND timestamp > now() - interval 7 day
    GROUP BY experiment, variant
    ORDER BY experiment, variant
  `;
  return await phQuery(q);
}

async function fetchFlagErrors7d() {
  const q = `
    SELECT
      JSONExtractString(properties, 'reason') as reason,
      JSONExtractString(properties, 'resolverUsed') as resolver,
      count() as cnt
    FROM events
    WHERE event = 'zdk_flag_resolution_error'
      AND timestamp > now() - interval 7 day
    GROUP BY reason, resolver
    ORDER BY cnt DESC
  `;
  return await phQuery(q);
}

async function fetchOutcomes7d() {
  const q = `
    SELECT
      event,
      JSONExtractString(properties, 'variants') as variants,
      count() as cnt
    FROM events
    WHERE event IN ('zdk_read_depth','zdk_engaged_60s','zdk_toc_click','zdk_related_click')
      AND timestamp > now() - interval 7 day
    GROUP BY event, variants
    ORDER BY event, variants
  `;
  return await phQuery(q);
}

function parseVariantKey(variantsStr) {
  if (!variantsStr) return null;
  try {
    const obj = JSON.parse(variantsStr);
    for (const k of ['code_fold','header_image','author_pos','font_size','line_height','toc_sticky','related_posts','ad_position','header_density']) {
      if (typeof obj[k] === 'string') return obj[k];
    }
    return null;
  } catch { return null; }
}

function analyze(flags, impressions, outcomes) {
  const activeFlags = flags.filter(f => f.active);
  const inactiveFlags = flags.filter(f => !f.active);

  const impByExpVar = {};
  for (const row of impressions) {
    const exp = row._cols[0];
    const vari = row._cols[1];
    const cnt = row._cols[2];
    if (!impByExpVar[exp]) impByExpVar[exp] = { control:0, treatment:0, nulls:0 };
    if (vari === null || vari === 'null' || vari === '') {
      impByExpVar[exp].nulls += cnt;
    } else if (vari === 'control') {
      impByExpVar[exp].control += cnt;
    } else {
      impByExpVar[exp].treatment += cnt;
      impByExpVar[exp]._treatmentLabel = vari;
    }
  }

  const outcomeByEventVar = {};
  for (const row of outcomes) {
    const event = row._cols[0];
    const variantsStr = row._cols[1];
    const cnt = row._cols[2];
    const vk = parseVariantKey(variantsStr);
    if (!outcomeByEventVar[event]) outcomeByEventVar[event] = { control:0, treatment:0 };
    if (vk === 'control') outcomeByEventVar[event].control += cnt;
    else if (vk) outcomeByEventVar[event].treatment += cnt;
  }

  return { activeFlags, inactiveFlags, impByExpVar, outcomeByEventVar };
}

function decisionForActive(flagKey, imp, outcomes, label, startedAt, deadline) {
  const expKey = flagKey;
  const i = imp[expKey] || { control:0, treatment:0, nulls:0 };

  const totalResolved = i.control + i.treatment;
  const total = totalResolved + i.nulls;
  const nullRate = total > 0 ? i.nulls / total : 1;

  const analysis = {
    experiment: expKey,
    label,
    startedAt: startedAt || null,
    deadline: deadline || null,
    impressions: { control:i.control, treatment:i.treatment, nulls:i.nulls },
    nullRate,
    nullOk: nullRate <= DECISION.maxNullRate,
    enoughDays: null,
    enoughImpressions: i.control >= DECISION.minImpressionsPerVariant && i.treatment >= DECISION.minImpressionsPerVariant,
  };

  const outcomesForFlag = { control:{}, treatment:{} };
  let totalOutcomeC = 0;
  let totalOutcomeT = 0;
  for (const ev of OUTCOMES) {
    const d = outcomes[ev.event] || { control:0, treatment:0 };
    outcomesForFlag.control[ev.event] = d.control;
    outcomesForFlag.treatment[ev.event] = d.treatment;
    totalOutcomeC += d.control;
    totalOutcomeT += d.treatment;
  }

  analysis.enoughOutcomes = totalOutcomeC >= DECISION.minOutcomesPerVariant && totalOutcomeT >= DECISION.minOutcomesPerVariant;

  analysis.outcomes = [];
  for (const ev of OUTCOMES) {
    const c = outcomesForFlag.control[ev.event];
    const t = outcomesForFlag.treatment[ev.event];
    const lift = formatLift(c, t, i.control, i.treatment);
    analysis.outcomes.push({ event:ev.event, label:ev.label, control:c, treatment:t, lift });
  }

  analysis.canDecide = analysis.enoughImpressions && analysis.enoughOutcomes && analysis.nullOk;
  analysis.recommendation = null;

  if (!analysis.nullOk) {
    analysis.recommendation = { action:'fix_null_rate', text:`Null rate ${formatPct(nullRate)} exceeds threshold ${formatPct(DECISION.maxNullRate)}. Fix instrumentation before deciding.` };
  } else if (!analysis.enoughImpressions) {
    analysis.recommendation = { action:'wait_impressions', text:`Need ${DECISION.minImpressionsPerVariant} impressions per variant. Currently control=${i.control} treatment=${i.treatment}. Check again in a few days.` };
  } else if (!analysis.enoughOutcomes) {
    analysis.recommendation = { action:'wait_outcomes', text:`Need ${DECISION.minOutcomesPerVariant} outcome events per variant. Currently have ${totalOutcomeC}/${totalOutcomeT} (c/t). Check again in a few days.` };
  } else if (analysis.canDecide) {
    const winner = determineWinner(analysis.outcomes, i);
    analysis.recommendation = winner;
  }

  return analysis;
}

function determineWinner(outcomes, imp) {
  let winsControl = 0;
  let winsTreatment = 0;
  const details = [];

  for (const o of outcomes) {
    const cRate = imp.control > 0 ? o.control / imp.control : 0;
    const tRate = imp.treatment > 0 ? o.treatment / imp.treatment : 0;
    const diff = tRate - cRate;
    const lift = cRate > 0 ? diff / cRate : (tRate > 0 ? Infinity : 0);

    const isPrimary = o.event === 'zdk_read_depth' || o.event === 'zdk_engaged_60s';

    if (lift > DECISION.meaningfulLift) {
      winsTreatment++;
      details.push(`  + ${o.label}: treatment leads by ${formatPct(lift)}${isPrimary?' *primary*':''}`);
    } else if (lift < -DECISION.meaningfulLift) {
      winsControl++;
      details.push(`  + ${o.label}: control leads by ${formatPct(Math.abs(lift))}${isPrimary?' *primary*':''}`);
    } else {
      details.push(`  ~ ${o.label}: no clear difference (lift ${formatPct(lift)})`);
    }
  }

  if (winsTreatment > winsControl) {
    return { action:'declare_treatment', text:`Treatment wins (${winsTreatment} vs ${winsControl} outcomes). Apply the change to single.php and close the flag.`, detail:details.join('\n') };
  } else if (winsControl > winsTreatment) {
    return { action:'declare_control', text:`Control wins (${winsControl} vs ${winsTreatment} outcomes). Close the flag, keep current UI.`, detail:details.join('\n') };
  } else {
    return { action:'inconclusive', text:`No clear winner (${winsControl}-${winsTreatment}). Consider extending or closing as no-difference.`, detail:details.join('\n') };
  }
}

function readPastReports(reportDir) {
  if (!existsSync(reportDir)) return [];
  try {
    const entries = readdirSync(reportDir);
    const reports = [];
    for (const entry of entries) {
      if (!entry.endsWith('.md')) continue;
      const d = dateFromSlug(entry);
      if (!d) continue;
      const fullPath = path.join(reportDir, entry);
      const raw = readFileSync(fullPath, 'utf8');

      const nullMatch = /Null variant rate.*?\|\s*(OK|FAIL|\*\*FAIL\*\*)\s*\|?\s*([\d.]+)%/.exec(raw);
      const impMatch = /control\s*\|\s*(\d+).*?\n\s*\|\s*\w+\s*\|\s*(\d+)/s.exec(raw);
      const decMatch = /\*\*Action\*\*:\s*`(\w+)`/.exec(raw);

      reports.push({
        date: d,
        nullRate: nullMatch ? parseFloat(nullMatch[2]) / 100 : null,
        impressionsC: impMatch ? parseInt(impMatch[1]) : 0,
        impressionsT: impMatch ? parseInt(impMatch[2]) : 0,
        decisionAction: decMatch ? decMatch[1] : null,
      });
    }
    reports.sort((a, b) => a.date.localeCompare(b.date));
    return reports;
  } catch {
    return [];
  }
}

function buildMetaRecommendations(analysis, flags, today, flagErrors) {
  const metas = [];

  const reportDir = path.join(process.cwd(), 'daily', 'posthog');
  const pastReports = readPastReports(reportDir);

  if (pastReports.length >= 2) {
    const recent = pastReports.filter(r => daysBetween(r.date, today) <= 14);
    const nullRates = recent.map(r => r.nullRate).filter(n => n !== null);

    if (nullRates.length >= 2) {
      const allHigh = nullRates.every(n => n > DECISION.maxNullRate);
      const improving = nullRates.length >= 2 && nullRates[nullRates.length - 1] < nullRates[0];

      if (allHigh && !improving) {
        metas.push({
          id: 'null_persistent',
          severity: 'high',
          text: 'Null rate has been persistently high across multiple reports without improvement. Consider adding a `zdk_experiment_flag_missing` diagnostic event to `posthog-experiments.js` to log when flags don\'t resolve. Root cause candidates: (1) `onFeatureFlags` callback not firing for some sessions, (2) CDN serving stale JS, (3) race condition between PostHog init and flag evaluation.',
          action: 'Add diagnostic event `zdk_flag_resolution_error` in posthog-experiments.js that fires when fallback timer exhausts or flags remain null after init. Deploy and monitor for 2 days.'
        });
      }
      if (allHigh && improving) {
        metas.push({
          id: 'null_improving',
          severity: 'info',
          text: `Null rate is high but trending down (${formatPct(nullRates[0])} → ${formatPct(nullRates[nullRates.length-1])}). The fix deployed on 2026-06-04 may be gradually taking effect as caches expire. Continue monitoring.`,
          action: 'No action needed. Check again next cycle.'
        });
      }
    }
  }

  if (analysis && analysis.startedAt && analysis.experiment) {
    const experimentDays = daysBetween(analysis.startedAt, today);
    if (experimentDays > META.maxExperimentDays && !analysis.canDecide) {
      metas.push({
        id: 'experiment_overdue',
        severity: 'medium',
        text: `${analysis.experiment} has been running for ${experimentDays} days (started ${analysis.startedAt}). Consider closing as inconclusive or extending with a clear deadline.`,
        action: 'If impressions are too low: consider running for max 21 days total. If outcomes are too low: the experiment may not be meaningful at current traffic levels.'
      });
    }
  }

  if (flagErrors && flagErrors.length > 0) {
    const totalErrors = flagErrors.reduce((s, r) => s + r._cols[2], 0);
    const reasons = flagErrors.map(r => `${r._cols[0]} (resolver:${r._cols[1] || '?'}) = ${r._cols[2]}`).join(', ');
    metas.push({
      id: 'flag_resolution_errors',
      severity: 'medium',
      text: `${totalErrors} zdk_flag_resolution_error events in past 7 days. Breakdown: ${reasons}. This is the diagnostic event added on 2026-06-05 to track why flags fail to resolve.`,
      action: 'If timeout errors dominate: PostHog SDK may not finish flag evaluation before the 10s safety net. If fallback_exhausted dominates: `onFeatureFlags` callback is not available and polling exhausts before flags resolve. If both are low but null rate is high: the problem is elsewhere (e.g., flag capture happening before flags are evaluated).'
    });
  }

  if (pastReports.length >= 1) {
    const todayReport = pastReports.find(r => r.date === today);
    const lastReport = pastReports[pastReports.length - 1];
    const daysSinceLast = lastReport ? daysBetween(lastReport.date, today) : 999;

    if (daysSinceLast > 7 && lastReport?.date !== today) {
      metas.push({
        id: 'check_gap',
        severity: 'medium',
        text: `Last check was ${daysSinceLast} days ago (${lastReport.date}). Recommended cadence: Mon + Thu (every 3–4 days). Long gaps mean experiments stall and pipeline backs up.`,
        action: 'Schedule the next check for 3 days from now. Consider adding a calendar reminder or cron-like trigger for `npm run posthog:check`.'
      });
    }
  }

  const totalChecks = pastReports.length;
  if (totalChecks >= 1 && totalChecks <= 2) {
    metas.push({
      id: 'early_stage',
      severity: 'info',
      text: 'This A/B operations system is in its early stage. The decision thresholds and meta-recommendations will improve as more data accumulates. Consider reviewing the thresholds after 3 completed experiment cycles.',
      action: 'After the first experiment concludes, review `DECISION` and `META` blocks in `scripts/posthog-check.mjs` and adjust based on observed traffic patterns.'
    });
  }

  if (analysis && analysis.outcomes) {
    const deadOutcomes = analysis.outcomes.filter(o => o.control === 0 && o.treatment === 0);
    const lowOutcomes = analysis.outcomes.filter(o => (o.control + o.treatment) > 0 && (o.control + o.treatment) <= META.lowOutcomeEventMin);

    if (deadOutcomes.length > 0) {
      const names = deadOutcomes.map(o => o.label).join(', ');
      metas.push({
        id: 'dead_outcome',
        severity: 'medium',
        text: `Outcome event(s) with zero data: ${names}. These events may not be wired correctly or the triggering UI elements may not exist on enough pages.`,
        action: `Check if ${names} triggers are correctly attached in posthog-experiments.js. Verify the CSS selectors match actual page markup. If the UI element is rarely present, consider removing the event from OUTCOMES or adding a diagnostic check.`
      });
    }

    if (lowOutcomes.length > 0) {
      const names = lowOutcomes.map(o => `${o.label} (${o.control+o.treatment} total)`).join(', ');
      metas.push({
        id: 'low_outcome',
        severity: 'info',
        text: `Low-volume outcome events: ${names}. These won't reach statistical significance quickly. They are still useful as secondary signals but shouldn't drive decisions alone.`,
        action: 'No change needed. These events will accumulate over longer experiment durations.'
      });
    }

    const singleDriver = analysis.outcomes.filter(o => {
      const cRate = analysis.impressions.control > 0 ? o.control / analysis.impressions.control : 0;
      const tRate = analysis.impressions.treatment > 0 ? o.treatment / analysis.impressions.treatment : 0;
      const lift = cRate > 0 ? Math.abs((tRate - cRate) / cRate) : 0;
      return lift > DECISION.meaningfulLift;
    });

    if (singleDriver.length === 1 && analysis.enoughImpressions) {
      metas.push({
        id: 'single_signal',
        severity: 'info',
        text: `Only one outcome event (${singleDriver[0].label}) shows a clear signal. The decision would rest on a single metric. If this is a secondary event (e.g., TOC click), consider whether it alone is sufficient justification for a site-wide change.`,
        action: 'If the lone signal is a primary outcome (read_depth or engaged_60s), trust it. If secondary (TOC/related click), wait for primary outcome confirmation or run a follow-up experiment focused on that metric.'
      });
    }
  }

  const totalInactive = flags.filter(f => !f.active).length;
  if (totalInactive >= 4 && analysis && analysis.canDecide) {
    metas.push({
      id: 'pipeline_ready',
      severity: 'info',
      text: `${totalInactive} inactive flags ready in the pipeline. As soon as the active experiment concludes, the next pipeline candidate should be activated immediately to maintain momentum.`,
      action: 'When declaring a winner, activate the next pipeline experiment via PostHog API in the same session. Update drat/posthog-experiments.md with new start date and decision deadline.'
    });
  }

  return metas;
}

function buildReport(analysis, flags, today, metaRecs) {
  const lines = [];
  const h2 = (s) => { lines.push('', `## ${s}`, ''); };
  const h3 = (s) => { lines.push('', `### ${s}`, ''); };
  const p  = (s) => { lines.push(s); };

  p(`# PostHog A/B Check — ${today}`);
  p('');
  p(`> Generated: ${today}`);
  p(`> Source: \`npm run posthog:check\``);

  h2('Flag States');
  p('| Flag | State |');
  p('|------|-------|');
  for (const f of flags) {
    p(`| \`${f.key}\` | ${f.active ? '**active**' : 'inactive'} |`);
  }

  if (!analysis) {
    p('No active experiment found.');
    p('');
    p('Next: activate `zdk_toc_sticky` via PostHog API and start the next experiment cycle.');
    lines.push('');
    return lines.join('\n');
  }

  const a = analysis;

  h2('Active Experiment: ' + (a.experiment || 'unknown'));

  h3('Health Check');
  const health = [];
  health.push(`| Check | Status | Value | Threshold |`);
  health.push(`|-------|--------|-------|-----------|`);
  health.push(`| Null variant rate | ${a.nullOk ? 'OK' : '**FAIL**'} | ${formatPct(a.nullRate)} | < ${formatPct(DECISION.maxNullRate)} |`);
  health.push(`| Impression count (control) | ${a.impressions.control >= DECISION.minImpressionsPerVariant ? 'OK' : '**LOW**'} | ${a.impressions.control} | >= ${DECISION.minImpressionsPerVariant} |`);
  health.push(`| Impression count (treatment) | ${a.impressions.treatment >= DECISION.minImpressionsPerVariant ? 'OK' : '**LOW**'} | ${a.impressions.treatment} | >= ${DECISION.minImpressionsPerVariant} |`);
  const totOutC = a.outcomes.reduce((s,o)=>s+o.control,0);
  const totOutT = a.outcomes.reduce((s,o)=>s+o.treatment,0);
  health.push(`| Outcome events (control) | ${totOutC >= DECISION.minOutcomesPerVariant ? 'OK' : '**LOW**'} | ${totOutC} | >= ${DECISION.minOutcomesPerVariant} |`);
  health.push(`| Outcome events (treatment) | ${totOutT >= DECISION.minOutcomesPerVariant ? 'OK' : '**LOW**'} | ${totOutT} | >= ${DECISION.minOutcomesPerVariant} |`);
  for (const hline of health) p(hline);

  h3('Impressions');
  p(`| Variant | Count |`);
  p(`|---------|-------|`);
  p(`| control | ${a.impressions.control} |`);
  p(`| ${a.impressions._treatmentLabel || 'treatment'} | ${a.impressions.treatment} |`);
  p(`| null | ${a.impressions.nulls} |`);

  h3('Outcome Events');
  p(`| Event | control | treatment | lift |`);
  p(`|-------|---------|-----------|------|`);
  for (const o of a.outcomes) {
    p(`| ${o.label} | ${o.control} | ${o.treatment} | ${o.lift} |`);
  }

  h3('Recommendation');
  if (a.recommendation) {
    const rec = a.recommendation;
    p(`**Action**: \`${rec.action}\``);
    p(`**Detail**: ${rec.text}`);
    if (rec.detail) {
      p('');
      p('```');
      p(rec.detail);
      p('```');
    }
  } else {
    p('No recommendation available.');
  }

  h3('Next Check');
  if (a.canDecide) {
    p('Decision ready. Review this report and apply the winner or close the experiment.');
    p(`Suggested deadline: today (${today})`);
  } else {
    const nextCheck = daysAgoDate(-3);
    p(`Data not sufficient yet. Check again after ${nextCheck}.`);
    p(`\`npm run posthog:check\``);
  }

  h3('Pipeline — Experiment Queue');
  p('Current active flags: `' + flags.filter(f => f.active).map(f => f.key).join('`, `') + '`');
  p('Pipeline: zdk_author_pos (next if slot opens)');

  h2('Meta Recommendations (Operations)');

  if (metaRecs.length === 0) {
    p('No meta recommendations. Operations look healthy.');
  } else {
    for (const m of metaRecs) {
      const badge = m.severity === 'high' ? '**[HIGH]**' : m.severity === 'medium' ? '**[MED]**' : '_[INFO]_';
      p(`### ${badge} ${m.id}`);
      p(`**Issue**: ${m.text}`);
      p('');
      p(`**Suggested action**: ${m.action}`);
      p('');
    }
  }

  h2('Meta Health Summary');
  const sevCounts = { high:0, medium:0, info:0 };
  for (const m of metaRecs) sevCounts[m.severity]++;
  p('| Severity | Count |');
  p('|----------|-------|');
  p(`| High | ${sevCounts.high} |`);
  p(`| Medium | ${sevCounts.medium} |`);
  p(`| Info | ${sevCounts.info} |`);

  return lines.join('\n');
}

function buildStatus(analysis, flags, metaRecs, today) {
  const lines = [];
  const p = (s) => { lines.push(s); };

  // Read previous status for trend comparison
  const statusFilePath = path.join(process.cwd(), 'drat', 'posthog-status.md');
  let prevNullRate = null;
  try {
    const prevRaw = readFileSync(statusFilePath, 'utf8');
    const prevMatch = /Null rate\s*\|\s*\w+\s*\|\s*([\d.]+)%/.exec(prevRaw);
    if (prevMatch) prevNullRate = parseFloat(prevMatch[1]) / 100;
  } catch {}

  function trend(current, previous, invert) {
    if (previous === null || previous === undefined) return '';
    const diff = current - previous;
    if (Math.abs(diff) < 0.02) return ' →';
    const up = invert ? '↑' : '↓';
    const down = invert ? '↓' : '↑';
    return diff > 0.02 ? ` ${invert ? up : down}` : ` ${invert ? down : up}`;
  }

  p('# PostHog A/B Status');
  p(`> Last check: ${today}  |  Run \`npm run posthog:check\` to refresh`);
  p('> **Before acting**: Check \`daily-agent/YYYYMMDD.md\` for active claims');
  p('');

  const activeFlags = flags.filter(f => f.active);
  if (!analysis || activeFlags.length === 0) {
    p('## No active experiment');
    p('');
    p('**Next**: Activate the next pipeline experiment via PostHog API and start the next cycle.');
    p('');
    p('### Pipeline');
    const inactive = flags.filter(f => !f.active).map(f => f.key);
    p(`Inactive flags waiting: ${inactive.join(', ') || 'none'}`);
    p('Typical order: code_fold → header_image → author_pos');
    return lines.join('\n');
  }

  p('## Active Experiment');
  p('');
  const a = analysis;
  p(`| Field | Value |`);
  p(`|-------|-------|`);
  p(`| Flag | \`${a.experiment}\` |`);
  p(`| Days running | ${daysBetween(a.startedAt || '2026-06-03', today)} |`);
  p(`| Decision deadline | ${a.deadline || '2026-06-10'} |`);

  p('');
  p('## Health');
  p('');
  const nullIcon = a.nullOk ? 'OK' : 'FAIL';
  const impIcon = a.enoughImpressions ? 'OK' : 'LOW';
  const outIcon = a.enoughOutcomes ? 'OK' : 'LOW';
  p(`| Metric | Status | Value |`);
  p(`|--------|--------|-------|`);
  const nullTrend = trend(a.nullRate, prevNullRate, true); // true=invert (lower null is better)
  p(`| Null rate | ${nullIcon} | ${formatPct(a.nullRate)} (max ${formatPct(DECISION.maxNullRate)})${nullTrend} |`);
  p(`| Impressions (ctrl/treat) | ${impIcon} | ${a.impressions.control} / ${a.impressions.treatment} |`);
  const totC = a.outcomes.reduce((s,o)=>s+o.control,0);
  const totT = a.outcomes.reduce((s,o)=>s+o.treatment,0);
  p(`| Outcome events (ctrl/treat) | ${outIcon} | ${totC} / ${totT} |`);

  p('');
  p('## Outcomes');
  p('');
  p(`| Event | Ctrl | Treat | Lift |`);
  p(`|-------|------|-------|------|`);
  for (const o of a.outcomes) {
    p(`| ${o.label} | ${o.control} | ${o.treatment} | ${o.lift} |`);
  }

  p('');
  p('## Next Action');
  p('');
  if (a.recommendation) {
    const icon = a.canDecide ? 'DECIDE' : 'WAIT';
    p(`**[${icon}]** ${a.recommendation.action}: ${a.recommendation.text}`);
  } else {
    p('No action determined.');
  }

  // Closeout checklist
  if (a.recommendation) {
    const rec = a.recommendation;
    p('');
    p('### Closeout Steps');
    p('');
    if (rec.action === 'fix_null_rate') {
      p('1. Run null rate troubleshooting: `docs/operations/posthog-ab-operations.md`');
      p('2. Check `zdk_flag_resolution_error` events in PostHog');
      p('3. Verify JS deployment: cache-bust the URL or clear CDN');
      p('4. Re-run `npm run posthog:check` after fix');
    } else if (rec.action === 'declare_treatment') {
      p('1. Merge treatment variant into production code');
      p('2. Disable `' + a.experiment + '` flag via PostHog API');
      p('3. Update `drat/posthog-experiments.md` — move to Completed');
      p('4. Activate next pipeline experiment via PostHog API');
      p('5. Update `drat/posthog-status.md` via `npm run posthog:check`');
    } else if (rec.action === 'declare_control') {
      p('1. Disable `' + a.experiment + '` flag via PostHog API (keep current UI)');
      p('2. Update `drat/posthog-experiments.md` — move to Rejected');
      p('3. Activate next pipeline experiment via PostHog API');
      p('4. Update `drat/posthog-status.md` via `npm run posthog:check`');
    } else if (rec.action === 'inconclusive') {
      p('1. Decide: extend (wait more data) or close (no meaningful difference)');
      p('2. If close: disable flag, move to Rejected, activate next pipeline');
      p('3. If extend: set new deadline (+7 days), update status');
    } else if (rec.action === 'wait_impressions' || rec.action === 'wait_outcomes') {
      p('1. Wait 3-4 days for more data to accumulate');
      p('2. Re-run `npm run posthog:check`');
      p('3. If still insufficient after 14 total days, consider closing as inconclusive');
    }
  }

  p('');
  p('## Pipeline');
  p('');
  p('| Status | Experiment | Flag |');
  p('|--------|------------|------|');
  const allFlags = flags.map(f => ({ key: f.key, active: f.active }));
  const defs = { zdk_code_fold: 'Code block fold', zdk_header_image: 'Header image', zdk_author_pos: 'Author position', zdk_header_density: 'Header density' };
  for (const f of allFlags) {
    const name = defs[f.key] || f.key;
    const badge = f.active ? '**running**' : 'pending';
    p(`| ${badge} | ${name} | \`${f.key}\` |`);
  }

  const highMeta = metaRecs.filter(m => m.severity === 'high');
  const medMeta = metaRecs.filter(m => m.severity === 'medium');
  if (highMeta.length + medMeta.length > 0) {
    p('');
    p('## Meta Alerts');
    p('');
    for (const m of [...highMeta, ...medMeta]) {
      const badge = m.severity === 'high' ? 'HIGH' : 'MED';
      p(`- **[${badge}]** ${m.id}: ${m.text.split('.')[0]}.`);
    }
  }

  p('');
  p('---');
  p(`*Full: daily/posthog/${today}.md | Pipeline: drat/posthog-experiments.md | Policy: docs/operations/posthog-ab-operations.md*`);

  return lines.join('\n');
}

async function main() {
  if (!PH_KEY || !PH_PROJ) {
    console.error('Missing POSTHOG_PERSONAL_API_KEY or POSTHOG_PROJECT_ID in .env');
    console.error('Add them to .env to use this script.');
    process.exit(1);
  }

  console.log('PostHog A/B Check ...');

  const [flagStates, impressions, outcomes, flagErrors] = await Promise.all([
    fetchFlagStates(),
    fetchImpressions7d(),
    fetchOutcomes7d(),
    fetchFlagErrors7d(),
  ]);

  const { activeFlags, impByExpVar, outcomeByEventVar } = analyze(flagStates, impressions, outcomes);

  const today = todayDate();

  let analysis = null;
  if (activeFlags.length > 0) {
    const activeFlag = activeFlags[0];
    const def = FLAGS.find(f => f.key === activeFlag.key) || { ui:activeFlag.key, startedAt:null, deadline:null };
    analysis = decisionForActive(activeFlag.key, impByExpVar, outcomeByEventVar, def.ui, def.startedAt, def.deadline);
  } else {
    console.log('No active zdk_* experiment flags found.');
  }

  const metaRecs = buildMetaRecommendations(analysis, flagStates, today, flagErrors);

  const report = buildReport(analysis, flagStates, today, metaRecs);

  const outDir = path.join(process.cwd(), 'daily', 'posthog');
  await mkdir(outDir, { recursive: true });
  const outFile = path.join(outDir, `${today}.md`);
  await writeFile(outFile, report, 'utf8');

  // Sync concise status file
  const statusFile = path.join(process.cwd(), 'drat', 'posthog-status.md');
  const statusContent = buildStatus(analysis, flagStates, metaRecs, today);
  await writeFile(statusFile, statusContent, 'utf8');

  console.log(`Report saved: ${outFile}`);
  console.log(`Status saved: ${statusFile}`);

  const consoleSummary = [];
  consoleSummary.push('');
  consoleSummary.push('=== PostHog A/B Summary ===');
  consoleSummary.push(`Active: ${activeFlags.map(f=>f.key).join(', ') || 'none'}`);
  if (analysis) {
    consoleSummary.push(`Null rate: ${formatPct(analysis.nullRate)}`);
    consoleSummary.push(`Impressions: ctrl=${analysis.impressions.control} treat=${analysis.impressions.treatment}`);
    if (analysis.recommendation) {
      consoleSummary.push(`Rec: ${analysis.recommendation.action} — ${analysis.recommendation.text}`);
    }
  }
  const highMeta = metaRecs.filter(m => m.severity === 'high');
  if (highMeta.length > 0) {
    consoleSummary.push(`Meta: ${highMeta.length} high-severity operation issue(s) found.`);
  }
  console.log(consoleSummary.join('\n'));
}

main().catch(err => {
  console.error('posthog-check failed:', err.message);
  process.exit(1);
});
