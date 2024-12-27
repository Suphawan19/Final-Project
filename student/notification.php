<?php
include '../config.php';

// Get the role from the URL
$role = $_GET['role'];
$current_date = date('Y-m-d');

// Query events based on the role and current date
$sql = "SELECT * FROM events WHERE status = 'completed' AND (role = '$role' OR role = 'all') AND end_date <= '$current_date'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Event: " . $row['title'] . "<br>";
        echo "Date: " . $row['Set_event_date'] . "<br>";
        echo "End Date: " . $row['end_date'] . "<br><br>";
    }
} else {
    echo "No events found.";
}
?>
