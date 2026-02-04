<?php
require_once __DIR__ . "/dbConfig.php";

$title = trim($_POST["title"] ?? "");
$category = trim($_POST["category"] ?? "");
$content = trim($_POST["content"] ?? "");
$announcementDate = trim($_POST["announcement_date"] ?? "");
$views = 0;
$isPinned = isset($_POST["is_pinned"]) ? 1 : 0;

if ($title === "" || $category === "" || $content === "" || $announcementDate === "") {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO announcements (title, category, content, is_pinned, views, announcement_date)
     VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database error."]);
    exit;
}

$stmt->bind_param("sssiss", $title, $category, $content, $isPinned, $views, $announcementDate);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Insert failed."]);
}
