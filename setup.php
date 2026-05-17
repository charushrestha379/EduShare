<?php
include 'db.php';

$sql = "CREATE TABLE IF NOT EXISTS users (

    -- user ko id next time auto increment huna parxa
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    city VARCHAR(100),
    bio TEXT,
    credits INT DEFAULT 10,
    image VARCHAR(255),
    rating FLOAT DEFAULT 5.0
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    skill_name VARCHAR(100),
    type VARCHAR(20)
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT,
    learner_id INT,
    skill_name VARCHAR(100),
    hours INT,
    status VARCHAR(20) DEFAULT 'pending'
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    review_user_id INT,
    rating INT,
    text TEXT
)";
mysqli_query($conn, $sql);

// naya data update garey purano empty garna
// as its manual  right now
mysqli_query($conn, "TRUNCATE TABLE users");
mysqli_query($conn, "TRUNCATE TABLE skills");
mysqli_query($conn, "TRUNCATE TABLE sessions");
mysqli_query($conn, "TRUNCATE TABLE reviews");

if (true) {
    
    mysqli_query($conn, "INSERT INTO users (id, name, city, bio, credits, image, rating) VALUES 
    (1, 'Bipin Subedi', 'Pokhara', 'Hi! I\'m Bipin. I love teaching Mobile App Development and I\'m looking to learn Guitar. I have been building apps for years and want to pick up a creative hobby.', 15, 'assets/images/avatars/bipin.jpg', 4.8),
    (2, 'Khusi Poudel', 'Syangja', 'Hi! I\'m Khusi. I love sharing my knowledge through Teaching and I\'m looking to learn UI Design. Passionate educator with a focus on student growth.', 8, 'assets/images/avatars/khusi.jpg', 4.9),
    (3, 'Suyog Regmi', 'Lamjung', 'Hi! I\'m Suyog. I love teaching Dancing and I\'m looking to learn Photoshop. I have been dancing for years and need help making our posters look good.', 12, 'assets/images/avatars/suyog.jpg', 4.7),
    (4, 'Charu Shrestha ', 'Pokhara', 'Hi! I\'m Charu. I love teaching UI/UX Design and I\'m looking to learn Photography. UX/UI Designer at a startup, want to get into street photography.', 10, 'assets/images/avatars/charu.jpg', 5.0),
    (5, 'Aaditya  Tripahti', 'Pokhara', 'Hi! I\'m Aaditya. I love teaching Singing and I\'m looking to learn Cooking. Professional singer by day, aspiring chef by night.', 20, 'assets/images/avatars/aaditya.jpg', 4.3),
    (6, 'Sushant BK', 'Birgunj', 'Hi! I\'m Sushant. I love teaching Data Science and I\'m looking to learn Piano. Working on my Masters and want to pick up music as a stress reliever.', 6, 'assets/images/avatars/sushant.jpg', 4.6),
    (7, 'Eraj Adhikari ', 'Pokhara', 'Hi! I\'m Eraj. I love teaching Riding and I\'m looking to learn Python. Expert rider who wants to build a specialized app.', 14, 'assets/images/avatars/eraj.jpg', 4.2),
    (8, 'Sudip dahal', 'Kathmandu', 'Hi! I\'m Sudip. I love teaching Singing and I\'m looking to learn Data Science. Vocal coach for 3 years, curious about working with data.', 9, 'assets/images/avatars/sudip.jpg', 4.5),
    (9, 'Sita Sharma', 'Hemja', 'Hi! I\'m Sita. I love teaching Video Editing and I\'m looking to learn Guitar. YouTuber with 5K subscribers looking to add music to my content.', 11, 'assets/images/avatars/sita.jpg', 4.7)
    ");

 
    mysqli_query($conn, "INSERT INTO skills (user_id, skill_name, type) VALUES 
    (1, 'Mobile App Developer', 'teach'), (1, 'Guitar', 'learn'),
    (2, 'Teaching', 'teach'), (2, 'UI Design', 'learn'),
    (3, 'Dancing', 'teach'), (3, 'Photoshop', 'learn'),
    (4, 'UI/UX Designer', 'teach'), (4, 'Photography', 'learn'),
    (5, 'Singing', 'teach'), (5, 'Cooking', 'learn'),
    (6, 'Data Science', 'teach'), (6, 'Piano', 'learn'),
    (7, 'Riding', 'teach'), (7, 'Python', 'learn'),
    (8, 'Singing', 'teach'), (8, 'Data Science', 'learn'),
    (9, 'Video Editing', 'teach'), (9, 'Guitar', 'learn')
    ");

    
    mysqli_query($conn, "INSERT INTO sessions (teacher_id, learner_id, skill_name, hours, status) VALUES 
    (2, 1, 'Photography', 2, 'completed'),
    (3, 1, 'Guitar', 1, 'pending'),
    (5, 7, 'Python', 3, 'accepted'),
    (8, 6, 'Piano', 2, 'completed'),
    (4, 2, 'UI Design', 1, 'pending')
    ");

    
    mysqli_query($conn, "INSERT INTO reviews (user_id, review_user_id, rating, text) VALUES 
    (1, 2, 5, 'Great photography lesson! Very helpful and patient.'),
    (2, 4, 5, 'Emma is amazing at explaining design principles.'),
    (6, 8, 4, 'Good piano basics, would recommend.'),
    (7, 5, 5, 'James explains Python so clearly. Best teacher!'),
    (8, 6, 4, 'Priya knows her stuff in data science.')
    ");
}

echo "Database setup successfully!";
?>
