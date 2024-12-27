<?php
include '../config.php';

$current_date = date('Y-m-d');
$update_query = "UPDATE events SET status = 'sent' WHERE status = 'pending' AND end_date <= '$current_date'";
$conn->query($update_query);

echo "Status updated successfully.";
?>
