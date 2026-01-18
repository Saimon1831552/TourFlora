<?php
require_once 'db_config.php';

$conn = getDBConnection();

echo "Starting data insertion...\n\n";

// Clear existing data (optional - uncomment if you want to reset)
// $conn->query("DELETE FROM tour_itineraries");
// $conn->query("DELETE FROM tour_images");
// $conn->query("DELETE FROM tour_highlights");
// $conn->query("DELETE FROM tour_amenities");
// $conn->query("DELETE FROM bookings");
// $conn->query("DELETE FROM reviews");
// $conn->query("DELETE FROM blogs");
// $conn->query("DELETE FROM tours");

// ============================================
// INSERT TOURS
// ============================================

$tours = [
    [
        'slug' => 'sundarbans',
        'title' => 'Silence of the Mangroves',
        'location' => 'Khulna',
        'duration_text' => '4 Days',
        'price' => 450,
        'description' => 'Journey deep into the world\'s largest mangrove forest, a UNESCO World Heritage Site. Track the elusive Royal Bengal Tiger, cruise through winding waterways, and witness the raw beauty of this unique ecosystem. Experience authentic village life and sustainable eco-tourism at its finest.',
        'tour_type' => 'Wildlife & Nature',
        'tour_category' => 'multidays_tour',
        'best_season' => 'Nov-Mar',
        'fitness_level' => 'Easy',
        'max_group_size' => 10,
        'acc_name' => 'Sundarbans Eco Resort',
        'acc_type' => 'Eco Lodge',
        'acc_description' => 'Nestled on the edge of the forest, our eco-resort offers comfortable rooms with river views, local cuisine, and knowledgeable naturalist guides.',
        'acc_image_url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'
    ],
    [
        'slug' => 'sylhet',
        'title' => 'Emerald Tea Trails',
        'location' => 'Sylhet',
        'duration_text' => '3 Days',
        'price' => 320,
        'description' => 'Walk through endless rolling tea estates, visit crystal-clear waterfalls, and discover the spiritual heart of Bangladesh. This journey takes you through lush green hills, sacred shrines, and the serene beauty of freshwater swamp forests.',
        'tour_type' => 'Nature & Culture',
        'tour_category' => 'multidays_tour',
        'best_season' => 'Oct-Apr',
        'fitness_level' => 'Moderate',
        'max_group_size' => 12,
        'acc_name' => 'Tea Garden Bungalow',
        'acc_type' => 'Heritage Stay',
        'acc_description' => 'Stay in a restored colonial-era tea planter\'s bungalow surrounded by tea gardens. Wake up to mountain views and the aroma of fresh Assam tea.',
        'acc_image_url' => 'https://images.unsplash.com/photo-1615880484746-a134be9a6ecf?q=80&w=800'
    ],
    [
        'slug' => 'dhaka',
        'title' => 'Lost Capitals',
        'location' => 'Dhaka',
        'duration_text' => '1 Day',
        'price' => 120,
        'description' => 'Step back in time through the narrow lanes of Old Dhaka and the ancient ruins of Sonargaon. Explore Mughal architecture, vibrant bazaars, and discover the rich heritage of Bengal\'s former capitals.',
        'tour_type' => 'Heritage & Culture',
        'tour_category' => 'day_tours',
        'best_season' => 'Year-round',
        'fitness_level' => 'Easy',
        'max_group_size' => 15,
        'acc_name' => 'N/A - Day Tour',
        'acc_type' => 'Day Tour',
        'acc_description' => 'This is a day tour. Lunch at a traditional Bengali restaurant is included.',
        'acc_image_url' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=800'
    ],
    [
        'slug' => 'chittagong',
        'title' => 'Peaks & Tribes',
        'location' => 'Chittagong',
        'duration_text' => '5 Days',
        'price' => 550,
        'description' => 'Trek through the misty hills of Bandarban, visit indigenous tribal villages, and experience the cultural diversity of the Chittagong Hill Tracts. This immersive journey combines adventure trekking with meaningful cultural exchange.',
        'tour_type' => 'Trekking & Culture',
        'tour_category' => 'multidays_tour',
        'best_season' => 'Oct-Mar',
        'fitness_level' => 'Challenging',
        'max_group_size' => 8,
        'acc_name' => 'Hillside Lodge',
        'acc_type' => 'Mountain Lodge',
        'acc_description' => 'Authentic hill lodge with panoramic views. Experience traditional tribal hospitality and locally-sourced organic meals.',
        'acc_image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=800'
    ]
];

foreach ($tours as $tour) {
    $stmt = $conn->prepare("INSERT INTO tours (slug, title, location, duration_text, price, description, tour_type, tour_category, best_season, fitness_level, max_group_size, acc_name, acc_type, acc_description, acc_image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssdsssssissss",
        $tour['slug'],
        $tour['title'],
        $tour['location'],
        $tour['duration_text'],
        $tour['price'],
        $tour['description'],
        $tour['tour_type'],
        $tour['tour_category'],
        $tour['best_season'],
        $tour['fitness_level'],
        $tour['max_group_size'],
        $tour['acc_name'],
        $tour['acc_type'],
        $tour['acc_description'],
        $tour['acc_image_url']
    );
    
    $stmt->execute();
    $tour_id = $stmt->insert_id;
    echo "✓ Inserted tour: {$tour['title']} (ID: $tour_id)\n";
    
    // Insert images
    $images = [
        'sundarbans' => [
            'https://images.unsplash.com/photo-1628063260338-3c35b801a2b0?q=80&w=1000',
            'https://images.unsplash.com/photo-1548232979-6c557ee14752?q=80&w=1000',
            'https://images.unsplash.com/photo-1614027164847-1b28cfe1df60?q=80&w=1000'
        ],
        'sylhet' => [
            'https://images.unsplash.com/photo-1598367772323-3a4b71891a32?q=80&w=1000',
            'https://images.unsplash.com/photo-1596895111956-bf1cf0599ce5?q=80&w=1000',
            'https://images.unsplash.com/photo-1587595431973-160d0d94add1?q=80&w=1000'
        ],
        'dhaka' => [
            'https://images.unsplash.com/photo-1681282903714-3580467c61f2?q=80&w=1000',
            'https://images.unsplash.com/photo-1548013146-72479768bada?q=80&w=1000',
            'https://images.unsplash.com/photo-1587595431973-160d0d94add1?q=80&w=1000'
        ],
        'chittagong' => [
            'https://images.unsplash.com/photo-1622306260824-3860afac7453?q=80&w=1000',
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=1000',
            'https://images.unsplash.com/photo-1454496522488-7a8e488e8606?q=80&w=1000'
        ]
    ];
    
    if (isset($images[$tour['slug']])) {
        foreach ($images[$tour['slug']] as $index => $img_url) {
            $is_primary = ($index === 0) ? 1 : 0;
            $img_stmt = $conn->prepare("INSERT INTO tour_images (tour_id, image_url, is_primary) VALUES (?, ?, ?)");
            $img_stmt->bind_param("isi", $tour_id, $img_url, $is_primary);
            $img_stmt->execute();
            $img_stmt->close();
        }
    }
    
    // Insert highlights
    $highlights = [
        'sundarbans' => [
            'Royal Bengal Tiger tracking expedition',
            'Sunset cruise through mangrove channels',
            'Visit to remote fishing villages',
            'Expert naturalist-guided walks',
            'Traditional boat accommodation option'
        ],
        'sylhet' => [
            'Walk through the Seven Color Tea Gardens',
            'Visit to Ratargul Swamp Forest',
            'Jaflong stone collection experience',
            'Shrine of Hazrat Shah Jalal',
            'Scenic Lalakhal river cruise'
        ],
        'dhaka' => [
            'Lalbagh Fort Mughal architecture',
            'Sonargaon Folk Art Museum',
            'Rickshaw ride through Old Dhaka',
            'Traditional Bengali lunch',
            'Ahsan Manzil Pink Palace'
        ],
        'chittagong' => [
            'Summit of Nilgiri Hills',
            'Boga Lake trek and camping',
            'Marma and Mru tribal village visits',
            'Sangu River boat journey',
            'Traditional handicraft workshops'
        ]
    ];
    
    if (isset($highlights[$tour['slug']])) {
        foreach ($highlights[$tour['slug']] as $hl) {
            $hl_stmt = $conn->prepare("INSERT INTO tour_highlights (tour_id, highlight_text) VALUES (?, ?)");
            $hl_stmt->bind_param("is", $tour_id, $hl);
            $hl_stmt->execute();
            $hl_stmt->close();
        }
    }
    
    // Insert itineraries
    $itineraries = [
        'sundarbans' => [
            ['Day 1', 'Arrival & Forest Entry', 'Depart Dhaka early morning. Arrive at Mongla port by afternoon. Board your traditional wooden boat and begin your journey into the mangrove maze.'],
            ['Day 2', 'Deep Forest Exploration', 'Full day cruising through narrow creeks. Visit the Karamjal Wildlife Center. Afternoon guided forest walk looking for deer, crocodiles, and bird species.'],
            ['Day 3', 'Tiger Territory', 'Early morning expedition to tiger tracking zones. Visit watchtowers for panoramic forest views. Sunset photography session.'],
            ['Day 4', 'Return Journey', 'Morning bird watching. Visit local honey collectors. Return to Mongla and transfer back to Dhaka.']
        ],
        'sylhet' => [
            ['Day 1', 'Tea Garden Immersion', 'Morning arrival in Sylhet. Visit Srimangal tea estates. Lunch at a tea planter\'s bungalow. Afternoon tea tasting and processing tour.'],
            ['Day 2', 'Natural Wonders', 'Early start to Ratargul Swamp Forest - Bangladesh\'s only freshwater swamp. Afternoon drive to Jaflong. Evening at leisure by Piyain River.'],
            ['Day 3', 'Spiritual & Scenic', 'Morning visit to the shrine of Hazrat Shah Jalal. Drive through Seven Color Tea Garden. Afternoon departure.']
        ],
        'dhaka' => [
            ['Full Day', 'Heritage Trail', 'Start at Lalbagh Fort (1678 AD). Walk through Chawk Bazaar\'s spice markets. Rickshaw ride to Pink Palace (Ahsan Manzil). Traditional Bengali lunch. Afternoon visit to Sonargaon and Folk Art Museum. Evening return.']
        ],
        'chittagong' => [
            ['Day 1', 'Journey to the Hills', 'Drive from Chittagong to Bandarban. Scenic mountain roads. Check into hillside lodge. Evening walk through local markets.'],
            ['Day 2', 'Nilgiri Peak', 'Early morning drive and trek to Nilgiri Hills (2200 ft). Panoramic views of surrounding peaks. Afternoon visit to Buddhist temple.'],
            ['Day 3', 'Tribal Villages', 'Full day excursion to Marma and Mru villages. Learn traditional weaving and bamboo crafts. Overnight in village homestay.'],
            ['Day 4', 'Boga Lake Trek', 'Trek to the pristine Boga Lake. Swimming and relaxation. Camp under the stars.'],
            ['Day 5', 'Return', 'Morning nature walk. Return trek and drive back to Chittagong.']
        ]
    ];
    
    if (isset($itineraries[$tour['slug']])) {
        foreach ($itineraries[$tour['slug']] as $index => $it) {
            $it_stmt = $conn->prepare("INSERT INTO tour_itineraries (tour_id, day_label, title, description, display_order) VALUES (?, ?, ?, ?, ?)");
            $it_stmt->bind_param("isssi", $tour_id, $it[0], $it[1], $it[2], $index);
            $it_stmt->execute();
            $it_stmt->close();
        }
    }
    
    // Insert amenities
    $amenities = ['WiFi', 'Hot Water', 'Local Cuisine', 'Guided Tours', 'Airport Transfer'];
    foreach ($amenities as $am) {
        $am_stmt = $conn->prepare("INSERT INTO tour_amenities (tour_id, amenity_name) VALUES (?, ?)");
        $am_stmt->bind_param("is", $tour_id, $am);
        $am_stmt->execute();
        $am_stmt->close();
    }
    
    $stmt->close();
}

// ============================================
// INSERT SAMPLE REVIEWS
// ============================================

echo "\n✓ Inserting sample reviews...\n";

$reviews = [
    ['Sarah Jenkins', 'London, UK', 5, 'The Sundarbans trip was absolutely magical! Our guide was incredibly knowledgeable and we were lucky enough to spot tiger tracks. An unforgettable experience.'],
    ['Michael Chen', 'Singapore', 5, 'TourFlora handled everything perfectly from start to finish. The accommodation was comfortable and the food was delicious. Highly recommended!'],
    ['Priya Patel', 'Mumbai, India', 5, 'The tea gardens in Sylhet are breathtaking. Waking up to those emerald hills was the most peaceful experience. Thank you TourFlora!'],
    ['James Wilson', 'Sydney, Australia', 4, 'Excellent organization and friendly staff. The boat ride through the mangroves was spectacular. Would definitely book again.']
];

foreach ($reviews as $rev) {
    $stmt = $conn->prepare("INSERT INTO reviews (user_name, user_location, rating, comment, review_date) VALUES (?, ?, ?, ?, CURDATE())");
    $stmt->bind_param("ssis", $rev[0], $rev[1], $rev[2], $rev[3]);
    $stmt->execute();
    $stmt->close();
}

// ============================================
// INSERT SAMPLE BLOGS
// ============================================

echo "✓ Inserting sample blogs...\n";

$blogs = [
    [
        'title' => 'Lost in the Mist: A Week in the Chittagong Hill Tracts',
        'author' => 'Arif Ahmed',
        'category' => 'Adventure',
        'content' => 'We left the city behind and ascended into the clouds. The air grew thinner, the noise faded, and we found ourselves in a world where time seems to stand still. The indigenous communities welcomed us with traditional dances and showed us their centuries-old weaving techniques...',
        'image' => 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?q=80&w=1527'
    ],
    [
        'title' => 'Tea, Tranquility, and Endless Green',
        'author' => 'Nadia Rahman',
        'category' => 'Nature',
        'content' => 'Sylhet stole my heart. Every morning I woke up to the sight of rolling tea estates stretching as far as the eye could see. The local tea workers shared their stories, and I learned about the intricate process of tea cultivation...',
        'image' => 'https://images.unsplash.com/photo-1598367772323-3a4b71891a32?q=80&w=800'
    ]
];

foreach ($blogs as $blog) {
    $stmt = $conn->prepare("INSERT INTO blogs (title, author_name, category, content, image_url, publish_date) VALUES (?, ?, ?, ?, ?, CURDATE())");
    $stmt->bind_param("sssss", $blog['title'], $blog['author'], $blog['category'], $blog['content'], $blog['image']);
    $stmt->execute();
    $stmt->close();
}

echo "\n✅ All sample data inserted successfully!\n";
echo "You can now test your website.\n";

$conn->close();
?>