<?php
require_once 'db_config.php';
handleCORS();

$conn = getDBConnection();

// Initialize stats
$stats = [
    'total_bookings' => 0,
    'revenue' => '$0',
    'pending_bookings' => 0,
    'active_tours' => 0
];

// 1. Total Bookings
$sql = "SELECT COUNT(*) as total FROM bookings";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['total_bookings'] = $row['total'];
}

// 2. Pending Bookings
$sql = "SELECT COUNT(*) as pending FROM bookings WHERE status = 'pending'";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['pending_bookings'] = $row['pending'];
}

// 3. Revenue (sum of confirmed bookings)
$sql = "SELECT SUM(t.price * b.num_guests) as total_revenue 
        FROM bookings b 
        INNER JOIN tours t ON b.tour_id = t.tour_id 
        WHERE b.status = 'confirmed'";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $revenue = floatval($row['total_revenue'] ?? 0);
    $stats['revenue'] = '$' . number_format($revenue, 0);
}

// 4. Active Tours
$sql = "SELECT COUNT(*) as active FROM tours";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['active_tours'] = $row['active'];
}

$conn->close();
sendJSON($stats);
?>