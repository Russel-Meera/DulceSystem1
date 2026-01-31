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
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$rememberMe = $input['rememberMe'] ?? false;

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

try {
    $conn = getDBConnection();
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    
    // Check login attempts (prevent brute force)
    $stmt = $conn->prepare("SELECT COUNT(*) as attempt_count FROM login_attempts WHERE email = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND status = 'failed'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    if ($row['attempt_count'] >= 5) {
        echo json_encode([
            'success' => false,
            'message' => 'Too many failed attempts. Please try again in 15 minutes.'
        ]);
        closeDBConnection($conn);
        exit;
    }
    
    // Get user from database
    $stmt = $conn->prepare("SELECT u.user_id, u.full_name, u.email, u.password_hash, u.status, r.role_name 
                           FROM users u 
                           JOIN roles r ON u.role_id = r.role_id 
                           WHERE u.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Log failed attempt
        $stmt2 = $conn->prepare("INSERT INTO login_attempts (email, ip_address, status) VALUES (?, ?, 'failed')");
        $stmt2->bind_param("ss", $email, $ipAddress);
        $stmt2->execute();
        $stmt2->close();
        
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        $stmt->close();
        closeDBConnection($conn);
        exit;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Check if account is active
    if ($user['status'] !== 'active') {
        echo json_encode([
            'success' => false,
            'message' => 'Your account is ' . $user['status'] . '. Please contact support.'
        ]);
        closeDBConnection($conn);
        exit;
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        // Log failed attempt
        $stmt = $conn->prepare("INSERT INTO login_attempts (email, ip_address, status) VALUES (?, ?, 'failed')");
        $stmt->bind_param("ss", $email, $ipAddress);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        closeDBConnection($conn);
        exit;
    }
    
    // Only allow CLIENT role to login to client portal
    if ($user['role_name'] !== 'CLIENT') {
        echo json_encode([
            'success' => false,
            'message' => 'Access denied. This portal is for clients only.'
        ]);
        closeDBConnection($conn);
        exit;
    }
    
    // Log successful attempt
    $stmt = $conn->prepare("INSERT INTO login_attempts (email, ip_address, status) VALUES (?, ?, 'success')");
    $stmt->bind_param("ss", $email, $ipAddress);
    $stmt->execute();
    $stmt->close();
    
    // Create session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role_name'];
    $_SESSION['login_time'] = time();
    $_SESSION['ip_address'] = $ipAddress;
    
    // Generate session token for additional security
    $sessionToken = bin2hex(random_bytes(32));
    $_SESSION['session_token'] = $sessionToken;
    
    // Store session in database
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $expiresAt = $rememberMe ? date('Y-m-d H:i:s', strtotime('+30 days')) : date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    $stmt = $conn->prepare("INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, expires_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user['user_id'], $sessionToken, $ipAddress, $userAgent, $expiresAt);
    $stmt->execute();
    $stmt->close();
    
    // Set session cookie lifetime
    if ($rememberMe) {
        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60); // 30 days
        session_set_cookie_params(30 * 24 * 60 * 60);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful!',
        'user' => [
            'name' => $user['full_name'],
            'email' => $user['email']
        ]
    ]);
    
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>