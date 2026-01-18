<?php
require_once 'db_config.php';
handleCORS();

$conn = getDBConnection();

// Handle POST (Update booking status)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $booking_id = intval($input['booking_id'] ?? 0);
    $status = trim($input['status'] ?? '');
    
    // Validate status
    $valid_statuses = ['pending', 'confirmed', 'cancelled'];
    if (!in_array($status, $valid_statuses)) {
        sendJSON(['success' => false, 'message' => 'Invalid status'], 400);
    }
    
    if ($booking_id <= 0) {
        sendJSON(['success' => false, 'message' => 'Invalid booking ID'], 400);
    }
    
    $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $booking_id);
    
    if ($stmt->execute()) {
        $affected = $stmt->affected_rows;
        $stmt->close();
        $conn->close();
        
        if ($affected > 0) {
            sendJSON(['success' => true, 'message' => 'Booking status updated']);
        } else {
            sendJSON(['success' => false, 'message' => 'Booking not found'], 404);
        }
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        
        sendJSON(['success' => false, 'message' => 'Database error: ' . $error], 500);
    }
}

// Handle GET (Fetch all bookings)
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT 
                b.booking_id,
                b.customer_name,
                b.customer_email,
                b.travel_date,
                b.num_guests,
                b.special_requests,
                b.status,
                b.created_at,
                t.title as tour_title,
                t.location as tour_location
            FROM bookings b
            LEFT JOIN tours t ON b.tour_id = t.tour_id
            ORDER BY b.created_at DESC";
    
    $result = $conn->query($sql);
    $bookings = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    
    $conn->close();
    sendJSON($bookings);
}

else {
    sendJSON(['error' => 'Invalid request method'], 405);
}
?>