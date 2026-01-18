<?php
require_once 'db_config.php';
handleCORS();

$conn = getDBConnection();

// Handle POST (Create new blog)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $title = trim($input['title'] ?? '');
    $author = trim($input['author_name'] ?? 'Anonymous');
    $content = trim($input['content'] ?? '');
    $category = trim($input['category'] ?? 'General');
    $image = trim($input['image_url'] ?? '');
    
    if (empty($title) || empty($content)) {
        sendJSON(['success' => false, 'message' => 'Title and content are required'], 400);
    }
    
    $sql = "INSERT INTO blogs (title, author_name, category, content, image_url, publish_date) 
            VALUES (?, ?, ?, ?, ?, CURDATE())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $author, $category, $content, $image);
    
    if ($stmt->execute()) {
        $blog_id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
        
        sendJSON(['success' => true, 'message' => 'Blog published', 'blog_id' => $blog_id]);
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        
        sendJSON(['success' => false, 'message' => 'Database error: ' . $error], 500);
    }
}

// Handle GET (Fetch all blogs)
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT blog_id, title, author_name, category, content, image_url, likes_count, publish_date 
            FROM blogs 
            ORDER BY publish_date DESC, created_at DESC 
            LIMIT 50";
    
    $result = $conn->query($sql);
    $blogs = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $blogs[] = $row;
        }
    }
    
    $conn->close();
    sendJSON($blogs);
}

else {
    sendJSON(['error' => 'Invalid request method'], 405);
}
?>