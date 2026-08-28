# 🤖 AGENT_HANDOVER.md - Master Context & Project Blueprint

> **To Any AI Agent / Assistant Resuming Work on this Project:**  
> Read this document completely. You are pair programming with the owner of **Armstrong Locksmith Inc**. Follow all design, architecture, and deployment rules below.

---

## 1. Business & Brand Identity
* **Company:** Armstrong Locksmith Inc.
* **Storefront Location:** 208 Thompson Ln, Nashville, TN 37211
* **Owner & Master Tech:** Rahim Ezzadpanah (20+ years automotive & security experience)
* **Phone:** (615) 625-8000
* **Credentials:** TN Locksmith License #406 &bull; Certified & Insured &bull; 4.9 Stars (771+ Google Reviews)
* **Live Domain:** `https://armstronglocksmithinc.com/`

---

## 2. Tech Stack & Engineering Architecture
* **Framework:** [Astro](https://astro.build/) (Static Site Generation / SSG — 44 pages)
* **Styling:** Tailwind CSS v4 (`src/styles/global.css`)
* **Fonts:** Self-Hosted WOFF2 in `/fonts/` (`Montserrat` for headings, `Inter` for body) with zero external DNS latency.
* **Core Web Vitals:** Optimised, not gamed. Deferred GTM, self-hosted preloaded fonts, explicit image dimensions (0 CLS), responsive WebP via <picture>. The old "100/100" depended on hiding GTM from Lighthouse; that gate was removed, so the reported lab score is now lower and honest. Field data (CrUX) is the number that matters.

### ⚠️ Strict Design & Content Constraints:
1. **Language Rule:** All user-facing website text, headings, meta tags, and code comments MUST remain **100% in English**.
2. **Zero-Curve Rule:** Every element site-wide MUST have `border-radius: 0px !important;` (Crisp rectangular industrial styling).
3. **Luxury Showroom Aesthetics:**
   * Cinematic Showroom Background: `.bg-showroom` (Deep Navy with ambient top spotlight)
   * Brushed Gold Foil: `.bg-gold-metallic` & `.text-gold-gradient`
   * Luxury Shadow Cards: `.shadow-luxury-card` with `.hover-lift`
   * Surface: Clean Crisp White `#ffffff` and Light Slate `#f8fafc` / `#f1f5f9`
   * Borders: Solid Hairline Slate `#e2e8f0` / `#cbd5e1`

---

## 3. Deployment & Git Workflow (CRITICAL)
Cloudways serves static files directly from the git root directory. Therefore, **every build must sync `dist/` to the root** before committing:

**`npm run build` already syncs `dist/` to the root.** Do NOT copy it by hand.
The build runs `scripts/sync-to-root.mjs`, which wipes `_astro/` before copying.
That pruning matters: every build emits new content-hashed bundle names, and the
old plain-copy approach only ever added files — 82 files (6.1 MB) had piled up in
`_astro/` of which exactly one was referenced. A manual `Copy-Item`/`cp` step
bypasses nothing and re-teaches the wrong model, so it was removed.

```bash
npm run build          # builds AND syncs to root, pruning stale _astro
git add -A
git commit -m "<descriptive message>"
git push origin main   # push to main is the deploy
```

### How deployment actually works
Pushing to `main` triggers `.github/workflows/deploy.yml`, which SSHes to
Cloudways and runs `git reset --hard origin/main`. **The action does not build.**
Whatever HTML is committed at the repo root is what goes live — so a commit that
touches only `src/` will never reach production. This has bitten the project
before: four commits sat unbuilt and the live site served stale HTML for days.

---

## 4. Key Integration Links & State

| Component / Feature | Active Live Link / Path | Note |
| :--- | :--- | :--- |
| **Website Bookings API** | `POST https://booking.armstronglocksmithinc.com/api/website-bookings` | Direct native form endpoint with honeypot & unix timestamp validation |
| **Live Booking Page** | `/book-online/` (`src/pages/book-online.astro`) | Native luxury 4-field dispatch form |
| **Workiz Backup URL** | `https://online-booking.workiz.com/?ac=ff72b9e4da07483dc3ea20c43712756d0e1998076465f7ea6768e02921635648` | Standalone backup booking link |
| **Verified Google Review Modal** | `https://g.page/r/CQS5BtikwbmqEBM/review` | Universal direct Google review creation link |
| **Verified Google Maps Page** | `https://g.page/r/CQS5BtikwbmqEBM` | Live GBP profile with all 771+ reviews |

---

## 5. Current Task State & Next Steps
* Entire 44-page site uses the luxury showroom aesthetic, self-hosted fonts, and zero-curve styling.
* Both the Hero Quote Form and `/book-online/` post natively to `https://booking.armstronglocksmithinc.com/api/website-bookings`.
* When continuing work: `git pull`, `npm run dev` (preview at `http://localhost:4321`), edit, `npm run build`, commit, push.

---

## 6. Hard-Won Constraints — read before changing these

**Do NOT create per-city landing pages.** The site previously had pages for every
surrounding city (Brentwood, Franklin, Antioch, …) and was penalised in a Google
update. They were deliberately deleted. Local relevance is earned instead through
real job write-ups in `src/content/blog/` — a genuine job, in a named place, with
specifics only this business can supply. That pattern survives updates; templated
city pages did not.

**Business hours are deliberately narrower than service hours.** Storefront is
Mon–Fri 08:00–18:00, Sat 10:00–16:00. Mobile dispatch runs until 23:30 Mon–Fri.
Google Business Profile main hours are set to the *storefront* window on purpose —
Google itself set them, and a walk-in arriving at 21:00 to a dark shop produces
one-star reviews that cost more than evening "Open" visibility is worth. The
JSON-LD `openingHoursSpecification` mirrors GBP exactly; do not "fix" it to 23:30.
Note: GBP's "Online service hours" field already holds 08:00–23:30 but does NOT
drive the Open/Closed label — only main hours do.

**`.htaccess` is inert for static files on this stack.** Nginx serves them
directly and never consults it. The security headers and cache rules defined
there never reach visitors. Anything of that kind must be done in Cloudflare.
The app's Nginx conf dir (`/home/master/applications/btfdkcdpdw/conf/nginx`)
does not exist, so the workflow's Nginx blocks have never applied either.

**Never gate behaviour on the Lighthouse/PageSpeed user agent.** Two scripts used
to skip work when they detected Google's inspection tools, to hold a 100/100
score. That made the lab number describe a page nobody loads, and is the
behaviour Google treats as cloaking. Both gates were removed. Deferring work
(idle callbacks, load-on-interaction) is fine — hiding it from one audience is not.

**Images in `public/` get no build-time processing.** `<Image />` from
`astro:assets` only transforms images imported from `src/`. The site instead uses
`<picture>` with WebP `<source>` variants generated by `scripts/gen-webp.mjs`
(`node scripts/gen-webp.mjs --widths=600,1200 images/foo.jpg`). Aspect ratio is
preserved and `object-cover` does the framing. Originals stay as `<img>` fallbacks.

**Tailwind arbitrary *breakpoint variants* do not compile here.** Arbitrary
*values* are fine (`text-[11px]`, `w-[860px]`, `max-w-[calc(100vw-2rem)]` all
work), but `min-[360px]:inline` and `[@media(min-width:360px)]:inline` are
silently dropped — no error, no rule in the bundle. The class stays in the HTML
and simply does nothing, so a pattern like `hidden min-[360px]:inline` leaves
only `hidden` applying at every width and the element disappears everywhere.
For a custom breakpoint, write plain CSS in `src/styles/global.css` against a
normal class name (see `.arm-menu-label`).

Two habits that follow from this:
* **Verify against the built bundle, not the dev server.** Dev generates classes
  on demand and can show a rule that never reaches production.
  `grep <class> _astro/*.css` after `npm run build`.
* **Lightning CSS rewrites media queries to range syntax.** `@media (max-width:
  359px)` is minified to `@media (width<=359px)`, so grepping the bundle for
  `max-width` finds nothing and looks like a compile failure when it is not.

## 7. Cloudflare configuration (not in this repo)
Zone `armstronglocksmithinc.com`, free plan. Three rules exist and are load-bearing:
* **Cache Rule "Short TTL for robots.txt"** — matches `/robots.txt`, `/llms.txt`,
  `/sitemap-index.xml`, `/sitemap-0.xml`; Edge TTL *ignores* the origin
  cache-control and uses 5 min. Required because the origin sends
  `max-age=31536000` on `.txt`, which once pinned a stale robots.txt at the edge.
* **Response Header Transform "Security headers"** — sets `X-Content-Type-Options`,
  `X-Frame-Options`, `Referrer-Policy` on all requests.
* **Redirect Rule "Trailing slash canonical"** — 301s extension-less, slash-less
  paths to `https://…{path}/` in one hop. Without it the origin emitted an
  HTTPS→HTTP→HTTPS three-hop chain. HSTS is intentionally NOT set yet.
