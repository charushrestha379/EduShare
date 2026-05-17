<?php
include 'db.php';
$logged_in_user_id = 1;

$user_id = isset($_GET['id']) ? (int) $_GET['id'] : $logged_in_user_id;

if (isset($_POST['request_session'])) {
    $teacher_id = (int) $_POST['teacher_id'];
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $hours = (int) $_POST['hours'];
    $sql = "INSERT INTO sessions (teacher_id, learner_id, skill_name, hours, status) VALUES ($teacher_id, $logged_in_user_id, '$skill', $hours, 'pending')";
    mysqli_query($conn, $sql);
    $msg = "Session request sent!";
}

$sql = "SELECT * FROM users WHERE id = $user_id";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);
if (!$user) { die("User not found."); }

$full_stars = floor($user['rating']);
$star_display = "";
for($i=1; $i<=5; $i++) {
    $fill = $i <= $full_stars ? 'fill="currentColor"' : 'fill="none"';
    $star_display .= '<i data-lucide="star" style="width:16px; height:16px; vertical-align:middle;" ' . $fill . '></i>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $user['name']; ?> - Profile</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <nav>
        <a href="home.php" class="logo">Edu<span>Swap</span></a>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="users.php">Find Skills</a></li>
            <li><a href="session.php">My Sessions</a></li>
            <li><a href="profile.php?id=<?php echo $logged_in_user_id; ?>">My Profile</a></li>
        </ul>
    </nav>

    <div class="profile-container">
        <?php if (isset($msg)) echo '<div class="msg-success">' . $msg . '</div>'; ?>

        <div class="profile-header">
            <img src="<?php echo $user['image']; ?>" class="profile-img" alt="">
            <h1><?php echo $user['name']; ?></h1>
            <p class="profile-location"><i data-lucide="map-pin" style="width:16px; height:16px; vertical-align:middle;"></i> <?php echo $user['city']; ?> • <?php echo $star_display; ?> (<?php echo $user['rating']; ?>)</p>
            <p class="profile-bio"><?php echo $user['bio']; ?></p>

            <div class="profile-skills">
                <h3>Skills</h3>
                <?php
                $s_sql = "SELECT * FROM skills WHERE user_id = $user_id";
                $s_res = mysqli_query($conn, $s_sql);
                while ($s_row = mysqli_fetch_assoc($s_res)) {
                    $type_class = $s_row['type'] == 'teach' ? 'badge-teach' : 'badge-learn';
                    echo '<span class="badge ' . $type_class . '">' . $s_row['skill_name'] . '</span>';
                }
                ?>
            </div>

            <div class="profile-credits">
                <i data-lucide="star" style="width:18px; height:18px; vertical-align:middle; fill:currentColor;"></i> <span><?php echo $user['credits']; ?></span> Credits
            </div>
        </div>

        <div class="profile-section">
            <h2>Recent Reviews</h2>
            <?php
            $r_sql = "SELECT r.*, u.name FROM reviews r JOIN users u ON r.review_user_id = u.id WHERE r.user_id = $user_id";
            $r_res = mysqli_query($conn, $r_sql);
            if (mysqli_num_rows($r_res) > 0) {
                $count = 0;
                while ($r_row = mysqli_fetch_assoc($r_res)) {
                    $r_stars = "";
                    for($i=1; $i<=5; $i++) {
                        $fill = $i <= floor($r_row['rating']) ? 'fill="currentColor"' : 'fill="none"';
                        $r_stars .= '<i data-lucide="star" style="width:14px; height:14px; vertical-align:middle;" ' . $fill . '></i>';
                    }
                    $hide = $count >= 2 ? ' style="display:none;" class="review-card extra-review"' : ' class="review-card"';
                    echo '<div' . $hide . '>';
                    echo '<h4>' . $r_row['name'] . '</h4>';
                    echo '<div class="stars">' . $r_stars . '</div>';
                    echo '<p>"' . $r_row['text'] . '"</p>';
                    echo '</div>';
                    $count++;
                }
                if ($count > 2) {
                    echo '<button class="btn btn-primary" id="show-more-reviews" style="margin-top:12px; padding:8px 16px; font-size:13px;" onclick="toggleReviews()">Show More Reviews</button>';
                }
            } else {
                echo '<p style="color:#999;">No reviews yet.</p>';
            }
            ?>
        </div>

        <?php if ($user_id != $logged_in_user_id): ?>
        <div class="request-form">
            <h3>Request a Session</h3>
            <p>Swap your credits for a lesson with <?php echo $user['name']; ?>.</p>
            <form action="" method="POST">
                <input type="hidden" name="teacher_id" value="<?php echo $user_id; ?>">
                <label>Which Skill?</label>
                <select name="skill">
                    <?php
                    $s_sql = "SELECT * FROM skills WHERE user_id = $user_id AND type = 'teach'";
                    $s_res = mysqli_query($conn, $s_sql);
                    while ($s_row = mysqli_fetch_assoc($s_res)) {
                        echo '<option value="' . $s_row['skill_name'] . '">' . $s_row['skill_name'] . '</option>';
                    }
                    ?>
                </select>
                <label>How many hours?</label>
                <input type="number" name="hours" value="1" min="1" max="5">
                <button type="submit" name="request_session" class="btn btn-primary">Request Session</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    
    <script src="script.js"></script>
</body>
</html>