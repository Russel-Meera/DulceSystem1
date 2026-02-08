<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Not authenticated'
    ]);
    exit;
}

try {
    $conn = getDBConnection();
    $userId = $_SESSION['user_id'];
    
    // Get filter parameter
    $filter = $_GET['filter'] ?? 'all';
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 0;
    $bookingId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    
    $statusFilter = $filter;
    if ($statusFilter === 'approved') {
        $statusFilter = 'confirmed';
    }

    $hasObituaryBooking = false;
    $check = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'booking_id'");
    if ($check && $check->num_rows > 0) {
        $hasObituaryBooking = true;
    }
    if ($check) {
        $check->free();
    }

    $sql = "SELECT b.booking_id, b.booking_status, b.service_date, b.service_time,
                   b.created_at, b.total_amount, c.name AS chapel_name, c.features AS chapel_features";
    if ($hasObituaryBooking) {
        $sql .= ", o.name AS deceased_name, o.birth_date, o.death_date, o.age, o.wake_schedule,
                 o.viewing_hours, o.interment";
    }
    $sql .= " FROM bookings b
            LEFT JOIN chapel_services c ON b.chapel_id = c.id";
    if ($hasObituaryBooking) {
        $sql .= " LEFT JOIN (
                    SELECT o1.booking_id, o1.name, o1.birth_date, o1.death_date, o1.age,
                           o1.wake_schedule, o1.viewing_hours, o1.interment
                    FROM obituaries o1
                    INNER JOIN (
                      SELECT booking_id, MAX(created_at) AS max_created
                      FROM obituaries
                      GROUP BY booking_id
                    ) latest ON latest.booking_id = o1.booking_id AND latest.max_created = o1.created_at
                  ) o ON o.booking_id = b.booking_id";
    }
    $sql .= " WHERE b.client_id = ?";
    if ($bookingId > 0) {
        $sql .= " AND b.booking_id = ?";
    }

    if ($statusFilter !== 'all') {
        $sql .= " AND b.booking_status = ?";
    }

    $sql .= " ORDER BY b.created_at DESC";
    if ($limit > 0) {
        $sql .= " LIMIT " . $limit;
    }

    if ($statusFilter !== 'all') {
        $stmt = $conn->prepare($sql);
        if ($bookingId > 0) {
            $stmt->bind_param("iis", $userId, $bookingId, $statusFilter);
        } else {
            $stmt->bind_param("is", $userId, $statusFilter);
        }
    } else {
        $stmt = $conn->prepare($sql);
        if ($bookingId > 0) {
            $stmt->bind_param("ii", $userId, $bookingId);
        } else {
            $stmt->bind_param("i", $userId);
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $row['booking_status'] = ucfirst($row['booking_status']);
        if (isset($row['chapel_features'])) {
            $decoded = json_decode($row['chapel_features'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $row['chapel_features'] = $decoded;
            }
        }
        $bookings[] = $row;
    }

    $stmt->close();

    if ($bookingId > 0) {
        echo json_encode([
            'success' => true,
            'booking' => $bookings[0] ?? null
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'bookings' => $bookings
        ]);
    }
    
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while fetching bookings'
    ]);
}
?>
