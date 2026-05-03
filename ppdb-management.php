<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola PPDB - SMP YZA 2 Bogor</title>
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
    ?>
    <div class="min-h-screen bg-gray-100">
      <!-- Header -->
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
        <!-- Sidebar -->
        <nav class="w-64 bg-white shadow-lg min-h-screen">
          <div class="p-6">
            <h2 class="text-lg font-semibold text-[#3d6625] mb-4">Menu Admin</h2>
            <ul class="space-y-2">
              <li>
                <a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-[#f0f9eb] rounded-lg transition-colors">
                  <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                  </svg>
                  Dashboard
                </a>
              </li>
              <li>
                <a href="ppdb-management.php" class="block px-4 py-2 bg-[#3d6625] text-white rounded-lg">
                  <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  Kelola PPDB
                </a>
              </li>
              <li>
                <a href="user-management.html" class="block px-4 py-2 text-gray-700 hover:bg-[#f0f9eb] rounded-lg transition-colors">
                  <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                  </svg>
                  Kelola User
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6">
          <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-3xl font-bold text-[#3d6625]">Kelola Pendaftaran PPDB</h1>
              <div class="flex gap-2">
                <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5a9a35] focus:border-transparent">
                  <option value="">Semua Status</option>
                  <option value="pending">Pending</option>
                  <option value="verified">Verified</option>
                  <option value="approved">Approved</option>
                  <option value="rejected">Rejected</option>
                </select>
                <input type="text" id="search-input" placeholder="Cari nama atau nomor pendaftaran..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5a9a35] focus:border-transparent">
              </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid md:grid-cols-4 gap-6 mb-8">
              <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                  <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-gray-800" id="total-count">0</p>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                  <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm text-gray-600">Menunggu Verifikasi</p>
                    <p class="text-2xl font-bold text-gray-800" id="pending-count">0</p>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                  <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm text-gray-600">Diterima</p>
                    <p class="text-2xl font-bold text-gray-800" id="approved-count">0</p>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                  <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm text-gray-600">Ditolak</p>
                    <p class="text-2xl font-bold text-gray-800" id="rejected-count">0</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Registrations Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full table-auto">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600">No. Pendaftaran</th>
                      <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600">Nama Lengkap</th>
                      <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600">Sekolah Asal</th>
                      <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600">Tanggal Daftar</th>
                      <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600">Status</th>
                      <th class="text-left py-4 px-6 text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="registrations-table">
                    <tr>
                      <td colspan="6" class="text-center py-12 text-gray-500">
                        <div class="flex items-center justify-center">
                          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                          Memuat data...
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex items-center justify-between">
                  <div class="text-sm text-gray-600">
                    Menampilkan <span id="showing-start">0</span> - <span id="showing-end">0</span> dari <span id="total-records">0</span> pendaftar
                  </div>
                  <div class="flex gap-2" id="pagination-controls">
                    <!-- Pagination buttons will be inserted here -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <script>
      let currentPage = 1;
      let currentStatus = '';
      let currentSearch = '';

      // Load registrations data
      async function loadRegistrations(page = 1, status = '', search = '') {
        try {
          const params = new URLSearchParams({
            page: page,
            status: status,
            search: search
          });

          const response = await fetch(`../public/php/ppdb-api.php?${params}`);
          const data = await response.json();

          // Update stats
          document.getElementById('total-count').textContent = data.stats.total || 0;
          document.getElementById('pending-count').textContent = data.stats.pending || 0;
          document.getElementById('approved-count').textContent = data.stats.approved || 0;
          document.getElementById('rejected-count').textContent = data.stats.rejected || 0;

          // Update table
          const tbody = document.getElementById('registrations-table');
          if (data.registrations.length > 0) {
            tbody.innerHTML = data.registrations.map(reg => `
              <tr class="border-b hover:bg-gray-50">
                <td class="py-4 px-6 text-sm font-medium text-gray-900">${reg.registration_number}</td>
                <td class="py-4 px-6 text-sm text-gray-900">${reg.nama_lengkap}</td>
                <td class="py-4 px-6 text-sm text-gray-600">${reg.sekolah_asal}</td>
                <td class="py-4 px-6 text-sm text-gray-600">${new Date(reg.submitted_at).toLocaleDateString('id-ID')}</td>
                <td class="py-4 px-6">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                    reg.status === 'approved' ? 'bg-green-100 text-green-800' :
                    reg.status === 'rejected' ? 'bg-red-100 text-red-800' :
                    reg.status === 'verified' ? 'bg-blue-100 text-blue-800' :
                    'bg-yellow-100 text-yellow-800'
                  }">
                    ${reg.status === 'approved' ? 'Diterima' :
                      reg.status === 'rejected' ? 'Ditolak' :
                      reg.status === 'verified' ? 'Terverifikasi' :
                      'Menunggu'}
                  </span>
                </td>
                <td class="py-4 px-6">
                  <div class="flex gap-2">
                    <a href="view-registration.php?id=${reg.id}" class="text-[#3d6625] hover:text-[#5a9a35] font-medium text-sm">Lihat Detail</a>
                    ${reg.status === 'pending' ? `
                      <button onclick="updateStatus(${reg.id}, 'verified')" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Verifikasi</button>
                    ` : ''}
                    ${reg.status === 'verified' ? `
                      <button onclick="updateStatus(${reg.id}, 'approved')" class="text-green-600 hover:text-green-800 font-medium text-sm">Terima</button>
                      <button onclick="updateStatus(${reg.id}, 'rejected')" class="text-red-600 hover:text-red-800 font-medium text-sm">Tolak</button>
                    ` : ''}
                  </div>
                </td>
              </tr>
            `).join('');
          } else {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-12 text-gray-500">Tidak ada data pendaftaran ditemukan</td></tr>';
          }

          // Update pagination info
          document.getElementById('showing-start').textContent = data.pagination.start;
          document.getElementById('showing-end').textContent = data.pagination.end;
          document.getElementById('total-records').textContent = data.pagination.total;

          // Update pagination controls
          updatePagination(data.pagination);

        } catch (error) {
          console.error('Error loading registrations:', error);
          document.getElementById('registrations-table').innerHTML =
            '<tr><td colspan="6" class="text-center py-12 text-red-500">Error memuat data</td></tr>';
        }
      }

      // Update pagination controls
      function updatePagination(pagination) {
        const controls = document.getElementById('pagination-controls');
        let html = '';

        if (pagination.hasPrev) {
          html += `<button onclick="changePage(${pagination.currentPage - 1})" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">Previous</button>`;
        }

        for (let i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++) {
          html += `<button onclick="changePage(${i})" class="px-3 py-1 border border-gray-300 rounded text-sm ${i === pagination.currentPage ? 'bg-[#3d6625] text-white' : 'hover:bg-gray-50'}">${i}</button>`;
        }

        if (pagination.hasNext) {
          html += `<button onclick="changePage(${pagination.currentPage + 1})" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">Next</button>`;
        }

        controls.innerHTML = html;
      }

      // Change page
      function changePage(page) {
        currentPage = page;
        loadRegistrations(currentPage, currentStatus, currentSearch);
      }

      // Update registration status
      async function updateStatus(id, status) {
        if (!confirm(`Apakah Anda yakin ingin ${status === 'approved' ? 'menerima' : status === 'rejected' ? 'menolak' : 'memverifikasi'} pendaftaran ini?`)) {
          return;
        }

        try {
          const response = await fetch('../public/php/update-status.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, status: status })
          });

          const result = await response.json();

          if (result.success) {
            alert('Status berhasil diperbarui');
            loadRegistrations(currentPage, currentStatus, currentSearch);
          } else {
            alert('Error: ' + result.message);
          }
        } catch (error) {
          console.error('Error updating status:', error);
          alert('Terjadi kesalahan saat memperbarui status');
        }
      }

      // Event listeners
      document.getElementById('status-filter').addEventListener('change', function() {
        currentStatus = this.value;
        currentPage = 1;
        loadRegistrations(currentPage, currentStatus, currentSearch);
      });

      document.getElementById('search-input').addEventListener('input', function() {
        currentSearch = this.value;
        currentPage = 1;
        loadRegistrations(currentPage, currentStatus, currentSearch);
      });

      // Load initial data
      loadRegistrations();
    </script>
  </body>
</html>