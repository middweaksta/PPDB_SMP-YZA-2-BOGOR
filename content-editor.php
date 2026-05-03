<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editor Konten - SMP YZA 2 Bogor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      html, body { height: 100%; margin: 0; }
    </style>
  </head>
  <body>
    <?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../html/login-admin.html');
        exit;
    }

    $pages = [
        'kontak' => [
            'label' => 'Kontak',
            'path' => __DIR__ . '/../html/kontak.html',
        ],
        'tentang' => [
            'label' => 'Tentang',
            'path' => __DIR__ . '/../html/tentang.html',
        ],
        'visi-misi' => [
            'label' => 'Visi & Misi',
            'path' => __DIR__ . '/../html/visi-misi.html',
        ],
        'guru' => [
            'label' => 'Guru',
            'path' => __DIR__ . '/../html/guru.html',
        ],
    ];

    $pageContents = [];
    foreach ($pages as $key => $page) {
        $pageContents[$key] = is_readable($page['path']) ? file_get_contents($page['path']) : '';
    }

    $statusMessage = null;
    if (isset($_GET['status'])) {
        $statusMessage = htmlspecialchars($_GET['status']);
    }
    ?>

    <div class="min-h-screen bg-gray-100">
      <header class="bg-[#3d6625] py-3 px-6 flex items-center justify-between shadow-md">
        <a href="dashboard.php" class="flex items-center gap-3">
          <img src="https://www.upload.ee/image/19236257/BYZAD.png" alt="Logo SMP YZA 2 Bogor" class="w-12 h-12 rounded-full object-cover">
          <span class="text-white text-xl font-bold tracking-wide">YZA 2 BOGOR - ADMIN</span>
        </a>
        <div class="flex items-center gap-4">
          <span class="text-white">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></strong></span>
          <a href="../public/php/logout.php" class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all border border-white/20">Logout</a>
        </div>
      </header>

      <div class="flex">
        <nav class="w-64 bg-white shadow-lg min-h-screen">
          <div class="p-6">
            <h2 class="text-lg font-semibold text-[#3d6625] mb-4">Menu Admin</h2>
            <ul class="space-y-2">
              <li><a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-[#f0f9eb] rounded-lg transition-colors">Dashboard</a></li>
              <li><a href="ppdb-management.php" class="block px-4 py-2 text-gray-700 hover:bg-[#f0f9eb] rounded-lg transition-colors">Kelola PPDB</a></li>
              <li><a href="content-editor.php" class="block px-4 py-2 bg-[#3d6625] text-white rounded-lg">Editor Konten</a></li>
              <li><a href="user-management.html" class="block px-4 py-2 text-gray-700 hover:bg-[#f0f9eb] rounded-lg transition-colors">Kelola User</a></li>
            </ul>
          </div>
        </nav>

        <main class="flex-1 p-6">
          <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-6">
              <div>
                <h1 class="text-3xl font-bold text-[#3d6625]">Editor Konten Publik</h1>
                <p class="text-gray-600">Edit halaman Kontak, Tentang, Visi & Misi, dan Guru langsung dari admin panel.</p>
              </div>
              <?php if ($statusMessage): ?>
                <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                  <?php echo $statusMessage; ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="grid gap-6">
              <?php foreach ($pages as $key => $page): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                  <div class="flex items-center justify-between mb-4">
                    <div>
                      <h2 class="text-xl font-semibold text-[#3d6625]">Halaman <?php echo $page['label']; ?></h2>
                      <p class="text-sm text-gray-500">Edit HTML lengkap halaman <?php echo $page['label']; ?>.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-[#e9f7ed] text-[#2f6b2f] text-sm"><?php echo $page['label']; ?></span>
                  </div>

                  <form action="../public/php/save-page.php" method="POST">
                    <input type="hidden" name="page_key" value="<?php echo $key; ?>">
                    <textarea name="content" rows="16" class="w-full border border-gray-300 rounded-xl p-4 text-sm font-mono bg-slate-50 resize-none" placeholder="HTML halaman <?php echo $page['label']; ?>"><?php echo htmlspecialchars($pageContents[$key]); ?></textarea>
                    <div class="mt-4 flex justify-end gap-3">
                      <button type="submit" class="px-5 py-3 bg-[#3d6625] text-white rounded-lg hover:bg-[#5a9a35] transition-all">Simpan Halaman</button>
                    </div>
                  </form>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
