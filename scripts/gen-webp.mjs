// Generate responsive WebP derivatives for images served from public/.
//
// Files in public/ are copied byte-for-byte by Astro and get no build-time
// processing, so <Image /> from astro:assets cannot help here without moving
// every asset into src/. This produces sized .webp siblings instead, which the
// markup consumes through <picture><source> with the original as fallback.
//
// Usage: node scripts/gen-webp.mjs <relative/path.jpg> [...]
import sharp from 'sharp';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const WIDTHS = [600, 1200];   // 1x and 2x for a 600px display box
const RATIO = 4 / 3;          // gallery cards render 600x450

const targets = process.argv.slice(2);
if (!targets.length) {
  console.error('usage: node scripts/gen-webp.mjs <image> [...]');
  process.exit(1);
}

let before = 0, after = 0;

for (const rel of targets) {
  const src = path.join(ROOT, 'public', rel);
  if (!fs.existsSync(src)) { console.log(`SKIP (missing) ${rel}`); continue; }

  const origBytes = fs.statSync(src).size;
  before += origBytes;
  const dir = path.dirname(src);
  const base = path.basename(src).replace(/\.[^.]+$/, '');
  const produced = [];

  for (const w of WIDTHS) {
    const out = path.join(dir, `${base}-${w}.webp`);
    await sharp(src)
      .resize(w, Math.round(w / RATIO), { fit: 'cover', position: 'attention' })
      .webp({ quality: w === 1200 ? 74 : 80 })
      .toFile(out);
    const sz = fs.statSync(out).size;
    produced.push(`${w}w ${(sz / 1024).toFixed(0)}KB`);
    if (w === 600) after += sz;   // the 1x variant is what most users download
  }

  console.log(
    `OK  ${(origBytes / 1024).toFixed(0).padStart(4)}KB  ${base.slice(0, 42).padEnd(42)} -> ${produced.join(', ')}`
  );
}

console.log(`\n1x payload: ${(before / 1024).toFixed(0)} KB -> ${(after / 1024).toFixed(0)} KB  ` +
            `(${(100 - (after / before) * 100).toFixed(0)}% smaller)`);
