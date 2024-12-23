<?php
include '../config.php';

// รับค่า ID ของนักศึกษาที่จะลบ
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // คำสั่ง SQL สำหรับลบข้อมูล
    $sql = "DELETE FROM students WHERE student_id = $student_id";

    if ($conn->query($sql) === TRUE) {
        // ลบสำเร็จ ให้เปลี่ยนไปหน้ารายการนักศึกษา
        header("Location: admin_student.php");
        exit();
    } else {
        // หากลบไม่สำเร็จ
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request!";
    exit();
}

$conn->close();
?>