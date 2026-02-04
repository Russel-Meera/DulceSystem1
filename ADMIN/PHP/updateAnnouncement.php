<?php
require_once __DIR__ . "/dbConfig.php";

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
$title = trim($_POST["title"] ?? "");
$category = trim($_POST["category"] ?? "");
$content = trim($_POST["content"] ?? "");
$announcementDate = trim($_POST["announcement_date"] ?? "");
$views = null;
$isPinned = isset($_POST["is_pinned"]) ? 1 : 0;

if ($id <= 0 || $title === "" || $category === "" || $content === "" || $announcementDate === "") {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

$stmt = $conn->prepare(
    "UPDATE announcements
     SET title = ?, category = ?, content = ?, is_pinned = ?, announcement_date = ?
     WHERE id = ?"
);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database error."]);
    exit;
}

$stmt->bind_param("sssisi", $title, $category, $content, $isPinned, $announcementDate, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed."]);
}
