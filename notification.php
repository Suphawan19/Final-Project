<?php
// notification.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
</head>
<body>
    <h1>Notifications</h1>
    <?php
    $role = 'student'; // Example role
    $conn = new mysqli('localhost', 'root', '', 'database_name');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT events.title, notifications.is_read FROM notifications 
            JOIN events ON notifications.event_id = events.id
            WHERE notifications.role = '$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $status = $row['is_read'] ? "Read" : "Unread";
            echo "<div><h3>{$row['title']}</h3><p>Status: $status</p></div>";
        }
    } else {
        echo "<p>No notifications found.</p>";
    }

    $conn->close();
    ?>
</body>
</html>