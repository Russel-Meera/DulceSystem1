<?php
require_once __DIR__ . '/dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../Pages/selectPayment.php");
  exit;
}

$bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
$clientId = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
$paymentMethodId = isset($_POST['payment_method_id']) ? (int)$_POST['payment_method_id'] : 0;

if ($bookingId <= 0 || $clientId <= 0 || $paymentMethodId <= 0) {
  header("Location: ../Pages/selectPayment.php?error=missing");
  exit;
}

$receiptPath = null;
if (!empty($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = __DIR__ . '/../uploads/paymentReceipts';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }
  $tmpName = $_FILES['receipt_image']['tmp_name'];
  $originalName = basename($_FILES['receipt_image']['name']);
  $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
  if (!in_array($extension, $allowedExtensions, true)) {
    header("Location: ../Pages/selectPayment.php?booking_id={$bookingId}&client_id={$clientId}&error=type");
    exit;
  }
  $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
  $fileName = time() . '_' . $safeName;
  $destination = $uploadDir . '/' . $fileName;
  if (!move_uploaded_file($tmpName, $destination)) {
    header("Location: ../Pages/selectPayment.php?booking_id={$bookingId}&client_id={$clientId}&error=upload");
    exit;
  }
  $receiptPath = 'uploads/paymentReceipts/' . $fileName;
}

$stmt = $conn->prepare("INSERT INTO booking_payments (booking_id, client_id, payment_method_id, status, receipt_image) VALUES (?, ?, ?, 'pending', ?)");
$stmt->bind_param("iiis", $bookingId, $clientId, $paymentMethodId, $receiptPath);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
  header("Location: ../Pages/bookings.html");
  exit;
}

header("Location: ../Pages/selectPayment.php?booking_id={$bookingId}&client_id={$clientId}&error=save");
exit;
