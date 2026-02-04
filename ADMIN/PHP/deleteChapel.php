<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include 'dbConfig.php';

$response = ['status' => 'error', 'message' => 'Unknown error'];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    $response['message'] = 'Invalid ID';
    echo json_encode($response);
    exit;
}

// Get current image
$currentImage = null;
$stmt = $conn->prepare("SELECT image FROM chapel_services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($currentImage);
$stmt->fetch();
$stmt->close();

// Delete record
$stmt = $conn->prepare("DELETE FROM chapel_services WHERE id = ?");
if (!$stmt) {
    $response['message'] = 'DB Prepare Failed: ' . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    // Remove image file
    if (!empty($currentImage)) {
        $uploadDir = '../uploads/packages/';
        $oldPath = $uploadDir . $currentImage;
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }
    }
    $response = ['status' => 'success'];
} else {
    $response['message'] = 'DB Execute Failed: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
