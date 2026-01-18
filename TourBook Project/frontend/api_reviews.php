<?php
require_once 'db_config.php';
handleCORS();

$conn = getDBConnection();

// Handle POST (Create new review)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $name = trim($input['name'] ?? 'Anonymous');
    $location = trim($input['location'] ?? '');
    $comment = trim($input['comment'] ?? '');
    $rating = intval($input['rating'] ?? 5);
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        sendJSON(['success' => false, 'message' => 'Rating must be between 1 and 5'], 400);
    }
    
    if (empty($comment)) {
        sendJSON(['success' => false, 'message' => 'Comment is required'], 400);
    }
    
    // Insert review (tour_id can be null for general reviews)
    $sql = "INSERT INTO reviews (tour_id, user_name, user_location, rating, comment, review_date) 
            VALUES (NULL, ?, ?, ?, ?, CURDATE())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $name, $location, $rating, $comment);
    
    if ($stmt->execute()) {
        $review_id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
        
        sendJSON(['success' => true, 'message' => 'Review submitted', 'review_id' => $review_id]);
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        
        sendJSON(['success' => false, 'message' => 'Database error: ' . $error], 500);
    }
}

// Handle GET (Fetch all reviews)
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT review_id, tour_id, user_name, user_location, rating, comment, review_date 
            FROM reviews 
            ORDER BY review_date DESC, review_id DESC 
            LIMIT 100";
    
    $result = $conn->query($sql);
    $reviews = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }
    
    $conn->close();
    sendJSON($reviews);
}

else {
    sendJSON(['error' => 'Invalid request method'], 405);
}
?>