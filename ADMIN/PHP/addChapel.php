<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include 'dbConfig.php';

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Fetch POST values
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? '';
$price_type = $_POST['price_type'] ?? '';
$features = $_POST['features'] ?? []; // array from features[] inputs
$badge = $_POST['badge'] ?? '';
$is_active = 1; // default to active

if (empty($name) || empty($description) || empty($price) || empty($price_type)) {
    $response['message'] = 'Please fill all required fields';
    echo json_encode($response);
    exit;
}

// Convert features array to JSON
$featuresJson = json_encode(array_values(array_filter($features))); // remove empty strings

$imageName = null;

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) { // 4 = no file
    $fileError = $_FILES['image']['error'];
    if ($fileError !== 0) {
        $response['message'] = 'File upload error code: ' . $fileError;
        echo json_encode($response);
        exit;
    }

    $uploadDir = '../uploads/packages/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $response['message'] = 'Failed to create upload directory';
            echo json_encode($response);
            exit;
        }
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowedExt)) {
        $response['message'] = 'Invalid file type. Only JPG, PNG, GIF allowed';
        echo json_encode($response);
        exit;
    }

    $imageName = time() . '_' . uniqid() . '.' . $ext;
    $targetFile = $uploadDir . $imageName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $response['message'] = 'Failed to move uploaded file. Check folder permissions';
        echo json_encode($response);
        exit;
    }
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO chapel_services 
(name, description, price, capacity_type, features, image, badge, is_active, created_at, updated_at) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

if (!$stmt) {
    $response['message'] = 'DB Prepare Failed: ' . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ssdssssi", $name, $description, $price, $price_type, $featuresJson, $imageName, $badge, $is_active);

if ($stmt->execute()) {
    $response = [
        'status' => 'success',
        'id' => $stmt->insert_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'price_type' => $price_type,
        'features' => $featuresJson,
        'image' => $imageName,
        'badge' => $badge
    ];
} else {
    $response['message'] = 'DB Execute Failed: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
