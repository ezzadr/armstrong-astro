// @ts-check
import { defineConfig } from 'astro/config';
import tailwindcss from '@tailwindcss/vite';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
export default defineConfig({
  site: 'https://armstronglocksmithinc.com',
  build: {
    // Inline critical CSS directly into HTML to eliminate the render-blocking
    // stylesheet round-trip on Slow 4G mobile connections (100/100 PageSpeed).
    inlineStylesheets: 'always'
  },
  vite: {
    plugins: [tailwindcss()]
  },
  integrations: [
    sitemap({
      // No lastmod: a build-time timestamp on every URL marks all 42 pages as
      // "just changed" on each deploy, which Google treats as noise and ignores.
      // Emitting no lastmod is more honest than emitting one that is always wrong.
      filter: (page) => !page.includes('/staff-sms-alerts-consent') && !page.includes('/404') && !page.includes('/review')
    })
  ]
});