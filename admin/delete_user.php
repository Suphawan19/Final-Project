<?php
// เปิดการแสดงข้อผิดพลาด
error_reporting(E_ALL);
ini_set('display_errors', 1);

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db"; // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ลบข้อมูลผู้ใช้
$user_id = $_GET['id'] ?? null;

if ($user_id) {
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
header("Location: user_management.php"); // กลับไปที่หน้าแรกหลังลบเสร็จ
exit();
?>
