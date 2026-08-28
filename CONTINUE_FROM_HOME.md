# 🏡 Armstrong Locksmith - Home Setup & Workflow Guide

Follow this simple step-by-step guide to continue working on this project from your home computer or laptop.

---

## 1️⃣ One-Time Setup on Your Home Computer

### Step A: Clone the Repository
Open your terminal (PowerShell, Command Prompt, or Mac Terminal) and run:
```bash
git clone https://github.com/ezzadr/armstrong-astro.git
cd armstrong-astro
```

### Step B: Install Project Dependencies
Make sure [Node.js](https://nodejs.org/) is installed on your computer, then run:
```bash
npm install
```

---

## 2️⃣ Daily Development & Previewing

### Start Local Live Server
To view and test your website in real-time as you make changes:
```bash
npm run dev
```
👉 Open your browser and go to: **`http://localhost:4321`**

*(Any changes you make to files in `src/` will instantly refresh in your browser!)*

---

## 3️⃣ How to Build & Push Your Updates Live

Whenever you make changes and want them deployed to Cloudways:

Same on every platform. **`npm run build` already syncs the built files to the
repo root** — do not copy `dist/` by hand. The build runs
`scripts/sync-to-root.cjs`, which also prunes stale `_astro/` bundles.

```bash
npm run build
git add -A
git commit -m "Updates from home"
git push origin main
```

---

## 4️⃣ Making It Live — nothing to click

Pushing to `main` is the deploy. A GitHub Action (`.github/workflows/deploy.yml`)
SSHes into Cloudways and runs `git reset --hard origin/main` automatically.

* You do **not** need to press "Pull" in the Cloudways dashboard any more.
* The Action does **not** run a build. Whatever is committed at the repo root is
  what goes live — so always `npm run build` before committing, or a change to
  `src/` will never reach the site.
* Watch a deploy with `gh run watch`, or in the repo's Actions tab on GitHub.

### ⚠️ Two things that catch people out
1. **Node 22.12 or newer is required** (see `engines` in `package.json`).
2. **`.env` is gitignored and will NOT come with a fresh clone.** That is fine for
   the website — nothing in `src/` reads environment variables, so `npm run dev`
   and `npm run build` work without it. You only need to recreate `.env` (copy
   `.env.example`) if you intend to run the standalone `*.cjs` automation scripts
   that talk to Twilio or the Google Places API.

---

## 5️⃣ Important Project Reference Links

| Item | Live Link / Value |
| :--- | :--- |
| **GitHub Repository** | `https://github.com/ezzadr/armstrong-astro.git` |
| **Live Domain** | `https://armstronglocksmithinc.com/` |
| **Live Workiz Booking** | `https://online-booking.workiz.com/?ac=ff72b9e4da07483dc3ea20c43712756d0e1998076465f7ea6768e02921635648` |
| **AI Dispatch Embed** | `https://booking.armstronglocksmithinc.com/embed/booking` |
| **Google Review Link** | `https://g.page/r/CQS5BtikwbmqEBM/review` |
| **Google Maps Listing** | `https://g.page/r/CQS5BtikwbmqEBM` |

---

## 6️⃣ Working with AI Assistants from Home
When you open this project in an AI coding assistant (like Antigravity / Cursor / VS Code) at home:
* Simply prompt: *"Read the project codebase in `src/` and continue from where we left off."*
* The entire project architecture is completely self-contained in this repository.
