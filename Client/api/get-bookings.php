<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Not authenticated'
    ]);
    exit;
}

try {
    $conn = getDBConnection();
    $userId = $_SESSION['user_id'];
    
    // Get filter parameter
    $filter = $_GET['filter'] ?? 'all';
    
    $statusFilter = $filter;
    if ($statusFilter === 'approved') {
        $statusFilter = 'confirmed';
    }

    $sql = "SELECT b.booking_id, b.booking_status, b.service_date, b.service_time,
                   b.created_at, b.total_amount, c.name AS chapel_name
            FROM bookings b
            LEFT JOIN chapel_services c ON b.chapel_id = c.id
            WHERE b.client_id = ?";

    if ($statusFilter !== 'all') {
        $sql .= " AND b.booking_status = ?";
    }

    $sql .= " ORDER BY b.created_at DESC";

    if ($statusFilter !== 'all') {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $statusFilter);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $row['booking_status'] = ucfirst($row['booking_status']);
        $bookings[] = $row;
    }

    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'bookings' => $bookings
    ]);
    
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while fetching bookings'
    ]);
}
?>
