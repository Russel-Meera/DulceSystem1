<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Not authenticated'
    ]);
    exit;
}

$chapelId = $_POST['chapel_id'] ?? '';
$serviceDate = $_POST['service_date'] ?? '';
$serviceTime = $_POST['service_time'] ?? '';

$obituaryName = $_POST['obituary_name'] ?? '';
$birthDate = $_POST['birth_date'] ?? '';
$deathDate = $_POST['death_date'] ?? '';
$excerpt = $_POST['excerpt'] ?? '';
$fullBiography = $_POST['full_biography'] ?? '';
$wakeSchedule = $_POST['wake_schedule'] ?? '';
$chapelName = $_POST['chapel'] ?? '';
$viewingHours = $_POST['viewing_hours'] ?? '';
$interment = $_POST['interment'] ?? '';
$survivorsRaw = $_POST['survivors'] ?? '';

if (empty($chapelId) || empty($serviceDate) || empty($serviceTime)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing booking details.'
    ]);
    exit;
}

if (empty($obituaryName) || empty($birthDate) || empty($deathDate)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required obituary details.'
    ]);
    exit;
}

try {
    $conn = getDBConnection();
    $conn->begin_transaction();

    $createdAt = date('Y-m-d H:i:s');

    $imageUrl = null;
    if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/obituaries';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $tmpName = $_FILES['image_file']['tmp_name'];
        $originalName = basename($_FILES['image_file']['name']);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new Exception('Invalid image file type.');
        }

        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $fileName = time() . '_' . $safeName;
        $destination = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpName, $destination)) {
            throw new Exception('Failed to upload image.');
        }

        $imageUrl = 'uploads/obituaries/' . $fileName;
    }

    $birthDateObj = new DateTime($birthDate);
    $deathDateObj = new DateTime($deathDate);
    $today = new DateTime(date('Y-m-d'));

    if ($birthDateObj > $today) {
        throw new Exception('Birth date cannot be after today.');
    }

    if ($deathDateObj < $today) {
        throw new Exception('Death date cannot be before today.');
    }

    if ($deathDateObj < $birthDateObj) {
        throw new Exception('Death date cannot be earlier than birth date.');
    }

    $age = (int) $birthDateObj->diff($deathDateObj)->y;

    $bookingStatus = 'pending';
    $bookingCreatedAt = date('Y-m-d H:i:s');
    $clientId = $_SESSION['user_id'];

    $stmtBooking = $conn->prepare(
        "INSERT INTO bookings (client_id, chapel_id, service_date, service_time, booking_status, created_at)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmtBooking->bind_param(
        "iissss",
        $clientId,
        $chapelId,
        $serviceDate,
        $serviceTime,
        $bookingStatus,
        $bookingCreatedAt
    );

    if (!$stmtBooking->execute()) {
        throw new Exception('Failed to save booking.');
    }

    $bookingId = $conn->insert_id;

    // Ensure linkage columns exist in obituaries, then insert with booking/client IDs.
    $hasClientCol = false;
    $hasBookingCol = false;
    $colCheck = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'client_id'");
    if ($colCheck && $colCheck->num_rows > 0) {
        $hasClientCol = true;
    }
    if ($colCheck) {
        $colCheck->free();
    }
    $colCheck = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'booking_id'");
    if ($colCheck && $colCheck->num_rows > 0) {
        $hasBookingCol = true;
    }
    if ($colCheck) {
        $colCheck->free();
    }

    if (!$hasClientCol || !$hasBookingCol) {
        $alterParts = [];
        if (!$hasClientCol) $alterParts[] = "ADD COLUMN client_id INT(11) NULL";
        if (!$hasBookingCol) $alterParts[] = "ADD COLUMN booking_id INT(11) NULL";
        $alterSql = "ALTER TABLE obituaries " . implode(", ", $alterParts);
        $conn->query($alterSql);

        $colCheck = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'client_id'");
        $hasClientCol = $colCheck && $colCheck->num_rows > 0;
        if ($colCheck) $colCheck->free();
        $colCheck = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'booking_id'");
        $hasBookingCol = $colCheck && $colCheck->num_rows > 0;
        if ($colCheck) $colCheck->free();
    }

    if (!$hasClientCol || !$hasBookingCol) {
        throw new Exception('Missing client_id/booking_id columns in obituaries. Please run DB migration.');
    }

    $stmtObituary = $conn->prepare(
        "INSERT INTO obituaries (client_id, booking_id, name, birth_date, death_date, age, image_url, excerpt, full_biography, wake_schedule, chapel, viewing_hours, interment, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmtObituary->bind_param(
        "iisssissssssss",
        $clientId,
        $bookingId,
        $obituaryName,
        $birthDate,
        $deathDate,
        $age,
        $imageUrl,
        $excerpt,
        $fullBiography,
        $wakeSchedule,
        $chapelName,
        $viewingHours,
        $interment,
        $createdAt
    );

    if (!$stmtObituary->execute()) {
        throw new Exception('Failed to save obituary.');
    }

    $obituaryId = $conn->insert_id;

    // Save survivors if provided
    $survivors = [];
    if (!empty($survivorsRaw)) {
        $decoded = json_decode($survivorsRaw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $survivors = $decoded;
        }
    }

    if (!empty($survivors)) {
        $stmtSurvivor = $conn->prepare(
            "INSERT INTO obituary_survivors (obituary_id, survivor_text) VALUES (?, ?)"
        );
        foreach ($survivors as $survivorText) {
            $survivorText = trim((string) $survivorText);
            if ($survivorText === '') {
                continue;
            }
            $stmtSurvivor->bind_param("is", $obituaryId, $survivorText);
            if (!$stmtSurvivor->execute()) {
                throw new Exception('Failed to save survivors.');
            }
        }
        $stmtSurvivor->close();
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Booking and obituary saved successfully.',
        'obituary_id' => $obituaryId,
        'booking_id' => $bookingId,
        'client_id' => $clientId
    ]);

    $stmtObituary->close();
    $stmtBooking->close();
    closeDBConnection($conn);
} catch (Exception $e) {
    if (isset($conn) && $conn->errno) {
        $conn->rollback();
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
