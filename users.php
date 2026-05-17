<?php
include 'db.php';
$logged_in_user_id = 1;

$search = "";
$city = "";
$type = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}
if (isset($_GET['city'])) {
    $city = mysqli_real_escape_string($conn, $_GET['city']);
}
if (isset($_GET['type'])) {
    $type = mysqli_real_escape_string($conn, $_GET['type']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Find Skills - EduSwap</title>
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

    <div class="container">
        <h1 class="section-title">Explore Skill Swappers</h1>

        <form action="users.php" method="GET" class="search-filters">
            <input type="text" name="search" placeholder="Search by skill (e.g. Photoshop, Guitar...)"
                value="<?php echo $search; ?>">
            <input type="text" name="city" class="city-input" placeholder="City"
                value="<?php echo $city; ?>">
            <select name="type">
                <option value="">All Types</option>
                <option value="teach" <?php if ($type == 'teach') echo 'selected'; ?>>Teaching</option>
                <option value="learn" <?php if ($type == 'learn') echo 'selected'; ?>>Learning</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="users-grid">
            <?php
            $sql = "SELECT DISTINCT u.* FROM users u 
                    LEFT JOIN skills s ON u.id = s.user_id 
                    WHERE u.id != $logged_in_user_id";

            if ($search != "") {
                $sql .= " AND s.skill_name LIKE '%$search%'";
            }
            if ($city != "") {
                $sql .= " AND u.city LIKE '%$city%'";
            }
            if ($type != "") {
                $sql .= " AND s.type = '$type'";
            }

            $res = mysqli_query($conn, $sql);
            if (!$res) {
                echo "Error: " . mysqli_error($conn);
            }

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo '<div class="card">';
                    echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p class="card-meta"><i data-lucide="map-pin" style="width:14px; height:14px; vertical-align:middle;"></i> ' . $row['city'] . ' • Credits: ' . $row['credits'] . '</p>';
                    echo '<p class="card-bio">"' . substr($row['bio'], 0, 80) . '..."</p>';

                    echo '<div class="skills-list">';
                    $uid = $row['id'];
                    $s_sql = "SELECT * FROM skills WHERE user_id = $uid";
                    $s_res = mysqli_query($conn, $s_sql);
                    while ($s_row = mysqli_fetch_assoc($s_res)) {
                        $type_class = $s_row['type'] == 'teach' ? 'badge-teach' : 'badge-learn';
                        echo '<span class="badge ' . $type_class . '">' . $s_row['skill_name'] . '</span>';
                    }
                    echo '</div>';

                    echo '<a href="profile.php?id=' . $row['id'] . '" class="btn btn-primary" style="margin-top:12px; width:100%; text-align:center; display:block;">View Profile</a>';
                    echo '</div>';
                }
            } else {
                echo '<p class="empty-state" style="grid-column: 1/-1;">No users found matching that skill. Try a different search!</p>';
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 EduSwap - Built with <i data-lucide="heart" style="width:14px; height:14px; vertical-align:middle; fill:#e11d48; color:#e11d48;"></i> by a Student</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>