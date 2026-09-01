// Embed GPS + attribution EXIF into JPEGs served from public/.
//
// Google Images can read EXIF metadata from indexed files, so job photos get
// the storefront coordinates baked in. The coordinates MUST stay identical to
// the LocalBusiness schema geo in src/layouts/Layout.astro — conflicting
// signals are worse than none.
//
// Run this BEFORE scripts/gen-webp.mjs: the WebP derivatives inherit the tags
// via keepExif().
//
// Usage:
//   node scripts/geotag.mjs --desc="What the photo shows" [--taken="2026:08:31 20:36:00"] images/foo.jpg [...]
import piexif from 'piexifjs';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');

// Storefront: 208 Thompson Ln, Nashville, TN 37211 (matches Layout.astro geo)
const LAT = 36.1118;
const LON = -86.7378;

const args = process.argv.slice(2);
const descArg = args.find(a => a.startsWith('--desc='));
const takenArg = args.find(a => a.startsWith('--taken='));
const targets = args.filter(a => !a.startsWith('--'));

if (!targets.length) {
  console.error('usage: node scripts/geotag.mjs --desc="..." [--taken="YYYY:MM:DD HH:MM:SS"] <image> [...]');
  process.exit(1);
}

const description = descArg ? descArg.split('=').slice(1).join('=') : '';

for (const rel of targets) {
  const file = path.join(ROOT, 'public', rel);
  if (!fs.existsSync(file)) { console.log(`SKIP (missing) ${rel}`); continue; }
  if (!/\.jpe?g$/i.test(file)) { console.log(`SKIP (not jpeg) ${rel}`); continue; }

  const zeroth = {
    [piexif.ImageIFD.Artist]: 'Armstrong Locksmith Inc',
    [piexif.ImageIFD.Copyright]: 'Armstrong Locksmith Inc, 208 Thompson Ln, Nashville, TN 37211',
  };
  if (description) zeroth[piexif.ImageIFD.ImageDescription] = description;

  const exif = {};
  if (takenArg) exif[piexif.ExifIFD.DateTimeOriginal] = takenArg.split('=').slice(1).join('=');

  const gps = {
    [piexif.GPSIFD.GPSLatitudeRef]: LAT >= 0 ? 'N' : 'S',
    [piexif.GPSIFD.GPSLatitude]: piexif.GPSHelper.degToDmsRational(Math.abs(LAT)),
    [piexif.GPSIFD.GPSLongitudeRef]: LON >= 0 ? 'E' : 'W',
    [piexif.GPSIFD.GPSLongitude]: piexif.GPSHelper.degToDmsRational(Math.abs(LON)),
  };

  const exifBytes = piexif.dump({ '0th': zeroth, Exif: exif, GPS: gps });
  const jpeg = fs.readFileSync(file).toString('binary');
  const tagged = piexif.insert(exifBytes, jpeg);
  fs.writeFileSync(file, Buffer.from(tagged, 'binary'));
  console.log(`OK  geotagged ${rel} @ ${LAT},${LON}`);
}
