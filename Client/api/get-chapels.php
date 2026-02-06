<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli("localhost", "root", "", "db_dulce");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$sql = "SELECT * FROM chapel_services WHERE is_active = 1 ORDER BY id DESC";
$result = $conn->query($sql);

$chapels = [];

while ($row = $result->fetch_assoc()) {
    $row['features'] = json_decode($row['features'], true);
    $row['price'] = $row['price'] ?? ($row['capacity'] ?? null);
    $row['price_type'] = $row['capacity_type'] ?? null;
    unset($row['capacity']);
    $chapels[] = $row;
}

echo json_encode($chapels);
