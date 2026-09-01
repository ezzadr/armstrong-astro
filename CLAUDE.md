# CLAUDE.md — Armstrong Locksmith Project Guidelines

## Strict Project & GitHub Isolation Rules (ABSOLUTE PRIORITY)

- **ARMSTRONG AI DISPATCH (`armstrong-ai-call-dispatch` / `jqkvdrpcfz`):** This is the user's most critical project. Under NO circumstances should any operation, automated task, cleanup script, or server manipulation touch or risk its stability, webhooks, or database.
- **NEVER Interfere with Other GitHub Projects or Apps:** Strictly NEVER modify, deploy to, reset, or run commands that touch any other GitHub repositories or server application directories outside this specific website workspace (`armstrong-astro` / `btfdkcdpdw`).
- **Target Directory:** All deployment actions and server commands must explicitly and exclusively target `/home/master/applications/btfdkcdpdw/public_html`.

## Core Build & Deployment Rules

1. **CI builds on push — do NOT run `npm run build` before committing:**
   - As of 2026-09-01 the GitHub Action `.github/workflows/deploy.yml` builds the
     site in CI (Linux) on every push to `main`, commits the refreshed root HTML,
     then deploys to Cloudways. Just edit files under `src/` (and `public/`),
     commit the **source**, and push — CI does the build + deploy.
   - **Committing a local `npm run build` is discouraged:** a local build produces
     slightly different minified CSS than CI (lightningcss native binary differs
     per OS), so alternating local/CI builds churns every page. `npm run dev` and
     `npm run build` are fine for local *preview*; don't commit the build output.
   - `npm run build` still runs `astro build && node scripts/sync-to-root.mjs`
     (syncs `dist/` to the repo root, prunes stale `_astro/`). It's what CI runs.
2. **Do NOT create separate per-city doorway pages:**
   - All regional coverage is consolidated on `/service-areas/` in compliance with Google Search Essentials.
   - Legacy city URLs are 301 permanently redirected to `/service-areas/` in `public/.htaccess` and `public/index.php`.
3. **Canonical Business Data:**
   - **Name:** Armstrong Locksmith Inc
   - **Phone:** (615) 625-8000
   - **Storefront:** 208 Thompson Ln, Nashville, TN 37211
   - **Reviews Metric:** 771+ Google Reviews (4.9 Stars) — keep synced with the live GBP count; update site schema `reviewCount` and visible "771+" mentions together
   - **Tennessee Licensing:** TN has no state locksmith license; never add license numbers.
   - **Operating Hours:** Storefront: Mon–Fri 8:00 AM – 6:00 PM, Sat 10:00 AM – 4:00 PM. Mobile dispatch: Mon–Fri 8:00 AM – 11:30 PM, Weekends On-Call.

## Copy & Content Voice Rules (PERMANENT)

- **No AI-sounding marketing fluff — anywhere.** This applies to page headlines, card titles, subtitles, alt text, meta descriptions, GBP posts, and blog content.
- **Banned vocabulary:** "precision," "seamless," "elevate," "unlock your," "effortless," "cutting-edge," "state-of-the-art," "unparalleled," "hassle-free," "peace of mind," "look no further," "we've got you covered," and similar polished-marketing phrases.
- **Never say "CNC laser cutting" (or any "CNC" phrasing) in customer-facing copy** — always "high-security laser cutting" instead. Customers know "high-security key"; "CNC" is machinist jargon.
- **Write like Rahim talks:** plain first-person shop language. "Rahim duplicating a residential key at the shop" beats "Precision Key Cutting by Owner Rahim." Concrete facts (prices, brands, times, addresses) beat adjectives.
- **When Rahim supplies his own phrasing, use it verbatim** (fix only clear typos, e.g. lock/key mixups — and confirm).
- The site's credibility angle is "real local shop, not a call center" — copy that sounds machine-written undermines the entire brand.

## Development Commands

- Start dev server: `npm run dev`
- Preview a production build locally: `npm run build` (do NOT commit its output — see rule 1)
- Deploy: commit **source only**, then `git push origin main` — CI builds + deploys.
  `git add -A && git commit -m "..." && git push origin main`
