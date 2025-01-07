<?php
include '../config.php';

// ดึงการแจ้งเตือนสำหรับ role = 'student' หรือ 'all'
$sql_notifications = "
    SELECT notifications.*, events.title AS event_title
    FROM notifications 
    LEFT JOIN events ON notifications.event_id = events.id
    WHERE notifications.role IN ('student', 'all') 
    ORDER BY notifications.date_created DESC
";
$result_notifications = $conn->query($sql_notifications);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Notifications</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f6f9;
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center !important;
            color:rgb(135, 21, 21);
        }

        .container {
            margin-top: 50px;
        }

        .notification-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .notification-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .notification-icon {
            font-size: 3rem;
            margin-right: 20px;
            transition: transform 0.3s;
        }

        .notification-icon.sent {
            color: #28a745;
        }

        .notification-icon.pending {
            color: #ffc107;
        }

        .notification-icon.unknown {
            color: #6c757d;
        }

        .notification-icon:hover {
            transform: scale(1.2);
        }

        .notification-text {
            flex-grow: 1;
        }

        .notification-text h5 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: bold;
            color: #fe2f2f;
        }

        .notification-text p {
            margin: 5px 0;
            color: #555;
        }

        .notification-date {
            font-size: 0.875rem;
            color: #888;
        }

        .alert {
            border-radius: 8px;
            padding: 15px;
            background-color: #e7f3e7;
            color: #333;
            font-weight: bold;
        }

        .alert i {
            margin-right: 10px;
        }

        .search-bar {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            border-radius: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 80%;
        }

        .search-bar button {
            background-color: #ffc111b5;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color:rgba(255, 192, 17, 0.99);
        }

        .send-button {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="text-center mb-4"><i class="fas fa-bell" style="color: #ffb03d;"></i> Student Notifications</h1>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" class="form-control" id="searchInput" placeholder="Search notifications..." onkeyup="searchNotifications()">
            <button class="btn custom-btn"><i class="fas fa-search"></i></button>
        </div>

        <div id="notificationContainer">
            <?php if ($result_notifications->num_rows > 0): ?>
                <?php while ($notification = $result_notifications->fetch_assoc()): ?>
                    <div class="notification-card" data-message="<?= strtolower($notification['message']) ?>" data-event="<?= strtolower($notification['event_title']) ?>">
                        <div class="notification-icon 
                    <?= !empty($notification['status']) ? strtolower($notification['status']) : 'unknown'; ?>">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="notification-text">
                            <h5><?= htmlspecialchars($notification['message']) ?></h5>
                            <p>Event: <?= htmlspecialchars($notification['event_title']) ?></p> <!-- แสดงชื่อเหตุการณ์ -->
                            <span class="notification-date"><?= htmlspecialchars($notification['date_created']) ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-smile"></i> No notifications available!
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function searchNotifications() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var notificationContainer = document.getElementById('notificationContainer');
            var notifications = notificationContainer.getElementsByClassName('notification-card');

            for (var i = 0; i < notifications.length; i++) {
                var notification = notifications[i];
                var message = notification.getAttribute('data-message');
                var event = notification.getAttribute('data-event');

                if (message.indexOf(filter) > -1 || event.indexOf(filter) > -1) {
                    notification.style.display = "";
                } else {
                    notification.style.display = "none";
                }
            }
        }
    </script>

</body>

</html>