# ARMSTRONG LOCKSMITH WEBSITE — RULES FOR AI AGENTS (v2, 2026-10-13)

Read fully before changing anything. `CLAUDE.md` in the repo root is the rulebook;
these notes add to it. Where `AGENTS.md`, `README.md`, `CONTINUE_FROM_HOME.md` or
`AGENT_HANDOVER.md` section 5 disagree with `CLAUDE.md` or with this note (they
still say to run `npm run build` before committing), they are out of date.

- **Repo**: `github.com/ezzadr/armstrong-astro` (Astro site, `armstronglocksmithinc.com`)
- **Host**: Cloudways app `btfdkcdpdw`, `/home/master/applications/btfdkcdpdw/public_html`
- **Deployment**: There is no staging site. A push to `main` is live within about two minutes.
- **Owner**: Rahim. He is not a developer. Plain words, one step at a time.

## 1. HANDS OFF: TWILIO AND THE AI DISPATCH APP (absolute)
- Never edit, move, delete, reformat, "clean up" or reorganise anything that
  links to Twilio or the Armstrong AI Dispatch app:
  - `public/api/contact.php` and its root copy `api/contact.php`
  - `public/api/dispatch.php` and its root copy `api/dispatch.php`
  - `public/claim.php` and its root copy `claim.php`
  - `serve.js`, `get_twilio_info.js`, `get_twilio_info.py`, `.env`, `.env.example`
  - the server-only files `api/.leads_store.json` and `api/.ai_dispatch_log.txt`
  - anything referencing `api.twilio.com` or `booking.armstronglocksmithinc.com`
- Never rotate, change, replace, move or suggest rotating the Twilio Account
  SID / Auth Token, the team phone numbers, or the Gmail app password. The
  Twilio credentials are shared with the dispatch app; changing them breaks
  Rahim's most critical system. Rahim knows they are in the repo. Do not
  report it again.
- Never touch the other repo (`armstrong-ai-call-dispatch`) or the other server
  app (`jqkvdrpcfz`). Never run `git clean`, `rm -rf` or `rsync --delete` on the
  server: that would wipe the untracked lead store.
- Never run any of the `*.cjs` or `*.ps1` scratch scripts at the repo root. Some
  send real SMS, some rewrite `contact.php`. They are gitignored; leave them.
- Lead forms (homepage hero form in `src/pages/index.astro`, the contact page,
  `src/components/BookingForm.astro`): you may edit markup, styling and the
  client-side JavaScript, but never change the endpoints they post to, the
  JSON field names they send (including the honeypot key `company_website` and
  `form_started_at`), or the photo-upload block in `BookingForm.astro`.
- If a fix seems to need a change inside any of the files above, stop and
  ask Rahim. Do not do a "small" or "safe-looking" version.

## 2. COMMIT SOURCE ONLY. NEVER COMMIT A LOCAL BUILD
- CI builds on every push to `main` (`.github/workflows/deploy.yml`): it runs the
  tests, lints the PHP, builds, commits the built HTML itself as
  `chore(build): CI build output for <sha>`, then deploys. You never build
  for deployment.
- Source you may edit: `src/`, `public/`, `scripts/`, `tests/`, `.github/`, and the
  config files (`astro.config.mjs`, `package.json`, `tsconfig.json`). Everything
  else at the repo root (`index.html`, every `<page>/index.html`, `_astro/`,
  `404.html`, `sitemap-index.xml`, `sitemap-0.xml`, `robots.txt`, `llms.txt`,
  `.htaccess`, `index.php`, `404-handler.php`, `api/`, `admin/`, `claim.php`, images)
  is generated from `src/` and `public/` at build time. Edit the `public/` version,
  never the root copy.
- Do not run `npm run build` before committing. To check that the site
  compiles use `npx astro build` (writes only `dist/`) or `npm run dev`.
  Test you can run locally: `node --test tests/booking-photo.test.mjs`
- Any build or dev run may rewrite `src/data/reviews-fallback.json`. Put it
  back before committing unless you were asked to change it:
  ```bash
  git restore -- src/data/reviews-fallback.json
  ```
- If you did run `npm run build`, restore the root before committing. Run
  from the repo root in Git Bash and read the output of each step:
  ```bash
  git status --porcelain
  git restore --source=HEAD --staged --worktree -- . ':(exclude)src' ':(exclude)public' ':(exclude)scripts' ':(exclude)tests' ':(exclude)docs' ':(exclude).github' ':(exclude)CLAUDE.md' ':(exclude)AGENTS.md' ':(exclude)astro.config.mjs' ':(exclude)package.json' ':(exclude)package-lock.json' ':(exclude)tsconfig.json'
  git clean -nd -- _astro      # dry run: shows the new CSS hash file
  git clean -fd -- _astro
  git status --porcelain       # must now show only source paths
  ```
- Before every commit, `git status --porcelain` must show only source paths
  (or a file Rahim explicitly asked you to change).
- Run `git pull --rebase origin main` before starting work and again before
  pushing: CI adds a bot commit after every push, so a stale local `main` is
  rejected.
- Keep diffs minimal: no reformatting, no line-ending changes (`core.autocrlf`
  is on; "LF will be replaced by CRLF" warnings are normal, ignore them), no
  renames, no "while I'm here" edits. Never add new files at the repo root.

## 3. APPROVAL GATES
- Start from a clean tree on a new branch named for the job
  (example: `fix/booking-error-banner`) and tell Rahim the branch name.
- Do not commit, push, merge to `main` or deploy without Rahim's explicit OK
  for that specific step. "Fix these" means make the changes and show them.
- Never remove a menu link, page or destination without asking.
- Do not claim something works unless you tested it: `npx astro build`
  passes, the page was checked in a browser at 375px width, links resolve.
- Then stop and wait. One step at a time.

## 4. CONTENT RULES (from CLAUDE.md, summary)
- No per-city pages. All area coverage lives on `/service-areas/`.
- Business name Armstrong Locksmith Inc, phone `(615) 625-8000`,
  `208 Thompson Ln, Nashville, TN 37211`. Tennessee has no locksmith licence;
  never add a licence number.
- Hours come only from `src/data/hours.mjs`. Closed Sunday. Never claim 24/7,
  after-hours or on-call service anywhere.
- Never hardcode a review count. Render `{reviewCount}+` inside
  `<span class="arm-review-count">`; only the schema uses `exactReviewCount`.
- Voice: plain shop language, the way Rahim talks. Banned: precision,
  seamless, elevate, unlock your, effortless, cutting-edge, state-of-the-art,
  unparalleled, hassle-free, peace of mind, look no further, we've got you
  covered, and similar. Never "CNC"; say "high-security laser cutting".
  If Rahim gives his own wording, use it verbatim.

## 5. LEAD FORM RULES
- Honeypot fields are hidden with `display:none` only, and never named or
  labelled with words browsers autofill (`company`, `website`, `url`, `organization`,
  `address`, `phone`, `email`, `name`). The JSON key sent to the server stays
  `company_website`.
- A form must never show success unless the request actually succeeded.
  Silent fake success is the worst failure this site can have.

## 6. REPORTING
- Commit messages: `type(scope): lower-case imperative summary`, for example
  `fix(booking): show the error banner when a request fails`
  Types in use: `fix`, `feat`, `chore`, `perf`, `style`, `refactor`, `seo`, `docs`, `ci`.
- At the end of every task list: files changed and why; what you tested and
  how; what you did NOT do and why; anything you noticed but left alone,
  especially inside hands-off files.
