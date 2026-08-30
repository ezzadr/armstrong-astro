import sharp from 'sharp';
import fs from 'fs';
import path from 'path';

async function optimizeHero() {
  const input = 'public/shop.jpeg';
  console.log('Reading from', input);

  // 1. Mobile Phone Variant (450px width, ~45KB)
  await sharp(input)
    .resize(450, null, { withoutEnlargement: true })
    .webp({ quality: 74, effort: 6 })
    .toFile('public/shop-450.webp');
  console.log('Created public/shop-450.webp');

  // 2. Tablet / Large Phone Variant (600px width, ~75KB)
  await sharp(input)
    .resize(600, null, { withoutEnlargement: true })
    .webp({ quality: 75, effort: 6 })
    .toFile('public/shop-600.webp');
  console.log('Created public/shop-600.webp');

  // 3. Desktop Variant (1200px width, ~180KB)
  await sharp(input)
    .resize(1200, null, { withoutEnlargement: true })
    .webp({ quality: 78, effort: 6 })
    .toFile('public/shop-1200.webp');
  console.log('Created public/shop-1200.webp');

  const stats450 = fs.statSync('public/shop-450.webp');
  const stats600 = fs.statSync('public/shop-600.webp');
  const stats1200 = fs.statSync('public/shop-1200.webp');

  console.log('shop-450.webp:', (stats450.size / 1024).toFixed(1), 'KB');
  console.log('shop-600.webp:', (stats600.size / 1024).toFixed(1), 'KB');
  console.log('shop-1200.webp:', (stats1200.size / 1024).toFixed(1), 'KB');
}

optimizeHero().catch(err => {
  console.error('Error optimizing hero images:', err);
  process.exit(1);
});
