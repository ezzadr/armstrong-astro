// Generate responsive WebP derivatives for images served from public/.
//
// Files in public/ are copied byte-for-byte by Astro and get no build-time
// processing, so <Image /> from astro:assets cannot help here without moving
// every asset into src/. This produces sized .webp siblings instead, which the
// markup consumes through <picture><source> with the original as fallback.
//
// Aspect ratio is preserved: every consuming element already uses object-cover,
// so framing stays exactly as it is today and no crop decisions are baked in.
//
// Usage:
//   node scripts/gen-webp.mjs --widths=600,1200 images/foo.jpg images/bar.png
import sharp from 'sharp';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');

const args = process.argv.slice(2);
const widthArg = args.find(a => a.startsWith('--widths='));
const WIDTHS = widthArg
  ? widthArg.split('=')[1].split(',').map(Number)
  : [600, 1200];
const targets = args.filter(a => !a.startsWith('--'));

if (!targets.length) {
  console.error('usage: node scripts/gen-webp.mjs [--widths=600,1200] <image> [...]');
  process.exit(1);
}

let before = 0, after = 0;

for (const rel of targets) {
  const src = path.join(ROOT, 'public', rel);
  if (!fs.existsSync(src)) { console.log(`SKIP (missing) ${rel}`); continue; }

  const origBytes = fs.statSync(src).size;
  const meta = await sharp(src).metadata();
  before += origBytes;

  const dir = path.dirname(src);
  const base = path.basename(src).replace(/\.[^.]+$/, '');
  const produced = [];
  let smallest = null;

  for (const w of WIDTHS) {
    // never upscale past the source
    const target = Math.min(w, meta.width || w);
    const out = path.join(dir, `${base}-${w}.webp`);
    await sharp(src)
      .resize({ width: target, withoutEnlargement: true })
      .webp({ quality: w >= 1000 ? 74 : 80 })
      .toFile(out);
    const sz = fs.statSync(out).size;
    produced.push(`${w}w ${(sz / 1024).toFixed(0)}KB`);
    if (smallest === null) smallest = sz;
  }

  after += smallest;
  console.log(
    `OK  ${(origBytes / 1024).toFixed(0).padStart(4)}KB  ${base.slice(0, 44).padEnd(44)} -> ${produced.join(', ')}`
  );
}

if (before) {
  console.log(`\n1x payload: ${(before / 1024).toFixed(0)} KB -> ${(after / 1024).toFixed(0)} KB  ` +
              `(${(100 - (after / before) * 100).toFixed(0)}% smaller)`);
}
