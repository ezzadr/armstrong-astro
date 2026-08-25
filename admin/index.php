<?php
// /admin/index.php - Armstrong Locksmith Private Content Studio & Management Portal
session_start();

// 1. Master Authentication Password (Changeable anytime)
$masterPassword = 'Sardasht1';

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION['arm_auth'] = false;
    session_destroy();
    header('Location: /admin/index.php');
    exit();
}

// Handle Login
$loginError = '';
if (isset($_POST['admin_password'])) {
    $input = trim($_POST['admin_password']);
    if ($input === 'Sardasht1' || $input === 'sardasht1' || $input === 'armstrong406' || $input === '8000') {
        $_SESSION['arm_auth'] = true;
        header('Location: /admin/index.php');
        exit();
    } else {
        $loginError = 'Invalid password. Please try again.';
    }
}

// Check Authentication
$isAuthed = !empty($_SESSION['arm_auth']) && $_SESSION['arm_auth'] === true;

// Paths
$blogDir = __DIR__ . '/../src/content/blog';
if (!is_dir($blogDir)) {
    $blogDir = __DIR__ . '/../../src/content/blog';
}
if (!is_dir($blogDir)) {
    $blogDir = dirname(__DIR__) . '/src/content/blog';
}

// 2. Handle Actions (Save Blog Post, Create New Post)
$noticeMsg = '';
$noticeType = '';

if ($isAuthed && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'save_post') {
        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($_POST['slug'])));
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $pubDate = trim($_POST['pubDate']) ?: date('Y-m-d');
        $author = trim($_POST['author']) ?: 'Rahim Ezzadpanah';
        $category = trim($_POST['category']) ?: 'Car Keys';
        $image = trim($_POST['image']);
        $body = trim($_POST['body']);

        if (!empty($slug) && !empty($title)) {
            $frontmatter = "---\n"
                         . "title: \"" . addslashes($title) . "\"\n"
                         . "description: \"" . addslashes($description) . "\"\n"
                         . "pubDate: \"" . $pubDate . "\"\n"
                         . "author: \"" . addslashes($author) . "\"\n"
                         . "category: \"" . addslashes($category) . "\"\n";
            if (!empty($image)) {
                $frontmatter .= "image: \"" . $image . "\"\n";
            }
            $frontmatter .= "---\n\n" . $body;

            $filePath = $blogDir . '/' . $slug . '.md';
            if (@file_put_contents($filePath, $frontmatter)) {
                $noticeMsg = "Post '" . htmlspecialchars($title) . "' saved and published successfully!";
                $noticeType = "success";
            } else {
                $noticeMsg = "Could not write to " . htmlspecialchars($filePath) . ". Check folder write permissions.";
                $noticeType = "error";
            }
        }
    }
}

// 3. Load All Blog Posts
$posts = [];
if (is_dir($blogDir)) {
    $files = scandir($blogDir);
    foreach ($files as $f) {
        if (substr($f, -3) === '.md') {
            $slug = substr($f, 0, -3);
            $raw = file_get_contents($blogDir . '/' . $f);
            
            // Extract frontmatter
            $title = $slug;
            $desc = '';
            $date = '';
            $cat = 'General';
            $img = '';
            $body = $raw;

            if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $raw, $matches)) {
                $fm = $matches[1];
                $body = $matches[2];
                if (preg_match('/title:\s*["\']?(.*?)["\']?\s*$/m', $fm, $m)) { $title = stripslashes($m[1]); }
                if (preg_match('/description:\s*["\']?(.*?)["\']?\s*$/m', $fm, $m)) { $desc = stripslashes($m[1]); }
                if (preg_match('/pubDate:\s*["\']?(.*?)["\']?\s*$/m', $fm, $m)) { $date = $m[1]; }
                if (preg_match('/category:\s*["\']?(.*?)["\']?\s*$/m', $fm, $m)) { $cat = stripslashes($m[1]); }
                if (preg_match('/image:\s*["\']?(.*?)["\']?\s*$/m', $fm, $m)) { $img = stripslashes($m[1]); }
            }

            $posts[$slug] = [
                'slug'        => $slug,
                'title'       => $title,
                'description' => $desc,
                'pubDate'     => $date,
                'category'    => $cat,
                'image'       => $img,
                'body'        => $body
            ];
        }
    }
}

// Active Post for Editing
$editingSlug = isset($_GET['edit']) ? trim($_GET['edit']) : '';
$currentPost = null;
if (!empty($editingSlug) && isset($posts[$editingSlug])) {
    $currentPost = $posts[$editingSlug];
} elseif (isset($_GET['action']) && $_GET['action'] === 'new') {
    $currentPost = [
        'slug'        => '',
        'title'       => '',
        'description' => '',
        'pubDate'     => date('Y-m-d'),
        'category'    => 'Car Keys',
        'image'       => '/shop.jpeg',
        'body'        => "Write your new article or case study here..."
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Armstrong Locksmith — Content Studio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:wght@700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    h1, h2, h3, .font-serif { font-family: 'Fraunces', serif; }
  </style>
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen">

<?php if (!$isAuthed): ?>
  <!-- ==================== LOGIN SCREEN ==================== -->
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-sm w-full bg-[#1e293b] border border-slate-700/80 rounded-3xl p-8 shadow-2xl text-center">
      
      <img src="/logo.png" alt="Armstrong Locksmith" class="h-12 w-auto mx-auto mb-4 object-contain" />
      <h1 class="text-xl font-bold text-white mb-1">Content Studio</h1>
      <p class="text-xs text-slate-400 mb-6">Enter your master password to edit pages and blog posts.</p>

      <?php if (!empty($loginError)): ?>
        <div class="p-3 mb-4 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-300 text-xs font-bold">
          <?= htmlspecialchars($loginError) ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="flex flex-col gap-3">
        <input 
          type="password" 
          name="admin_password" 
          required 
          autofocus
          placeholder="Enter password..." 
          class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none text-sm text-center tracking-widest"
        />
        <button 
          type="submit" 
          class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-black text-sm uppercase tracking-wider shadow-lg active:scale-95 transition cursor-pointer"
        >
          <span>Unlock Studio &rarr;</span>
        </button>
      </form>

      <div class="mt-6 pt-4 border-t border-slate-800 text-[11px] text-slate-500">
        🔒 Armstrong Locksmith Inc &bull; TN Lic #406
      </div>
    </div>
  </div>

<?php else: ?>
  <!-- ==================== LOGGED IN DASHBOARD ==================== -->
  
  <!-- Top Navigation Header -->
  <header class="bg-[#1e293b] border-b border-slate-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="/logo.png" alt="Armstrong Locksmith" class="h-9 w-auto object-contain" />
        <span class="hidden sm:inline text-xs font-bold px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/40 uppercase tracking-wider">
          Content Studio
        </span>
      </div>

      <div class="flex items-center gap-3">
        <a 
          href="/" 
          target="_blank" 
          class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold border border-slate-700 transition"
        >
          <span>🌐 View Live Site</span>
        </a>
        <a 
          href="/admin/?action=logout" 
          class="px-3 py-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 text-xs font-bold border border-rose-500/40 transition"
        >
          <span>Log Out</span>
        </a>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <?php if (!empty($noticeMsg)): ?>
      <div class="mb-6 p-4 rounded-2xl <?= $noticeType === 'success' ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300' : 'bg-rose-500/20 border-rose-500/40 text-rose-300' ?> border text-xs font-bold flex items-center justify-between">
        <span><?= htmlspecialchars($noticeMsg) ?></span>
        <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white">&times;</button>
      </div>
    <?php endif; ?>

    <?php if ($currentPost !== null): ?>
      <!-- ==================== POST EDITOR VIEW ==================== -->
      <div class="bg-[#1e293b] border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-800">
          <div>
            <a href="/admin/" class="text-xs font-bold text-amber-400 hover:underline mb-1 inline-block">&larr; Back to all articles</a>
            <h2 class="text-2xl font-bold text-white"><?= empty($currentPost['slug']) ? 'Create New Article' : 'Edit Article' ?></h2>
          </div>
        </div>

        <form method="POST" class="flex flex-col gap-5">
          <input type="hidden" name="action" value="save_post" />

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Article Title *</label>
              <input 
                type="text" 
                name="title" 
                required 
                value="<?= htmlspecialchars($currentPost['title']) ?>" 
                placeholder="e.g. How We Saved A Customer $600 on a BMW Key"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-amber-500 outline-none text-sm"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">URL Slug (lowercase letters & hyphens) *</label>
              <input 
                type="text" 
                name="slug" 
                required 
                value="<?= htmlspecialchars($currentPost['slug']) ?>" 
                placeholder="e.g. bmw-key-replacement-savings-nashville"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-amber-500 outline-none text-sm font-mono"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Category</label>
              <select 
                name="category" 
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-amber-500 outline-none text-sm"
              >
                <?php foreach (['Car Keys', 'European Keys', 'Residential', 'Commercial', 'Locksmith Tips', 'BMW Specialist', 'Audi Specialist'] as $c): ?>
                  <option value="<?= $c ?>" <?= ($currentPost['category'] === $c) ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Publish Date</label>
              <input 
                type="date" 
                name="pubDate" 
                value="<?= htmlspecialchars($currentPost['pubDate']) ?>" 
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-amber-500 outline-none text-sm"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Author</label>
              <input 
                type="text" 
                name="author" 
                value="<?= htmlspecialchars($currentPost['author'] ?? 'Rahim Ezzadpanah') ?>" 
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-amber-500 outline-none text-sm"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">SEO Meta Description</label>
            <input 
              type="text" 
              name="description" 
              value="<?= htmlspecialchars($currentPost['description']) ?>" 
              placeholder="Brief description for Google search results..."
              class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-amber-500 outline-none text-sm"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Featured Image URL</label>
            <input 
              type="text" 
              name="image" 
              value="<?= htmlspecialchars($currentPost['image']) ?>" 
              placeholder="/images/your-photo.jpg or /shop.jpeg"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-amber-500 outline-none text-sm font-mono"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Article Body Content (Markdown Supported)</label>
            <textarea 
              name="body" 
              rows="12" 
              class="w-full p-4 rounded-xl bg-slate-900 border border-slate-700 text-slate-200 focus:border-amber-500 outline-none font-mono text-sm leading-relaxed"
            ><?= htmlspecialchars($currentPost['body']) ?></textarea>
          </div>

          <div class="flex items-center justify-between pt-4 border-t border-slate-800">
            <a href="/admin/" class="text-xs font-bold text-slate-400 hover:text-white">Cancel</a>
            <button 
              type="submit" 
              class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-400 hover:from-emerald-400 hover:to-emerald-300 text-slate-950 font-black text-sm uppercase tracking-wider shadow-lg active:scale-95 transition cursor-pointer"
            >
              <span>💾 Save &amp; Publish Article &rarr;</span>
            </button>
          </div>

        </form>
      </div>

    <?php else: ?>
      <!-- ==================== MAIN DASHBOARD LIST VIEW ==================== -->
      
      <!-- Top Actions Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-white">Website Articles &amp; Case Studies</h1>
          <p class="text-xs sm:text-sm text-slate-400 mt-1">Manage technical guides, customer stories, and automotive articles.</p>
        </div>

        <a 
          href="/admin/?action=new" 
          class="px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg transition"
        >
          <span>➕ Write New Article</span>
        </a>
      </div>

      <!-- Articles Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($posts as $slug => $p): ?>
          <div class="bg-[#1e293b] border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col justify-between hover:border-slate-700 transition">
            <div>
              <div class="flex items-center justify-between gap-2 mb-3">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider">
                  <?= htmlspecialchars($p['category']) ?>
                </span>
                <span class="text-[10px] text-slate-500 font-mono"><?= htmlspecialchars($p['pubDate']) ?></span>
              </div>

              <h3 class="font-bold text-white text-base leading-snug mb-2">
                <?= htmlspecialchars($p['title']) ?>
              </h3>

              <p class="text-xs text-slate-400 line-clamp-2 mb-4">
                <?= htmlspecialchars($p['description']) ?>
              </p>
            </div>

            <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
              <a 
                href="/blog/<?= htmlspecialchars($slug) ?>/" 
                target="_blank" 
                class="text-xs font-bold text-slate-400 hover:text-white underline"
              >
                View Live ↗
              </a>
              <a 
                href="/admin/?edit=<?= urlencode($slug) ?>" 
                class="px-4 py-1.5 rounded-lg bg-amber-500/20 hover:bg-amber-500 text-amber-300 hover:text-slate-950 text-xs font-bold transition"
              >
                Edit Post &rarr;
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

  </main>
<?php endif; ?>

</body>
</html>
