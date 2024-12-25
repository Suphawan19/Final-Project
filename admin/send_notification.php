<?php
include '../config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = htmlspecialchars($_GET['id']);
    $status = htmlspecialchars($_GET['status']);
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .notification-container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .notification-container h1 {
            font-size: 26px;
            margin-bottom: 20px;
        }
        .notification-container .btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .notification-container .btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .status-pending {
            color: #ff9800;
            font-weight: bold;
        }
        .status-sent {
            color: #4caf50;
            font-weight: bold;
        }
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
            animation: bounce 1.5s infinite;
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
<div class="notification-container">
    <?php
    // Retrieve event details
    $sql_event = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql_event);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_event = $stmt->get_result();

    if ($result_event->num_rows > 0) {
        $event = $result_event->fetch_assoc();
        $role = $event['role']; // Retrieve the role

        // Display notification details based on the status
        if ($status === 'pending') {
            echo '<i class="fas fa-clock icon text-warning"></i>';
            echo '<h1 class="status-pending">Notification Pending</h1>';
            echo '<p>Event: <strong>' . htmlspecialchars($event['title']) . '</strong></p>';
            echo "Event ID: $id<br>";
            echo "Status: $status<br>";
            echo '<button class="btn btn-warning" onclick="sendNotification(\'' . $role . '\', ' . $event['id'] . ', \'' . $status . '\')">Send Notification</button>';
        } else {
            echo '<i class="fas fa-check-circle icon text-success"></i>';
            echo '<h1 class="status-sent">Notification Sent</h1>';
            echo '<p>Event: <strong>' . htmlspecialchars($event['title']) . '</strong></p>';
        }
    } else {
        echo '<i class="fas fa-exclamation-triangle icon text-danger"></i>';
        echo '<h1 class="text-danger">Event Not Found</h1>';
    }
    ?>
    <a href="event_display.php" class="btn btn-secondary">Back to Events</a>
</div>

<!-- JavaScript -->
<script>
function sendNotification(role, eventId, status) {
    console.log("Role:", role); // Log the role to check its value

    let url;
    // Set the URL based on the role
    if (role === 'student') {
        url = 'notifications_student.php';
    } else if (role === 'staff') {
        url = 'notifications_staff.php';
    } else if (role === 'all') {
        url = 'manager_notification.php';
    } else {
        // If the role is not recognized
        alert('Invalid role: ' + role);  // Display the role in the alert to see its value
        return;
    }

    // Show a confirmation message on the page
    let confirmationDiv = document.createElement("div");
    confirmationDiv.style.position = "fixed";
    confirmationDiv.style.top = "50%";
    confirmationDiv.style.left = "50%";
    confirmationDiv.style.transform = "translate(-50%, -50%)";
    confirmationDiv.style.padding = "20px";
    confirmationDiv.style.backgroundColor = "#4caf50";
    confirmationDiv.style.color = "#fff";
    confirmationDiv.style.borderRadius = "5px";
    confirmationDiv.innerHTML = "Notification for event '" + eventId + "' is being sent to role: " + role;
    document.body.appendChild(confirmationDiv);

    // Automatically hide the confirmation after 3 seconds
    setTimeout(function() {
        confirmationDiv.style.display = "none";
    }, 3000);

    // Redirect to the appropriate URL with event ID, status, and role as parameters
    window.location.href = `${url}?id=${eventId}&status=${status}&role=${role}`;
}


</script>

</body>
</html>
