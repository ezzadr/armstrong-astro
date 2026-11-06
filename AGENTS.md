# ARMSTRONG LOCKSMITH WEBSITE — RULES FOR AI AGENTS (v3, 2026-10-13)

## THE FIVE RULES THAT MATTER MOST
1. New branch from a clean tree; tell Rahim the branch name.
2. Edit only `src/`, `public/` (except the three PHP files named below), `tests/`
   and config files. Treat the repo root as read-only.
3. Never `npm run build`. Never `git add -A`, `git add .` or `git commit -a`.
   Stage files by name.
4. Before every commit, `git status --porcelain` must list only the files
   Rahim asked you to change.
5. Nothing is committed, pushed, merged, or run in GitHub Actions without
   Rahim's OK for that exact step.

The detail follows. `CLAUDE.md` in the repo root is the rulebook; this note
adds to it. `CONTINUE_FROM_HOME.md` sections 3-4 and `AGENT_HANDOVER.md`
section 5 are out of date where they say to build before committing or to
hand-sync the review count: ignore those parts. `README.md` is the stock Astro
starter.

- **Repo**: `github.com/ezzadr/armstrong-astro` (Astro site, `armstronglocksmithinc.com`)
- **Host**: Cloudways app `btfdkcdpdw`, `/home/master/applications/btfdkcdpdw/public_html`
- **Deployment**: no staging site. A push to `main` is live within about two minutes.
- **Owner**: Rahim. He is not a developer. Plain words, one step at a time.
- If a task is unclear, could be read two ways, or would touch more files
  than it names, ask one plain question before writing code.

## 1. HANDS OFF: TWILIO AND THE AI DISPATCH APP (absolute)
- Never edit, move, delete, reformat, "clean up" or reorganise these:
  - `public/api/contact.php` and its root copy `api/contact.php`
  - `public/claim.php` and its root copy `claim.php`
  - `public/api/dispatch.php` and its root copy `api/dispatch.php`
    (legacy mail endpoint nothing calls today; leave it anyway)
  - `serve.js`, `get_twilio_info.js`, `get_twilio_info.py`, `.env`, `.env.example`
  - the server-only files `api/.leads_store.json` and `api/.ai_dispatch_log.txt`

  These are the hands-off files. Front-end files that merely mention
  `booking.armstronglocksmithinc.com` or Twilio (`src/layouts/Layout.astro`
  preconnect tags, the upload block in `src/components/BookingForm.astro`,
  `tests/booking-photo.test.mjs`, the consent sentence on
  `src/pages/staff-sms-alerts-consent.astro`) may be edited for other reasons,
  but those URLs, request fields and that sentence stay exactly as they are,
  and you never add a new call to either host.
- Never rotate, change, replace, move or suggest rotating the Twilio Account
  SID / Auth Token, the team phone numbers, or the Gmail app password. The
  Twilio credentials are shared with the dispatch app; changing them breaks
  Rahim's most critical system. He knows they are in the repo. The only two
  things you may ever offer about them are (a) making the GitHub repo private
  or (b) reading the unchanged token from an untracked file on the server.
  Do neither without his explicit OK.
- If you find a bug or security problem inside a hands-off file: do not touch
  it, do not patch around it. Describe it in one plain paragraph in your
  end-of-task report and let Rahim decide.
- Never run `serve.js`, `temp.js`, `get_twilio_info.js`, `get_twilio_info.py`,
  `scripts/release-booking-page.py`, or any `*.cjs` / `*.ps1` file at the repo
  root. `serve.js` sends real SMS from the shop's Twilio account; several
  scratch scripts send SMS or rewrite `contact.php`.
- Never touch the other repo (`armstrong-ai-call-dispatch`) or the other server
  app (`jqkvdrpcfz`). Any server command must name
  `/home/master/applications/btfdkcdpdw/public_html` explicitly; never use a
  wildcard, glob or loop across `/home/master/applications/`. Never run
  `git clean`, `rm -rf` or `rsync --delete` on the server: the lead store and
  dispatch log are untracked files there and would be destroyed. If anything
  (including DNS or Cloudflare) could affect another app, stop and ask.
- Lead forms: homepage hero form in `src/pages/index.astro`, the contact page
  `src/pages/contact-armstrong-locksmith.astro`, and
  `src/components/BookingForm.astro`. You may edit their markup, styling and
  client-side JavaScript, but never change the endpoints they post to or the
  field names they send. To `/api/contact.php` the JSON keys are `name`, `phone`,
  `service`, `details`, `notes`, `email`, `company_website`. BookingForm's photo
  upload to `booking.armstronglocksmithinc.com` (`customer_name`, `phone`,
  `service`, `service_type`, `vehicle`, `form_started_at`, `company_website`,
  `photo`) must not change; a CI test asserts it. The homepage and contact page
  also build an unused payload object: leave it, do not "clean it up".
- In `BookingForm.astro` do not reformat, re-indent or rewrite the submit
  handler (`form.addEventListener('submit', async function (e) {` ... `});`).
  `tests/booking-photo.test.mjs` finds it by that exact text; a change fails CI
  and blocks every deploy.
- If a fix seems to need a change inside any hands-off file, stop and ask.
  Do not do a "small" or "safe-looking" version.

## 2. COMMIT SOURCE ONLY. NEVER COMMIT A LOCAL BUILD
- CI builds on every push to `main` (`.github/workflows/deploy.yml`): it runs the
  tests, lints the PHP, builds, commits the built HTML itself as
  `chore(build): CI build output for <sha>`, then deploys. You never build
  for deployment.
- Source you may edit without asking: `src/`, `public/` (minus the hands-off PHP
  files), `tests/`, `scripts/` except `release-booking-page.py`, and the config
  files (`astro.config.mjs`, `package.json`, `tsconfig.json`). The workflow files
  under `.github/` and `scripts/release-booking-page.py` run commands on the live
  server: do not change them without Rahim's OK for that exact edit.
- The repo root is read-only. The build copies `dist/` over it, so `index.html`,
  every `<page>/index.html`, `_astro/`, `404.html`, `sitemap-index.xml`,
  `sitemap-0.xml`, `robots.txt`, `llms.txt`, `.htaccess`, `index.php`,
  `404-handler.php`, `api/`, `admin/`, `claim.php` and the images are build
  output: change them only through `src/` or `public/`. The root also holds
  hand-kept files (`README.md`, `AGENT_HANDOVER.md`, `CONTINUE_FROM_HOME.md`,
  `SITE-STATE-2026-09.md`, `meta-backup-2026-09.md`, the management guide HTML,
  two PDFs, `*.pub` keys, `downloaded_images.json`, `temp.js`, `serve.js`).
  Never add, move, rename, tidy or delete anything at the root.
- Do not run `npm run build`. To check that the site compiles use
  `npx astro build` (writes `dist/` and the ignored `.astro/` folder, not the
  root) or the dev server. If your shell blocks on `npm run dev`, run it in
  the background and stop it when done.
- Checks CI runs before deploying, in order; run the ones that apply to your
  change and say which you ran (if python or php is not installed, say so):
  ```bash
  node --test tests/booking-photo.test.mjs
  python3 -m unittest discover -s tests -p "test_release*.py"
  php -l public/*.php public/api/*.php   # one file at a time in CI
  ```
- Any build or dev run may rewrite `src/data/reviews-fallback.json`. Never
  hand-edit it; put it back before committing unless asked to change it:
  ```bash
  git restore -- src/data/reviews-fallback.json
  ```
- If you did run `npm run build`, restore the root before committing. From
  the repo root in Git Bash, reading the output of each step:
  ```bash
  git status --porcelain
  git restore --source=HEAD --staged --worktree -- . ':(exclude)src' ':(exclude)public' ':(exclude)scripts' ':(exclude)tests' ':(exclude)docs' ':(exclude).github' ':(exclude)CLAUDE.md' ':(exclude)AGENTS.md' ':(exclude)README.md' ':(exclude).gitignore' ':(exclude)AGENT_HANDOVER.md' ':(exclude)CONTINUE_FROM_HOME.md' ':(exclude)astro.config.mjs' ':(exclude)package.json' ':(exclude)package-lock.json' ':(exclude)tsconfig.json'
  git clean -nd -- _astro      # dry run: shows the new CSS hash file
  git clean -fd -- _astro
  git status --porcelain       # must now show only your source files
  ```
  That is the only `git clean` you ever run locally. Never `git clean` the whole
  tree and never with `-x`: that deletes `.env` and the scratch scripts. If
  status still shows another `??` path (a new page folder the build made),
  tell Rahim; do not delete it yourself.
- Last time, commit `986bfbe3` included 49 built files and CI had to re-churn
  47 files. That is what these rules prevent.
- Run `git pull --rebase origin main` before starting work and again before
  any push: CI adds a bot commit after nearly every push and a nightly job
  commits to `main` most mornings, so yesterday's local `main` is stale.
- Keep diffs minimal: no reformatting, no line-ending changes (`core.autocrlf`
  is on; "LF will be replaced by CRLF" warnings are normal, ignore them), no
  renames, no "while I'm here" edits.

## 3. APPROVAL GATES
- Start from a clean tree on a new branch named for the job
  (example: `fix/booking-error-banner`) and tell Rahim the branch name.
- Do not commit, push, open or merge a pull request, run `gh workflow run`,
  press Run workflow in the Actions tab, or do anything that deploys,
  without Rahim's explicit OK for that exact step. Both workflows have a
  manual trigger; never use it. "Fix these" means make the changes and show
  them.
- Changes reach `main` only this way: Rahim says "merge". Then
  `git checkout main`; `git pull --rebase origin main`;
  `git merge --ff-only <branch>`; show him `git log --oneline origin/main..main`;
  wait for a second OK; then `git push origin main`. Pushing the branch itself
  (`git push -u origin <branch>`) is fine once he approved the commit; it
  deploys nothing.
- The menu is `src/data/nav.json`. Do not add, remove, reorder, relabel or
  retarget any entry, and do not delete or rename a file under `src/pages/`,
  without asking. Removing a page also needs 301 rules in `public/.htaccess`
  and `public/index.php`, so that is always Rahim's call.
- If what Rahim asks for could slow the mobile site, hurt rankings, lower
  form conversions or make the site look less trustworthy, say so first in
  plain words, offer the alternative, and wait for his choice.
- Do not claim something works unless you tested it: `npx astro build`
  passes, the page was checked in a browser at 375px width, links resolve.
- Then stop and wait. One step at a time.

## 4. CONTENT RULES & DEEP ARMSTRONG LOCKSMITH KNOWLEDGE BASE (from CLAUDE.md)

> **CRITICAL RULE FOR ALL AGENTS & SUBAGENTS:** Every agent (and every subagent prompt you launch for browser, directory, GBP, Apple Maps, Thumbtack, or website tasks) MUST know and use the exact business facts below. Never guess a year of experience, hour, price, license number, or URL.

### 4A. Identity, Owner Experience & Team (NEVER CONFUSE THESE)
- **Legal / Business Name**: `Armstrong Locksmith Inc`
- **Owner, Founder & Master Locksmith**: **Rahim Ezzadpanah**
  - **Hands-On Field Experience**: **Over 20 years (`20+ years`) of hands-on locksmith and automotive key programming experience in the field.**
  - **Company Founding Year**: Founded **Armstrong Locksmith Inc** in Nashville in **2015** (`11+ years` as an established Nashville storefront and mobile fleet).
  - **NEVER write "10 years of experience"**: `2015` is the year Armstrong Locksmith Inc was incorporated in Nashville, NOT when Rahim started as a locksmith. Always state **over 20 years of hands-on locksmith experience in the field** (and founded Armstrong Locksmith Inc in Nashville in 2015).
- **Field Technicians**: **Rahim, Kawa, Sako** (real local in-house technicians — never out-of-state call-center dispatchers or sub-brokers).
- **Walk-In Storefront Address**: `208 Thompson Ln, Nashville, TN 37211` (Woodbine / South Nashville, off Thompson Lane near Nolensville Pike & Briley Pkwy). Free self-parking lot; walk-ins welcome.
- **Walk-In Storefront Advantage**: **`$0 Service Call Fee`** when customers bring their vehicle, lock cylinder, door hardware, or key to `208 Thompson Ln` (saves mobile trip fees).
- **Phone (Call or Text)**: `(615) 625-8000`
- **Email**: `locksmith@armstronglocksmithinc.com`

### 4B. Operating Hours, Licensing, Reviews & Payments
- **Hours (`src/data/hours.mjs`)**:
  - **Walk-In Storefront (`208 Thompson Ln`)**: Mon–Fri `8:00 AM – 6:00 PM` | Sat `10:00 AM – 4:00 PM` | Sun `Closed`
  - **Mobile Dispatch Vans**: Mon–Fri `8:00 AM – 11:30 PM` | Sat `10:00 AM – 4:00 PM` | Sun `Closed`
  - They live in `src/data/hours.mjs` and every component imports from it; never type an hour into a component. Three spots cannot import it and are typed by hand (`public/llms.txt` and the hours line in two blog posts, listed in the `hours.mjs` header); if hours change, all change in the same commit.
  - **Never claim 24/7, overnight, after-hours, or on-call service anywhere** (website, schema, GBP, Apple Maps, Thumbtack, or any directory).
- **Tennessee Locksmith Licensing**: Tennessee repealed state locksmith licensing in 2021 (`SB 0012 / HB 0009`). **Never add a license number** (such as old `#406`) anywhere. State that the shop is **Certified, Bonded, and Insured** and **Background Checked**.
- **Review Counts**: Never hardcode a review count anywhere on the website (copy, meta, schema). Visible counts are `{reviewCount}+` inside `<span class="arm-review-count">` and are rounded DOWN to the 10s (`"790+"` / `"800+"`) so every counter agrees; only the JSON-LD schema uses `exactReviewCount`. The one exception is the `FALLBACK` constant in `src/lib/reviewStats.mjs`, which Rahim may ask you to bump.
- **Payments Accepted (`/payment-methods/`)**: Cash, Visa, Mastercard, American Express, Discover, Debit Cards, Apple Pay, Google Pay, Contactless Payments, Zelle, Venmo, Square Cash App, PayPal.
- **Service Areas (`/service-areas/`)**: No per-city doorway pages. All area coverage lives on `/service-areas/` covering Nashville (`37211`, `37203`, `37204`, `37205`, `37206`, `37209`, `37210`, `37212`, `37214`, `37215`, `37217`, `37220`, `37221`), Brentwood, Franklin, Oak Hill, Forest Hills, Belle Meade, Green Hills, Berry Hill, Nolensville, Antioch, Donelson, and Hermitage. The old city URLs are 301-redirected there by rules in `public/.htaccess` and `public/index.php`; never remove or reorder those rules.

### 4C. Upfront Pricing Guide (`/locksmith-pricing-nashville/`)
- **In-Shop Walk-In Service Call Fee (`208 Thompson Ln`)**: `$0`
- **In-Shop Lock Cylinder Re-Key (`208 Thompson Ln`)**: `$20 per cylinder`
- **Standard Metal Automotive Key Copy (In-Shop)**: `$35+` (standard house & commercial keys duplicated while you wait)
- **Emergency Car Lockout (Standard Vehicles)**: `$65+`
- **High-Security / European Car Lockout (BMW, Mercedes, Audi, Lexus, Porsche)**: `$85+` (100% damage-free using German **Lishi** door lock decoders & air-wedge tools)
- **Transponder / Chipped Car Key Cut & Programmed**: `$95+`
- **Remote Head Car Key Cut & Programmed**: `$120+`
- **Smart Proximity Push-to-Start Fob**: `$145+`
- **European Car Key Replacement (BMW, Mercedes-Benz, Audi, VW, Volvo, Land Rover, Porsche, Bentley)**: `$180+` (typically **40%–60% below Nashville dealership quotes**, with **zero towing**)
- **Acura MDX Smart Key Replacement**: `$285` (in-shop service at `208 Thompson Ln`) / `$325` (mobile service)

### 4D. Deep Technical Specialties & Hardware We Work On
- **European & Luxury Automotive Keys (Rahim's Specialty)**:
  - **BMW & MINI**: `CAS1–CAS4+`, `FEM/BDC`, `EWS` immobilizer systems, smart proximity fobs, and high-security laser key cutting (`/bmw/`).
  - **Mercedes-Benz & Sprinter**: `FBS3/FBS4` support, `EIS/EZS` electronic ignition switch programming, `ESL/ELV` steering lock repair/emulation, `Keyless-Go` smart fobs (`/mercedes-benz/`).
  - **Audi & Volkswagen**: `BCM2` and `MQB` platform smart keys, flip keys, and all-keys-lost programming (`/audi-car-keys/`, `/audi-q3-key-replacement/`).
  - **Volvo**: Push-to-start smart fobs and high-security keys for **all Volvo models up to 2026**, including `XC90`, `XC60`, `XC40`, `S60`, `S90`, `V60`, `V90` (`/volvo-car-key-replacement/`).
  - **Land Rover & Range Rover**: `KVM/RFA` module programming and smart proximity fobs for Range Rover, Sport, Velar, Evoque, Discovery, Defender (`/land-rover-key-replacement-in-nashville-tn-armstrong-locksmith-inc/`).
  - **Bentley, Porsche, Ferrari, Lamborghini, Rolls-Royce, Maserati, Alfa Romeo & Jaguar**: High-security laser cutting and EEPROM/immobilizer programming (`/bentley-key-replacement/`, `/european-car-key-specialist/`). Never remove Ferrari, Lamborghini, or Rolls-Royce from GBP or directory service lists — Rahim makes keys for those exotics too.
- **American & Asian Automotive Keys (All-Keys-Lost & Spare Fobs)**:
  - **Ford & Lincoln**: Official **Ford FDRS** dealer diagnostic/programming tool for F-150, F-250/F-350 Super Duty, Bronco, Explorer, Expedition, Escape, Edge, Transit, Mustang, Lincoln Navigator (`/ford-car-key-replacement/`).
  - **Jeep, Dodge, RAM & Chrysler (Stellantis)**: Proximity smart fobs, `FOBIK` keys, `WIN` module replacement, and RF Hub programming for Ram 1500/2500/3500, **Ram ProMaster** commercial vans, Jeep Wrangler, Grand Cherokee, Gladiator, Dodge Charger, Challenger, Durango, Chrysler Pacifica (`/dodge-ram-car-key-replacement/`).
  - **Chevrolet, GMC, Cadillac, Buick & Hummer**: Silverado, Sierra, Tahoe, Suburban, Yukon, Escalade, Equinox, Express/Savana vans, Hummer H2/H3 (`/hummer-key-replacement-nashville/`).
  - **Toyota & Lexus**: Smart proximity fobs and laser-cut keys for **Toyota Grand Highlander**, Highlander, Camry, Corolla, RAV4, Tacoma, Tundra, 4Runner, Sienna, Prius, Lexus RX/ES/GX/NX/IS (`/toyota-car-key-replacement/`).
  - **Honda & Acura**: Smart fobs (`Driver 1` / `Driver 2` memory), laser-cut keys, and **worn Honda ignition & door lock cylinder wafer repair** for Accord, Civic, CR-V, Pilot, Odyssey, Ridgeline, Acura MDX, RDX, TLX (`/honda-car-keys/`, `/acura-car-key-replacement/`).
  - **Hyundai, Genesis & Kia**: Push-to-start smart fobs and flip keys for Tucson, Santa Fe, Palisade, Elantra, Sonata, **Genesis GV80/GV70/G80**, **Kia Sorento**, Telluride, Sportage, Forte, K5, Soul (`/hyundai/`, `/kia-car-key-replacement/`).
  - **Nissan, Infiniti, Subaru, Mazda & Mitsubishi**: Altima, Rogue, Sentra, Maxima, Pathfinder, Outback, Forester, Crosstrek, CX-5, CX-90 (`/car-key-replacement-nashville/`).
- **Commercial Locksmith & Storefront Hardware (`/commercial-locksmith/`)**:
  - Aluminum glass storefront mortise deadlatches (**Adams Rite / ASSA ABLOY**), hookbolts, deadbolts, and interior **push exit paddles**.
  - Commercial digital & audit-trail keypad locks: **Alarm Lock Trilogy (`DL1200`, `DL2700`)**, **Dormakaba E-Plex 2000** pushbutton exit trims, and **Yale** commercial keypad levers.
  - **Detex** alarmed exit devices, UL-listed panic crash bars, commercial door closers, **Grade 1 & Grade 2** cylindrical lever locks, **Schlage** commercial cylinders, restricted keyways, and **master key systems**.
- **Residential Locksmith & Rekeying (`/residential-locksmith-nashville/`, `/nashville-rekey-service/`)**:
  - In-shop cylinder rekeying (`$20/cyl` at `208 Thompson Ln`) and mobile home rekeying (**Schlage, Kwikset, Yale, Baldwin**), deadbolt installation, smart touchscreen deadbolts, mailbox & office furniture locks, and damage-free house lockouts.

### 4E. Canonical Live Website URLs (NEVER Use Old WordPress `/product/...` or `/price-list/` Links)
- **Core Pages**:
  - Home: `https://armstronglocksmithinc.com/`
  - Walk-In Storefront (`208 Thompson Ln`): `https://armstronglocksmithinc.com/nashville-locksmith-storefront/`
  - 2026 Locksmith Pricing Guide: `https://armstronglocksmithinc.com/locksmith-pricing-nashville/`
  - Book Online: `https://armstronglocksmithinc.com/book-online/`
  - Contact Us: `https://armstronglocksmithinc.com/contact-armstrong-locksmith/`
  - About Rahim & Our Shop: `https://armstronglocksmithinc.com/about-us/`
  - Service Areas (Middle TN): `https://armstronglocksmithinc.com/service-areas/`
  - Verified Google Reviews: `https://armstronglocksmithinc.com/google-reviews/`
  - FAQ: `https://armstronglocksmithinc.com/faq/`
  - Payment Methods: `https://armstronglocksmithinc.com/payment-methods/`
- **Core Locksmith Services**:
  - Car Key Replacement Hub: `https://armstronglocksmithinc.com/car-key-replacement-nashville/`
  - Key Fob Replacement & Programming: `https://armstronglocksmithinc.com/key-fob-replacement-nashville/`
  - Mobile Auto Locksmith Nashville: `https://armstronglocksmithinc.com/automotive-locksmith-in-nashville-tn/`
  - Dealership Key Alternative: `https://armstronglocksmithinc.com/dealer-key/`
  - Emergency Car Lockout: `https://armstronglocksmithinc.com/emergency-car-lockout/`
  - Nashville Car Unlock Service: `https://armstronglocksmithinc.com/nashville-car-unlock-service/`
  - Commercial Locksmith Nashville: `https://armstronglocksmithinc.com/commercial-locksmith/`
  - Residential Locksmith Nashville: `https://armstronglocksmithinc.com/residential-locksmith-nashville/`
  - Nashville Lock Re-Keying Service: `https://armstronglocksmithinc.com/nashville-rekey-service/`
- **Vehicle Make Specialist Pages**:
  - European Specialist Hub: `https://armstronglocksmithinc.com/european-car-key-specialist/`
  - Acura: `https://armstronglocksmithinc.com/acura-car-key-replacement/`
  - Audi: `https://armstronglocksmithinc.com/audi-car-keys/` (Audi Q3: `https://armstronglocksmithinc.com/audi-q3-key-replacement/`)
  - Bentley: `https://armstronglocksmithinc.com/bentley-key-replacement/`
  - BMW & MINI: `https://armstronglocksmithinc.com/bmw/`
  - Dodge, RAM, Jeep & Chrysler: `https://armstronglocksmithinc.com/dodge-ram-car-key-replacement/`
  - Ford & Lincoln: `https://armstronglocksmithinc.com/ford-car-key-replacement/`
  - Honda: `https://armstronglocksmithinc.com/honda-car-keys/`
  - Hummer & GM: `https://armstronglocksmithinc.com/hummer-key-replacement-nashville/`
  - Hyundai & Genesis: `https://armstronglocksmithinc.com/hyundai/`
  - Kia: `https://armstronglocksmithinc.com/kia-car-key-replacement/`
  - Land Rover & Range Rover: `https://armstronglocksmithinc.com/land-rover-key-replacement-in-nashville-tn-armstrong-locksmith-inc/`
  - Mercedes-Benz: `https://armstronglocksmithinc.com/mercedes-benz/`
  - Toyota & Lexus: `https://armstronglocksmithinc.com/toyota-car-key-replacement/`
  - Volvo: `https://armstronglocksmithinc.com/volvo-car-key-replacement/`

### 4F. Voice & Banned Words (Applies Everywhere)
- Voice applies to everything a visitor or Google reads: headlines, card
  titles, alt text, meta titles and descriptions, blog posts, `llms.txt`,
  GBP products/posts, Apple Maps, and Thumbtack.
  Plain first-person shop language, the way Rahim talks. Concrete facts
  (price, brand, time, address) beat adjectives. The site's angle is "real
  local shop, not a call center"; machine-sounding copy breaks it. Banned:
  precision, seamless, elevate, unlock your, effortless, cutting-edge,
  state-of-the-art, unparalleled, hassle-free, peace of mind, look no
  further, we've got you covered, and similar. Never say "lab", "key lab",
  "mobile lab", "workshop", or "workstation" — always say "locksmith van",
  "mobile locksmith van", or "locksmith shop" (a real local locksmith never
  calls his van or shop a "lab"). Never "CNC"; say "high-security laser
  cutting". Always talk like a real local locksmith so Rahim never has to
  correct AI-sounding words or phrases. If Rahim gives his own wording, use it
  verbatim; fix only an obvious typo and tell him you did.

## 5. LEAD FORM RULES
- Honeypot rule for any form you are asked to touch: hide it with
  `display:none` only, and never name or label it with words browsers autofill
  (`company`, `website`, `url`, `organization`, `address`, `phone`, `email`,
  `name`). The JSON key sent to the server stays `company_website`.
- Known state: only `BookingForm.astro` (field `hp_leave_empty`) follows this.
  The homepage hero form and the contact page still use the old
  `company_website` field and label. Do not change them on your own. If Rahim
  asks, change the input name, its label, the wrapper style and the JS
  lookup (`document.getElementById("company_website")` on the homepage,
  `form.company_website` on the contact page) in the same edit, and keep the
  JSON key `company_website`.
- A form must never show success to a real customer unless the request
  actually succeeded. Silent fake success is the worst failure this site can
  have. One deliberate exception: when the honeypot is filled, all three
  forms show the success screen without sending anything. That is how bots
  are swallowed. Leave it.

## 6. REPORTING
- Commit messages: `type(scope): lower-case imperative summary`, for example
  `fix(booking): show the error banner when a request fails`
  Types in use: `fix`, `feat`, `chore`, `perf`, `style`, `refactor`, `seo`, `docs`, `ci`.
- At the end of every task list: files changed and why; what you tested and
  how; what you did NOT do and why; anything you noticed but left alone,
  especially inside hands-off files.

## 7. SEO OWNERSHIP (ANTIGRAVITY ONLY — HANDS OFF FOR CLAUDE & OTHER AGENTS)
- **Antigravity is the ONLY agent allowed to touch SEO on this website.**
- Claude (and any other AI tool or agent) must NEVER edit, rewrite, or "improve":
  - Page `<title>` / `seoTitle` or `<meta name="description">` / `seoDescription`
  - H1/H2 keyword targeting, internal SEO links, or blog case-study cross-links
  - JSON-LD structured data / schema (`Layout.astro` or page-level `schema`)
  - Canonical tags, `robots.txt`, `llms.txt`, or sitemap configuration
  - 301 redirects in `public/.htaccess` and `public/index.php`
  - Regional coverage on `/service-areas/` or city/neighborhood strategy
- All SEO decisions are driven exclusively by Antigravity inspecting Rahim's live
  Google Search Console data so two agents never give conflicting advice or churn
  titles/redirects while Google is evaluating the site.
- **Homepage Stability Reminder**: While Google is testing and settling `/` (`src/pages/index.astro`) on Page 1 for `"locksmith"`, if Rahim asks for any change that would touch the homepage, STOP and remind him not to change the homepage while it is settling.
- **MANDATORY 14-Day Anti-Churn Check Before Proposing Any SEO Edit (Check `git log` & `PROJECT_MEMORY_LOG.md` FIRST!)**:
  - Whenever Rahim mentions a keyword ranking or page (e.g., *"for locksmith hermitage tn and locksmith brentwood tn we are on page 2"*), **NEVER jump into proposing new edits to that page before checking when we last edited it**.
  - Always run `git log -n 5 --date=short --format="%h %ad %s" -- <file>` and check `docs/PROJECT_MEMORY_LOG.md` **FIRST**.
  - If the page was already updated within the last **14 days** (such as `src/pages/service-areas.astro` updated on `Oct 31` / `Nov 3`, or the Make/Service/Blog pages updated on `Nov 3–5`), **you** must be the one to tell Rahim immediately: *"We just updated that page X days ago on `<date>` — reaching Page 2 already means the update is climbing. Do not touch it for at least 14 days so we don't reset Google's evaluation timer."* Rahim should never have to remind you of a change you made days ago.

## 8. "I AM HOME" / "I AM AT THE SHOP" SYNC TRIGGER
- Whenever Rahim says **"I am home"** (or **"I am at the shop"**), immediately run:
  ```bash
  git checkout main
  git pull --rebase origin main
  git status --porcelain
  git log -n 3 --oneline
  ```
- Report the last 3 commits in plain words and confirm that his computer now has the latest live website updates and is ready for work.

## 9. ALWAYS SHOW A LIVE PREVIEW BEFORE ASKING TO COMMIT
- Whenever you make any visual, copy, layout, or page change, ALWAYS start the local preview (`npx astro preview --port 4321` after `npx astro build`, or `npm run dev`) and open the changed page in Rahim's browser (plus give him the clickable `http://localhost:4321/...` link) BEFORE asking him to commit or merge.
- Never ask Rahim to commit a page or design change he has not seen in preview first.

## 10. EXTERNAL BUSINESS LISTINGS & DIRECTORY PROFILES (GBP, APPLE MAPS, THUMBTACK)
- Antigravity manages and monitors Rahim's core local listings alongside `armstronglocksmithinc.com`:
  1. **Google Search Console**: Property `https://armstronglocksmithinc.com/`
  2. **Google Business Profile (GBP / GMB)**: Profile ID `1996957340716580179` (`https://g.page/r/CQS5BtikwbmqEBM`, reviews: `https://g.page/r/CQS5BtikwbmqEBM/review`)
     - **20 Published GBP Products (`15 Automotive + 5 Commercial`)**: Every GBP product must point to its exact live Astro landing page in Section 4E above — NEVER to an old WordPress `/product/...` URL.
     - **Smart Profile Description Rule (Applies to GBP 750-char Description & All Directories)**:
       1. **Always show Rahim the exact draft text and character count in chat FIRST** and wait for his OK before saving any description, bio, or post.
       2. **Never waste character-limited description boxes repeating fields that already have their own dedicated slot on the listing** (never put operating hours, phone numbers, or URLs inside the 750-character GBP Description).
       3. **Use 100% of the character budget on high-value search terms**: `$0 walk-in service call fee` at `208 Thompson Ln`, all 3 vehicle groups (`Ford, Chevy, GMC, Dodge, RAM, Jeep, Toyota, Lexus, Honda, Acura, Nissan, Hyundai, Kia` + `BMW, Mercedes, Audi, VW, Volvo, Land Rover, Porsche`), core auto services (`all-keys-lost`, `push-to-start smart fobs`, `transponder keys`, `high-security laser cutting`, `ignition repair`, `damage-free car unlocks`), and commercial/residential hardware (`house lockouts`, `lock rekeying`, `smart deadbolts`, `Adams Rite storefront locks`, `Alarm Lock keypads`, `master key systems`, `Detex panic bars`).
  3. **Apple Business Connect (Apple Maps)**: Org `1461790501084574208` · Brand `1570815753027878912` (`Verified`) · Location `1563086994871944632` (`https://business.apple.com/companies/1461790501084574208/maps/locations/1563086994871944632/info`, live Place Card: `https://maps.apple.com/place?auid=10423668849052500220`).
     - **Quick Actions**: `Primary Action = Schedule` → `https://armstronglocksmithinc.com/book-online/`, `Pricing` → `https://armstronglocksmithinc.com/locksmith-pricing-nashville/`, `Quote` → `https://armstronglocksmithinc.com/book-online/`.
     - **Showcases**: 4 approved 30-day Showcases run back-to-back `2026-10-30` through `2027-02-28` (remind Rahim by `2027-02-15` to queue the next 4).
  4. **Thumbtack Pro Profile**: Service ID `225574154156794931` (`https://www.thumbtack.com/tn/nashville/locksmiths/armstrong-locksmith-inc/service/225574154156794931`, Pro editor: `https://www.thumbtack.com/profile/services/225574154156794931/`).
     - **Live Profile State**: `Exceptional 5.0 ★ (130 Verified Reviews)` with free Google Reviews sync connected; `Background Check: Completed (Rahim Ezzadpanah)` (no unverified TN license entry); `Website: https://armstronglocksmithinc.com/`; Storefront hours `Mon–Fri 8:00 AM – 6:00 PM, Sat 10:00 AM – 4:00 PM, Sun Closed`; 24 captioned photos; all 8 lock types & job types enabled; Introduction Bio and FAQs #1–#8 highlight **over 20 years of hands-on locksmith experience in the field**, our `208 Thompson Ln` walk-in shop, and our mobile locksmith vans.
     - **Thumbtack Technical Notes**: Never touch paid budget/card settings without Rahim's explicit OK. Thumbtack's React textareas (`edit-description`, `edit-q-and-a`) require calling the native `HTMLTextAreaElement.prototype.value` setter + `__reactProps.onChange` before clicking `Save`, and `edit-q-and-a` returns HTTP `422` if a phone number is included inside FAQ answers.

## 11. ALWAYS OFFER CLICKABLE SUGGESTED REPLIES (`ask_question`) AFTER EVERY STEP
- At the end of every completed task, preview, or approval gate, **always call the `ask_question` tool** to present Rahim with clickable suggested-reply buttons based on the current workflow step and overall project health:
  - **When a branch is built and previewed (`http://localhost:4321/...`)**: Offer `(Recommended) Commit and merge` alongside options to tweak the page or run the next priority SEO/directory fix.
  - **When a branch is committed and merged into `main` (`git log --oneline origin/main..main` shown)**: Offer `(Recommended) Push to live site` alongside an option to hold/inspect first.
  - **After a push or completed directory/GBP update**: Proactively offer the top 3–4 highest-impact next steps based on live project health (e.g., Search Console Page 2 -> Page 1 opportunities, GBP/Facebook/Apple Maps/Thumbtack/Yelp/BBB alignment, AI Overview optimization, or new job case studies) with `(Recommended)` on the smartest next move.

## 12. PROJECT MEMORY & CONTINUOUS HANDOVER LOG (`docs/PROJECT_MEMORY_LOG.md`)
- **Single Source of Historical Truth**: `docs/PROJECT_MEMORY_LOG.md` is the permanent, repo-tracked memory log of everything done across the website (`armstronglocksmithinc.com`), Google Search Console, Google Business Profile (`1996957340716580179`), Apple Business Connect (`1563086994871944632`), Thumbtack Pro (`225574154156794931`), Facebook (`facebook.com/Armstronglocksmithinc`), and third-party directory cleanups (such as the Setmore takedown).
- **Mandatory Startup Read for Any New Session / Agent**:
  - Any agent starting a new conversation or resuming work on this project MUST read `docs/PROJECT_MEMORY_LOG.md` before making any website, SEO, GBP, or directory changes so it knows what has already been completed, what is currently settling in Google, and what pending follow-ups exist.
- **Mandatory Recording Rule After Every Completed Task**:
  - Whenever you complete a website commit, a Google Business Profile update/post, a Search Console ranking check, or an external directory update, you **MUST** update `docs/PROJECT_MEMORY_LOG.md` with the date, commit SHA or platform ID, exact changes made, and any pending follow-ups.

## 13. AUTOMATIC SEARCH ENGINE & AI INDEXING AFTER EVERY PUSH (GOOGLE + BING INDEXNOW)
- **Never wait for Rahim to remind you to notify Bing or Google after a live push to `main`.**
- Immediately after every push to `main` that creates or modifies any website page, blog post, or `llms.txt`:
  1. **Bing / Yahoo / DuckDuckGo / ChatGPT & Copilot Search (IndexNow API — Mandatory Every Push)**:
     - Automatically POST the full list of newly created or modified canonical URLs (`https://armstronglocksmithinc.com/...`) to **both** `https://api.indexnow.org/indexnow` and `https://www.bing.com/indexnow` using our verified site key `a8f4c910e53b471286d9a17320afce65` (`keyLocation: "https://armstronglocksmithinc.com/a8f4c910e53b471286d9a17320afce65.txt"`), and confirm HTTP `200`/`202` in your report.
  2. **Google Search Console (Priority Indexing for New Pages & Blog Posts)**:
     - Whenever a brand-new page or blog post URL is published (or a high-priority page is overhauled), inspect the live URL in Google Search Console (`https://search.google.com/search-console?resource_id=https%3A%2F%2Farmstronglocksmithinc.com%2F`) and click **Request Indexing** so it enters Google's priority crawl queue immediately.
