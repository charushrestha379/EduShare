<?php
include 'db.php';
$logged_in_user_id = 1;

if (isset($_POST['action'])) {
    $session_id = (int) $_POST['session_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        mysqli_query($conn, "UPDATE sessions SET status = 'accepted' WHERE id = $session_id");
    } elseif ($action == 'complete') {
        $s_sql = "SELECT * FROM sessions WHERE id = $session_id";
        $s_res = mysqli_query($conn, $s_sql);
        $s_data = mysqli_fetch_assoc($s_res);
        $hours = $s_data['hours'];
        $teacher_id = $s_data['teacher_id'];
        $learner_id = $s_data['learner_id'];
        mysqli_query($conn, "UPDATE users SET credits = credits + $hours WHERE id = $teacher_id");
        mysqli_query($conn, "UPDATE users SET credits = credits - $hours WHERE id = $learner_id");
        mysqli_query($conn, "UPDATE sessions SET status = 'completed' WHERE id = $session_id");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Sessions - EduSwap</title>
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
        <h1 class="section-title">Your Learning & Teaching Sessions</h1>

        <h2 class="session-section-title">Incoming Requests (Teaching)</h2>
        <?php
        $sql = "SELECT s.*, u.name as learner_name FROM sessions s 
                JOIN users u ON s.learner_id = u.id 
                WHERE s.teacher_id = $logged_in_user_id AND s.status != 'completed'";
        $res = mysqli_query($conn, $sql);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                echo '<div class="session-card">';
                echo '<div>';
                echo '<h3>' . $row['skill_name'] . ' for ' . $row['learner_name'] . '</h3>';
                echo '<p>Duration: ' . $row['hours'] . ' hours • Status: <span style="background:#fbbf24; color:#333; padding:4px 10px; border-radius:20px; font-size:13px;">' . ucfirst($row['status']) . '</span></p>';
                echo '</div>';
                echo '<div>';
                if ($row['status'] == 'pending') {
                    echo '<form action="" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="session_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="action" value="accept" class="btn btn-primary">Accept</button>';
                    echo '</form>';
                } else if ($row['status'] == 'accepted') {
                    echo '<form action="" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="session_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="action" value="complete" class="btn btn-primary" style="background:#27ae60;">Mark Done</button>';
                    echo '</form>';
                }
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="empty-state">No incoming requests.</p>';
        }
        ?>

        <h2 class="session-section-title" style="margin-top:40px;">Outgoing Requests (Learning)</h2>
        <?php
        $sql = "SELECT s.*, u.name as teacher_name FROM sessions s 
                JOIN users u ON s.teacher_id = u.id 
                WHERE s.learner_id = $logged_in_user_id AND s.status != 'completed'";
        $res = mysqli_query($conn, $sql);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                echo '<div class="session-card">';
                echo '<div>';
                echo '<h3>Learning ' . $row['skill_name'] . ' from ' . $row['teacher_name'] . '</h3>';
                echo '<p>Duration: ' . $row['hours'] . ' hours • Status: <span style="background:#27ae60; color:white; padding:4px 10px; border-radius:20px; font-size:13px;">' . ucfirst($row['status']) . '</span></p>';
                echo '</div>';
                echo '<p style="font-style:italic; color:#999;">Waiting for response...</p>';
                echo '</div>';
            }
        } else {
            echo '<p class="empty-state">You don\'t have any sessions yet. Why not browse some skills?</p>';
        }
        ?>

        <h2 class="session-section-title" style="margin-top:40px;">Completed History</h2>
        <?php
        $sql = "SELECT s.*, t.name as teacher_name, l.name as learner_name FROM sessions s 
                JOIN users t ON s.teacher_id = t.id 
                JOIN users l ON s.learner_id = l.id 
                WHERE (s.teacher_id = $logged_in_user_id OR s.learner_id = $logged_in_user_id) 
                AND s.status = 'completed'";
        $res = mysqli_query($conn, $sql);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $role = $row['teacher_id'] == $logged_in_user_id ? 'Taught' : 'Learned';
                $other = $row['teacher_id'] == $logged_in_user_id ? $row['learner_name'] : $row['teacher_name'];
                echo '<div class="session-card" style="opacity:0.7;">';
                echo '<div>';
                echo '<h3>' . $role . ' ' . $row['skill_name'] . ' with ' . $other . '</h3>';
                echo '<p>' . $row['hours'] . ' hours • <span style="background:#27ae60; color:white; padding:4px 10px; border-radius:20px; font-size:13px;">Completed</span></p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="empty-state">No completed sessions yet.</p>';
        }
        ?>
    </div>

    
    <script src="script.js"></script>
</body>
</html>