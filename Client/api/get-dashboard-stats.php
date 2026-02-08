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

    $totalBookings = 0;
    $pendingBookings = 0;
    $totalDocuments = 0;
    $totalPayments = 0;

    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM bookings WHERE client_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalBookings = (int) ($row['total'] ?? 0);
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) as pending FROM bookings WHERE client_id = ? AND booking_status = 'pending'");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $pendingBookings = (int) ($row['pending'] ?? 0);
    $stmt->close();

    // Optional documents: count obituaries if linked to client.
    $hasObituaryClient = false;
    $check = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'client_id'");
    if ($check && $check->num_rows > 0) {
        $hasObituaryClient = true;
    }
    if ($check) {
        $check->free();
    }

    if ($hasObituaryClient) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM obituaries WHERE client_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $totalDocuments = (int) ($row['total'] ?? 0);
        $stmt->close();
    }

    $stmt = $conn->prepare("SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE client_id = ? AND payment_status = 'paid'");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalPayments = (float) ($row['total'] ?? 0);
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'totalDocuments' => $totalDocuments,
            'totalPayments' => $totalPayments
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
