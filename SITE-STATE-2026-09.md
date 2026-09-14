# Armstrong Locksmith — Site State & Work Log

**Last updated:** 2026-09-14
**Purpose:** Context for any agent or consultant writing instructions for this
website. Read this before producing a task list. Two SEO handoffs written in
September 2026 assumed the wrong platform and a page inventory that no longer
exists; roughly half of each document could not be executed. This file exists so
that does not happen again.

---

## 1. What this site actually is

| | |
|---|---|
| **Platform** | **Astro** static site generator. **Not WordPress.** |
| **Repo** | `github.com/ezzadr/armstrong-astro` (not `armstrong-locksmith-website`) |
| **Host** | Cloudways app `btfdkcdpdw`, webroot `/home/master/applications/btfdkcdpdw/public_html` |
| **Domain** | https://armstronglocksmithinc.com |
| **CMS / SEO plugin** | **None.** No WordPress, no Yoast, no WP Rocket, no wp-admin. |
| **Cache** | Varnish on Cloudways, purged automatically by the deploy workflow. |

The site was migrated off WordPress and redesigned. Any instruction that names a
Yoast field, a WP admin screen, or a WP Rocket cache action has no equivalent
here and cannot be carried out as written.

### Where titles and meta descriptions live

There is no central SEO panel. Each page sets its own values in frontmatter:

    src/pages/<page>.astro
      const seoTitle = `...`;
      const seoDescription = `...`;
      <Layout title={seoTitle} description={seoDescription} ...>
            |
            v
    src/layouts/Layout.astro:240
      <title>{title}</title>
      <meta name="description" content={description} />

`Layout.astro` renders the title **raw** — no sitewide suffix is appended, so
character limits are plain string length. Blog posts live in
`src/content/blog/*.md` and take their title/description from frontmatter.

### Deploy pipeline

Commit **source only** and push to `main`. GitHub Actions
(`.github/workflows/deploy.yml`) builds in CI, commits the refreshed root HTML,
deploys to Cloudways via `git reset --hard origin/main`, and purges Varnish.
**Do not commit a local `npm run build`** — local lightningcss output differs
from CI's and churns every page. `npm run build` is fine for local preview only.

Because the server deploys by `git reset --hard`, deleting a file from the repo
also deletes it from the webroot. The webroot *is* the repo root, so any file
committed at root is publicly reachable unless `.htaccess` denies it (`.md` and
`.mjs` are denied).

---

## 2. Canonical facts — never contradict these

- **Name:** Armstrong Locksmith Inc — no period after "Inc" (matches the Google Business Profile).
- **Phone:** (615) 625-8000
- **Storefront:** 208 Thompson Ln, Nashville, TN 37211
- **Hours, storefront:** Mon–Fri 8:00 AM–6:00 PM, Sat 10:00 AM–4:00 PM, **closed Sunday**.
- **Hours, mobile dispatch:** Mon–Fri 8:00 AM–11:30 PM, Sat 10:00 AM–4:00 PM, closed Sunday.
- **There is no 24/7, after-hours, or on-call service.** Never claim it.
  "Emergency" inside a service *name* (e.g. the `/emergency-car-lockout/` page)
  is fine — the ban is on availability claims.
- **Licensing:** Tennessee has no state locksmith license. Never add a license
  number. The site says "certified & insured."
- **Experience:** 20+ years, stated consistently sitewide. (A Sept 2026 handoff
  claimed 17 / 18 / 18+ / 20+ were mixed across pages — that was old WordPress
  copy and is not true of the current site.)

### Review count — do not hardcode it

The Google review total is fetched at build time by `src/lib/reviewStats.mjs`
and refreshed live in the browser. It was **781 (4.9 stars) on 2026-09-14** and
rises every few days.

- In page copy: render inside `<span class="arm-review-count">` so the browser can refresh it.
- In titles and meta descriptions: interpolate `${reviewCount}` from `getReviewStats()`.
- A nightly GitHub Action syncs the schema `reviewCount` and commits it.
- Bump `FALLBACK` in `reviewStats.mjs` when you notice it drifting.

**Never write a fixed number into copy.** An instruction like "use 780+" will be
wrong within a week and will contradict the counter displayed on the same page.

---

## 3. Page inventory

44 live routes, all listed in `/sitemap-0.xml`. Pages live in
`src/pages/*.astro`; blog posts in `src/content/blog/*.md`.

**No per-city pages exist, by design.** All regional coverage is consolidated on
`/service-areas/` for Google Search Essentials compliance. Roughly 40 legacy
city URLs (`/brentwood/`, `/locksmith-antioch/`, `/locksmith-franklin/`, …) are
301'd to `/service-areas/` in `public/.htaccess` and `public/index.php`.
**Do not create city pages.**

### WordPress-era URLs that no longer exist

These appear in Search Console data (the property predates the migration) but
are dead. Do not write tasks against them:

| URL | Status |
|---|---|
| `/locksmith-antioch/` | 301 to `/service-areas/` |
| `/brentwood/` | 301 to `/service-areas/` |
| `/locked-out-in-antioch-heres-what-to-do-before-calling-a-locksmith/` | 301 to `/service-areas/` |
| `/price-list/` | 404 |
| `/key-fob-locked-in-car-nashville/` | 404 |
| `/best-places-to-hide-valuable-in-car/` | 404 |
| `/locksmith-services-for-property-managers-and-landlords/` | 404 |
| `/automotive-transponder-keys/` | 404 |
| `/service-in-your-city/` | 404 |
| `/nashville/` | 404 |
| `/auto-locksmith-immediate-response-nashville/` | 404 |

**Open item:** the 404s above still hold real Search Console traffic.
`/price-list/`, `/key-fob-locked-in-car-nashville/` and
`/best-places-to-hide-valuable-in-car/` together account for roughly 151 clicks
and 29,000 impressions in the Nov 2025 – Sep 2026 window. Nobody has decided
whether to 301 them to the nearest live page or rebuild the content. This is
probably a bigger lever than any remaining CTR work.

---

## 4. Work completed 2026-09-14

### Titles and meta descriptions rewritten (5 pages)

Goal was closing a CTR gap: page-one rankings with far below expected
click-through (e.g. "locksmith" at position 3.5 with 0.9% CTR). Old values are
preserved in `meta-backup-2026-09.md`.

| Route | New title | Chars |
|---|---|---|
| `/` | Locksmith Nashville TN \| Armstrong Locksmith 4.9★ (781+) | 56 |
| `/automotive-locksmith-in-nashville-tn/` | Automotive Locksmith Nashville \| Car Keys & Fobs 4.9★ | 53 |
| `/locksmith-pricing-nashville/` | Locksmith Prices in Nashville \| Honest Upfront Rates | 52 |
| `/bmw/` | BMW Key Replacement Nashville \| BMW Locksmith 4.9★ | 50 |
| `/audi-car-keys/` | Audi Key Replacement Nashville \| Audi Locksmith 4.9★ | 52 |

All meta descriptions are 141–149 characters and interpolate the live review
count. All five verified in live view-source. No hours, URLs, routes or page
content were changed.

### `/scraped_content/` removed

42 HTML files scraped from the old WordPress site during the migration were
sitting in the repo root — and therefore in the public webroot — served at HTTP
200 with `<meta name="robots" content="index, follow">`. They were
near-duplicates of live pages carrying pre-redesign copy and stale hours.
Canonicals pointed at the real URLs, which limited the damage, but nothing
excluded them in `robots.txt`.

Deleted from the repo, and the path now returns 403 via `.htaccess`. Verified:
`/scraped_content/home.html` returns 404.

### Homepage section order (2026-09-07)

The Recent Work gallery was moved above the "Why Nashville Trusts Us" pillar
cards, so photographic proof of real jobs precedes the trust claims. This also
fixed two `bg-slate-50` sections sitting back to back.

### Checked, already compliant — no change needed

- `priceRange` is `"$$"` in the LocalBusiness schema (`Layout.astro:92`).
- Schema `name` and `legalName` are `Armstrong Locksmith Inc`, no period.
- No sitewide title suffix exists, so there is no "Inc." period to strip.
- **Zero 24/7 / after-hours / round-the-clock availability claims anywhere** in
  the deployed site. The redesign already removed them.

---

## 5. Open flags — reported, not acted on

1. **Dead URLs with traffic** — see the table in Section 3. Needs a decision.
2. **Car-unlock overlap:** `/emergency-car-lockout/` and
   `/nashville-car-unlock-service/` target adjacent queries and may be splitting
   rankings. (The other two pages named in the Sept handoff are 404s.)
3. **Sticky footer "OPEN NOW":** `src/components/MobileCallBar.astro` computes
   the label correctly from real hours (MOBILE DISPATCH / OPENS 8:00 AM /
   CLOSED SUNDAY), but the static markup at line 19 reads `OPEN NOW` before the
   script runs — briefly visible on a Sunday.
4. **Hours discrepancy:** the storefront closes at 6 PM but mobile dispatch runs
   to 11:30 PM. Several places state one or the other. The owner was settling
   this separately; hours were left untouched.
5. **Stale tracked bytecode:** `__pycache__/release-booking-page.cpython-312.pyc`
   is gitignored but still tracked, so CI re-commits it on every build. Harmless
   noise; `git rm --cached` would end it.

---

## 6. Writing instructions for this site

- Verify a route is live (`curl -o /dev/null -w "%{http_code}"`) before writing a
  task against it. Search Console data includes URLs killed in the migration.
- Name files and code paths (`src/pages/bmw.astro`), not CMS fields.
- Don't specify a review count, a license number, or any hours — the first is
  automated and the other two are fixed facts above.
- Don't ask for per-city pages.
- Assume commit-and-push deploys to production within about two minutes. There
  is no staging environment.
