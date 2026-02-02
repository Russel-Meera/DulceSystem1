<?php
header('Content-Type: application/json');
include 'dbConfig.php';

$response = [];

$sql = "SELECT id, name, description, capacity, capacity_type, features, image, badge FROM chapel_services WHERE is_active = 1 ORDER BY id DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Decode features from JSON to array
        $featuresArr = [];
        if (!empty($row['features'])) {
            $featuresArr = json_decode($row['features'], true);
            if (!is_array($featuresArr))
                $featuresArr = [$row['features']];
        }

        $response[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'], // added
            'capacity' => $row['capacity'],
            'capacity_type' => $row['capacity_type'],
            'features' => $featuresArr,
            'badge' => $row['badge'],
            'image' => $row['image'] ?? ''
        ];
    }
}

echo json_encode($response);
$conn->close();
