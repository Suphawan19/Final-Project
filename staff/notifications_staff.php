<?php
include '../config.php';

// ดึงการแจ้งเตือนสำหรับ role = 'staff' หรือ 'all'
$sql_notifications = "
    SELECT notifications.*, events.title AS event_title
    FROM notifications 
    LEFT JOIN events ON notifications.event_id = events.id
    WHERE notifications.role IN ('staff', 'all') 
    ORDER BY notifications.date_created DESC
";
$result_notifications = $conn->query($sql_notifications);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Notifications</title>
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
            background: linear-gradient(145deg, #ffffff, #e6e9f0);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 25px;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease-in-out;
        }

        .notification-card:hover {
            transform: translateY(-10px);
        }

        .notification-icon {
            font-size: 2.5rem;
            margin-right: 20px;
            padding: 10px;
            border-radius: 50%;
            background-color: #f0f4f8;
        }

        .notification-icon.sent {
            color: #28a745;
            background-color: #e1f5e0;
        }

        .notification-icon.pending {
            color: #ffc107;
            background-color: #fff8e1;
        }

        .notification-icon.unknown {
            color: #6c757d;
            background-color: #e2e6ea;
        }

        .notification-text {
            flex-grow: 1;
        }

        .notification-text h5 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: bold;
            color:#fe2f2f;
        }

        .notification-text p {
            margin: 8px 0;
            color: #555;
            font-size: 1rem;
        }

        .notification-date {
            font-size: 0.9rem;
            color: #888;
        }

        /* Custom styling for the search and button */
        .search-bar {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar input {
            width: 80%;
            padding: 10px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: 1px solid #ddd;
            transition: border 0.3s ease;
        }

        .search-bar input:focus {
            border: 1px solid #007bff;
            outline: none;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 1.1rem;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color:rgb(251, 25, 25);
        }

        .no-notifications {
            text-align: center;
            margin-top: 30px;
        }

        .alert {
            background-color: #e9f7ef;
            color: #28a745;
        }

    </style>
</head>

<body>

<div class="container">
<h1 class="text-center mb-4"><i class="fas fa-bell" style="color:rgb(255, 207, 65);"></i> Staff Notifications</h1>


      <!-- Search bar -->
      <div class="search-bar">
        <input type="text" class="form-control" id="searchInput" placeholder="Search notifications..." onkeyup="searchNotifications()">
        <button class="btn btn-primary">Send Notification</button>
    </div>

    <?php if ($result_notifications->num_rows > 0): ?>
        <?php while ($notification = $result_notifications->fetch_assoc()): ?>
            <div class="notification-card">
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
        <div class="no-notifications">
            <div class="alert alert-info">
                <i class="fas fa-smile"></i> No notifications available!
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to search through notifications
    function searchNotifications() {
        var input, filter, cards, card, text, i;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        cards = document.getElementsByClassName('notification-card');

        for (i = 0; i < cards.length; i++) {
            card = cards[i];
            text = card.textContent || card.innerText;
            if (text.toUpperCase().indexOf(filter) > -1) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        }
    }
</script>
</body>

</html>
