<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User & Logs - SMP YZA 2 Bogor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<?php
session_start();
require_once '../public/php/config.php';
requireLogin();
$currentPage = basename($_SERVER['PHP_SELF']);

$pdo = getDBConnection();
$stmt = $pdo->query("SELECT id, username, email, name, role, status, created_at, last_login FROM admin_users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 50");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script>
window.initialUsers = <?= json_encode($users, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
window.initialLogs = <?= json_encode($logs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>

    <div class="min-h-screen">
        <header class="bg-[#3d6625] py-3 px-6 flex items-center justify-between shadow-md">
<a href="dashboard.html" class="flex items-center gap-3">
                <img src="https://www.upload.ee/image/19236257/BYZAD.png" alt="Logo SMP YZA 2 Bogor" class="w-12 h-12 rounded-full object-cover">
                <span class="text-white text-xl font-bold tracking-wide">YZA 2 BOGOR - ADMIN</span>
            </a>
            <div class="flex items-center gap-4">
                <span id="admin-name" class="text-white"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                <a href="../public/php/logout.php" onclick="return confirm('Logout?')" class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all border border-white/20">Logout</a>
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

            <main class="flex-1 p-6">
                <div class="max-w-6xl mx-auto">
                    <h1 class="text-3xl font-bold text-[#3d6625] mb-8">Kelola User Admin & Logs Aktivitas</h1>
                    
                    <!-- User Management Section -->
                    <div class="mb-12">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-[#3d6625]">Kelola User Admin</h2>
                            <button onclick="showAddUserModal()" class="bg-[#3d6625] text-white px-6 py-2 rounded-lg hover:bg-[#5a9a35] transition-all">+ Tambah User</button>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                        <th class="px-6 py-3 text-left">Email</th>
                                        <th class="px-6 py-3 text-left">Role</th>
                                        <th class="px-6 py-3 text-left">Status</th>
                                        <th class="px-6 py-3 text-left">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table">
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($user['username']) ?></td>
                                                <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 py-1 rounded-full text-xs <?= $user['role'] === 'super_admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>"><?= htmlspecialchars(str_replace('_', ' ', strtoupper($user['role']))) ?></span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>"><?= $user['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?></span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <button onclick="toggleStatus(<?= $user['id'] ?>, '<?= $user['status'] ?>')" class="mr-2 px-3 py-1 <?= $user['status'] === 'active' ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' ?> rounded text-sm"><?= $user['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                                                    <button onclick="deleteUser(<?= $user['id'] ?>)" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm">Hapus</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-8 text-gray-500">No users found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Activity Logs Section -->
                    <div>
                        <h2 class="text-2xl font-bold text-[#3d6625] mb-6">Logs Aktivitas Sistem</h2>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="overflow-x-auto">
                                <table class="w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody id="logs-table" class="divide-y divide-gray-200">
                                        <?php if (!empty($logs)): ?>
                                            <?php foreach ($logs as $log): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                                                <td class="px-6 py-4 font-medium text-sm"><?= htmlspecialchars($log['username'] ?? 'Guest') ?></td>
                                                <td class="px-6 py-4 text-sm"><?= htmlspecialchars($log['activity']) ?></td>
                                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($log['ip_address']) ?></td>
                                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars(substr($log['user_agent'], 0, 50)) ?>...</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center py-8 text-gray-500">No logs found</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Add User Modal -->
        <div id="add-user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full max-h-[90vh] overflow-y-auto">
                <h3 class="text-2xl font-bold text-[#3d6625] mb-6">Tambah User Baru</h3>
                <form id="add-user-form">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Username</label>
                            <input type="text" id="new-username" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Email</label>
                            <input type="email" id="new-email" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" id="new-name" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Role</label>
                            <select id="new-role" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625]">
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Password</label>
                            <input type="password" id="new-password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625]">
                        </div>
                    </div>
                    <div class="flex gap-3 mt-8">
                        <button type="submit" class="flex-1 bg-[#3d6625] text-white py-3 rounded-lg hover:bg-[#5a9a35]">Tambah User</button>
                        <button type="button" onclick="hideAddUserModal()" class="flex-1 bg-gray-300 py-3 rounded-lg hover:bg-gray-400">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        // User Management Functions
        function showAddUserModal() {
            document.getElementById('add-user-modal').classList.remove('hidden');
        }

        function hideAddUserModal() {
            document.getElementById('add-user-modal').classList.add('hidden');
            document.getElementById('add-user-form').reset();
        }

document.getElementById('add-user-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'create');
            formData.append('username', document.getElementById('new-username').value);
            formData.append('email', document.getElementById('new-email').value);
            formData.append('name', document.getElementById('new-name').value);
            formData.append('role', document.getElementById('new-role').value);
            formData.append('password', document.getElementById('new-password').value);
            
            try {
                const res = await fetch('../public/php/user-api.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                });
                const text = await res.text();
                const result = text && text.trim().startsWith('{') ? JSON.parse(text) : { error: text || 'Unknown response' };
                if (res.ok) {
                    hideAddUserModal();
                    window.location.reload();
                } else {
                    if (res.status === 401) {
                        document.location = '../html/login-admin.html';
                        return;
                    }
                    const result = text && text.trim().startsWith('{') ? JSON.parse(text) : { error: text || 'Unknown response' };
                    alert('Error: ' + (result.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Create user error:', error);
                alert('Network error: ' + error.message);
            }
        });

async function toggleStatus(id, currentStatus) {
            if (!confirm(`Yakin ${currentStatus === 'active' ? 'nonaktifkan' : 'aktifkan'} user ini?`)) return;
            
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('id', id);
            formData.append('status', currentStatus === 'active' ? 'inactive' : 'active');
            
            try {
                const res = await fetch('../public/php/user-api.php', { method: 'POST', credentials: 'same-origin', body: formData });
                const text = await res.text();
                const result = text && text.trim().startsWith('{') ? JSON.parse(text) : { error: text || 'Unknown response' };
                if (res.ok) {
                    // Update UI langsung tanpa reload
                    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                    const button = document.querySelector(`button[onclick*="toggleStatus(${id}, '${currentStatus}')"]`);
                    if (button) {
                        const row = button.closest('tr');
                        const statusSpan = row.querySelector('td:nth-child(4) span');
                        const actionButton = row.querySelector('td:nth-child(5) button:first-child');

                        // Update status span
                        statusSpan.textContent = newStatus === 'active' ? 'Aktif' : 'Nonaktif';
                        statusSpan.className = `px-3 py-1 rounded-full text-xs font-medium ${newStatus === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;

                        // Update button
                        actionButton.textContent = newStatus === 'active' ? 'Nonaktifkan' : 'Aktifkan';
                        actionButton.className = `mr-2 px-3 py-1 ${newStatus === 'active' ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'} rounded text-sm`;
                        actionButton.setAttribute('onclick', `toggleStatus(${id}, '${newStatus}')`);
                    }
                    alert('Status berhasil diupdate!');
                } else {
                    if (res.status === 401) {
                        document.location = '../html/login-admin.html';
                        return;
                    }
                    alert('Error: ' + (result.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Toggle status error:', error);
                alert('Network error');
            }
        }

async function deleteUser(id) {
            if (!confirm('Yakin hapus user ini? Data tidak bisa dikembalikan!')) return;
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);
            
            try {
                const res = await fetch('../public/php/user-api.php', { method: 'POST', credentials: 'same-origin', body: formData });
                const text = await res.text();
                const result = text && text.trim().startsWith('{') ? JSON.parse(text) : { error: text || 'Unknown response' };
                if (res.ok) {
                    window.location.reload();
                } else {
                    if (res.status === 401) {
                        document.location = '../html/login-admin.html';
                        return;
                    }
                    alert('Error: ' + (result.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Delete user error:', error);
                alert('Network error');
            }
        }

        // Log page view
        fetch('../public/php/log-activity-api.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({activity: 'Mengakses halaman kelola user & logs'})
        }).catch(console.error);
        </script>

</body>
</html>

