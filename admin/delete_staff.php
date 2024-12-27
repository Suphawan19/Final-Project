<?php
include '../config.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    $sql = "DELETE FROM staff WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_staff.php");
        exit();
    } else {
        echo "Error deleting staff: " . $conn->error;
    }
}
?>
