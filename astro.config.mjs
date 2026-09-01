// @ts-check
import { defineConfig } from 'astro/config';
import tailwindcss from '@tailwindcss/vite';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
export default defineConfig({
  site: 'https://armstronglocksmithinc.com',
  build: {
    // Inline the stylesheet into every page: kills the render-blocking CSS
    // request PageSpeed flags (~170ms on mobile). Costs ~14KB gzipped per
    // page view, a good trade for a call-now site where the first paint
    // is what converts.
    inlineStylesheets: 'always'
  },
  vite: {
    plugins: [tailwindcss()]
  },
  integrations: [
    sitemap({
      filter: (page) => !page.includes('/staff-sms-alerts-consent') && !page.includes('/404') && !page.includes('/review'),
      serialize(item) {
        // Stamp lastmod at build time so Google prioritizes recrawling updated pages
        item.lastmod = new Date().toISOString();
        return item;
      }
    })
  ]
});