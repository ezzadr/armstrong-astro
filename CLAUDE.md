# CLAUDE.md — Armstrong Locksmith Project Guidelines

## Strict Project & GitHub Isolation Rules (ABSOLUTE PRIORITY)

- **ARMSTRONG AI DISPATCH (`armstrong-ai-call-dispatch` / `jqkvdrpcfz`):** This is the user's most critical project. Under NO circumstances should any operation, automated task, cleanup script, or server manipulation touch or risk its stability, webhooks, or database.
- **NEVER Interfere with Other GitHub Projects or Apps:** Strictly NEVER modify, deploy to, reset, or run commands that touch any other GitHub repositories or server application directories outside this specific website workspace (`armstrong-astro` / `btfdkcdpdw`).
- **Target Directory:** All deployment actions and server commands must explicitly and exclusively target `/home/master/applications/btfdkcdpdw/public_html`.

## Core Build & Deployment Rules

1. **`npm run build` is MANDATORY before committing:**
   - Runs `astro build && node scripts/sync-to-root.mjs`.
   - Automatically syncs `dist/` to the repo root and cleans stale `_astro/` files.
   - Pushing to `origin/main` triggers automated Cloudways deployment via git. If you only edit `src/` without running `npm run build`, production will NOT be updated.
2. **Do NOT create separate per-city doorway pages:**
   - All regional coverage is consolidated on `/service-areas/` in compliance with Google Search Essentials.
   - Legacy city URLs are 301 permanently redirected to `/service-areas/` in `public/.htaccess` and `public/index.php`.
3. **Canonical Business Data:**
   - **Name:** Armstrong Locksmith Inc
   - **Phone:** (615) 625-8000
   - **Storefront:** 208 Thompson Ln, Nashville, TN 37211
   - **Reviews Metric:** 769+ Google Reviews (4.9 Stars)
   - **Tennessee Licensing:** TN has no state locksmith license; never add license numbers.
   - **Operating Hours:** Storefront: Mon–Fri 8:00 AM – 6:00 PM, Sat 10:00 AM – 4:00 PM. Mobile dispatch: Mon–Fri 8:00 AM – 11:30 PM, Weekends On-Call.

## Development Commands

- Start dev server: `npm run dev`
- Build & sync: `npm run build`
- Deploy: `git add -A && git commit -m "..." && git push origin main`
