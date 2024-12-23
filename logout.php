<?php
// เริ่มต้นการใช้งาน session
session_start();

// ลบข้อมูลทั้งหมดใน session
session_unset(); // ลบค่าทั้งหมดที่เก็บใน session

// ทำลาย session
session_destroy(); // ทำลาย session ทั้งหมด

// เปลี่ยนเส้นทางไปยังหน้า login หลังจากออกจากระบบ
header("Location: login.php");
exit;
?>