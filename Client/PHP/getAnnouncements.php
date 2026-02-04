<?php
header("Content-Type: application/json");

require_once __DIR__ . "/dbConfig.php";

$sql = "SELECT id, title, category, content, is_pinned, views, announcement_date
        FROM announcements
        ORDER BY is_pinned DESC, announcement_date DESC, id DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch announcements"]);
    exit;
}

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = [
        "id" => (int) $row["id"],
        "title" => $row["title"],
        "category" => $row["category"],
        "content" => $row["content"],
        "isPinned" => (int) $row["is_pinned"],
        "views" => (int) $row["views"],
        "date" => $row["announcement_date"],
        "dateFormatted" => $row["announcement_date"]
            ? date("F j, Y", strtotime($row["announcement_date"]))
            : "",
        "author" => "DULCE Admin",
    ];
}

echo json_encode($rows);
