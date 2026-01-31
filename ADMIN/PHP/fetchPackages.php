<?php
header('Content-Type: application/json');
include 'dbConfig.php';

$response = [];

$sql = "SELECT * FROM funeral_packages ORDER BY id DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $response[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'type' => $row['type'],
            'price' => $row['price'],
            'description' => $row['description'],
            'details' => $row['details'],
            'image' => $row['image']
        ];
    }
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

echo json_encode($response);

$conn->close();
