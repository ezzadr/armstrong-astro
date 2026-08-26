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

### On Windows (PowerShell):
```powershell
# 1. Build the Astro static pages
npm run build

# 2. Sync compiled files to the root directory for Cloudways
Copy-Item "dist\*" "." -Recurse -Force

# 3. Commit and push to GitHub
git add -A
git commit -m "Updates from home"
git push origin main
```

### On Mac / Linux Terminal:
```bash
npm run build
cp -r dist/* .
git add -A
git commit -m "Updates from home"
git push origin main
```

---

## 4️⃣ Make It Live on Cloudways
1. Log into your **[Cloudways Dashboard](https://platform.cloudways.com/)**.
2. Navigate to: **Applications** &rarr; **Armstrong Locksmith** &rarr; **Deployment via Git**.
3. Click the orange **"Pull"** button.
4. *(Optional)* If you use Cloudflare, log into Cloudflare and click **Purge Everything** under **Caching** to see the changes immediately.

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
