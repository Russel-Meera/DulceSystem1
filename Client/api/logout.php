<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

try {
    // Delete session from database
    if (isset($_SESSION['session_token'])) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_token = ?");
        $stmt->bind_param("s", $_SESSION['session_token']);
        $stmt->execute();
        $stmt->close();
        closeDBConnection($conn);
    }
    
    // Destroy session
    session_unset();
    session_destroy();
    
    echo json_encode([
        'success' => true,
        'message' => 'Logged out successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Logout failed'
    ]);
}
?>