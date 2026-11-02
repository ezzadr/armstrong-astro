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
3. **Canonical Business Data & Deep Armstrong Locksmith Profile:**
   - **Legal / Business Name:** `Armstrong Locksmith Inc`
   - **Owner, Founder & Master Locksmith:** **Rahim Ezzadpanah**
     - **Hands-On Field Experience:** **Over 20 years (`20+ years`) of hands-on locksmith and automotive key programming experience in the field.**
     - **Company Founding Year:** Founded **Armstrong Locksmith Inc** in Nashville in **2015** (`11+ years` as an established Nashville storefront and mobile fleet).
     - **NEVER write "10 years of experience":** `2015` is when Armstrong Locksmith Inc was incorporated in Nashville, NOT when Rahim started as a locksmith. Always state **over 20 years of hands-on locksmith experience in the field** (and founded Armstrong Locksmith Inc in Nashville in 2015).
   - **Field Technicians:** **Rahim, Kawa, Sako** (in-house local Nashville technicians — never subcontractors or out-of-state dispatch centers).
   - **Phone (Call/Text):** `(615) 625-8000` | **Email:** `locksmith@armstronglocksmithinc.com`
   - **Walk-In Storefront:** `208 Thompson Ln, Nashville, TN 37211` (Woodbine / South Nashville). Free self-parking lot; walk-ins welcome with **`$0 Service Call Fee`** (customers save mobile trip fees by bringing locks, keys, or vehicles to the shop).
   - **Operating Hours:** Storefront: Mon–Fri `8:00 AM – 6:00 PM`, Sat `10:00 AM – 4:00 PM`. Mobile dispatch: Mon–Fri `8:00 AM – 11:30 PM`, Sat `10:00 AM – 4:00 PM`. **Closed Sunday.** There is no 24/7, after-hours or on-call service; never claim it anywhere (copy, schema, `llms.txt`, GBP, Apple Maps, Thumbtack). Hours live in **one file, `src/data/hours.mjs`** — pages, menu, footer, call bar and schema import from it; never type hours into a component. (`public/llms.txt` and two blog posts can't import it; the file's header lists them.)
   - **Tennessee Licensing:** TN repealed state locksmith licensing in 2021; never add license numbers (such as old `#406`). The shop is **Certified, Bonded, Insured**, and **Background Checked**.
   - **Reviews Metric:** synced automatically from Google at build time (`src/lib/reviewStats.mjs`; bump its `FALLBACK` when you notice it drifting). **Never hardcode a review count** in copy, meta descriptions or schema: render `{reviewCount}+` inside `<span class="arm-review-count">` so it is right server-side and refreshes live. Visible counts are **rounded down to the 10s** ("790+" / "800+") at build and in the live refresh, so title and counters always agree; only the schema uses the exact `exactReviewCount`.
   - **Payments Accepted (`/payment-methods/`):** Cash, Visa, Mastercard, American Express, Discover, Debit Cards, Apple Pay, Google Pay, Contactless Payments, Zelle, Venmo, Square Cash App, PayPal.
   - **Upfront Pricing Guide (`/locksmith-pricing-nashville/`):**
     - In-Shop Walk-In Service Call Fee (`208 Thompson Ln`): `$0`
     - In-Shop Lock Cylinder Re-Key (`208 Thompson Ln`): `$20 per cylinder`
     - Standard Metal Automotive Key Copy (In-Shop): `$35+`
     - Emergency Car Lockout (Standard Vehicles): `$65+` | High-Security / European Car Lockout: `$85+` (damage-free using German **Lishi** door lock decoders & air-wedge tools)
     - Transponder / Chipped Car Key Cut & Programmed: `$95+`
     - Remote Head Car Key Cut & Programmed: `$120+`
     - Smart Proximity Push-to-Start Fob: `$145+`
     - European Car Key Replacement: `$180+` (typically 40%–60% below Nashville dealership quotes, zero towing)
     - Acura MDX Smart Key Replacement: `$285` (in-shop) / `$325` (mobile)
   - **Technical Specialties:**
     - **European & Luxury Automotive Keys:** BMW & MINI (`CAS1–CAS4+`, `FEM/BDC`, `/bmw/`), Mercedes-Benz (`FBS3/FBS4`, `EIS/EZS`, `ESL/ELV` steering lock repair, `Keyless-Go`, `/mercedes-benz/`), Audi & VW (`BCM2`, `MQB`, `/audi-car-keys/`, `/audi-q3-key-replacement/`), Volvo (all models up to **2026** including `XC90`, `XC60`, `XC40`, `S60`, `S90`, `/volvo-car-key-replacement/`), Land Rover & Range Rover (`KVM/RFA`, `/land-rover-key-replacement-in-nashville-tn-armstrong-locksmith-inc/`), Bentley (`/bentley-key-replacement/`), Porsche, Maserati, Jaguar (`/european-car-key-specialist/`).
     - **American & Asian Automotive Keys:** Ford & Lincoln (official **Ford FDRS** dealer tool, `/ford-car-key-replacement/`), Jeep/Dodge/RAM/Chrysler (proximity fobs, `FOBIK`, `WIN` module, Ram ProMaster, `/dodge-ram-car-key-replacement/`), Chevy/GMC/Cadillac/Hummer (`/hummer-key-replacement-nashville/`), Toyota & Lexus (Grand Highlander, Camry, RAV4, Tacoma, Tundra, 4Runner, `/toyota-car-key-replacement/`), Honda & Acura (`Driver 1`/`Driver 2` memory fobs, worn Honda ignition cylinder repair, `/honda-car-keys/`, `/acura-car-key-replacement/`), Hyundai & Genesis (`/hyundai/`), Kia (`/kia-car-key-replacement/`), Nissan, Infiniti, Subaru, Mazda, Mitsubishi (`/car-key-replacement-nashville/`).
     - **Commercial Door Hardware (`/commercial-locksmith/`):** Aluminum storefront mortise deadlatches (**Adams Rite / ASSA ABLOY**), interior push exit paddles, **Alarm Lock Trilogy (`DL1200`, `DL2700`)** keypad locks, **Dormakaba E-Plex 2000** pushbutton exit trims, **Yale** commercial keypads, **Detex** alarmed exit devices, UL-listed panic crash bars, Grade 1/2 cylindrical levers, **Schlage** commercial cylinders, and master key systems.
     - **Residential Locksmith (`/residential-locksmith-nashville/`, `/nashville-rekey-service/`):** In-shop (`$20/cyl`) and mobile home rekeying (**Schlage, Kwikset, Yale, Baldwin**), deadbolt & smart lock installation, mailbox & cabinet locks, and house lockouts.
   - **Full Canonical URL Directory & External Listings:** See `AGENTS.md` Sections 4E and 10 for the complete list of live Astro page URLs and external profiles (Google Business Profile, Apple Business Connect, Thumbtack). Never use legacy WordPress `/product/...` or `/price-list/` links.

## Copy & Content Voice Rules (PERMANENT)

- **No AI-sounding marketing fluff — anywhere.** This applies to page headlines, card titles, subtitles, alt text, meta descriptions, GBP posts, and blog content.
- **Banned vocabulary:** "precision," "seamless," "elevate," "unlock your," "effortless," "cutting-edge," "state-of-the-art," "unparalleled," "hassle-free," "peace of mind," "look no further," "we've got you covered," "lab," "key lab," "mobile lab," "workshop," "workstation," and similar AI/marketing phrases.
- **Never call the van or shop a "lab," "key lab," "workshop," or "workstation"** — always say "locksmith van," "mobile locksmith van," or "locksmith shop." A real local locksmith never calls a van or shop a "lab."
- **Never say "CNC laser cutting" (or any "CNC" phrasing) in customer-facing copy** — always "high-security laser cutting" instead. Customers know "high-security key"; "CNC" is machinist jargon.
- **Write like Rahim talks:** plain first-person shop language. "Rahim duplicating a residential key at the shop" beats "Precision Key Cutting by Owner Rahim." Concrete facts (prices, brands, times, addresses) beat adjectives.
- **When Rahim supplies his own phrasing, use it verbatim** (fix only clear typos, e.g. lock/key mixups — and confirm).
- The site's credibility angle is "real local shop, not a call center" — copy that sounds machine-written undermines the entire brand.

## SEO Ownership Rule (ANTIGRAVITY ONLY — HANDS OFF FOR CLAUDE)

- **Antigravity is the ONLY agent allowed to touch SEO on this website.**
- Claude must **NEVER** edit, rewrite, suggest changes to, or "optimize":
  - Page `<title>` / `seoTitle` or `<meta name="description">` / `seoDescription`
  - H1/H2 keyword targeting, internal SEO links, or blog case-study cross-links
  - JSON-LD structured data / schema (`Layout.astro` or page-level `schema`)
  - Canonical tags, `robots.txt`, `llms.txt`, or sitemap configuration
  - 301 redirects in `public/.htaccess` and `public/index.php`
  - Regional coverage on `/service-areas/` or city/neighborhood strategy
- If Rahim asks Claude a question that touches SEO, rankings, titles, meta descriptions, redirects, or Search Console, Claude must remind him that **Antigravity exclusively handles all SEO** for this site and leave all SEO elements untouched.

## Development Commands

- Start dev server: `npm run dev`
- Preview a production build locally: `npm run build` (do NOT commit its output — see rule 1)
- Deploy: commit **source only**, then `git push origin main` — CI builds + deploys.
  `git add -A && git commit -m "..." && git push origin main`
