<?php
header("Content-Type: application/json");

require_once __DIR__ . "/dbConfig.php";

$sql = "SELECT id, name, birth_date, death_date, age, image_url, excerpt, full_biography, wake_schedule, chapel, viewing_hours, interment
        FROM obituaries
        ORDER BY death_date DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch obituaries"]);
    exit;
}

$rows = [];
while ($row = $result->fetch_assoc()) {
    $survivors = [];
    $survivorSql = "SELECT survivor_text FROM obituary_survivors WHERE obituary_id = " . (int) $row["id"] . " ORDER BY id ASC";
    $survivorResult = $conn->query($survivorSql);
    if ($survivorResult) {
        while ($s = $survivorResult->fetch_assoc()) {
            $survivors[] = $s["survivor_text"];
        }
    }

    $rows[] = [
        "id" => (int) $row["id"],
        "name" => $row["name"],
        "birthDate" => $row["birth_date"] ? date("F j, Y", strtotime($row["birth_date"])) : "",
        "deathDate" => $row["death_date"] ? date("F j, Y", strtotime($row["death_date"])) : "",
        "deathDateISO" => $row["death_date"] ?? "",
        "age" => isset($row["age"]) ? (int) $row["age"] : null,
        "image" => $row["image_url"] ?? "",
        "excerpt" => $row["excerpt"] ?? "",
        "fullBiography" => $row["full_biography"] ?? "",
        "wakeSchedule" => $row["wake_schedule"] ?? "",
        "chapel" => $row["chapel"] ?? "",
        "viewingHours" => $row["viewing_hours"] ?? "",
        "interment" => $row["interment"] ?? "",
        "survivedBy" => $survivors,
    ];
}

echo json_encode($rows);
