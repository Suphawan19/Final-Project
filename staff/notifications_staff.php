<?php
include '../config.php';

// ดึงแจ้งเตือนเฉพาะ role = 'staff'
$sql = "SELECT * FROM notifications WHERE role = 'staff'";
$result = $conn->query($sql);

echo "<h2>Notifications for Staff</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<p><b>Event:</b> " . $row['message'] . "</p>";
        echo "<p><b>Date:</b> " . $row['created_at'] . "</p>";
        echo "</div><hr>";
    }
} else {
    echo "No notifications available.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>staff Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>
    <h1>Notifications for staff</h1>
    <table>
        <thead>
            <tr>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['created_at']))); ?></td>
                    <td><?php echo $row['is_read'] ? 'Read' : 'Unread'; ?></td>
                    <td>
                        <!-- ลิงก์ไปยังหน้ารายละเอียด -->
                        <a href="../student/notifications_studdent.php?php echo $row['notification_id']; ?>">View Details</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>