<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

session_start(); // เริ่มต้น session
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in!"); // ถ้าผู้ใช้ไม่ได้ล็อกอิน
}

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $title = $_POST['title'];
    $description = $_POST['description'];

    // จัดการกับการอัพโหลดไฟล์ภาพ
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = '../uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image_path);
    }

    // SQL สำหรับบันทึกโพสต์ลงในฐานข้อมูล
    $sql = "INSERT INTO posts (title, description, image_path, created_at, user_id)
            VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $image_path, $user_id);
    $stmt->execute();

    // รีไดเรกต์ไปที่หน้า home_student
    header("Location: home_student.php");
    exit;
}
?>