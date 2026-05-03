<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin - SMP YZA 2 Bogor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      html, body { height: 100%; margin: 0; }
    </style>
  </head>

  <body>
    <?php
session_start();
    require_once '../public/php/config.php';
    requireLogin();
    $currentPage = basename($_SERVER['PHP_SELF']);

    ?>
    <div class="min-h-screen bg-gray-100">
      <!-- Header -->
      <header class="bg-[#3d6625] py-3 px-6 flex items-center justify-between shadow-md">
        <a href="../html/index.html" class="flex items-center gap-3">
          <img src="https://www.upload.ee/image/19236257/BYZAD.png" alt="Logo SMP YZA 2 Bogor" class="w-12 h-12 rounded-full object-cover">
          <span class="text-white text-xl font-bold tracking-wide">YZA 2 BOGOR - ADMIN</span>
        </a>

        <div class="flex items-center gap-4">
          <span class="text-white">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></strong></span>
          <a href="../public/php/logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');" class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all border border-white/20">Logout</a>
        </div>
      </header>

      <div class="flex">
        <!-- Sidebar -->
        <nav class="w-64 bg-white shadow-lg min-h-screen">
          <div class="p-6">
            <h2 class="text-lg font-semibold text-[#3d6625] mb-4">Menu Admin</h2>
            <ul class="space-y-2">
              <li>
                <a href="dashboard.php" class="block px-4 py-2 rounded-lg transition-colors <?= $currentPage === 'dashboard.php' ? 'bg-[#3d6625] text-white' : 'text-gray-700 hover:bg-[#f0f9eb]' ?>">
                  <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                  </svg>
                  Dashboard
                </a>
              </li>
              <li>
                <a href="user-management.php" class="block px-4 py-2 rounded-lg transition-colors <?= $currentPage === 'user-management.php' ? 'bg-[#3d6625] text-white' : 'text-gray-700 hover:bg-[#f0f9eb]' ?>">
                  <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                  </svg>
                  Kelola User & Logs
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6">
          <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-[#3d6625] mb-6">Dashboard Admin</h1>

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
                    <p class="text-2xl font-bold text-gray-800" id="total-registrations">0</p>
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
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-800" id="pending-registrations">0</p>
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
                    <p class="text-sm text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-800" id="approved-registrations">0</p>
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
                    <p class="text-sm text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-800" id="rejected-registrations">0</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="grid md:grid-cols-1 gap-6 mb-8">
              <div class="bg-white rounded-xl shadow-lg p-6 border border-dashed border-[#3d6625]">
                <h2 class="text-xl font-bold text-[#3d6625] mb-3">Data Cepat</h2>
                <p class="text-gray-600 mb-4">Ringkasan PPDB tersedia di halaman admin utama.</p>
              </div>
            </div>

            <!-- Pending Registrations -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-[#3d6625]">Pendaftaran Baru Masuk</h2>
                <span class="text-sm text-gray-500">Semua data upload tampil di sini</span>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full table-auto">
                  <thead>
                    <tr class="border-b">
                      <th class="text-left py-2 px-4">No. Pendaftaran</th>
                      <th class="text-left py-2 px-4">Nama</th>
                      <th class="text-left py-2 px-4">Sekolah Asal</th>
                      <th class="text-left py-2 px-4">Status</th>
                      <th class="text-left py-2 px-4">Tanggal</th>
                      <th class="text-left py-2 px-4">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="pending-registrations-table">
                    <tr>
                      <td colspan="6" class="text-center py-8 text-gray-500">Memuat pendaftaran baru...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </main>
      </div>
    </div>

    <!-- Registration Detail Modal -->
    <div id="registrationDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center p-4">
      <div class="bg-white rounded-2xl overflow-hidden shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-[#3d6625] text-white p-6 flex items-start justify-between gap-4">
          <div>
            <h2 class="text-2xl font-bold">Detail Pendaftaran</h2>
            <p class="mt-1 text-sm opacity-90">Lihat semua data yang diupload dan proses pendaftaran.</p>
          </div>
          <button onclick="closeRegistrationDetailModal()" class="text-white text-3xl leading-none">&times;</button>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">No. Pendaftaran</p>
              <p id="detail-registration-number" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Status</p>
              <p id="detail-status" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Nama Lengkap</p>
              <p id="detail-name" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Email</p>
              <p id="detail-email" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Nomor HP</p>
              <p id="detail-phone" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Tempat/Tanggal Lahir</p>
              <p id="detail-birth" class="font-semibold"></p>
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Asal Sekolah</p>
              <p id="detail-school" class="font-semibold"></p>
            </div>
            <div>
              <p class="text-sm text-gray-500">NPSN</p>
              <p id="detail-npsn" class="font-semibold"></p>
            </div>
            <div class="md:col-span-2">
              <p class="text-sm text-gray-500">Alamat</p>
              <p id="detail-address" class="font-semibold"></p>
            </div>
          </div>

          <div>
            <p class="text-sm text-gray-500 mb-2">Berkas Upload</p>
            <div class="grid md:grid-cols-2 gap-3" id="detail-files"></div>
          </div>

          <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-gray-200">
            <button id="detail-approve-btn" onclick="updateRegistrationStatus(currentDetailId, 'approved')" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Accept</button>
            <button id="detail-reject-btn" onclick="updateRegistrationStatus(currentDetailId, 'rejected')" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">Denied</button>
            <button id="detail-delete-btn" onclick="deleteRegistration(currentDetailId)" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-all">Hapus</button>
            <button onclick="closeRegistrationDetailModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Load dashboard data
async function loadDashboardData() {
        try {
          const response = await fetch('../public/php/dashboard-api.php', {
            credentials: 'same-origin'
          });

          const data = await response.json();

          // Update stats
          document.getElementById('total-registrations').textContent = data.stats.total || 0;
          document.getElementById('pending-registrations').textContent = data.stats.pending || 0;
          document.getElementById('approved-registrations').textContent = data.stats.approved || 0;
          document.getElementById('rejected-registrations').textContent = data.stats.rejected || 0;

          renderPendingRegistrations(data.pending || []);
        } catch (error) {
          console.error('Error loading dashboard data:', error);
        }
      }

      function renderPendingRegistrations(pending) {
        const tbody = document.getElementById('pending-registrations-table');
        if (!pending || pending.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">Tidak ada pendaftaran baru</td></tr>';
          return;
        }

        tbody.innerHTML = pending.map(reg => `
          <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-4">${reg.registration_number}</td>
            <td class="py-2 px-4">${reg.nama_lengkap}</td>
            <td class="py-2 px-4">${reg.sekolah_asal}</td>
            <td class="py-2 px-4">
              <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
            </td>
            <td class="py-2 px-4">${new Date(reg.submitted_at).toLocaleDateString('id-ID')}</td>
            <td class="py-2 px-4 flex flex-wrap gap-2">
              <button onclick="showRegistrationDetail(${reg.id})" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-[#3d6625] hover:bg-gray-50">Detail</button>
              <button onclick="updateRegistrationStatus(${reg.id}, 'approved')" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Accept</button>
              <button onclick="updateRegistrationStatus(${reg.id}, 'rejected')" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">Denied</button>
              <button onclick="deleteRegistration(${reg.id})" class="px-3 py-1 bg-gray-200 text-gray-800 rounded text-sm hover:bg-gray-300">Hapus</button>
            </td>
          </tr>
        `).join('');

        window.pendingRegistrations = pending.reduce((map, reg) => {
          map[reg.id] = reg;
          return map;
        }, {});
      }

      function closeRegistrationDetailModal() {
        document.getElementById('registrationDetailModal').classList.add('hidden');
      }

      function showRegistrationDetail(id) {
        const reg = window.pendingRegistrations?.[id];
        if (!reg) return;

        window.currentDetailId = id;
        document.getElementById('detail-registration-number').textContent = reg.registration_number;
        document.getElementById('detail-status').textContent = reg.status === 'pending' ? 'Menunggu' : reg.status;
        document.getElementById('detail-name').textContent = reg.nama_lengkap;
        document.getElementById('detail-email').textContent = reg.email || '-';
        document.getElementById('detail-phone').textContent = reg.no_hp;
        document.getElementById('detail-birth').textContent = `${reg.tempat_lahir}, ${reg.tanggal_lahir}`;
        document.getElementById('detail-school').textContent = reg.sekolah_asal;
        document.getElementById('detail-npsn').textContent = reg.npsn || '-';
        document.getElementById('detail-address').textContent = reg.alamat;

        const filesEl = document.getElementById('detail-files');
        const fileFields = {
          Ijazah: reg.ijazah_path,
          SHUN: reg.shun_path,
          KK: reg.kk_path,
          Akta: reg.akta_path,
          'Pas Foto': reg.pas_foto_path,
          Rapor: reg.rapor_path,
          'SK Sehat': reg.sk_sehat_path,
          'SK Kelakuan': reg.sk_kelakuan_path
        };

        filesEl.innerHTML = Object.entries(fileFields).map(([label, filename]) => {
          const fileLink = filename ? `<a target="_blank" href="../uploads/${encodeURIComponent(filename)}" class="text-[#3d6625] hover:text-[#5a9a35] underline">Lihat file</a>` : '<span class="text-gray-500">Tidak ada file</span>';
          return `<div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-500">${label}</p>
            <div class="mt-2">${fileLink}</div>
          </div>`;
        }).join('');

        document.getElementById('registrationDetailModal').classList.remove('hidden');
      }

      async function updateRegistrationStatus(id, status) {
        if (!confirm(`Apakah Anda yakin ingin ${status === 'approved' ? 'menerima' : 'menolak'} pendaftaran ini?`)) return;

        try {
          const response = await fetch('../public/php/update-status.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id, status })
          });

          const result = await response.json();
          if (result.success) {
            alert('Status berhasil diperbarui');
            closeRegistrationDetailModal();
            loadDashboardData();
          } else {
            alert('Error: ' + result.message);
          }
        } catch (error) {
          console.error('Error updating status:', error);
          alert('Terjadi kesalahan saat memperbarui status');
        }
      }

      async function deleteRegistration(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')) return;

        try {
          const response = await fetch('../public/php/delete-registration.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id })
          });

          const result = await response.json();
          if (result.success) {
            alert('Pendaftaran berhasil dihapus');
            closeRegistrationDetailModal();
            loadDashboardData();
          } else {
            alert('Error: ' + result.message);
          }
        } catch (error) {
          console.error('Error deleting registration:', error);
          alert('Terjadi kesalahan saat menghapus pendaftaran');
        }
      }

      // Load data on page load
      loadDashboardData();
      // Refresh dashboard data every 15 seconds
      setInterval(loadDashboardData, 15000);
    </script>
  </body>
</html>