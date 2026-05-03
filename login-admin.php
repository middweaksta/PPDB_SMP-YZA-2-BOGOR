<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin - SMP YZA 2 Bogor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      html, body { height: 100%; margin: 0; }
    </style>
  </head>

  <body>
    <div class="min-h-screen bg-white">
      <!-- Header -->
      <header class="bg-[#3d6625] py-3 px-6 flex items-center justify-between shadow-md sticky top-0 z-50">
        <a href="index.html" class="flex items-center gap-3">
          <img src="https://www.upload.ee/image/19236257/BYZAD.png" alt="Logo SMP YZA 2 Bogor" class="w-12 h-12 rounded-full object-cover">
          <span class="text-white text-xl font-bold tracking-wide">YZA 2 BOGOR</span>
        </a>

        <nav class="flex items-center gap-2">
          <a href="index.html" class="px-5 py-2 rounded-lg transition-all text-white hover:bg-[#4a7c2c]">Home</a>
          <a href="tentang.html" class="px-5 py-2 rounded-lg transition-all text-white hover:bg-[#4a7c2c]">Tentang</a>
          <a href="visi-misi.html" class="px-5 py-2 rounded-lg transition-all text-white hover:bg-[#4a7c2c]">Visi & Misi</a>
          <a href="guru.html" class="px-5 py-2 rounded-lg transition-all text-white hover:bg-[#4a7c2c]">Guru</a>
          <a href="kontak.html" class="px-5 py-2 rounded-lg transition-all text-white hover:bg-[#4a7c2c]">Kontak</a>
          <div class="ml-4 h-8 w-px bg-white/30"></div>
          <a href="ppdb.html" class="px-5 py-2 bg-[#5a9a35] text-white rounded-lg hover:bg-[#6bb044] transition-all font-medium">PPDB</a>
          <a href="login-admin.php" class="px-5 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all border border-white/20">Login Admin</a>
        </nav>
      </header>

      <!-- Login Form -->
      <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-6 py-12 bg-gradient-to-br from-[#f0f9eb] to-white">
        <div class="w-full max-w-md">
          <!-- Login Card -->
          <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-8">
              <div class="w-20 h-20 bg-gradient-to-br from-[#3d6625] to-[#5a9a35] rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
              </div>
              <h2 class="text-3xl font-bold text-[#3d6625] mb-2">Login Admin</h2>
              <p class="text-gray-600">Masuk ke Dashboard Administrator</p>
            </div>

            <!-- Login Form -->
            <form action="../public/php/login.php" method="POST" class="space-y-6">
              <!-- Error Message -->
              <?php
              session_start();
              $error_message = null;
              
              if (isset($_GET['error'])) {
                  $error_message = htmlspecialchars($_GET['error']);
              } elseif (isset($_SESSION['login_error'])) {
                  $error_message = htmlspecialchars($_SESSION['login_error']);
                  unset($_SESSION['login_error']);
              }
              
              if ($error_message) {
                  echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                          <div class="flex gap-3">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-sm text-red-800">' . $error_message . '</p>
                          </div>
                        </div>';
              }
              ?>
              <!-- Username Field -->
              <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                  Username atau Email
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <input
                    type="text"
                    id="username"
                    name="username"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5a9a35] focus:border-transparent outline-none transition-all"
                    placeholder="Masukkan username atau email"
                    required
                  />
                </div>
              </div>

              <!-- Password Field -->
              <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                  Password
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                  </div>
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5a9a35] focus:border-transparent outline-none transition-all"
                    placeholder="Masukkan password"
                    required
                  />
                </div>
              </div>

              <!-- Remember Me & Forgot Password -->
              <div class="flex items-center justify-between">
                <label class="flex items-center">
                  <input
                    type="checkbox"
                    class="w-4 h-4 text-[#5a9a35] border-gray-300 rounded focus:ring-[#5a9a35]"
                  />
                  <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
                <a href="#" class="text-sm text-[#3d6625] hover:text-[#5a9a35] font-medium">
                  Lupa password?
                </a>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                class="w-full bg-gradient-to-r from-[#3d6625] to-[#5a9a35] text-white py-3 rounded-lg font-semibold hover:from-[#2d4a1a] hover:to-[#4a8028] transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
              >
                Login
              </button>
            </form>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
              <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                  <p class="font-semibold mb-1">Cara Login Admin:</p>
                  <ul class="list-disc list-inside space-y-1 text-blue-700">
                    <li>Gunakan username atau email yang terdaftar</li>
                    <li>Masukkan password dengan benar</li>
                    <li>Hubungi administrator jika lupa password</li>
                    <li>Akses hanya untuk staff sekolah yang berwenang</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-6 text-center">
              <p class="text-sm text-gray-600">
                Butuh bantuan?{' '}
                <a href="mailto:smp.yza2bogor@yahoo.com" class="text-[#3d6625] hover:text-[#5a9a35] font-medium">
                  Hubungi Administrator
                </a>
              </p>
            </div>
          </div>

          <!-- Additional Security Notice -->
          <div class="mt-6 text-center text-sm text-gray-500">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Koneksi aman dan terenkripsi
          </div>
        </div>
      </div>
    </div>
  </body>
</html>