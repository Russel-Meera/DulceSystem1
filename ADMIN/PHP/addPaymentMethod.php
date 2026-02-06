<?php
header('Content-Type: application/json');
require_once __DIR__ . '/dbConfig.php';

$bankName = trim($_POST['bankName'] ?? '');
$cardNumber = trim($_POST['cardNumber'] ?? '');
$status = ($_POST['status'] ?? 'enabled') === 'disabled' ? 'disabled' : 'enabled';
$qrImagePath = null;

if ($bankName === '' || $cardNumber === '') {
  echo json_encode(["success" => false, "message" => "Bank name and card number are required."]);
  exit;
}

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
}

$stmt = $conn->prepare("INSERT INTO payment_methods (bank_name, card_number, status, qr_image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $bankName, $cardNumber, $status, $qrImagePath);
$ok = $stmt->execute();

echo json_encode(["success" => $ok]);
