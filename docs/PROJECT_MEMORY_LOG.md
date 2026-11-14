# ARMSTRONG LOCKSMITH INC — MASTER PROJECT MEMORY & HANDOVER LOG

> **FOR ANY AI AGENT STARTING OR RESUMING WORK ON THIS PROJECT:**
> Read [`AGENTS.md`](../AGENTS.md) first (loaded automatically as workspace rules), then read this file completely.
> **Rule (per `AGENTS.md` Section 12):** Whenever you complete a website commit, Google Business Profile update, Search Console check, or external directory edit, you **MUST** record what was done, when, and why in this file so any future agent has 100% continuity.

---

## 1. Current Active Safeguards & Pending Follow-Ups (Check First!)

1. **Homepage, Recently Upgraded Pages & November 2026 Core Update Freeze Rule — DO NOT RE-EDIT / CHURN WHILE SETTLING**:
   - **Active Google November 2026 Core Update (`Started 2026-11-10T18:06:00Z`)**: Google launched a global ~2-week Core Update (`expected completion ~2026-11-24`). Do NOT churn or panic-edit existing ranked pages mid-rollout.
   - **Homepage (`src/pages/index.astro`) — Freeze until at least `2026-11-24`**: Broke onto **Page 1 (`#6.9–#8.1`)** for head terms **`"locksmith"`** (`3 → 20 clicks`, `+567%`), **`"locksmith nashville"`**, and **`"locksmith nashville tn"`.** Do NOT edit title, meta, H1/H2, or body copy.
   - **Service Areas Page (`src/pages/service-areas.astro`) — Freeze until at least `2026-11-24`**: Overhauled on `Oct 31` (`96a21baf`) and updated `Nov 3` (`25d5f9f8`, `0e14ef04`) to target **Brentwood (`37027`), Antioch (`37013`), and Hermitage (`37076`)**, and already quadrupled 7-day impressions (`204 → 829`, `+306%`) and hit `#5.6` in 24h view. Do NOT re-edit `src/pages/service-areas.astro` while Google digests those changes through the Core Update.
   - **Make Pages, Core Service Pages & 14 Blog Posts (`Nov 3–5, 2026` upgrades) — Freeze until at least `2026-11-24`**: All 15 vehicle make pages, 8 core service pages, and 14 blog posts were audited and upgraded between `Nov 3–5, 2026`. Leave them untouched through the 14-day settling window and the November 2026 Core Update rollout.
2. **Setmore Legacy Profile Resolved & Purged from Google Cache (`https://armstronglocksmithinc.setmore.com/`)**:
   - **Status (Nov 4, 2026 — RESOLVED)**: Rahim logged into the `armstronglocksmithinc.setmore.com` account (`armstrongcitation@gmail.com`). Verified **0 customer bookings** ever came into that account.
   - **Fixes Applied Live on Setmore**:
     - Replaced `admin@armstronglocksmithinc.com` with **`locksmith@armstronglocksmithinc.com`**.
     - Fixed Staff & Brand hours from `9:00 AM – 5:00 PM` to **Mon–Fri `8:00 AM – 11:30 PM`, Sat `10:00 AM – 4:00 PM`, Sun `Closed`** (with explicit Storefront vs. Mobile hours in the About description).
     - Changed Website URL to **`https://armstronglocksmithinc.com/book-online/`** and fixed all 4 social links (Facebook `/Armstronglocksmithinc`, Instagram `/armstrong_locksmith_nashville/`, YouTube `/@Armstronglocksmithinc`, TikTok `/@armstronglocksmithinc`).
     - Deleted the 3 fake default placeholder services (`15 Minutes Meeting`, `30 Minutes Meeting`, `1 Hour Meeting`) and added 6 real locksmith services with upfront prices (`$20`, `$65+`, `$85+`, `$95+`, `$145+`, `$180+`).
     - Disabled Setmore's calendar slot booking button (`Book appointment`, `Book class`, `Classes` unchecked) and confirmed `<meta name="robots" content="noindex, nofollow">` is active.
     - Submitted `https://armstronglocksmithinc.setmore.com/` (removed word: `admin@armstronglocksmithinc.com`) to **Google's Outdated Content Removal Tool** — **Approved** on `Nov 5, 2026`!
3. **Apple Business Connect Showcases (`1563086994871944632`)**:
   - 4 approved 30-day Showcases are queued back-to-back from `2026-10-30` through `2027-02-28`.
   - **Next Action**: Remind Rahim by **`2027-02-15`** to queue the next 4 Showcases.
4. **Weekly Google Business Profile (GBP) Update Post Cadence**:
   - Latest GBP Update post published **Nov 13, 2026** (`defiant-keypad-deadbolt-lock-rekey-franklin-tn.jpg` + `franklin-tn-front-door-deadbolt-rekey-move-in-lockbox.jpg`, Kawa's residential move-in lock rekey & matte black Defiant keypad deadbolt job in **Franklin, TN** + `$20/cyl` in-shop rekey / `$0` service call fee at `208 Thompson Ln` + `Learn more` → `https://armstronglocksmithinc.com/nashville-rekey-service/`). Previous GBP Update posts: `Nov 11, 2026` (`/car-key-replacement-nashville/`), `Nov 4, 2026` (`/nashville-rekey-service/`).
   - **GBP Post Content Filter Rule**: **NEVER include a phone number (`(615) 625-8000`) or raw URL inside GBP post body text**—Google's automated post filter immediately flags posts with phone numbers in the body as `"Not approved"`. Always use the native GBP button (`Call now` or `Learn more` / `Book`) instead.
5. **Roadmap Reminder (`Remind Rahim On/After 2026-11-24 / 2026-11-25` Once November 2026 Core Update Finishes Rolling Out)**:
   - **Ranking Lock-In & Revenue Balance Priority (`Added 2026-11-13`)**: On Nov 13 Rahim reported hitting **`#1`** for `locksmith` with core keywords on **Page 1**. Check Google Search Console **first** on `Nov 24`, **never shake `#1–#3` winning pages** with unnecessary copy/title/H1 churn, and focus upgrades on **Residential Lock Rekeying** (*Rahim's #1 current revenue priority*) and **Commercial Storefront** pages plus adding the **OpenAI Measurement Pixel** in `Layout.astro` (on **Gemini 4 Carbon High**).
   - **Eye-Catching Mobile Quote Form Redesign (`375px` Mobile UX & Conversion Upgrade)**:
     - **Trigger Condition**: Check `node "C:\Users\dilar\.gemini\config\skills\armstrong-local-seo\scripts\check-google-algo-updates.mjs"` on or after `2026-11-24`; once the **November 2026 Core Update** status changes from `ACTIVE / IN PROGRESS` to `AVAILABLE` (completed), **proactively remind Rahim** to build and preview the full eye-catching mobile quote form redesign:
       1. `src/pages/index.astro`: Replace the plain washed-out `bg-slate-50` / `border-slate-200` card header with a bold high-contrast Armstrong gold frame (`border-2 border-amber-400`, live green pulse `"Direct to Rahim — Fast Flat-Rate Text Quote"` header, `$0` walk-in / upfront flat-price badge), tighten mobile vertical spacing so all fields + the **`Text Me a Flat Quote →`** button fit cleanly in one phone screen (`375px`), and add an above-the-fold **`Get Text Quote ↓`** mobile jump button (`md:hidden`) — without touching `<h1>`, meta tags, JSON-LD, or `/api/contact.php` payload keys.
       2. `src/pages/contact-armstrong-locksmith.astro`: Use responsive ordering (`order-first lg:order-none`) so the `Send Us A Message` quote form sits *above* the address card and `280px` Google Map iframe on mobile phones (`375px`).
       3. Preview both pages at `375px` width (`http://localhost:4321/` and `http://localhost:4321/contact-armstrong-locksmith/`) before asking Rahim to commit/merge.
6. **GBP `Go Rankers` Boundary Rule (`Do NOT Remove`)**:
   - Rahim intentionally re-added **`Go Rankers`** (`Manager`) on `2026-11-11` strictly for off-page GBP Map Pack link-building, with explicit instructions **not to publish GBP posts** (note: `Go Rankers` does not reply to reviews; the earlier AI review replies came from a Claude browser extension). Never remove `Go Rankers` from GBP `People and access` unless Rahim explicitly asks.

---

## 2. Live Dashboards & External Profile IDs

| Platform | ID / URL | Current Verified State |
| :--- | :--- | :--- |
| **Live Website** | `https://armstronglocksmithinc.com/` | 57 static Astro pages deployed on Cloudways (`btfdkcdpdw`) via GitHub Actions CI (`17b0f33f`) |
| **Google Search Console** | Property `https://armstronglocksmithinc.com/` | 48+ pages indexed, `0` stuck in `Discovered - currently not indexed`; Users verified clean (`admin@armstronglocksmithinc.com` Owner + `Dilara.ezzad@gmail.com` Full; `mustafaonsea1@gmail.com` removed `Nov 11`) |
| **Google Analytics 4** | Account `88040929` &bull; Property `331631713` (`G-MNVMEG2BSG`) | Verified clean (`admin@armstronglocksmithinc.com` Administrator + Google Ads `265-018-5170` link; `0` outside agencies) |
| **Looker Studio SEO Report** | `https://lookerstudio.google.com/reporting/683fc781-983a-4c90-ae4c-cccc28b4a742/page/6zXD` | Live GSC Impressions, Clicks, CTR, Device Breakdown & Top Queries |
| **Google Business Profile** | Profile ID `1996957340716580179` &bull; Place ID `ChIJ17Fz8F9vZIgRBLkG2KTBuao` &bull; CID `12302076770882337028` (`https://g.page/r/CQS5BtikwbmqEBM`) | `4.9 ★ (817 Google Reviews)`, 20 Products (`15 Auto + 5 Commercial`), 50+ structured Services with upfront prices; `16` Service Areas verified (including `Brentwood, TN`); Preferred Booking link `https://booking.armstronglocksmithinc.com/book` + Workiz; `Go Rankers` retained as Manager for Map Pack link-building only (no GBP posts) |
| **GBP Performance** | `https://www.google.com/local/business/1996957340716580179/promote/performance?hl=en` | 3,170 interactions (up from 3,073 on Nov 5) |
| **Apple Business Connect** | Org `1461790501084574208` &bull; Brand `1570815753027878912` &bull; Location `1563086994871944632` (`https://maps.apple.com/place?auid=10423668849052500220`) | Verified; Quick Actions (`Schedule`, `Pricing`, `Quote`) live; 4 Showcases queued `2026-10-30` to `2027-02-28` |
| **Thumbtack Pro Profile** | Service ID `225574154156794931` (`https://www.thumbtack.com/tn/nashville/locksmiths/armstrong-locksmith-inc/service/225574154156794931`) | `5.0 ★ (130+ Verified Reviews)` synced with Google; Background Check Completed; 24 captioned photos; 8 FAQs |
| **Facebook Business Page** | `https://www.facebook.com/Armstronglocksmithinc` | Intro Bio updated (`Nov 4, 2026`) with exact storefront & mobile hours (`not 24/7`), `$0 walk-in service call fee` at `208 Thompson Ln`, and `armstronglocksmithinc.com/book-online/` |
| **Google Ads** | Account `Google Gurantee Main` (CID `265-018-5170`) &bull; Campaign **`Armstrong Locksmith — Designed by Your Google Agent`** (`campaignId=21940022290`) | `$44.00/day` budget; Ad Schedule verified `M–F 7:45 AM–11:30 PM`, `Sat 10:00 AM–4:00 PM`, `Closed Sun`; `Maximize conversions`; `"24 hour locksmith nashville tn"` & `"24 hour key maker"` paused `Nov 12`; `AI Max` OFF; `0` outside agencies |
| **ChatGPT Business Hub** | Profile `bizprof_727112a94bf881a29047cb797e67aea7` | Verified badge; Category `Locksmith`; Verified Email `locksmith@armstronglocksmithinc.com`; Central Time storefront hours; `6` uploaded shop/job photos; `297`-char automotive-first description (plus full residential & commercial, zero hours repeated) |
| **OpenAI Ads Manager** | Ad Account `adacct_6af78ae24e68819a8f394ecc12ae6bcb` (`Armstrong Locksmith Inc`) | `Business advertiser` cleared; `$40.00/day` budget (`Autobid`, `24/7` no native dayparting in OpenAI Beta); `4` themed Ad Groups (`Car Keys`, `Mobile/Lockouts`, `Residential Rekeying`, `Commercial Locks`); `5` `Serving` Ads with photos, service URLs & UTMs; pending Rahim email to `ads-support@openai.com` for `$500` matching credit + OpenAI Pixel (`Nov 24`) |

---

## 3. Search Console Performance Baseline (`Oct 19 – Nov 1, 2026` vs. `Oct 5 – Oct 18, 2026`)

| Metric | Previous 14 Days (`Oct 5 – Oct 18`) | Last 14 Days (`Oct 19 – Nov 1`) | 2-Week Change |
| :--- | :---: | :---: | :---: |
| **Total Search Clicks** | `75` | **`112`** | **`+37 clicks (+49.3%)`** |
| **Total Search Impressions** | `5,530` | **`39,000`** | **`+33,470 (+605% / 7x)`** |
| **Sitewide Average Position** | `#9.7` | **`#2.7`** | **`+7.0 spots higher`** |
| **Pages Indexed in Google** | `42 pages` | **`48 pages`** | **`+6 new pages indexed`** |

### Key Pure-Organic Inner Page Jumps (`Oct 19 – Nov 1` vs. `Oct 5 – Oct 18`)
- `/residential-locksmith-nashville/`: `#15.5` → **`#3.4` (Page 1)** (`+12.1 spots`, 110 impressions)
- `/audi-car-keys/`: `#31.0` → **`#5.0` (Page 1)** (`+26.0 spots`)
- `/mercedes-benz/`: `#25.3` → **`#5.4` (Page 1)** (`+19.9 spots`, 3 clicks)
- `/european-car-key-specialist/`: `#26.0` → **`#5.4` (Page 1)** (`+20.6 spots`, 1 click)
- `/automotive-locksmith-in-nashville-tn/`: `#51.3` → **`#5.5` (Page 1)** (`+45.8 spots`)
- `/audi-q3-key-replacement/`: `#28.0` → **`#6.3` (Page 1)** (`+21.7 spots`)
- `/key-fob-replacement-nashville/`: `#9.0` → **`#8.4` (Page 1)** (258 impressions, 2 clicks)
- `/bmw/`: `#25.6` → **`#8.7` (Page 1)** (`+16.9 spots`, 1 click)
- `/ford-car-key-replacement/`: *Unranked* → **`#9.9` (Page 1)** (434 impressions)
- `/dodge-ram-car-key-replacement/`: *Unranked* → **`#10.0` (Page 1)** (433 impressions, 2 clicks)
- `/honda-car-keys/`: `#24.3` → **`#11.1` (Top of Page 2)** (997 impressions — 13x growth)
- `/locksmith-pricing-nashville/`: `#16.1` → **`#11.2` (Top of Page 2)** (397 impressions, 2 clicks)
- `/kia-car-key-replacement/`: `#12.7` → **`#11.2` (Top of Page 2)** (569 impressions — 10x growth)
- `/toyota-car-key-replacement/`: `#15.4` → **`#11.3` (Top of Page 2)** (1,016 impressions — 14x growth, 3 clicks)
- `/hyundai/`: `#25.5` → **`#11.8` (Top of Page 2)** (584 impressions — 10x growth, 3 clicks)

---

## 4. Chronological Log of Major Website, SEO & Directory Work Completed

### 2026-11-12 to 2026-11-13 — AI Search Audit, Google Ads Cleanup, ChatGPT Business Hub + OpenAI Ads Build, Rules Guardrails & Franklin Residential Rekey Case Study (`cd402b9d` / CI `17b0f33f`)
- **AI Search Citation Audit & Nov 24 Roadmap**:
  - Audited schema/HTML crawl hygiene across `armstronglocksmithinc.com`; queued Wave 1 (`Layout.astro` `@id` graph, nested `<main>`, `faq.astro` Q8 schema, sitemap/llms fixes, OpenAI Measurement Pixel) & Wave 2 (semantic HTML `<table>` comparisons, European price reconciliation, residential/commercial strengthening) for **Nov 24, 2026** (`09:00 AM` Google Calendar event `rml0e56la4m2mjphi3n8mihme4` + in-app reminder `task-1152`) while keeping existing ranked pages untouched during Google's November 2026 Core Update.
  - Added `Gemini 4 Hydrogen (High)` vs `Gemini 4 Carbon (High)` Model Advisor rule (`AGENTS.md` commit `eec29c42`, global `GEMINI.md`, and `armstrong-local-seo/SKILL.md`), plus the standing **Google Policy & SEO Protection Guardrail** in global `GEMINI.md` and `#1` Ranking Lock-In Rule + Residential Rekeying priority in the Nov 24 checklist.
- **Google Ads Updates (`CID 265-018-5170`, Campaign `21940022290`)**:
  - Paused `"24 hour locksmith nashville tn"` and `"24 hour key maker"` (`Nov 12`) so ad spend stays compliant with actual shop/mobile hours (`no 24/7`).
  - Audited live **Ad schedule** (`Nov 13`): verified **`M–F 7:45 AM–11:30 PM`**, **`Sat 10:00 AM–4:00 PM`**, **`Closed Sunday`**.
- **ChatGPT Business Hub & OpenAI Ads Manager (`Nov 13`)**:
  - **ChatGPT Business Hub (`bizprof_727112a94bf881a29047cb797e67aea7`)**: Set `Locksmith` category, `America/Chicago` timezone + storefront hours, verified `locksmith@armstronglocksmithinc.com` (`019910`), uploaded `6` real shop/job photos, and saved a `297`-char automotive-first description (with full residential & commercial coverage, zero repeated hours).
  - **OpenAI Ads Manager (`adacct_6af78ae24e68819a8f394ecc12ae6bcb`)**: Audited live campaign; cleared `Business advertiser` verification; lowered daily budget `$100.00/day -> $40.00/day`; configured campaign UTM tracking (`utm_source=chatgpt&utm_medium=cpc&utm_campaign={campaign_id}&utm_adgroup={ad_group_id}&utm_content={ad_id}`); expanded from 1 mismatched commercial ad into **`4` themed Ad Groups** and **`5` `Serving` Ads** with authentic photos pointing to `/car-key-replacement-nashville/`, `/european-car-key-specialist/`, `/nashville-car-unlock-service/`, `/nashville-rekey-service/`, and `/commercial-locksmith/`. Confirmed OpenAI Beta has no native dayparting yet and noted `ads-support@openai.com` follow-up for the `$500` matching ad credit.
- **Franklin, TN Residential Lock Rekey & Defiant Keypad Case Study + GBP Update (`cd402b9d` / CI `17b0f33f`, `Nov 13`)**:
  - Created [`src/content/blog/franklin-tn-lock-rekey-keypad-deadbolt-installation.md`](../src/content/blog/franklin-tn-lock-rekey-keypad-deadbolt-installation.md) and 8 responsive `.jpg`/`.webp` variants in `public/images/` featuring Kawa's real Franklin, TN move-in handleset/deadbolt rekey & matte black Defiant electronic keypad deadbolt job (with zero customer street address).
  - Verified `npx astro build` (`57` pages), `tests/booking-photo.test.mjs` (`4/4` pass), and `375px` mobile preview before merging to `main` (`cd402b9d` -> live CI `17b0f33f`, `HTTP 200`).
  - Published matching **Google Business Profile Update** (`Learn more` → `/nashville-rekey-service/`, both Franklin photos, zero phone number in body) and submitted the new blog URL via **IndexNow** (`HTTP 200` on Bing & IndexNow). *(Note: Google Search Console `Request indexing` hit Google's reCAPTCHA dialog — paused cleanly per policy rules for Rahim to trigger manually or allow `sitemap-0.xml` discovery.)*

### 2026-11-12 — GBP Service Areas Verification (`16` Suburbs Including `Brentwood, TN`) & 2 New 5-Star Human Review Replies (`817` Reviews)
- **GBP Location & Service Areas Read-Only Audit (`1996957340716580179`)**:
  - Confirmed `208 Thompson Lane, Nashville, TN 37211` storefront address visibility is **ON** (`Show business address to customers`).
  - Verified all **`16` active GBP Service Areas** are cleanly configured with **`Brentwood, TN, USA` confirmed present (#3)**: `Franklin, TN`, `Oak Hill, TN`, `Brentwood, TN`, `Nashville, TN`, `Berry Hill, TN`, `Belle Meade, TN`, `Nolensville, TN`, `Forest Hills, TN`, `Antioch, TN 37013`, `Madison`, `Bellevue`, `Donelson`, `Goodlettsville, TN 37072`, `Hermitage`, `Green Hills`, and `Old Hickory, TN 37138`.
  - Confirmed profile climbed to **`817 Google reviews (4.9 ★)`** (+2 since Nov 11) and **`3,170 customer interactions`** (+4 since Nov 11), with `Thumbtack 5/5` and `Facebook 4.9/5` displaying live on the Google Knowledge Panel.
  - Confirmed `Nov 11` Toyota Highlander Hybrid photo post remains **#1** at the top of `Your posts` (`0` blank agency posts overnight).
- **2 Brand-New 5-Star Google Reviews Replied Live (`Ashley Burns` & `David Caruso`)**:
  - Drafted, got Rahim's approval, and published warm first-person human replies to:
    1. **Ashley Burns** (`5.0 ★`, community trust review) — first-person neighborhood shop reply signed by Rahim.
    2. **David Caruso** (`5.0 ★`, `Great price · $250–300`, lost **Toyota 4Runner** key fob replaced, programmed on the spot + backup blade cut) — specific first-person reply thanking him and referencing his Toyota 4Runner fob & backup key, signed by Rahim.

### 2026-11-11 — 5-Day GSC Progress Check, November 2026 Core Update Safeguard, Legacy Booking 301 Redirect (`3cf1b040` / CI `9d988bf0`) & GBP Weekly Update Post
- **Google Algorithm Check (`status.search.google.com`)**:
  - Detected the **November 2026 Core Update** (`started 2026-11-10T18:06:00Z`, ~2-week global rollout through `~2026-11-24`). Extended existing-page anti-churn freeze windows through `2026-11-24` so Google evaluates our `Nov 3–5` sitewide E-E-A-T, schema, and internal-linking upgrades without mid-rollout edits.
- **5-Day Google Search Console Progress (`Nov 10` official export vs. `Nov 5` snapshot)**:
  - **3-Month Totals**: Climbed from `701 clicks / 82K impressions` to **`785 clicks (+84 clicks in 5 days) / 88,103 impressions (+6,103)`**.
  - **Mobile Real-Customer Surge (`Last 7d` vs. `Prev 7d`)**: Mobile clicks rose `21 → 23 (+9.5%)` and Mobile impressions surged **`726 → 2,055 (+183% / 2.8x)`**.
  - **First Rich Pricing Snippets**: Recorded first **`6 Product snippet` impressions** from the `Nov 4` `AggregateOffer` schema upgrades.
  - **Pure-Organic Inner Pages (`46 pages`)**: Impressions rose **`+30.4%` (`5,074 → 6,615`, `15 clicks`)**, led by `/service-areas/` (`204 → 829 imp`, `+306%`, and `#5.6` with `1 click` in 24h view; `locksmith brentwood tn` entered Page 2 at `#17.0`, `locksmith antioch tn` climbed `#23.0 → #17.5`, `locksmith east nashville` hit `#10.0`), `/residential-locksmith-nashville/` (`#2.0`), `/nashville-car-unlock-service/` (`#2.5`), `/key-fob-replacement-nashville/` (`#2.6`, `1 click`), `/contact-armstrong-locksmith/` (`#20.9 → #2.9`, `1 click`), `/faq/` (`#20.0 → #9.6`, `1 click`), `/ford-car-key-replacement/` (`434 → 680 imp`, `+57%`, `2 clicks`), `/dodge-ram-car-key-replacement/` (`433 → 673 imp`, `+55%`), `/acura-car-key-replacement/` (`#20.6 → #10.8`, `7 → 40 imp`), `/land-rover-key-replacement-.../` (`#54.0 → #28.8`, `2 clicks`, `#1.0` in 24h view), and `/blog/toyota-highlander-hybrid-all-keys-lost-smart-key-nashville/` (earned its **first organic click** within 6 days of publishing).
  - **Map Pack (`Position 1.0–3.0` on `/`)**: `49 clicks`, `21,474 impressions`, Avg Position `#2.4`, with `armstrong locksmith` clicks up **`+167%` (`6 → 16 clicks`)** and `#1.0` placements on `local locksmith to program key fob` (`1 click`), `copy car keys` (`39 imp`), `car keys` (`37 imp`), `copy keys` (`11 imp`), `key fob replacement near me` (`5 imp`), `locksmith in antioch tn` (`3 imp`), `locksmith brentwood` (`2 imp`), and `locksmith franklin tn` (`1 imp`).
- **GBP Bookings Inspection (`1996957340716580179`) & Legacy `/request-locksmith-service-online/` `301` Fix (`3cf1b040` / CI `9d988bf0`)**:
  - Verified read-only in GBP (`1996957340716580179`, now at **`815 Google reviews`** and **`3,166 interactions`**) that the live Bookings panel is clean: Featured provider **Workiz** and Preferred custom link **`https://booking.armstronglocksmithinc.com/book`** (left 100% untouched per AI Dispatch isolation rules), plus canonical Website `https://armstronglocksmithinc.com/`.
  - Added `'request-locksmith-service-online' => '/book-online/'` in `public/index.php` and `RewriteRule ^request-locksmith-service-online/?$ /book-online/ [L,R=301,NC]` in `public/.htaccess` (`3cf1b040`, deployed via CI `9d988bf0`) so cached Google Reserve / Local Services (`?gsas=1&ahbb=1&rwg_token=...`) clicks on the retired WordPress booking slug `301`-redirect cleanly to `/book-online/`. Submitted `/book-online/` and `/request-locksmith-service-online/` to Bing & IndexNow (`HTTP 200`).
- **Google Business Profile Weekly Update Post, Agency Access Cleanup & Human Review Replies (`1996957340716580179`)**:
  - Published `1,323`-character GBP Update post featuring `toyota-highlander-hybrid-smart-key-replacement-nashville.jpg` (Davidson County Toyota Highlander Hybrid Limited all-keys-lost job), `$95+`/`$120+`/`$145+`/`$180+` key tiers across American, Asian & European brands, `$0` walk-in service call fee at `208 Thompson Ln`, exact shop & mobile van hours, and `Learn more` CTA → `https://armstronglocksmithinc.com/car-key-replacement-nashville/` (verified **`Published`** and live at the top of `Your posts`).
  - **Removed Ex-Agency `Go Rankers` & Cleaned Top Updates Carousel**: Identified agency **`Go Rankers`** (`Manager`, Business Profile group ID `106819869461023726473`) in `Business Profile settings -> People and access` as the source of automated photo-less 3rd-person AI blurbs every 2–3 days. Removed `Go Rankers` from `People and access` (preserving 100% of Rahim's 3 owner/family accounts: `admin@armstronglocksmithinc.com`, `dilara.ezzad@gmail.com`, `roya.ezzad@gmail.com`) and removed the recent ~2 months of top-of-feed blank text blurbs so the visible GBP carousel shows 100% real shop & field job photos (older 3+ month archive intentionally left intact).
  - **Replaced 10 AI-Sounding Google Review Replies with Warm First-Person Human Replies**: Audited the 10 newest Google reviews (`Dariusz Wyrzykowski`, `Sydney Ware`, `Jason Santos`, `Noor Ibrahim`, `Shihabeldin Abdoun`, `Gino`, `Lisa`, `Keith Roland`, `Ohio Local Guide-Moe`, `Blake Schmitt`), caught an AI batch that had called Rahim `"Ravi"` (#1) and `"Raheem"` (#8) in the 3rd person with 9x `"Thanks X, glad..."` repetition, and updated all 10 live with Rahim-approved warm first-person human replies.
- **Google Ads 6-Day Security & Performance Audit + Un-Choke Optimization (`Google Gurantee Main`, CID `265-018-5170`, Campaign `21940022290`, `$44.00/day` budget untouched)**:
  - **Access & Security Verified Clean**: Checked `Admin -> Access and security` (`Users` + `Managers` tabs); confirmed `0` Manager (MCC) accounts and confirmed neither `Go Rankers` nor `Mostafiz` has access (only `admin@armstronglocksmithinc.com` and `roya.ezzad@gmail.com`).
  - **6-Day Diagnosis (`Nov 5 – Nov 11` vs. `Oct 29 – Nov 4`)**: `$0.00` spent (`20` impressions, `0` clicks, `0` conversions) because Google flagged the campaign `Eligible (Limited) — Limited by search volume, Missing enough relevant keywords` after pausing Broad Match `mobile locksmith near me for cars` (`"mobile locksmith near me for cars"` got `0` impressions), and the `$25.00` Target CPA cold-start cap choked bids below Nashville's top-of-page floor.
  - **Safe Un-Choke Fixes Applied Live (`Nov 11, 2026`)**:
    1. Kept **`$44.00/day` daily budget** and custom goal **`Armstrong Qualified Calls + Forms`** 100% untouched.
    2. Unchecked the `$25.00` Target CPA cap so the campaign runs pure **`Maximize conversions`** (verified no ad-group Target CPA caps on `Locksmith` or `Emergency Locksmith Nashville`) until ~15 conversions accumulate.
    3. Added **15 Phrase & Exact match keywords (`0` Broad Match!)** to `Locksmith` (`33 → 48` enabled keywords: `46` Phrase, `2` Exact, `0` Broad Match): `"car key replacement"`, `[car key replacement nashville]`, `"key fob replacement"`, `[key fob replacement nashville]`, `"car key programming"`, `"lost car keys locksmith"`, `"toyota key replacement"`, `"honda key replacement"`, `"ford key replacement"`, `"bmw key replacement"`, `"mercedes key replacement"`, `"dodge key replacement"`, `"jeep key replacement"`, `"lock rekey nashville"`, `"commercial locksmith nashville"` (submitted with Local Services policy exception, `Pending - Under review`).
    4. Added 2 competitor Phrase match negative keywords: **`"west end lock"`** and **`"615 locksmith"`** (`58 → 60` campaign negative keywords).
    5. Renamed Campaign `21940022290` from `Dilara New Search Campaign by Mostafiz` to **`Armstrong Locksmith — Designed by Your Google Agent`** (`$44.00/day` budget and `Maximize conversions` verified untouched).
- **Full Marketing Access Audit, Search Console Cleanup & Monday 8:00 AM Health Check (`Nov 11, 2026`)**:
  - **Google Search Console (`https://armstronglocksmithinc.com/`)**: Caught unauthorized ex-freelancer **`mustafaonsea1@gmail.com`** (`Full` access) and removed them live (`0` unused ownership tokens; only `admin@armstronglocksmithinc.com` Owner + `Dilara.ezzad@gmail.com` Full remain).
  - **Google Analytics 4 (`G-MNVMEG2BSG`, Property `331631713`)**: Audited both Account and Property Access Management read-only — verified 100% clean (`admin@armstronglocksmithinc.com` Administrator + Google Ads `265-018-5170` link; `0` outside agencies).
  - **GBP `Go Rankers` Note**: Rahim re-added `Go Rankers` (`Manager`) strictly for off-page GBP Map Pack link-building with explicit instructions not to publish GBP posts (the earlier AI review replies came from a Claude browser extension, now replaced with 10 human replies).
  - **Monday 8:00 AM Nashville (`America/Chicago`) Read-Only Health Check (`weekly-seo-ads-audit`)**: Configured `Weekly SEO & Google Ads Audit` scheduled automation for every Monday at `8:00 AM America/Chicago` to run a 100% read-only check on Google Algorithm updates, roadmap reminders (including the post-`Nov 24` eye-catching Mobile Quote Form redesign), `booking-photo` tests, and conversion tags.

### 2026-11-05 — Google Ads 12-Day Review & Optimization + 13 Blog Posts SEO Upgrade (`45d04c13`) + Setmore Cache Purge Approved
- **Google Ads 12-Day Audit & 3 Optimizations Applied (`Google Gurantee Main`, CID `265-018-5170`, Campaign `21940022290`, `$44.00/day` budget untouched)**:
  - **12-Day Performance (`Oct 25 – Nov 5` vs. `Oct 13 – Oct 24`)**: Spend dropped `30.3%` (`$105.20` → `$73.38`), CTR rose from `4.34%` → `5.45%`, and the Oct 25 website conversion tag fix (`AW-10794342610`) cleared the old `"Misconfigured"` error and recorded **`3.00` `Call Click Form Website` (`aioDCNO37OwZENKxkpso`) calls** + **`1.00` `Submit Lead Form website` (`ZSBoCKzJ-uwZENKxkpso`) lead** + **`1.00` direct ad call** (`5` total leads at `$14.68/lead`).
  - **Fix 1 (Conversion Actions & Custom Goal)**: Promoted **`Call Click Form Website` (`ctId=6939155411`)** from `Secondary` to **`Primary action`** and added it to the campaign's custom goal **`Armstrong Qualified Calls + Forms`** (now `3 actions`: `Calls from ads`, `Call Click Form Website`, `Submit Lead Form website`). Demoted duplicate **`GA4 (web) contact_form_submission` (`ctId=7815489756`)** from `Primary` to **`Secondary`** so 1 form lead counts once instead of twice.
  - **Fix 2 (Keywords & Negatives)**: Paused the remaining Broad Match keyword **`mobile locksmith near me for cars`** in the `Locksmith` ad group (which spent `$34.01` with `0` conversions) and replaced it with **Phrase match `"mobile locksmith near me for cars"`**. Added campaign-level negative keywords **`"batteries plus"`** (Phrase match) and **`[car keys]`** (Exact match) (`56` → **`58` negative keywords**).
  - **Fix 3 (Target CPA Un-Throttled)**: Updated Target CPA from `$18.00` to **`$25.00`** at the Campaign level and on both active Ad Groups (`Locksmith` and `Emergency Locksmith Nashville`) so `$4.89` Avg. CPC Nashville car key clicks are not choked out of top-of-page auctions. Verified **`$44.00/day` daily budget untouched**.
- **Google Search Console Priority Re-Indexing (`Nov 5, 2026`)**:
  - Confirmed yesterday's new `/blog/toyota-highlander-hybrid-all-keys-lost-smart-key-nashville/` post is already **`URL is on Google` (`Page is indexed`)** with valid `Breadcrumbs` and `Review snippets`.
  - Submitted priority `Request indexing` in GSC for the 4 top upgraded blog posts (`BMW X5 Brentwood`, `Audi Donelson/Hermitage/BNA`, `Home Rekey vs Lock Replacement Brentwood`, and `Cold Weather Key Fob`).
- **Setmore Outdated Content Removal Approved (`Nov 5, 2026`)**:
  - Confirmed in Google Search Console Outdated Content Removal tool that `https://armstronglocksmithinc.setmore.com/` (`admin@armstronglocksmithinc.com`) moved from `Pending` to **`Approved`** overnight, purging the stale cached Setmore snippet from Google Search and AI Overviews.
- **Morning Google Search Console Snapshot (`Nov 5, 2026`)**:
  - **3-Month Total (`Aug 4 – Nov 3, 2026`)**: `701 Clicks`, `82K Impressions`, `5.5 Avg Position`.
  - **Last 7 Days (`Oct 28 – Nov 3, 2026`)**: `73 Clicks`, `37.1K Impressions`, `2.0 Avg Position` (`Nov 3` hit `17 clicks` and `5,243 impressions`).
  - **Last 24 Hours (`Nov 4 – Nov 5` Hourly)**: `12 Clicks`, `1.56K Impressions`, `4.3 Avg Position`, with clicks on `/`, `/toyota-car-key-replacement/`, `/locksmith-pricing-nashville/`, `/key-fob-replacement-nashville/`, `/contact-armstrong-locksmith/`, `/faq/`, and `/land-rover-key-replacement-in-nashville-tn-armstrong-locksmith-inc/`.
  - **Page 1 Inner-Page Moves (Last 7 Days)**: `/hyundai/` jumped from `#11.8` onto **Page 1 at `#8.4`**; `/mercedes-benz/` at **`#4.9`** (`3 clicks`); `/ford-car-key-replacement/` at **`#9.9`**; `/dodge-ram-car-key-replacement/` at **`#10.0`**; `/toyota-car-key-replacement/` at **`#10.2`** (`542 impressions`, `3 clicks`).
- **Audited & Upgraded All 13 Blog Posts + Blog Template (`45d04c13`)**:
  - Updated `src/pages/blog/[...slug].astro` so the **"Related Locksmith Articles"** section sorts by matching `category` first and newest `pubDate` second (instead of alphabetical filename order).
  - Audited and updated all 13 existing blog posts in `src/content/blog/`: removed banned word *"mobile workstation"* in the BMW X5 post, fixed glued-together neighborhood formatting and raw `&amp;` in the Audi Donelson/Hermitage/BNA post, removed duplicate author lines/bios, and added contextual internal links across all 13 posts to canonical make and service pages (`/bmw/`, `/mercedes-benz/`, `/audi-car-keys/`, `/audi-q3-key-replacement/`, `/land-rover-key-replacement-in-nashville-tn-armstrong-locksmith-inc/`, `/bentley-key-replacement/`, `/dodge-ram-car-key-replacement/`, `/honda-car-keys/`, `/acura-car-key-replacement/`, `/european-car-key-specialist/`, `/dealer-key/`, `/key-fob-replacement-nashville/`, `/car-key-replacement-nashville/`, `/nashville-rekey-service/`, `/residential-locksmith-nashville/`, `/commercial-locksmith/`, `/nashville-locksmith-storefront/`, `/locksmith-pricing-nashville/`, `/service-areas/`, `/google-reviews/`, `/about-us/`).
  - Submitted all 15 blog URLs (`/blog/` index + all 14 blog posts) to **Bing IndexNow** (`www.bing.com/indexnow` and `api.indexnow.org` → `HTTP 200 OK`).

### 2026-11-04 — New Toyota Highlander Hybrid Limited Blog Case Study (`feat/blog-toyota-highlander-hybrid-smart-key-nashville`)
- **Created `src/content/blog/toyota-highlander-hybrid-all-keys-lost-smart-key-nashville.md`** (`/blog/toyota-highlander-hybrid-all-keys-lost-smart-key-nashville/`):
  - Full Nashville case study using Rahim's real field photo (`/images/toyota-highlander-hybrid-smart-key-replacement-nashville.jpg`) and verbatim job breakdown: damage-free unlock, cutting the mechanical `TOY48` laser door key to turn off the factory alarm, resetting the immobilizer, programming a new proximity smart key, and deleting lost keys at no extra charge.
  - Added the Toyota Highlander Hybrid Limited job to `src/components/RecentWorkGallery.astro` and linked `<RelatedPost>` on `/toyota-car-key-replacement/`, `/car-key-replacement-nashville/`, `/dealer-key/`, and `/automotive-locksmith-in-nashville-tn/` to `/blog/toyota-highlander-hybrid-all-keys-lost-smart-key-nashville/`.

### 2026-11-04 — Core Service Pages Audit & 8-Page E-E-A-T / FAQ / Schema Upgrade (`63407366` / CI `6d84600c`)
- **Audited all Core Service Pages in `src/pages/` and upgraded 8 core pages**:
  1. `src/pages/dealer-key.astro`: Added missing `<Layout image="/images/ARMSTRONG-LOCKSMITH-KEY-MACHINES-INSIDE-THE-SHOP.jpg">` (so every page on the site now has a social/search preview thumbnail), added a 3-photo **Dealer-Level Key Jobs Completed In-House** showcase (`2022-ford-escape-smart-key-all-keys-lost-nashville-900.webp`, `toyota-highlander-hybrid-smart-key-replacement-nashville-900.webp`, and `rahim-programming-mercedes-benz-key-on-site-nashville-900.webp`), expanded `FAQPage` JSON-LD schema from 2 to 4 questions and added the matching **4-card visible FAQ section**, added `AggregateOffer` schema (`$95–$425`), and added `<RelatedPost>` linking to `/blog/2022-ford-escape-all-keys-lost-smart-key-nashville/`.
  2. `src/pages/european-car-key-specialist.astro`: Converted the `"European Brands We Service Daily"` grid into clickable internal links (`/bmw/`, `/mercedes-benz/`, `/audi-car-keys/`, `/volvo-car-key-replacement/`, `/land-rover-key-replacement-in-nashville-tn-armstrong-locksmith-inc/`, `/bentley-key-replacement/`), explicitly added **Ferrari, Lamborghini, Rolls-Royce, and Maserati** in the exotic brand cards, synced all 4 FAQ questions across both `FAQPage` JSON-LD schema and the 4 visible FAQ cards, added `AggregateOffer` schema (`$180–$650`), and added `<ServiceReviews service="auto" label="European Car Key" />`.
  3. `src/pages/car-key-replacement-nashville.astro`: Added **Grand Highlander** to the Toyota & Lexus line and **Maserati, Ferrari, Lamborghini, Rolls-Royce** to the European exotic line, added the missing **4-card visible FAQ section** matching all 4 `FAQPage` JSON-LD schema questions, and added `<ServiceReviews service="auto" label="Car Key Replacement" />` + `<RelatedPost>`.
  4. `src/pages/key-fob-replacement-nashville.astro`: Expanded `FAQPage` JSON-LD schema from 2 to 4 questions (`$120+` remote head / `$145+` smart fob / `$180+` European fob pricing, erasing lost fobs at no extra charge, `$0 service call fee` at `208 Thompson Ln`, and American/Asian/European brand coverage), added the matching **4-card visible FAQ section**, and added `AggregateOffer` schema (`$120–$325`).
  5. `src/pages/automotive-locksmith-in-nashville-tn.astro`: Expanded `FAQPage` JSON-LD schema from 2 to 4 questions, added the missing **4-card visible FAQ section**, added `AggregateOffer` schema (`$65–$425`), and added `<ServiceReviews service="auto" label="Automotive" />` + `<RelatedPost>` linking to `/blog/2012-honda-civic-ignition-switch-repair-door-lock-cylinder-nashville/`.
  6. `src/pages/emergency-car-lockout.astro`: Expanded `FAQPage` JSON-LD schema from 2 to 4 questions (`$65+` standard lockout, `$85+` German Lishi European deadlock picking, damage-free entry, and on-site lost key replacement), added the matching **4-card visible FAQ section**, and added `AggregateOffer` schema (`$65–$125`).
  7. `src/pages/nashville-locksmith-storefront.astro`: Expanded `FAQPage` JSON-LD schema from 2 to 4 questions (`208 Thompson Ln` Woodbine location & hours, `$0 service call fee` + `$20/cylinder` walk-in rekeying, walk-in policy, and free front-door parking lot), added the matching **4-card visible FAQ section**, added `AggregateOffer` schema (`$20–$325`), and added `<ServiceReviews service="all" label="Storefront & Key Cutting" />`.
  8. `src/pages/commercial-locksmith.astro`: Added a 4th FAQ question (`Can you install keypad locks with employee PIN codes on commercial doors?` covering **Alarm Lock Trilogy DL1200/DL2700**, **Dormakaba E-Plex 2000**, and **Yale** commercial keypad levers) to both `FAQPage` JSON-LD schema and the visible FAQ section.

### 2026-11-04 — Vehicle Make Pages Audit & 10-Page Upgrade + Verbatim Toyota Story + GBP Weekly Update Post
- **Website Commits `49e76061` (CI `16ac73bf`) & `027aba8f` (CI `e26a219c`)**:
  - Audited all 15 vehicle make pages in `src/pages/` and upgraded 10 pages:
    1. `src/pages/toyota-car-key-replacement.astro` + `public/images/toyota-highlander-hybrid-smart-key-replacement-nashville*` (`.jpg`, `-600.webp`, `-900.webp`, `-1200.webp`): Added Rahim's real **Toyota Highlander Hybrid Limited** Davidson County field photo (with the Armstrong Locksmith mobile van in the background), added **Toyota Grand Highlander** to the model grid, and displayed the 208 Thompson Ln shop photo (`toyota-chr-and-audi-spare-key-fobs-brentwood-nashville-900.webp`) and German Lishi decoder photo (`TOYOTA-LISHI-TOOL-900.webp`).
    2. `src/pages/dodge-ram-car-key-replacement.astro`: Added the missing 4-card visible FAQ section matching all 4 `FAQPage` JSON-LD schema questions (broken FOBIK rubber buttons, 2018–2026 SGW smart fobs, `$120–$295` pricing vs. dealer, and RAM ProMaster SIP22 cargo van keys).
    3. `src/pages/acura-car-key-replacement.astro`: Added real `2022-acura-mdx-smart-remote-driver-2-no-emergency-key-43.webp` photo card, upfront **Acura MDX Smart Key Replacement** pricing (`$285` in-shop at `208 Thompson Ln` with `$0 service call fee` / `$325` mobile service), `AggregateOffer` schema (`$120–$325`), fixed `"on-site on-site"` typo, and synced 4 FAQ cards with `FAQPage` schema.
    4. `src/pages/audi-q3-key-replacement.astro`: Fixed all 5 `"Audi Q3 Q3"` duplicated trim card titles (`Audi Q3 Premium`, `Premium Plus`, `S-Line`, `Prestige`, `Quattro`), added 2 real Audi photos (`rahim-made-audi-keys-in-nashville-1-900.webp` and `2023-audi-q7-emergency-laser-key-cutting-nashville-900.webp`), added `AggregateOffer` schema (`$180–$385`), and synced 4 FAQ cards with `FAQPage` schema.
    5. `src/pages/bentley-key-replacement.astro`: Added `<Layout image>`, displayed real `Maserati-key-cut-and-programmed--scaled-900.webp` job photo, added **Maserati, Ferrari, Lamborghini, Rolls-Royce, and Porsche** exotic coverage + `AggregateOffer` schema (`$250–$650`), linked `/blog/maserati-granturismo-key-replacement-nashville/` via `<RelatedPost>`, and expanded visible FAQs from 1 to 4 cards matching `FAQPage` schema.
    6. `src/pages/mercedes-benz.astro`: Displayed `mercedes-Benz_20250627_002342_0000-1-scaled-900.webp` on-page and added the 2 missing visible FAQ cards (EIS/ESL steering lock repair and `$225–$425` pricing) so all 4 `FAQPage` schema questions are visible.
    7. `src/pages/honda-car-keys.astro` & `src/pages/kia-car-key-replacement.astro`: Expanded visible FAQs from 1 card to 4 cards each (matching `FAQPage` schema) and added `AggregateOffer` pricing schema (`$120–$245` for Honda, `$120–$265` for Kia).
    8. `src/pages/hyundai.astro`: Fixed `"on-site on-site"` typo in `FAQPage` schema.
    9. `src/pages/bmw.astro`: Added real Brentwood/Nashville BMW job photo card (`Bmw-key-replacement-in-Brentwood-tn_20250629_163626_0000-900.webp`) linking to `/blog/how-we-saved-a-customer-over-600-on-a-bmw-key-replacement-in-brentwood-tn/`.
- **Google Business Profile Weekly Update Post (`1996957340716580179`)**:
  - Published live GBP Update post featuring `adams-rite-mortise-deadlatch-push-paddle-nashville-37211.jpg`, `$20/cylinder` walk-in rekeying at `208 Thompson Ln` (`$0 service call fee`), mobile residential/commercial rekeying, home warranty rekey receipts, and exact shop & mobile hours with `Learn more` CTA → `https://armstronglocksmithinc.com/nashville-rekey-service/`.
- **Setmore Takedown Escalation**:
  - Rahim uploaded `Armstrong locksmith shop.png` into Setmore support chat; agent **Itbah** escalated deletion of `https://armstronglocksmithinc.setmore.com/` to Setmore's internal team.

### 2026-11-04 — Google AI Overview Entity Corrections & Facebook Page Bio Update
- **Website Commit `87e903fa` (CI build `6f213619`)**:
  - Updated `src/layouts/Layout.astro`, `src/pages/about-us.astro`, `src/pages/contact-armstrong-locksmith.astro`, `src/pages/faq.astro`, `src/pages/service-areas.astro`, and `public/llms.txt`:
    - Replaced wrong Google Maps CIDs with the real decoded CID (`https://www.google.com/maps?cid=12302076770882337028` / `https://g.page/r/CQS5BtikwbmqEBM`).
    - Fixed social `sameAs` URLs (YouTube `@Armstronglocksmithinc`, TikTok `@armstronglocksmithinc`, Instagram `armstrong_locksmith_nashville`, Thumbtack Pro profile).
    - Added `ReserveAction` (`https://armstronglocksmithinc.com/book-online/`) to override third-party Setmore booking links.
    - Added explicit structured & visible facts on `/about-us/`, `/contact-armstrong-locksmith/`, `/faq/`, and `/llms.txt` clarifying exact hours (**not 24/7**), official email (`locksmith@armstronglocksmithinc.com`), official booking URL (`https://armstronglocksmithinc.com/book-online/`), and exotic vehicle coverage (`Ferrari, Lamborghini, Rolls-Royce, Maserati, Alfa Romeo`).
- **Facebook Page (`facebook.com/Armstronglocksmithinc`)**:
  - Updated Intro Bio (`244/255` chars) to remove any outdated `24/7` wording and state exact shop (`Mon–Fri 8am–6pm, Sat 10am–4pm`) and mobile van (`Mon–Fri 8am–11:30pm`) hours, `$0 walk-in service call fee` at `208 Thompson Ln`, and `(615) 625-8000`.

### 2026-11-03 — GBP Full Service & Description Overhaul + Inner-Page SEO Upgrades
- **Google Business Profile (`1996957340716580179`)**:
  - Updated 750-char GBP Description (`744/750` chars, zero wasted characters on hours/phone/URL).
  - Enabled `Has wheelchair accessible parking lot = Yes`.
  - Deleted `29` junk/broken WordPress/defunct-brand service items, fixed `10` service descriptions, populated all `3` empty GBP categories (`Locks supplier`, `Laser cutting service`, `Auto electrical service`), and populated `26` vehicle make & exotic services with upfront `From $...` prices and descriptions.
- **Website Commits `25d5f9f8` & `0e14ef04`**:
  - Added sitewide **"Vehicle Key Replacement by Brand (Walk-In Shop & Mobile Vans)"** link bar in `src/components/Footer.astro`.
  - Upgraded `/nashville-rekey-service/` (real rekey photos, lock brands, Home Warranty Rekey Receipts, visible FAQs) and `/hummer-key-replacement-nashville/` (full Chevrolet, GMC, Cadillac & Hummer H1/H2/H3 coverage, real photos, visible FAQs).
  - Added missing `<Layout image="...">` thumbnails across 8 inner pages and real `208 Thompson Ln` storefront photo cards on `/locksmith-pricing-nashville/` and `/service-areas/`.

### 2026-10-30 to 2026-11-02 — Apple Maps, Thumbtack Pro & GBP 20-Product Catalog
- **Apple Business Connect (`1563086994871944632`)**:
  - Configured Quick Actions (`Schedule`, `Pricing`, `Quote`) and scheduled 4 back-to-back 30-day Showcases (`2026-10-30` through `2027-02-28`).
- **Thumbtack Pro Profile (`225574154156794931`)**:
  - Synced `130+` Google Reviews (`5.0 ★`), verified Background Check, uploaded 24 captioned job/shop/van photos, enabled all 8 lock/job types, and updated Bio + FAQs #1–#8 (`20+ years` field experience, `208 Thompson Ln` storefront, mobile vans).
- **Google Business Profile (`1996957340716580179`)**:
  - Audited and aligned all **20 Published GBP Products (`15 Automotive + 5 Commercial`)** so every product links to its canonical Astro page (`Section 4E` of `AGENTS.md`), uploaded real shop/van/job photos to GBP Photos, and published weekly GBP Update posts.
