<?php
include '../config.php';

$id = intval($_GET['id']); // รับ ID ที่ส่งมา
$sql = "DELETE FROM notifications WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Notification deleted successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
