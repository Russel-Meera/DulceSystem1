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

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$fullName = trim($input['fullName'] ?? '');
$contactNumber = trim($input['contactNumber'] ?? '');
$address = trim($input['address'] ?? '');

$errors = [];

if (empty($fullName)) $errors[] = 'Full name is required';
if (empty($contactNumber)) $errors[] = 'Contact number is required';
if (empty($address)) $errors[] = 'Address is required';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    $conn = getDBConnection();
    $userId = $_SESSION['user_id'];
    
    // Update user profile
    $stmt = $conn->prepare("UPDATE users 
                           SET full_name = ?, contact_number = ?, address = ? 
                           WHERE user_id = ?");
    $stmt->bind_param("sssi", $fullName, $contactNumber, $address, $userId);
    
    if ($stmt->execute()) {
        // Update session variable
        $_SESSION['full_name'] = $fullName;
        
        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update profile'
        ]);
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while updating profile'
    ]);
}
?>