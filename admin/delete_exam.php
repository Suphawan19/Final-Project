<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่ง exam_id
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    // ลบการสอบจากฐานข้อมูล
    $sql = "DELETE FROM Exams WHERE exam_id = $exam_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: exam_schedule.php"); // Redirect after successful deletion
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>