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
    
    // For now, returning empty array since bookings table doesn't exist yet
    // TODO: Uncomment when bookings table is created
    /*
    $sql = "SELECT b.*, p.package_name, c.chapel_name 
            FROM bookings b
            LEFT JOIN funeral_packages p ON b.package_id = p.package_id
            LEFT JOIN chapel_services c ON b.chapel_id = c.chapel_id
            WHERE b.client_id = ?";
    
    if ($filter !== 'all') {
        $sql .= " AND b.booking_status = ?";
    }
    
    $sql .= " ORDER BY b.created_at DESC";
    
    if ($filter !== 'all') {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $filter);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    $stmt->close();
    */
    
    $bookings = []; // Empty for now
    
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