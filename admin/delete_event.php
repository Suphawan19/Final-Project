<?php
include('../config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ปิดการใช้ foreign key check ชั่วคราว
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    // ลบข้อมูลในตาราง events
    $delete_sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // เปิดการใช้ foreign key check หลังจากลบข้อมูล
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    echo "เหตุการณ์ถูกลบสำเร็จ!";
    header("Location: event_display.php");
    exit;
}
?>