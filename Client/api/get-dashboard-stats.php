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
    
    // Get total bookings count (will work once bookings table exists)
    // For now, returning 0
    $totalBookings = 0;
    $pendingBookings = 0;
    $totalDocuments = 0;
    $totalPayments = 0;
    
    // TODO: Uncomment when bookings table is created
    /*
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM bookings WHERE client_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalBookings = $row['total'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as pending FROM bookings WHERE client_id = ? AND booking_status = 'Pending'");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $pendingBookings = $row['pending'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM documents d 
                           JOIN bookings b ON d.booking_id = b.booking_id 
                           WHERE b.client_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalDocuments = $row['total'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COALESCE(SUM(p.amount_paid), 0) as total FROM payments p 
                           JOIN billings bil ON p.billing_id = bil.billing_id 
                           JOIN bookings b ON bil.booking_id = b.booking_id 
                           WHERE b.client_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalPayments = $row['total'];
    $stmt->close();
    */
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'totalDocuments' => $totalDocuments,
            'totalPayments' => number_format($totalPayments, 2)
        ]
    ]);
    
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while fetching statistics'
    ]);
}
?>