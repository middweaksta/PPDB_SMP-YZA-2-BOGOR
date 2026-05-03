<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Pendaftaran - SMP YZA 2 Bogor</title>
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

      <div class="max-w-6xl mx-auto p-6">
        <!-- Back Button -->
        <div class="mb-6">
          <a href="ppdb-management.php" class="inline-flex items-center text-[#3d6625] hover:text-[#5a9a35] font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Pendaftaran
          </a>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="bg-white rounded-xl shadow-lg p-8 text-center">
          <div class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-[#3d6625]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-lg text-gray-600">Memuat data pendaftaran...</span>
          </div>
        </div>

        <!-- Main Content -->
        <div id="registration-content" class="hidden">
          <!-- Header Info -->
          <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h1 class="text-2xl font-bold text-[#3d6625]" id="registration-title">Detail Pendaftaran</h1>
                <p class="text-gray-600" id="registration-number">No. Pendaftaran: -</p>
              </div>
              <div class="text-right">
                <span id="status-badge" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                <p class="text-sm text-gray-500 mt-1" id="submitted-date">Tanggal: -</p>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3" id="action-buttons">
              <!-- Buttons will be inserted here -->
            </div>
          </div>

          <!-- Personal Information -->
          <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-[#3d6625] mb-4">Data Diri Calon Peserta Didik</h2>
            <div class="grid md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                  <p class="mt-1 text-gray-900" id="nama-lengkap">-</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                  <p class="mt-1 text-gray-900" id="tempat-lahir">-</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                  <p class="mt-1 text-gray-900" id="tanggal-lahir">-</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                  <p class="mt-1 text-gray-900" id="jenis-kelamin">-</p>
                </div>
              </div>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                  <p class="mt-1 text-gray-900" id="no-hp">-</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <p class="mt-1 text-gray-900" id="email">-</p>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                  <p class="mt-1 text-gray-900" id="alamat">-</p>
                </div>
              </div>
            </div>
          </div>

          <!-- School Information -->
          <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-[#3d6625] mb-4">Asal Sekolah</h2>
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Nama Sekolah Asal</label>
                <p class="mt-1 text-gray-900" id="sekolah-asal">-</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">NPSN Sekolah Asal</label>
                <p class="mt-1 text-gray-900" id="npsn">-</p>
              </div>
            </div>
          </div>

          <!-- Uploaded Documents -->
          <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-[#3d6625] mb-4">Dokumen yang Diupload</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4" id="documents-list">
              <!-- Documents will be inserted here -->
            </div>
          </div>

          <!-- Verification Notes -->
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-[#3d6625] mb-4">Catatan Verifikasi</h2>
            <div id="verification-notes">
              <p class="text-gray-600">Belum ada catatan verifikasi.</p>
            </div>

            <!-- Add Note Form -->
            <div class="mt-6 border-t pt-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Catatan</h3>
              <form id="note-form" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Catatan</label>
                  <textarea id="note-text" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-[#5a9a35] focus:border-[#5a9a35]" placeholder="Tambahkan catatan verifikasi..."></textarea>
                </div>
                <button type="submit" class="bg-[#3d6625] text-white px-4 py-2 rounded-md hover:bg-[#4a7c2c] transition-colors">
                  Simpan Catatan
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for viewing documents -->
    <div id="document-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b">
          <h3 class="text-xl font-bold text-[#3d6625]" id="modal-title">Dokumen</h3>
          <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <div class="p-6">
          <div id="modal-content" class="flex justify-center">
            <!-- Document content will be inserted here -->
          </div>
        </div>
      </div>
    </div>

    <script>
      const urlParams = new URLSearchParams(window.location.search);
      const registrationId = urlParams.get('id');

      if (!registrationId) {
        alert('ID pendaftaran tidak ditemukan');
        window.location.href = 'ppdb-management.html';
      }

      // Load registration details
      async function loadRegistrationDetails() {
        try {
          const response = await fetch(`../public/php/registration-detail.php?id=${registrationId}`);
          const data = await response.json();

          if (data.success) {
            displayRegistrationData(data.registration);
          } else {
            alert('Error: ' + data.message);
            window.location.href = 'ppdb-management.php';
          }
        } catch (error) {
          console.error('Error loading registration details:', error);
          alert('Terjadi kesalahan saat memuat data');
        }
      }

      // Display registration data
      function displayRegistrationData(registration) {
        // Update header
        document.getElementById('registration-title').textContent = `Detail Pendaftaran - ${registration.nama_lengkap}`;
        document.getElementById('registration-number').textContent = `No. Pendaftaran: ${registration.registration_number}`;
        document.getElementById('submitted-date').textContent = `Tanggal: ${new Date(registration.submitted_at).toLocaleDateString('id-ID')}`;

        // Update status badge
        const statusBadge = document.getElementById('status-badge');
        statusBadge.className = `inline-flex px-3 py-1 text-sm font-semibold rounded-full ${
          registration.status === 'approved' ? 'bg-green-100 text-green-800' :
          registration.status === 'rejected' ? 'bg-red-100 text-red-800' :
          registration.status === 'verified' ? 'bg-blue-100 text-blue-800' :
          'bg-yellow-100 text-yellow-800'
        }`;
        statusBadge.textContent = registration.status === 'approved' ? 'Diterima' :
                                 registration.status === 'rejected' ? 'Ditolak' :
                                 registration.status === 'verified' ? 'Terverifikasi' :
                                 'Menunggu';

        // Update action buttons
        const actionButtons = document.getElementById('action-buttons');
        let buttonsHtml = '';

        if (registration.status === 'pending') {
          buttonsHtml = `
            <button onclick="updateStatus('verified')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
              Verifikasi
            </button>
          `;
        } else if (registration.status === 'verified') {
          buttonsHtml = `
            <button onclick="updateStatus('approved')" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors mr-2">
              Terima Pendaftaran
            </button>
            <button onclick="updateStatus('rejected')" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
              Tolak Pendaftaran
            </button>
          `;
        }

        actionButtons.innerHTML = buttonsHtml;

        // Update personal information
        document.getElementById('nama-lengkap').textContent = registration.nama_lengkap;
        document.getElementById('tempat-lahir').textContent = registration.tempat_lahir;
        document.getElementById('tanggal-lahir').textContent = new Date(registration.tanggal_lahir).toLocaleDateString('id-ID');
        document.getElementById('jenis-kelamin').textContent = registration.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
        document.getElementById('no-hp').textContent = registration.no_hp;
        document.getElementById('email').textContent = registration.email || '-';
        document.getElementById('alamat').textContent = registration.alamat;

        // Update school information
        document.getElementById('sekolah-asal').textContent = registration.sekolah_asal;
        document.getElementById('npsn').textContent = registration.npsn || '-';

        // Update documents
        displayDocuments(registration);

        // Update verification notes
        displayNotes(registration);

        // Show content and hide loading
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('registration-content').classList.remove('hidden');
      }

      // Display uploaded documents
      function displayDocuments(registration) {
        const documentsList = document.getElementById('documents-list');
        const documents = [
          { key: 'ijazah_path', label: 'Fotokopi Ijazah SD/MI', required: true },
          { key: 'shun_path', label: 'Fotokopi SHUN SD/MI', required: true },
          { key: 'kk_path', label: 'Fotokopi Kartu Keluarga', required: true },
          { key: 'akta_path', label: 'Fotokopi Akta Kelahiran', required: true },
          { key: 'pas_foto_path', label: 'Pas Foto Berwarna 3x4', required: true },
          { key: 'rapor_path', label: 'Fotokopi Rapor Kelas 4,5,6', required: true },
          { key: 'sk_sehat_path', label: 'Surat Keterangan Sehat', required: true },
          { key: 'sk_kelakuan_path', label: 'Surat Keterangan Kelakuan Baik', required: true }
        ];

        documentsList.innerHTML = documents.map(doc => {
          const filePath = registration[doc.key];
          const hasFile = filePath && filePath.trim() !== '';

          return `
            <div class="border border-gray-200 rounded-lg p-4 ${hasFile ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900">${doc.label}</h4>
                  <p class="text-sm ${hasFile ? 'text-green-600' : 'text-red-600'}">
                    ${hasFile ? '✓ File tersedia' : '✗ File belum diupload'}
                  </p>
                </div>
                ${hasFile ? `
                  <button onclick="viewDocument('${filePath}', '${doc.label}')" class="text-[#3d6625] hover:text-[#5a9a35] font-medium text-sm">
                    Lihat File
                  </button>
                ` : ''}
              </div>
            </div>
          `;
        }).join('');
      }

      // Display verification notes
      function displayNotes(registration) {
        const notesContainer = document.getElementById('verification-notes');

        if (registration.notes && registration.notes.trim() !== '') {
          notesContainer.innerHTML = `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
              <p class="text-blue-800">${registration.notes}</p>
              ${registration.verified_at ? `<p class="text-sm text-blue-600 mt-2">Diverifikasi pada: ${new Date(registration.verified_at).toLocaleString('id-ID')}</p>` : ''}
            </div>
          `;
        } else {
          notesContainer.innerHTML = '<p class="text-gray-600">Belum ada catatan verifikasi.</p>';
        }
      }

      // View document in modal
      function viewDocument(filePath, title) {
        const modal = document.getElementById('document-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');

        modalTitle.textContent = title;

        // Determine file type and display accordingly
        const fileExtension = filePath.split('.').pop().toLowerCase();

        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
          modalContent.innerHTML = `<img src="../uploads/${filePath}" alt="${title}" class="max-w-full max-h-96 object-contain">`;
        } else if (fileExtension === 'pdf') {
          modalContent.innerHTML = `<iframe src="../uploads/${filePath}" class="w-full h-96 border-0"></iframe>`;
        } else {
          modalContent.innerHTML = `
            <div class="text-center">
              <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <p class="text-gray-600">File tidak dapat dipreview. <a href="../uploads/${filePath}" target="_blank" class="text-[#3d6625] hover:text-[#5a9a35]">Download file</a></p>
            </div>
          `;
        }

        modal.classList.remove('hidden');
      }

      // Close modal
      function closeModal() {
        document.getElementById('document-modal').classList.add('hidden');
      }

      // Update registration status
      async function updateStatus(status) {
        const confirmMessage = status === 'approved' ? 'Apakah Anda yakin ingin MENERIMA pendaftaran ini?' :
                              status === 'rejected' ? 'Apakah Anda yakin ingin MENOLAK pendaftaran ini?' :
                              'Apakah Anda yakin ingin MEMVERIFIKASI pendaftaran ini?';

        if (!confirm(confirmMessage)) {
          return;
        }

        try {
          const response = await fetch('../public/php/update-status.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: registrationId, status: status })
          });

          const result = await response.json();

          if (result.success) {
            alert('Status berhasil diperbarui');
            location.reload();
          } else {
            alert('Error: ' + result.message);
          }
        } catch (error) {
          console.error('Error updating status:', error);
          alert('Terjadi kesalahan saat memperbarui status');
        }
      }

      // Handle note form submission
      document.getElementById('note-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const noteText = document.getElementById('note-text').value.trim();
        if (!noteText) {
          alert('Catatan tidak boleh kosong');
          return;
        }

        try {
          const response = await fetch('../public/php/add-note.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: registrationId, note: noteText })
          });

          const result = await response.json();

          if (result.success) {
            alert('Catatan berhasil ditambahkan');
            document.getElementById('note-text').value = '';
            loadRegistrationDetails(); // Reload to show new note
          } else {
            alert('Error: ' + result.message);
          }
        } catch (error) {
          console.error('Error adding note:', error);
          alert('Terjadi kesalahan saat menambah catatan');
        }
      });

      // Load data on page load
      loadRegistrationDetails();

      // Close modal when clicking outside
      document.getElementById('document-modal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeModal();
        }
      });
    </script>
  </body>
</html>