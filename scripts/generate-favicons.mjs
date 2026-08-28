import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const sourceImage = path.resolve(__dirname, '../public/images/cropped-cropped-cropped-favicon-270x270.webp');
const publicDir = path.resolve(__dirname, '../public');

async function generateFavicons() {
  console.log('Generating favicons from original WordPress icon:', sourceImage);

  // 1. Generate PNGs of various sizes
  const sizes = [16, 32, 48, 180, 192, 512];
  const pngBuffers = {};

  for (const size of sizes) {
    const buf = await sharp(sourceImage)
      .resize(size, size, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
      .png()
      .toBuffer();
    pngBuffers[size] = buf;

    if (size === 180) {
      fs.writeFileSync(path.join(publicDir, 'apple-touch-icon.png'), buf);
    } else if (size === 32) {
      fs.writeFileSync(path.join(publicDir, 'favicon-32x32.png'), buf);
    } else if (size === 16) {
      fs.writeFileSync(path.join(publicDir, 'favicon-16x16.png'), buf);
    } else if (size === 192) {
      fs.writeFileSync(path.join(publicDir, 'icon-192.png'), buf);
    } else if (size === 512) {
      fs.writeFileSync(path.join(publicDir, 'icon-512.png'), buf);
    }
  }

  // 2. Build multi-resolution favicon.ico containing 16x16, 32x32, and 48x48
  const icoSizes = [16, 32, 48];
  const count = icoSizes.length;
  const headerSize = 6;
  const entrySize = 16;
  let offset = headerSize + (count * entrySize);

  const header = Buffer.alloc(headerSize);
  header.writeUInt16LE(0, 0); // reserved
  header.writeUInt16LE(1, 2); // ICO type
  header.writeUInt16LE(count, 4); // count

  const entries = [];
  const imageBuffers = [];

  for (const s of icoSizes) {
    const buf = pngBuffers[s];
    imageBuffers.push(buf);

    const entry = Buffer.alloc(entrySize);
    entry.writeUInt8(s === 256 ? 0 : s, 0); // width
    entry.writeUInt8(s === 256 ? 0 : s, 1); // height
    entry.writeUInt8(0, 2); // color count
    entry.writeUInt8(0, 3); // reserved
    entry.writeUInt16LE(1, 4); // color planes
    entry.writeUInt16LE(32, 6); // bpp
    entry.writeUInt32LE(buf.length, 8); // size
    entry.writeUInt32LE(offset, 12); // offset
    entries.push(entry);

    offset += buf.length;
  }

  const icoBuffer = Buffer.concat([header, ...entries, ...imageBuffers]);
  fs.writeFileSync(path.join(publicDir, 'favicon.ico'), icoBuffer);

  // 3. Also generate an SVG-wrapped high-res version for favicon.svg if desired
  const base64Png = pngBuffers[192].toString('base64');
  const svgContent = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 192" width="100%" height="100%">
  <image width="192" height="192" href="data:image/png;base64,${base64Png}" />
</svg>`;
  fs.writeFileSync(path.join(publicDir, 'favicon.svg'), svgContent, 'utf8');

  console.log('✓ Successfully generated all favicon assets from original WordPress icon!');
}

generateFavicons().catch(err => {
  console.error('Error generating favicons:', err);
  process.exit(1);
});
