import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const projectRoot = path.resolve(__dirname, '..');
const distDir = path.join(projectRoot, 'dist');
const rootAstroDir = path.join(projectRoot, '_astro');

if (!fs.existsSync(distDir)) {
  console.error('Error: dist directory does not exist. Run astro build first.');
  process.exit(1);
}

// 1. Wipe stale root _astro directory to prevent accumulating orphaned bundle hashes
if (fs.existsSync(rootAstroDir)) {
  fs.rmSync(rootAstroDir, { recursive: true, force: true });
}

// 2. Copy compiled dist files and directories to project root for Cloudways deployment
fs.cpSync(distDir, projectRoot, { recursive: true });

console.log('✓ Successfully synchronized dist/ to project root (pruned stale _astro assets).');
