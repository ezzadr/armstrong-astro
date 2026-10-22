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

## 4. CONTENT RULES (from CLAUDE.md)
- No per-city pages. All area coverage lives on `/service-areas/`. The old
  city URLs are 301-redirected there by rules in `public/.htaccess` and
  `public/index.php`; never remove or reorder those rules.
- Business name Armstrong Locksmith Inc, phone `(615) 625-8000`,
  `208 Thompson Ln, Nashville, TN 37211`. Tennessee has no locksmith licence;
  never add a licence number.
- Hours: storefront Mon-Fri 8 AM-6 PM, Sat 10 AM-4 PM; mobile dispatch
  Mon-Fri 8 AM-11:30 PM, Sat 10 AM-4 PM; closed Sunday. They live in
  `src/data/hours.mjs` and every component imports from it; never type an hour
  into a component. Three spots cannot import it and are typed by hand
  (`public/llms.txt` and the hours line in two blog posts, listed in the
  `hours.mjs` header); if hours change, all change in the same commit. Never
  claim 24/7, after-hours or on-call service anywhere.
- Never hardcode a review count anywhere (copy, meta, schema). Visible counts
  are `{reviewCount}+` inside `<span class="arm-review-count">` and are rounded
  DOWN to the 10s ("790+") so every counter agrees; only the JSON-LD schema
  uses `exactReviewCount`. The one exception is the `FALLBACK` constant in
  `src/lib/reviewStats.mjs`, which Rahim may ask you to bump.
- Voice applies to everything a visitor or Google reads: headlines, card
  titles, alt text, meta titles and descriptions, blog posts, `llms.txt`.
  Plain first-person shop language, the way Rahim talks. Concrete facts
  (price, brand, time, address) beat adjectives. The site's angle is "real
  local shop, not a call center"; machine-sounding copy breaks it. Banned:
  precision, seamless, elevate, unlock your, effortless, cutting-edge,
  state-of-the-art, unparalleled, hassle-free, peace of mind, look no
  further, we've got you covered, and similar. Never "CNC"; say
  "high-security laser cutting". If Rahim gives his own wording, use it
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
