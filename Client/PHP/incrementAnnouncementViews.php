<?php
header("Content-Type: application/json");

require_once __DIR__ . "/dbConfig.php";

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid ID."]);
    exit;
}

$stmt = $conn->prepare("UPDATE announcements SET views = views + 1 WHERE id = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database error."]);
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed."]);
}
