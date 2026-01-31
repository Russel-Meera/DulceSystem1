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
$type = $_POST['type'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';
$details = $_POST['details'] ?? []; // This is an array from details[] inputs

if (empty($name) || empty($type) || empty($price) || empty($description)) {
    $response['message'] = 'Please fill all required fields';
    echo json_encode($response);
    exit;
}

// Convert details array to JSON for storage
$detailsJson = json_encode(array_values(array_filter($details))); // Remove empty strings

$imageName = null;

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) { // 4 = no file uploaded
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

// Prepare and execute DB insert
$stmt = $conn->prepare("INSERT INTO funeral_packages (name, type, price, description, details, image, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
if (!$stmt) {
    $response['message'] = 'DB Prepare Failed: ' . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ssisss", $name, $type, $price, $description, $detailsJson, $imageName);

if ($stmt->execute()) {
    $response = [
        'status' => 'success',
        'id' => $stmt->insert_id,
        'name' => $name,
        'type' => $type,
        'price' => $price,
        'description' => $description,
        'details' => $detailsJson,
        'image' => $imageName
    ];
} else {
    $response['message'] = 'DB Execute Failed: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
