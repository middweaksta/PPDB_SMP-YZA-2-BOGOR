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
if (!isLoggedIn()) {
    header('Location: login-admin.html');
    exit;
}
$pdo = getDBConnection();
$users = $pdo->query("SELECT id, username, email, name, role, status, created_at FROM admin_users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$logs = $pdo->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="min-h-screen">
        <header class="bg-[#3d6625] py-3 px-6 flex items-center justify-between shadow-md">
            <a href="dashboard.html" class="flex items-center gap-3">
                <img src="https://www.upload.ee/image/19236257/BYZAD.png" alt="Logo SMP YZA 2 Bogor" class="w-12 h-12 rounded-full object-cover">
                <span class="text-white text-xl font-bold tracking-wide">YZA 2 BOGOR - ADMIN</span>
            </a>
            <div class="flex items-center gap-4">
                <span class="text-white">Selamat datang, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
                <a href="../public/php/logout.php" onclick="return confirm('Logout?')" class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all border border-white/20">Logout</a>
            </div>
        </header>

        <div class="flex">
            <nav class="w-64 bg-white shadow-lg min-h-screen">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-[#3d6625] mb-4">Menu Admin</h2>
                    <ul class="space-y-2">
                        <li><a href="dashboard.html" class="block px-4 py-2 text-gray-700 hover:bg-[#f0f9eb] rounded-lg transition-colors">Dashboard</a></li>
                        <li><a href="user-management-fixed.php" class="block px-4 py-2 bg-[#3d6625] text-white rounded-lg transition-colors">Kelola User & Logs</a></li>
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
                            <button onclick="showAddUserModal()" class="bg-[#3d6625] text-white px-6 py-2 rounded-lg hover:bg-[#5a9a35] transition-all shadow-md">+ Tambah User</button>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table">
                                    <?php foreach ($users as $user): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($user['username']) ?></td>
                                        <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($user['email']) ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $user['role'] === 'super_admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>"><?= ucwords(str_replace('_', ' ', $user['role'])) ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>"><?= $user['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                                            <button onclick="toggleStatus(<?= $user['id'] ?>, '<?= $user['status'] ?>')" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all text-xs mr-2"><?= $user['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                                            <button onclick="deleteUser(<?= $user['id'] ?>)" class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-all text-xs">Hapus</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($users)): ?>
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody id="logs-table">
                                        <?php foreach ($logs as $log): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                                            <td class="px-6 py-4 font-medium text-sm"><?= htmlspecialchars($log['username'] ?? 'Guest') ?></td>
                                            <td class="px-6 py-4 text-sm max-w-md truncate" title="<?= htmlspecialchars($log['activity']) ?>"><?= htmlspecialchars($log['activity']) ?></td>
                                            <td class="px-6 py-4 text-sm font-mono text-gray-500"><?= htmlspecialchars($log['ip_address']) ?></td>
                                            <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate" title="<?= htmlspecialchars($log['user_agent']) ?>"><?= htmlspecialchars(substr($log['user_agent'], 0, 30)) ?>...</td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($logs)): ?>
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
        <div id="add-user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" role="dialog">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                <h3 class="text-2xl font-bold text-[#3d6625] mb-6">Tambah User Baru</h3>
                <form id="add-user-form">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Username *</label>
                            <input type="text" id="new-username" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Email *</label>
                            <input type="email" id="new-email" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Nama Lengkap *</label>
                            <input type="text" id="new-name" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Role *</label>
                            <select id="new-role" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625] focus:border-transparent transition-all">
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Password *</label>
                            <input type="password" id="new-password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3d6625] focus:border-transparent transition-all">
                        </div>
                    </div>
                    <div class="flex gap-3 mt-8">
                        <button type="submit" class="flex-1 bg-[#3d6625] text-white py-3 rounded-lg hover:bg-[#5a9a35] transition-all font-medium shadow-md hover:shadow-lg">Tambah User</button>
                        <button type="button" onclick="hideAddUserModal()" class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 transition-all font-medium">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
// JS functions for API refresh

        // Modal functions
        function showAddUserModal() {
            document.getElementById('add-user-modal').classList.remove('hidden');
        }

        function hideAddUserModal() {
            document.getElementById('add-user-modal').classList.add('hidden');
            document.getElementById('add-user-form').reset();
        }

        document.getElementById('add-user-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.disabled = true;

            const formData = new FormData(e.target);
            formData.append('action', 'create');

            try {
                const res = await fetch('../public/php/user-api.php', { method: 'POST', credentials: 'same-origin', body: formData });
                if (res.ok) {
                    hideAddUserModal();
                    window.location.reload();
                } else {
                    if (res.status === 401) {
                        document.location = '../html/login-admin.html';
                        return;
                    }
                    const error = await res.json();
                    alert('Error: ' + (error.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Create user error:', error);
                alert('Network error: ' + error.message);
            }

            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });

        async function toggleStatus(id, currentStatus) {
            if (!confirm(`Yakin ${currentStatus === 'active' ? 'nonaktifkan' : 'aktifkan'} user ini?`)) return;

            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('id', id);
            formData.append('status', currentStatus === 'active' ? 'inactive' : 'active');

            try {
                const res = await fetch('../public/php/user-api.php', { method: 'POST', credentials: 'same-origin', body: formData });
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
                        statusSpan.className = `px-3 py-1 rounded-full text-xs font-semibold ${newStatus === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;

                        // Update button
                        actionButton.textContent = newStatus === 'active' ? 'Nonaktifkan' : 'Aktifkan';
                        actionButton.className = `px-3 py-1 ${newStatus === 'active' ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'} rounded-md transition-all text-xs mr-2`;
                        actionButton.setAttribute('onclick', `toggleStatus(${id}, '${newStatus}')`);
                    }
                    alert('Status berhasil diupdate!');
                } else {
                    if (res.status === 401) {
                        document.location = '../html/login-admin.html';
                        return;
                    }
                    alert('Error updating status');
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
        }

        async function deleteUser(id) {
            if (!confirm('Yakin hapus user ini? Data tidak bisa dikembalikan!')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            try {
                const res = await fetch('../public/php/user-api.php', { method: 'POST', credentials: 'same-origin', body: formData });
                if (res.ok) {
                    window.location.reload();
                } else {
                    if (res.status === 401) {
                        document.location = '../html/login-admin.html';
                        return;
                    }
                    alert('Error deleting user');
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
        }

        // Set active sidebar item
        const currentPage = window.location.pathname.split('/').pop();
        const sidebarLinks = document.querySelectorAll('nav a');
        sidebarLinks.forEach(link => {
            link.classList.remove('bg-[#3d6625]', 'text-white');
            link.classList.add('text-gray-700', 'hover:bg-[#f0f9eb]');
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('bg-[#3d6625]', 'text-white');
                link.classList.remove('text-gray-700', 'hover:bg-[#f0f9eb]');
            }
        });

        </script>
</body>
</html>

