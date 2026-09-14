# Meta Backup — September 2026

Pre-change snapshot of SEO titles and meta descriptions, recorded before the
CTR-focused rewrite described in `armstrong-meta-update-instructions.md`.
Taken 2026-09-14 from `src/pages/*.astro` at commit cd41800e.

Restore by putting these strings back into the `seoTitle` / `seoDescription`
consts at the top of each page file.

---

## 1. Homepage — `/` (src/pages/index.astro)

- **Old title:** `Armstrong Locksmith Nashville | Car Keys, Rekeys & Lockouts`
- **Old meta:** `Owner-led Nashville locksmith with a storefront at 208 Thompson Ln. Car keys, rekeys, lockouts & commercial service. Certified. Call (615) 625-8000.`

## 2. `/automotive-locksmith-in-nashville-tn/` (src/pages/automotive-locksmith-in-nashville-tn.astro)

- **Old title:** `Automotive Locksmith Nashville TN | Armstrong Locksmith`
- **Old meta:** `Complete auto locksmith services in Nashville, TN. Car key cutting, transponders, smart fobs & ignition repairs on-site. (615) 625-8000.`

## 3. `/locksmith-pricing-nashville/` (src/pages/locksmith-pricing-nashville.astro)

- **Old title:** `2026 Locksmith Pricing Nashville | No Hidden Fees | Armstrong`
- **Old meta:** `Transparent Nashville locksmith prices for car keys, house rekeys, business locks & emergency lockouts. Real shop at 208 Thompson Ln. (615) 625-8000.`

## 4. `/bmw/` (src/pages/bmw.astro)

- **Old title:** `BMW Key Replacement Nashville TN | Armstrong Locksmith`
- **Old meta:** `Affordable BMW car key cutting, transponder & smart proximity fob programming in Nashville. Mobile van or shop at 208 Thompson Ln. (615) 625-8000.`

## 5. `/audi-car-keys/` (src/pages/audi-car-keys.astro)

- **Old title:** `Audi Key Replacement Nashville TN | Armstrong Locksmith`
- **Old meta:** `Affordable Audi car key cutting, transponder & smart key fob programming in Nashville. Mobile van & shop at 208 Thompson Ln. (615) 625-8000.`

---

## Not touched

`src/lib/reviewStats.mjs` FALLBACK was bumped 778 -> 781 (live Google total on
2026-09-14) per CLAUDE.md. Not a title/meta change.

Pages 3, 4, 6 and 9 of the instruction doc (`/locksmith-antioch/`,
`/brentwood/`, `/key-fob-locked-in-car-nashville/`,
`/best-places-to-hide-valuable-in-car/`) do not exist in this codebase and were
not edited. See the report for details.
