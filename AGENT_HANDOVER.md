# 🤖 AGENT_HANDOVER.md - Master Context & Project Blueprint

> **To Any AI Agent / Assistant Resuming Work on this Project:**  
> Read this document completely. You are pair programming with the owner of **Armstrong Locksmith Inc**. Follow all design, architecture, and deployment rules below.

---

## 1. Business & Brand Identity
* **Company:** Armstrong Locksmith Inc.
* **Storefront Location:** 208 Thompson Ln, Nashville, TN 37211
* **Owner & Master Tech:** Rahim Ezzadpanah (20+ years automotive & security experience)
* **Phone:** (615) 625-8000
* **Credentials:** TN Locksmith License #406 &bull; Certified & Insured &bull; 4.9 Stars (750+ Google Reviews)
* **Live Domain:** `https://armstronglocksmithinc.com/`

---

## 2. Tech Stack & Engineering Architecture
* **Framework:** [Astro](https://astro.build/) (Static Site Generation / SSG — 44 pages)
* **Styling:** Tailwind CSS v4 (`src/styles/global.css`)
* **Fonts:** Self-Hosted WOFF2 in `/fonts/` (`Montserrat` for headings, `Inter` for body) with zero external DNS latency.
* **Core Web Vitals:** Strict 100/100 compliance (0ms TBT, 0.000 CLS, <1.0s FCP/LCP, async image decoding).

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

### Windows PowerShell Build Command:
```powershell
npm run build
Copy-Item "dist\*" "." -Recurse -Force
git add -A
git commit -m "<descriptive message>"
git push origin main
```

### Mac / Linux Terminal Build Command:
```bash
npm run build
cp -r dist/* .
git add -A
git commit -m "<descriptive message>"
git push origin main
```

---

## 4. Key Integration Links & State

| Component / Feature | Active Live Link / Path | Note |
| :--- | :--- | :--- |
| **Live Booking Page** | `/book-online/` (`src/pages/book-online.astro`) | Active on all "Book Online" CTAs sitewide (hosts `booking.armstronglocksmithinc.com/embed/booking`) |
| **Workiz Backup URL** | `https://online-booking.workiz.com/?ac=ff72b9e4da07483dc3ea20c43712756d0e1998076465f7ea6768e02921635648` | Standalone backup booking link |
| **Verified Google Review Modal** | `https://g.page/r/CQS5BtikwbmqEBM/review` | Universal direct Google review creation link |
| **Verified Google Maps Page** | `https://g.page/r/CQS5BtikwbmqEBM` | Live GBP profile with all 750+ reviews |

---

## 5. Current Task State & Next Steps
* Entire 44-page site is updated with the luxury showroom aesthetic, self-hosted fonts, and zero-curve styling.
* All "Book Online" CTAs sitewide link to `/book-online/`.
* When continuing work from home: run `git pull`, run `npm run dev` to preview at `http://localhost:4321`, make edits, build, sync to root, and push!
