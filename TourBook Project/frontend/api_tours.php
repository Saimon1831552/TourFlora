<?php
require_once 'db_config.php';
handleCORS();

$conn = getDBConnection();

// Fetch all tours with related data
$sql = "SELECT * FROM tours ORDER BY created_at DESC";
$result = $conn->query($sql);

$tours = [];

if ($result && $result->num_rows > 0) {
    while ($tour = $result->fetch_assoc()) {
        $tour_id = $tour['tour_id'];
        $slug = $tour['slug'];
        
        // Fetch images
        $img_sql = "SELECT image_url, is_primary FROM tour_images WHERE tour_id = ? ORDER BY is_primary DESC";
        $img_stmt = $conn->prepare($img_sql);
        $img_stmt->bind_param("i", $tour_id);
        $img_stmt->execute();
        $img_result = $img_stmt->get_result();
        
        $images = [];
        while ($img = $img_result->fetch_assoc()) {
            $images[] = $img['image_url'];
        }
        $img_stmt->close();
        
        // Fetch highlights
        $hl_sql = "SELECT highlight_text FROM tour_highlights WHERE tour_id = ?";
        $hl_stmt = $conn->prepare($hl_sql);
        $hl_stmt->bind_param("i", $tour_id);
        $hl_stmt->execute();
        $hl_result = $hl_stmt->get_result();
        
        $highlights = [];
        while ($hl = $hl_result->fetch_assoc()) {
            $highlights[] = $hl['highlight_text'];
        }
        $hl_stmt->close();
        
        // Fetch itinerary
        $it_sql = "SELECT day_label, title, description FROM tour_itineraries WHERE tour_id = ? ORDER BY display_order ASC";
        $it_stmt = $conn->prepare($it_sql);
        $it_stmt->bind_param("i", $tour_id);
        $it_stmt->execute();
        $it_result = $it_stmt->get_result();
        
        $itinerary = [];
        while ($it = $it_result->fetch_assoc()) {
            $itinerary[] = [
                'day' => $it['day_label'],
                'title' => $it['title'],
                'desc' => $it['description']
            ];
        }
        $it_stmt->close();
        
        // Fetch amenities
        $am_sql = "SELECT amenity_name FROM tour_amenities WHERE tour_id = ?";
        $am_stmt = $conn->prepare($am_sql);
        $am_stmt->bind_param("i", $tour_id);
        $am_stmt->execute();
        $am_result = $am_stmt->get_result();
        
        $amenities = [];
        while ($am = $am_result->fetch_assoc()) {
            $amenities[] = $am['amenity_name'];
        }
        $am_stmt->close();
        
        // Build tour object
        $tours[$slug] = [
            'title' => $tour['title'],
            'location' => $tour['location'],
            'duration' => $tour['duration_text'],
            'price' => '$' . number_format($tour['price'], 0),
            'desc' => $tour['description'],
            'image' => $images[0] ?? '',
            'images' => $images,
            'highlights' => $highlights,
            'itinerary' => $itinerary,
            'stats' => [
                'type' => $tour['tour_type'] ?? 'Adventure',
                'season' => $tour['best_season'] ?? 'Year-round',
                'fitness' => $tour['fitness_level'] ?? 'Moderate',
                'group' => $tour['max_group_size'] ? 'Max ' . $tour['max_group_size'] : 'Private'
            ],
            'accommodation' => [
                'name' => $tour['acc_name'] ?? 'Standard Accommodation',
                'type' => $tour['acc_type'] ?? 'Hotel',
                'desc' => $tour['acc_description'] ?? 'Comfortable accommodation included.',
                'image' => $tour['acc_image_url'] ?? $images[0] ?? '',
                'amenities' => $amenities
            ]
        ];
    }
}

$conn->close();
sendJSON($tours);
?>