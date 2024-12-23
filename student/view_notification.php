<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า id ถูกส่งเข้ามาหรือไม่
if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];

    // ดึงข้อมูลการแจ้งเตือนที่เลือกจากฐานข้อมูล
    $sql = "SELECT * FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบว่ามีการแจ้งเตือนที่ตรงกับ id หรือไม่
    if ($result->num_rows > 0) {
        $notification = $result->fetch_assoc();
    } else {
        echo "No notification found.";
        exit;
    }
} else {
    echo "No notification ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .notification-details { padding: 20px; border: 1px solid #ddd; margin-top: 20px; }
        h2 { margin-bottom: 20px; }
        .time { font-size: 0.9em; color: #888; }
        .message { margin-top: 10px; font-size: 1.2em; }
    </style>
</head>
<body>
    <h1>Notification Details</h1>

    <div class="notification-details">
        <h2><?php echo htmlspecialchars($notification['message']); ?></h2>
        <p class="time"><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($notification['created_at']))); ?></p>
        <p class="message"><?php echo nl2br(htmlspecialchars($notification['details'])); ?></p>
    </div>
</body>
</html>