<?php
// เชื่อมต่อฐานข้อมูล
include('../config.php');

// ตรวจสอบว่ามีการส่ง 'id' มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // สร้างคำสั่ง SQL สำหรับลบข้อมูล
    $query = "DELETE FROM career_advice WHERE id = ?";

    // เตรียมการเตรียมคำสั่ง SQL
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $id); // 'i' สำหรับ integer
        if ($stmt->execute()) {
            // หากลบสำเร็จ
            header('Location: career guidance.php'); // กลับไปที่หน้า Career Guidance
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    // หากไม่มี id ที่ส่งมา
    echo "No career advice ID provided.";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>