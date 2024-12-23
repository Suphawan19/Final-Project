<?php
include '../config.php';

if (isset($_GET['schedule_id'])) {
    $schedule_id= $_GET['schedule_id'];

    // ลบข้อมูลที่มี id ตรงกับที่ส่งมา
    $sql_delete = "DELETE FROM class_schedule WHERE schedule_id = '$schedule_id'";

    if ($conn->query($sql_delete) === TRUE) {
        echo "<p>Schedule deleted successfully! <a href='class_schedule.php'>Back to Schedule</a></p>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid ID.";
}

$conn->close();
?>