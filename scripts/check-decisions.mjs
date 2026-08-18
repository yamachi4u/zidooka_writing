import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const decisionsDir = path.join(__dirname, '..', 'docs', 'decisions');

function parseDecision(filePath) {
  const content = fs.readFileSync(filePath, 'utf-8');
  const lines = content.split('\n');

  let frontmatter = {};
  let inFrontmatter = false;
  let bodyStart = 0;

  for (let i = 0; i < lines.length; i++) {
    const line = lines[i].trim();
    if (i === 0 && line === '---') {
      inFrontmatter = true;
      continue;
    }
    if (inFrontmatter && line === '---') {
      inFrontmatter = false;
      bodyStart = i + 1;
      break;
    }
    if (inFrontmatter) {
      const colonIdx = line.indexOf(':');
      if (colonIdx > 0) {
        const key = line.slice(0, colonIdx).trim();
        const val = line.slice(colonIdx + 1).trim();
        frontmatter[key] = val.replace(/^["']|["']$/g, '');
      }
    }
  }

  const body = lines.slice(bodyStart).join('\n').trim();

  // Extract verification date from frontmatter or body
  const verifyDate = frontmatter.verify_date || null;
  const createdAt = frontmatter.created || null;
  const status = frontmatter.status || 'pending';
  const title = frontmatter.title || path.basename(filePath, '.md').replace(/^\d{4}-\d{2}-\d{2}-/, '').replace(/-/g, ' ');

  return { filePath, title, createdAt, verifyDate, status, body, frontmatter };
}

function toDate(str) {
  if (!str) return null;
  const d = new Date(str);
  return isNaN(d.getTime()) ? null : d;
}

function formatDate(d) {
  return d.toISOString().slice(0, 10);
}

const now = new Date();
const today = formatDate(now);
const sevenDaysFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);

const files = fs.readdirSync(decisionsDir).filter(f => f.endsWith('.md'));
const decisions = files.map(f => parseDecision(path.join(decisionsDir, f)));

console.log('═══════════════════════════════════════════');
console.log('  Decision Records Review');
console.log('═══════════════════════════════════════════');
console.log(`  Checked: ${today}`);
console.log(`  Total decisions: ${decisions.length}`);
console.log('');

// --- Due for review ---
const due = decisions.filter(d => {
  if (d.status === 'completed') return false;
  const vd = toDate(d.verifyDate);
  return vd && vd <= now;
});

if (due.length > 0) {
  console.log('── Due for review ─────────────────────────');
  for (const d of due) {
    console.log(`  ⏰ ${d.verifyDate}  ${d.title}`);
    console.log(`     ${d.filePath}`);
    console.log('');
  }
} else {
  console.log('── Due for review ─────────────────────────');
  console.log('  (none)');
  console.log('');
}

// --- Upcoming reviews (within 7 days) ---
const upcoming = decisions.filter(d => {
  if (d.status === 'completed') return false;
  const vd = toDate(d.verifyDate);
  return vd && vd > now && vd <= sevenDaysFromNow;
});

if (upcoming.length > 0) {
  console.log('── Upcoming reviews (within 7 days) ───────');
  for (const d of upcoming) {
    console.log(`  📅 ${d.verifyDate}  ${d.title}`);
    console.log(`     ${d.filePath}`);
    console.log('');
  }
}

// --- Summary table ---
console.log('── All decisions ──────────────────────────');
console.log(`  ${'Date'.padEnd(12)} ${'Status'.padEnd(10)} ${'Verify'.padEnd(12)} Title`);
console.log(`  ${''.padEnd(12, '─')} ${''.padEnd(10, '─')} ${''.padEnd(12, '─')} ${''.padEnd(30, '─')}`);
for (const d of decisions) {
  const created = d.createdAt ? d.createdAt.padEnd(12) : ''.padEnd(12);
  const status = d.status.padEnd(10);
  const verify = d.verifyDate ? d.verifyDate.padEnd(12) : '—'.padEnd(12);
  const title = d.title.slice(0, 50);
  console.log(`  ${created} ${status} ${verify} ${title}`);
}
console.log('');

// --- Next action prompt ---
if (due.length > 0) {
  console.log('── Recommended actions ────────────────────');
  for (const d of due) {
    console.log(`  Run data check for: ${d.title}`);
    console.log(`  npm run ph:analyze`);
    console.log(`  Then update ${path.basename(d.filePath)} with results.`);
    console.log('');
  }
}
