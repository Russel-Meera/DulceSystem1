<?php
header('Content-Type: application/json');
require_once __DIR__ . '/dbConfig.php';

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id <= 0) {
  echo json_encode(["success" => false, "message" => "Invalid ID."]);
  exit;
}

$currentQr = null;
$check = $conn->prepare("SELECT qr_image FROM payment_methods WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$check->bind_result($currentQr);
$check->fetch();
$check->close();

$stmt = $conn->prepare("DELETE FROM payment_methods WHERE id = ?");
$stmt->bind_param("i", $id);
$ok = $stmt->execute();

if ($ok && $currentQr && file_exists(__DIR__ . '/../../' . $currentQr)) {
  unlink(__DIR__ . '/../../' . $currentQr);
}

echo json_encode(["success" => $ok]);
