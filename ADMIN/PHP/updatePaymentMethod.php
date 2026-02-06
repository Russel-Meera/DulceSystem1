<?php
header('Content-Type: application/json');
require_once __DIR__ . '/dbConfig.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$bankName = trim($_POST['bankName'] ?? '');
$cardNumber = trim($_POST['cardNumber'] ?? '');
$status = ($_POST['status'] ?? 'enabled') === 'disabled' ? 'disabled' : 'enabled';
$qrImagePath = null;

if ($id <= 0 || $bankName === '' || $cardNumber === '') {
  echo json_encode(["success" => false, "message" => "Invalid data."]);
  exit;
}

$currentQr = null;
$check = $conn->prepare("SELECT qr_image FROM payment_methods WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$check->bind_result($currentQr);
$check->fetch();
$check->close();

if (!empty($_FILES['qrImage']['name'])) {
  $uploadDir = __DIR__ . '/../../uploads/paymentsQR/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }
  $ext = pathinfo($_FILES['qrImage']['name'], PATHINFO_EXTENSION);
  $safeName = 'qr_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
  $targetPath = $uploadDir . $safeName;
  if (!move_uploaded_file($_FILES['qrImage']['tmp_name'], $targetPath)) {
    echo json_encode(["success" => false, "message" => "QR upload failed."]);
    exit;
  }
  $qrImagePath = 'uploads/paymentsQR/' . $safeName;
  if ($currentQr && file_exists(__DIR__ . '/../../' . $currentQr)) {
    unlink(__DIR__ . '/../../' . $currentQr);
  }
} else {
  $qrImagePath = $currentQr;
}

$stmt = $conn->prepare("UPDATE payment_methods SET bank_name = ?, card_number = ?, status = ?, qr_image = ? WHERE id = ?");
$stmt->bind_param("ssssi", $bankName, $cardNumber, $status, $qrImagePath, $id);
$ok = $stmt->execute();

echo json_encode(["success" => $ok]);
