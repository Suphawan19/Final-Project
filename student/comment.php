<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าได้รับข้อมูลมาจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'], $_POST['author_name'], $_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $author_name = $_POST['author_name'];
    $comment = $_POST['comment'];

    // สร้างคำสั่ง SQL เพื่อบันทึกคอมเมนต์
    $sql = "INSERT INTO comments (post_id, author_name, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $post_id, $author_name, $comment);

    // ตรวจสอบว่าการบันทึกสำเร็จหรือไม่
    if ($stmt->execute()) {
        // หลังจากบันทึกคอมเมนต์เสร็จแล้ว รีเฟรชไปที่หน้า home_student.php
        header("Location: home_student.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
