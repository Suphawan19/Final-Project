<?php

$servername = "localhost";
$username = "root"; // หรือชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = ""; // หรือรหัสผ่าน MySQL
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>




