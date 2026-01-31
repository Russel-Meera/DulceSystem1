<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../config/database.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$firstName = trim($input['firstName'] ?? '');
$lastName = trim($input['lastName'] ?? '');
$email = trim($input['email'] ?? '');
$contactNumber = trim($input['contactNumber'] ?? '');
$address = trim($input['address'] ?? '');
$password = $input['password'] ?? '';

// Validation
$errors = [];

if (empty($firstName)) $errors[] = 'First name is required';
if (empty($lastName)) $errors[] = 'Last name is required';
if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}
if (empty($contactNumber)) $errors[] = 'Contact number is required';
if (empty($address)) $errors[] = 'Address is required';
if (empty($password)) {
    $errors[] = 'Password is required';
} elseif (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    $conn = getDBConnection();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        $stmt->close();
        closeDBConnection($conn);
        exit;
    }
    $stmt->close();
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $fullName = $firstName . ' ' . $lastName;
    
    // Insert new user (role_id = 1 for CLIENT by default)
    $stmt = $conn->prepare("INSERT INTO users (role_id, full_name, email, password_hash, contact_number, address, status) VALUES (1, ?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("sssss", $fullName, $email, $passwordHash, $contactNumber, $address);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Account created successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Registration failed. Please try again.'
        ]);
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>