<?php
require_once __DIR__ . "/dbConfig.php";

$sql = "SELECT id, title, category, content, is_pinned, views, announcement_date
        FROM announcements
        ORDER BY is_pinned DESC, announcement_date DESC, id DESC";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([]);
    exit;
}

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = [
        "id" => (int) $row["id"],
        "title" => $row["title"],
        "category" => $row["category"],
        "content" => $row["content"],
        "is_pinned" => (int) $row["is_pinned"],
        "views" => (int) $row["views"],
        "announcement_date" => $row["announcement_date"],
    ];
}

echo json_encode($rows);
