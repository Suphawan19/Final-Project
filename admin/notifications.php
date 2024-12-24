<?php
include '../config.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล

// Query ข้อมูลการแจ้งเตือน
$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);

$notifications = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}

// ส่งข้อมูลเป็น JSON
header('Content-Type: application/json');
echo json_encode($notifications);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .notification-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .notification {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .notification:last-child {
            border-bottom: none;
        }
        .notification .details {
            max-width: 80%;
        }
        .notification .details h4 {
            margin: 0;
            font-size: 1rem;
        }
        .notification .details p {
            margin: 5px 0;
            color: #555;
            font-size: 0.9rem;
        }
        .notification .actions button {
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
        }
        .read-btn {
            background: #4caf50;
            color: white;
        }
        .delete-btn {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="notification-container" id="notifications"></div>

    <script>
        // ดึงข้อมูลจาก API
        fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('notifications');
                container.innerHTML = ''; // เคลียร์เนื้อหาเก่า
                data.forEach(notification => {
                    container.innerHTML += `
                        <div class="notification">
                            <div class="details">
                                <h4>${notification.title}</h4>
                                <p>${notification.message}</p>
                                <small>${new Date(notification.created_at).toLocaleString()}</small>
                            </div>
                            <div class="actions">
                                <button class="read-btn" onclick="markAsRead(${notification.id})">Mark as Read</button>
                                <button class="delete-btn" onclick="deleteNotification(${notification.id})">Delete</button>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(error => console.error('Error:', error));

        // ฟังก์ชัน Mark as Read
        function markAsRead(id) {
            fetch(`mark_as_read.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    alert(data); // แสดงผลตอบกลับ
                    location.reload(); // โหลดหน้าใหม่
                });
        }

        // ฟังก์ชัน Delete Notification
        function deleteNotification(id) {
            fetch(`delete_notification.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    alert(data); // แสดงผลตอบกลับ
                    location.reload(); // โหลดหน้าใหม่
                });
        }
    </script>
</body>
</html>

