<?php
include '../config.php';

if (isset($_GET['id']) && isset($_GET['status']) && isset($_GET['role'])) {
    $event_id = htmlspecialchars($_GET['id']);
    $status = htmlspecialchars($_GET['status']);
    $role = htmlspecialchars($_GET['role']);

    // Prepare the SQL query to insert the notification into the database
    $sql_insert_notification = "INSERT INTO notifications (event_id, recipient_role, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert_notification);
    $stmt->bind_param("iss", $event_id, $role, $status);

    // Execute the query to insert the notification
    if ($stmt->execute()) {
        // Success message
        echo "Notification for event ID $event_id has been successfully sent to role: $role.";
    } else {
        // Error message
        echo "Error sending notification: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
