<?php
include '../config.php';
session_start();

$user_id = isset($_SESSION['user_id']) ? mysqli_real_escape_string($conn, $_SESSION['user_id']) : null;
$role = isset($_SESSION['role']) ? mysqli_real_escape_string($conn, $_SESSION['role']) : null;

if ($user_id && $role) {
    $sql_notifications = "
        SELECT * FROM notifications 
        WHERE role = '$role' OR role = 'all'
        ORDER BY created_at DESC";
    $result_notifications = $conn->query($sql_notifications);
} else {
    die("Error: User not authenticated.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Notifications</h2>
    <ul class="list-group">
        <?php while ($row = $result_notifications->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?= $row['message'] ?></strong>
                <span class="badge bg-secondary"><?= $row['created_at'] ?></span>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
