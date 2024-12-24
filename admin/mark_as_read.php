<?php
include '../config.php';

$id = intval($_GET['id']); // รับ ID ที่ส่งมา
$sql = "UPDATE notifications SET is_read = 1 WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Marked as read successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
