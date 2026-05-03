<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Database configuration
$host = 'localhost';
$dbname = 'smp_yza2';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    $limit = 10; // Records per page
    $offset = ($page - 1) * $limit;

    // Build WHERE clause
    $whereConditions = [];
    $params = [];

    if (!empty($status)) {
        $whereConditions[] = "status = ?";
        $params[] = $status;
    }

    if (!empty($search)) {
        $whereConditions[] = "(nama_lengkap LIKE ? OR registration_number LIKE ? OR sekolah_asal LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
    }

    $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM ppdb_registrations $whereClause";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $totalRecords = $countStmt->fetch()['total'];

    // Get statistics
    $statsQuery = "SELECT
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM ppdb_registrations";
    $stats = $pdo->query($statsQuery)->fetch(PDO::FETCH_ASSOC);

    // Get paginated results
    $query = "SELECT id, registration_number, nama_lengkap, sekolah_asal, status, submitted_at
              FROM ppdb_registrations
              $whereClause
              ORDER BY submitted_at DESC
              LIMIT ? OFFSET ?";

    $stmt = $pdo->prepare($query);
    $stmt->execute(array_merge($params, [$limit, $offset]));
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate pagination info
    $totalPages = ceil($totalRecords / $limit);
    $start = $offset + 1;
    $end = min($offset + $limit, $totalRecords);

    echo json_encode([
        'stats' => $stats,
        'registrations' => $registrations,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $totalRecords,
            'start' => $start,
            'end' => $end,
            'hasPrev' => $page > 1,
            'hasNext' => $page < $totalPages
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>