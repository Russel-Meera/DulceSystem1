<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['session_token'])) {
    echo json_encode([
        'logged_in' => false
    ]);
    exit;
}

try {
    $conn = getDBConnection();
    
    // Verify session token in database
    $stmt = $conn->prepare("SELECT user_id FROM user_sessions WHERE session_token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $_SESSION['session_token']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Session expired or invalid
        session_unset();
        session_destroy();
        
        echo json_encode([
            'logged_in' => false
        ]);
    } else {
        echo json_encode([
            'logged_in' => true,
            'user' => [
                'name' => $_SESSION['full_name'],
                'email' => $_SESSION['email']
            ]
        ]);
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo json_encode([
        'logged_in' => false
    ]);
}
?>