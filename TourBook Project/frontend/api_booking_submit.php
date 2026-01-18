<?php
require_once 'db_config.php';
handleCORS();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSON(['success' => false, 'message' => 'Invalid request method'], 405);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$destination = trim($input['destination'] ?? '');
$date = $input['date'] ?? null;
$guests = intval($input['guests'] ?? 1);
$message = trim($input['message'] ?? '');

if (empty($name) || empty($email) || empty($destination)) {
    sendJSON(['success' => false, 'message' => 'Name, email, and destination are required'], 400);
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJSON(['success' => false, 'message' => 'Invalid email format'], 400);
}

$conn = getDBConnection();

// Find tour by title/location match
$tour_sql = "SELECT tour_id FROM tours WHERE title LIKE ? OR location LIKE ? LIMIT 1";
$tour_stmt = $conn->prepare($tour_sql);
$search_term = "%$destination%";
$tour_stmt->bind_param("ss", $search_term, $search_term);
$tour_stmt->execute();
$tour_result = $tour_stmt->get_result();
$tour_id = null;

if ($tour_result->num_rows > 0) {
    $tour_row = $tour_result->fetch_assoc();
    $tour_id = $tour_row['tour_id'];
}
$tour_stmt->close();

// Insert booking
$sql = "INSERT INTO bookings (tour_id, customer_name, customer_email, travel_date, num_guests, special_requests, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssis", $tour_id, $name, $email, $date, $guests, $message);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    
    sendJSON([
        'success' => true, 
        'message' => 'Booking inquiry submitted successfully',
        'booking_id' => $booking_id
    ]);
} else {
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    
    sendJSON(['success' => false, 'message' => 'Database error: ' . $error], 500);
}
?>