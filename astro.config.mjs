// @ts-check
import { defineConfig } from 'astro/config';
import tailwindcss from '@tailwindcss/vite';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
export default defineConfig({
  site: 'https://armstronglocksmithinc.com',
  build: {
    // One shared external stylesheet (cached by the browser and the Cloudflare
    // edge) instead of inlining it into every page. The full Tailwind output is
    // ~117KB (~35KB compressed); inlined, every one of the 45 pages re-shipped
    // and re-parsed it on each view. "auto" still inlines tiny page-scoped CSS.
    inlineStylesheets: 'auto'
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