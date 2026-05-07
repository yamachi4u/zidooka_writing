import fs from "node:fs/promises";
import path from "node:path";
import PptxGenJS from "pptxgenjs";
import sharp from "sharp";

const rootDir = process.cwd();
const outDir = path.join(rootDir, "images", "2026", "04", "pptxgenjs-demo");
const pptxPath = path.join(outDir, "pptxgenjs-codex-claude-demo.pptx");

const COLORS = {
  bg: "#F4F0E8",
  paper: "#FFFDFC",
  text: "#1F2937",
  muted: "#6B7280",
  accent: "#0E7490",
  accent2: "#E76F51",
  line: "#D6D3D1",
  card: "#F8FAFC",
  success: "#2A9D8F",
};

const slides = [
  {
    file: "slide-01-title.svg",
    title: "AI Agents Can Draft Real Slides",
    subtitle:
      "Codex や Claude に雑な箇条書きを渡しても、\nPptxGenJS なら PowerPoint まで持っていきやすいです。",
    kicker: "PptxGenJS Demo",
    pills: ["Node.js", "PowerPoint", "Japanese + English"],
  },
  {
    file: "slide-02-bilingual.svg",
    title: "Bilingual Executive Brief",
    leftTitle: "日本語サマリー",
    rightTitle: "English Summary",
    leftItems: [
      "経営会議向けの3枚資料を自動生成",
      "見出し、表、グラフをコードで固定",
      "毎週の数値差し替えだけで再利用しやすい",
    ],
    rightItems: [
      "Generate a 3-slide executive brief automatically",
      "Lock layout, charts, and tables in code",
      "Reuse weekly by swapping data only",
    ],
  },
  {
    file: "slide-03-chart.svg",
    title: "Weekly Metrics Snapshot",
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri"],
    values: [42, 58, 61, 77, 94],
    notes: [
      "問い合わせ初回返信率: 94%",
      "英語版ドラフト所要時間: 18分",
      "更新コストはテンプレ化でさらに下げやすい",
    ],
  },
];

function esc(value) {
  return String(value)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

function svgTextBlock(lines, x, y, opts = {}) {
  const {
    size = 28,
    fill = COLORS.text,
    weight = 400,
    lineGap = 1.35,
  } = opts;
  const tspans = lines
    .map((line, index) => {
      const dy = index === 0 ? 0 : size * lineGap;
      return `<tspan x="${x}" dy="${dy}">${esc(line)}</tspan>`;
    })
    .join("");
  return `<text x="${x}" y="${y}" font-family="Segoe UI, Arial, sans-serif" font-size="${size}" font-weight="${weight}" fill="${fill}">${tspans}</text>`;
}

function renderSlide01(slide) {
  const pills = slide.pills
    .map((pill, index) => {
      const x = 110 + index * 220;
      return `
      <rect x="${x}" y="555" rx="20" ry="20" width="190" height="42" fill="#FFFFFF" stroke="${COLORS.line}" />
      <text x="${x + 24}" y="582" font-family="Segoe UI, Arial, sans-serif" font-size="20" fill="${COLORS.accent}">${esc(
        pill
      )}</text>`;
    })
    .join("");
  return `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1280" height="720" viewBox="0 0 1280 720">
  <rect width="1280" height="720" fill="${COLORS.bg}"/>
  <rect x="72" y="72" width="1136" height="576" rx="28" fill="${COLORS.paper}" stroke="${COLORS.line}"/>
  <rect x="72" y="72" width="220" height="576" rx="28" fill="${COLORS.accent}"/>
  <circle cx="182" cy="170" r="58" fill="none" stroke="#9ED9E3" stroke-width="10"/>
  <circle cx="182" cy="170" r="30" fill="none" stroke="#FFFFFF" stroke-width="6"/>
  <text x="145" y="182" font-family="Segoe UI, Arial, sans-serif" font-size="30" font-weight="700" fill="#FFFFFF">AI</text>
  ${svgTextBlock([slide.kicker], 340, 150, { size: 22, fill: COLORS.accent, weight: 700 })}
  ${svgTextBlock([slide.title], 340, 240, { size: 54, weight: 700 })}
  ${svgTextBlock(slide.subtitle.split("\n"), 340, 332, { size: 28, fill: COLORS.muted })}
  <rect x="340" y="430" width="540" height="8" rx="4" fill="${COLORS.accent2}"/>
  ${pills}
</svg>`;
}

function renderSlide02(slide) {
  const leftItems = slide.leftItems
    .map(
      (item, index) => `
      <circle cx="158" cy="${238 + index * 92}" r="8" fill="${COLORS.accent}"/>
      ${svgTextBlock([item], 182, 248 + index * 92, { size: 28 })}`
    )
    .join("");
  const rightItems = slide.rightItems
    .map(
      (item, index) => `
      <circle cx="698" cy="${238 + index * 92}" r="8" fill="${COLORS.accent2}"/>
      ${svgTextBlock([item], 722, 248 + index * 92, { size: 26 })}`
    )
    .join("");
  return `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1280" height="720" viewBox="0 0 1280 720">
  <rect width="1280" height="720" fill="${COLORS.bg}"/>
  <rect x="56" y="56" width="1168" height="608" rx="28" fill="${COLORS.paper}" stroke="${COLORS.line}"/>
  ${svgTextBlock([slide.title], 96, 130, { size: 48, weight: 700 })}
  <rect x="96" y="166" width="1088" height="1" fill="${COLORS.line}"/>
  <rect x="96" y="198" width="500" height="388" rx="22" fill="#F2FBFD" stroke="#CFECEF"/>
  <rect x="636" y="198" width="500" height="388" rx="22" fill="#FFF3EF" stroke="#F7D6CD"/>
  ${svgTextBlock([slide.leftTitle], 126, 246, { size: 30, weight: 700, fill: COLORS.accent })}
  ${svgTextBlock([slide.rightTitle], 666, 246, { size: 30, weight: 700, fill: COLORS.accent2 })}
  ${leftItems}
  ${rightItems}
</svg>`;
}

function renderSlide03(slide) {
  const max = Math.max(...slide.values);
  const bars = slide.values
    .map((value, index) => {
      const barHeight = Math.round((value / max) * 230);
      const x = 118 + index * 108;
      const y = 498 - barHeight;
      return `
      <rect x="${x}" y="${y}" width="72" height="${barHeight}" rx="16" fill="${
        index === slide.values.length - 1 ? COLORS.accent2 : COLORS.accent
      }"/>
      <text x="${x + 20}" y="536" font-family="Segoe UI, Arial, sans-serif" font-size="20" fill="${COLORS.muted}">${esc(
        slide.labels[index]
      )}</text>
      <text x="${x + 16}" y="${y - 14}" font-family="Segoe UI, Arial, sans-serif" font-size="20" fill="${COLORS.text}">${value}</text>`;
    })
    .join("");
  const notes = slide.notes
    .map(
      (note, index) => `
      <circle cx="818" cy="${252 + index * 92}" r="7" fill="${COLORS.success}"/>
      ${svgTextBlock([note], 842, 262 + index * 92, { size: 26 })}`
    )
    .join("");
  return `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1280" height="720" viewBox="0 0 1280 720">
  <rect width="1280" height="720" fill="${COLORS.bg}"/>
  <rect x="56" y="56" width="1168" height="608" rx="28" fill="${COLORS.paper}" stroke="${COLORS.line}"/>
  ${svgTextBlock([slide.title], 96, 128, { size: 48, weight: 700 })}
  <rect x="96" y="184" width="620" height="360" rx="22" fill="${COLORS.card}" stroke="${COLORS.line}"/>
  <rect x="760" y="184" width="376" height="360" rx="22" fill="#F2FBF7" stroke="#C8EAD9"/>
  <line x1="118" y1="498" x2="672" y2="498" stroke="${COLORS.line}" stroke-width="2"/>
  ${bars}
  ${svgTextBlock(["What makes this useful"], 792, 236, { size: 30, weight: 700, fill: COLORS.success })}
  ${notes}
</svg>`;
}

async function writeSvg(fileName, content) {
  const svgPath = path.join(outDir, fileName);
  const pngPath = svgPath.replace(/\.svg$/i, ".png");
  await fs.writeFile(svgPath, content, "utf8");
  await sharp(Buffer.from(content)).png().toFile(pngPath);
}

async function buildPreviewImages() {
  await writeSvg(slides[0].file, renderSlide01(slides[0]));
  await writeSvg(slides[1].file, renderSlide02(slides[1]));
  await writeSvg(slides[2].file, renderSlide03(slides[2]));
}

async function buildPptx() {
  const pptx = new PptxGenJS();
  pptx.layout = "LAYOUT_WIDE";
  pptx.author = "Codex";
  pptx.company = "ZIDOOKA";
  pptx.subject = "PptxGenJS demo deck";
  pptx.title = "PptxGenJS Codex Claude Demo";
  pptx.lang = "ja-JP";
  pptx.theme = {
    headFontFace: "Aptos Display",
    bodyFontFace: "Aptos",
    lang: "ja-JP",
  };

  const s1 = pptx.addSlide();
  s1.background = { color: "F4F0E8" };
  s1.addShape(pptx.ShapeType.roundRect, {
    x: 0.6,
    y: 0.6,
    w: 12.1,
    h: 6.1,
    rectRadius: 0.1,
    fill: { color: "FFFDFC" },
    line: { color: "D6D3D1", pt: 1 },
  });
  s1.addShape(pptx.ShapeType.roundRect, {
    x: 0.6,
    y: 0.6,
    w: 2.3,
    h: 6.1,
    rectRadius: 0.1,
    fill: { color: "0E7490" },
    line: { color: "0E7490", pt: 0 },
  });
  s1.addText("PptxGenJS Demo", {
    x: 3.2,
    y: 1.0,
    w: 3.2,
    h: 0.3,
    color: "0E7490",
    bold: true,
    fontSize: 16,
  });
  s1.addText("AI Agents Can Draft Real Slides", {
    x: 3.2,
    y: 1.7,
    w: 7.8,
    h: 0.6,
    color: "1F2937",
    bold: true,
    fontSize: 26,
  });
  s1.addText(
    "Codex や Claude に雑な箇条書きを渡しても、PptxGenJS なら\nPowerPoint まで持っていきやすいです。",
    {
      x: 3.2,
      y: 2.55,
      w: 7.4,
      h: 1.0,
      color: "6B7280",
      fontSize: 16,
      breakLine: false,
      margin: 0,
    }
  );
  s1.addShape(pptx.ShapeType.line, {
    x: 3.2,
    y: 4.0,
    w: 4.6,
    h: 0,
    line: { color: "E76F51", pt: 3 },
  });
  [["Node.js", 3.2], ["PowerPoint", 5.05], ["Japanese + English", 6.95]].forEach(
    ([label, x]) => {
      s1.addText(label, {
        x,
        y: 4.7,
        w: label === "Japanese + English" ? 2.1 : 1.5,
        h: 0.35,
        fontSize: 12,
        color: "0E7490",
        align: "center",
        fill: { color: "FFFFFF" },
        line: { color: "D6D3D1", pt: 1 },
        margin: 0.08,
        radius: 0.12,
      });
    }
  );

  const s2 = pptx.addSlide();
  s2.background = { color: "F4F0E8" };
  s2.addShape(pptx.ShapeType.roundRect, {
    x: 0.45,
    y: 0.45,
    w: 12.4,
    h: 6.4,
    rectRadius: 0.1,
    fill: { color: "FFFDFC" },
    line: { color: "D6D3D1", pt: 1 },
  });
  s2.addText("Bilingual Executive Brief", {
    x: 0.85,
    y: 0.78,
    w: 6,
    h: 0.5,
    fontSize: 24,
    bold: true,
    color: "1F2937",
  });
  s2.addShape(pptx.ShapeType.roundRect, {
    x: 0.85,
    y: 1.8,
    w: 5.2,
    h: 3.9,
    rectRadius: 0.1,
    fill: { color: "F2FBFD" },
    line: { color: "CFECEF", pt: 1 },
  });
  s2.addShape(pptx.ShapeType.roundRect, {
    x: 6.45,
    y: 1.8,
    w: 5.2,
    h: 3.9,
    rectRadius: 0.1,
    fill: { color: "FFF3EF" },
    line: { color: "F7D6CD", pt: 1 },
  });
  s2.addText("日本語サマリー", {
    x: 1.1,
    y: 2.1,
    w: 2.5,
    h: 0.4,
    fontSize: 18,
    bold: true,
    color: "0E7490",
  });
  s2.addText("English Summary", {
    x: 6.7,
    y: 2.1,
    w: 3,
    h: 0.4,
    fontSize: 18,
    bold: true,
    color: "E76F51",
  });
  s2.addText(
    [
      { text: "• 経営会議向けの3枚資料を自動生成" },
      { text: "• 見出し、表、グラフをコードで固定" },
      { text: "• 毎週の数値差し替えだけで再利用しやすい" },
    ],
    {
      x: 1.1,
      y: 2.7,
      w: 4.4,
      h: 2.2,
      fontSize: 16,
      color: "1F2937",
      breakLine: true,
      margin: 0,
      paraSpaceAfterPt: 12,
    }
  );
  s2.addText(
    [
      { text: "• Generate a 3-slide executive brief automatically" },
      { text: "• Lock layout, charts, and tables in code" },
      { text: "• Reuse weekly by swapping data only" },
    ],
    {
      x: 6.7,
      y: 2.7,
      w: 4.2,
      h: 2.2,
      fontSize: 16,
      color: "1F2937",
      breakLine: true,
      margin: 0,
      paraSpaceAfterPt: 12,
    }
  );

  const s3 = pptx.addSlide();
  s3.background = { color: "F4F0E8" };
  s3.addShape(pptx.ShapeType.roundRect, {
    x: 0.45,
    y: 0.45,
    w: 12.4,
    h: 6.4,
    rectRadius: 0.1,
    fill: { color: "FFFDFC" },
    line: { color: "D6D3D1", pt: 1 },
  });
  s3.addText("Weekly Metrics Snapshot", {
    x: 0.85,
    y: 0.78,
    w: 6.4,
    h: 0.5,
    fontSize: 24,
    bold: true,
    color: "1F2937",
  });
  const chartData = [
    {
      name: "Reply Rate",
      labels: slides[2].labels,
      values: slides[2].values,
    },
  ];
  s3.addChart(pptx.ChartType.bar, chartData, {
    x: 0.95,
    y: 1.7,
    w: 6.1,
    h: 3.7,
    catAxisLabelFontSize: 12,
    valAxisLabelFontSize: 12,
    showTitle: false,
    showLegend: false,
    showValue: true,
    valAxisMinVal: 0,
    valAxisMaxVal: 100,
    chartColors: ["0E7490"],
    showCatName: true,
    showValAxisTitle: false,
    showCatAxisTitle: false,
  });
  s3.addShape(pptx.ShapeType.roundRect, {
    x: 7.45,
    y: 1.7,
    w: 4.3,
    h: 3.7,
    rectRadius: 0.1,
    fill: { color: "F2FBF7" },
    line: { color: "C8EAD9", pt: 1 },
  });
  s3.addText("What makes this useful", {
    x: 7.8,
    y: 2.1,
    w: 3.4,
    h: 0.4,
    fontSize: 18,
    bold: true,
    color: "2A9D8F",
  });
  s3.addText(
    [
      { text: "• 問い合わせ初回返信率: 94%" },
      { text: "• 英語版ドラフト所要時間: 18分" },
      { text: "• 更新コストはテンプレ化でさらに下げやすい" },
    ],
    {
      x: 7.8,
      y: 2.7,
      w: 3.2,
      h: 2.2,
      fontSize: 15,
      color: "1F2937",
      breakLine: true,
      margin: 0,
      paraSpaceAfterPt: 12,
    }
  );

  await pptx.writeFile({ fileName: pptxPath });
}

await fs.mkdir(outDir, { recursive: true });
await buildPreviewImages();
await buildPptx();

console.log(`Generated: ${pptxPath}`);
