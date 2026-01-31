<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include 'dbConfig.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

$sql = "SELECT * FROM funeral_packages ORDER BY id ASC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        'status' => 'error',
        'message' => 'DB Query Failed: ' . $conn->error
    ]);
    exit;
}

$packages = [];

while ($row = $result->fetch_assoc()) {

    // Decode details JSON safely
    $details = json_decode($row['details'], true);
    if (!is_array($details)) {
        $details = [];
    }

    $packages[$row['id']] = [
        'id' => (int) $row['id'],
        'name' => $row['name'],
        'type' => $row['type'],
        'price' => (int) $row['price'], // ✅ NUMBER ONLY
        'description' => $row['description'],
        'details' => $details,
        'image' => !empty($row['image'])
            ? '../../ADMIN/uploads/packages/' . $row['image']
            : null
    ];
}

echo json_encode([
    'status' => 'success',
    'packages' => $packages
]);

$conn->close();
