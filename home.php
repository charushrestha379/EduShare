<?php
include 'db.php';
$logged_in_user_id = 1;

$result = mysqli_query($conn, "SELECT * FROM users WHERE id != $logged_in_user_id ORDER BY rating DESC LIMIT 3");
if (!$result) {
    echo "Error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduSwap - Swap Skills, Not Money</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <nav>
        <a href="home.php" class="logo">Edu<span>Swap</span></a>
        <ul>
            <li><a href="home.php" class="active">Home</a></li>
            <li><a href="users.php">Find Skills</a></li>
            <li><a href="session.php">My Sessions</a></li>
            <li><a href="profile.php?id=<?php echo $logged_in_user_id; ?>">My Profile</a></li>
        </ul>
    </nav>

    <div class="hero" style="padding: 60px 20px 80px 20px;">
        <div class="hero-inner" style="max-width: 900px; margin: 0 auto;">
            <h1 style="font-size: 52px; text-align: center; margin-bottom: 20px; font-family: Georgia, serif; font-weight: normal;">Learn new skills. Teach what you know. No money needed.</h1>
            <br>
            <p class="hero-subtitle" style="text-align: left; max-width: 600px; font-size: 15px; color: #aaa; margin-bottom: 25px;">EduSwap is a platform where you can offer your skills to others and earn credits that you can use to learn from someone else in the community.</p>
            <div class="hero-buttons" style="justify-content: left; gap: 10px; flex-wrap: wrap;">
                <a href="users.php" class="btn btn-primary" style="padding: 12px 28px;">Get Started</a>
                <a href="users.php" class="btn btn-outline" style="padding: 12px 24px;">Browse Skills</a>
                <a href="users.php" style="color: #aaa; font-size: 13px; align-self: center; margin-left: 8px;">or just look around</a>
            </div>
        </div>
    </div>

    <div class="how-it-works" style="padding: 40px 30px;">
        <h2 style="text-align: center; margin-bottom: 30px; font-size: 24px;">How It Works</h2>
        <div style="display: flex; gap: 0; max-width: 860px; margin: 0 auto; align-items: flex-start;">

            <div style="flex: 1; padding: 10px 20px 10px 0; text-align: left;">
                <div style="width: 42px; height: 42px; background: #2E86AB; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 600; margin-bottom: 12px;">1</div>
                <h3 style="font-size: 17px; margin-bottom: 6px;">Create Profile</h3>
                <p style="font-size: 13px; color: #555;">Add your skills and what you want to learn from other people on the platform.</p>
            </div>

            <hr style="border: none; border-left: 1px dashed #ccc; height: 80px; margin: 20px 0 0 0;">

            <div style="flex: 1; padding: 25px 20px 10px 20px; text-align: center;">
                <div style="width: 50px; height: 50px; background: #6C3FC5; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; margin: 0 auto 12px;">2</div>
                <h3 style="font-size: 16px; margin-bottom: 8px; color: #2E86AB;">Find a Match</h3>
                <p style="font-size: 14px; color: #777;">Browse users and find someone who teaches what you need</p>
            </div>

            <hr style="border: none; border-left: 1px dashed #ccc; height: 80px; margin: 20px 0 0 0;">

            <div style="flex: 1.2; padding: 10px 0 10px 20px; text-align: center;">
                <span style="display: inline-block; background: #27ae60; color: white; font-size: 18px; font-weight: bold; width: 38px; height: 38px; line-height: 38px; border-radius: 50%; margin-bottom: 14px;">3</span>
                <h3 style="font-size: 18px; margin-bottom: 6px;">Swap & Learn</h3>
                <p style="font-size: 13px; color: #555; line-height: 1.4;">Request a session and exchange credits for knowledge</p>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="featured-section">
            <h2>Featured Users</h2>
            <div class="grid">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="card">';
                        echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '">';
                        echo '<h3>' . $row['name'] . '</h3>';
                        echo '<p class="card-meta"><i data-lucide="map-pin" style="width:14px; height:14px; vertical-align:middle;"></i> ' . $row['city'] . ' • <i data-lucide="star" style="width:14px; height:14px; vertical-align:middle; fill:currentColor;"></i> ' . $row['rating'] . '</p>';
                        echo '<p class="card-bio">"' . substr($row['bio'], 0, 80) . '..."</p>';

                        echo '<div class="skills-list">';
                        $uid = $row['id'];
                        $s_res = mysqli_query($conn, "SELECT * FROM skills WHERE user_id = $uid");
                        while ($s_row = mysqli_fetch_assoc($s_res)) {
                            $type_class = $s_row['type'] == 'teach' ? 'badge-teach' : 'badge-learn';
                            echo '<span class="badge ' . $type_class . '">' . $s_row['skill_name'] . '</span>';
                        }
                        echo '</div>';

                        echo '<a href="profile.php?id=' . $row['id'] . '" class="btn btn-primary" style="margin-top:12px; width:100%; text-align:center; display:block;">View Profile</a>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>

        <div style="text-align:center; margin-bottom: 40px;">
            <a href="users.php" class="btn btn-primary">See All Users <i data-lucide="arrow-right" style="width:16px; height:16px; vertical-align:middle;"></i></a>
        </div>
    </div>

   

    <script src="script.js"></script>
</body>

</html>
