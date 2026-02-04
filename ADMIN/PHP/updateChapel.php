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

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$capacity_type = $_POST['capacity_type'] ?? '';
$features = $_POST['features'] ?? [];
$badge = $_POST['badge'] ?? '';

if (empty($id) || empty($name) || empty($description) || empty($capacity) || empty($capacity_type)) {
    $response['message'] = 'Please fill all required fields';
    echo json_encode($response);
    exit;
}

$featuresJson = json_encode(array_values(array_filter($features)));

// Get current image
$currentImage = null;
$stmt = $conn->prepare("SELECT image FROM chapel_services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($currentImage);
$stmt->fetch();
$stmt->close();

$imageName = $currentImage;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) {
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

    // Remove old image if replaced
    if (!empty($currentImage) && $currentImage !== $imageName) {
        $oldPath = $uploadDir . $currentImage;
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }
    }
}

$stmt = $conn->prepare("UPDATE chapel_services 
SET name = ?, description = ?, capacity = ?, capacity_type = ?, features = ?, image = ?, badge = ?, updated_at = NOW()
WHERE id = ?");

if (!$stmt) {
    $response['message'] = 'DB Prepare Failed: ' . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ssissssi", $name, $description, $capacity, $capacity_type, $featuresJson, $imageName, $badge, $id);

if ($stmt->execute()) {
    $response = ['status' => 'success'];
} else {
    $response['message'] = 'DB Execute Failed: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
