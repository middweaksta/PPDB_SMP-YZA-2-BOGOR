<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - SMP YZA 2 Bogor</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
  </style>
</head>

<body>
  <div class="min-h-screen bg-gray-100">

    <!-- Header -->
    <header class="bg-[#3d6625] py-3 px-6 flex items-center justify-between shadow-md">
      <a href="../html/index.html" class="flex items-center gap-3">
        <img src="https://www.upload.ee/image/19236257/BYZAD.png"
             class="w-12 h-12 rounded-full object-cover">

        <span class="text-white text-xl font-bold tracking-wide">
          YZA 2 BOGOR - ADMIN
        </span>
      </a>

      <div class="flex items-center gap-4">
        <span class="text-white">
          Selamat datang,
          <strong>
            <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
          </strong>
        </span>

        <a href="../public/php/logout.php"
           onclick="return confirm('Apakah Anda yakin ingin logout?');"
           class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 border border-white/20">
          Logout
        </a>
      </div>
    </header>


    <div class="flex">

      <!-- Sidebar -->
      <nav class="w-64 bg-white shadow-lg min-h-screen">

        <div class="p-6">

          <h2 class="text-lg font-semibold text-[#3d6625] mb-4">
            Menu Admin
          </h2>

          <ul class="space-y-2">

            <!-- Dashboard -->
            <li>
              <a href="dashboard.php"
                 class="block px-4 py-2 rounded-lg transition-colors
                 <?= $currentPage == 'dashboard.php'
                    ? 'bg-[#3d6625] text-white'
                    : 'text-gray-700 hover:bg-[#f0f9eb]' ?>">

                Dashboard
              </a>
            </li>


            <!-- Jadwal -->
            <li>
              <a href="ppdb-schedule.php"
                 class="block px-4 py-2 rounded-lg transition-colors
                 <?= $currentPage == 'ppdb-schedule.php'
                    ? 'bg-[#3d6625] text-white'
                    : 'text-gray-700 hover:bg-[#f0f9eb]' ?>">

                Kelola Jadwal PPDB
              </a>
            </li>


            <!-- User -->
            <li>
              <a href="user-management.php"
                 class="block px-4 py-2 rounded-lg transition-colors
                 <?= $currentPage == 'user-management.php'
                    ? 'bg-[#3d6625] text-white'
                    : 'text-gray-700 hover:bg-[#f0f9eb]' ?>">

                Kelola User & Logs
              </a>
            </li>

          </ul>

        </div>
      </nav>


      <!-- Main Content -->
      <main class="flex-1 p-6">

        <h1 class="text-3xl font-bold text-[#3d6625]">
          Dashboard Admin
        </h1>

        <!-- isi dashboard -->
        
      </main>

    </div>

  </div>
</body>
</html>