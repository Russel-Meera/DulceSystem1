<?php
header('Content-Type: application/json');
require_once __DIR__ . '/dbConfig.php';

$sql = "SELECT id, bank_name, card_number, status, qr_image FROM payment_methods ORDER BY id DESC";
$result = $conn->query($sql);
$rows = [];

if ($result) {
  while ($row = $result->fetch_assoc()) {
    $rows[] = [
      "id" => (int)$row["id"],
      "bankName" => $row["bank_name"],
      "cardNumber" => $row["card_number"],
      "status" => $row["status"],
      "qrImage" => $row["qr_image"]
    ];
  }
}

echo json_encode($rows);
