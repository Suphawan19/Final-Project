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

// ตรวจสอบว่าได้รับ ID ของการสอบที่ต้องการแก้ไข
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    // ดึงข้อมูลการสอบจากฐานข้อมูล
    $exam_result = $conn->query("SELECT * FROM Exams WHERE exam_id = $exam_id");
    if ($exam_result->num_rows > 0) {
        $exam = $exam_result->fetch_assoc();
    } else {
        echo "Exam not found.";
        exit();
    }
}

// ดึงข้อมูลสำหรับตัวเลือก (Dropdown) รายวิชาและอาจารย์
$courses_result = $conn->query("SELECT course_id, course_name FROM courses");
$instructors_result = $conn->query("SELECT instructor_id, instructor_name FROM instructors");

// ตรวจสอบว่ามีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_date = $_POST['exam_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $course_id = $_POST['course_id'];
    $room = $_POST['room'];
    $instructor_id = $_POST['instructor_id'];

    // อัปเดตข้อมูลการสอบในฐานข้อมูล
    $sql = "UPDATE Exams 
            SET exam_date = '$exam_date', start_time = '$start_time', end_time = '$end_time', 
                course_id = '$course_id', room = '$room', instructor_id = '$instructor_id'
            WHERE exam_id = $exam_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: exam_schedule.php"); // Redirect after successful update
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #FF0000;
            margin-top: 30px;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        label {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
            background-color: #f9f9f9;
        }
        input:focus, select:focus {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color:rgba(255, 255, 255, 0.55);
            outline: none;
        }
        button {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #FF0000;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #e68900;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Exam</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="exam_date">Exam Date:</label>
                <input type="date" id="exam_date" name="exam_date" value="<?php echo htmlspecialchars($exam['exam_date']); ?>" required>
            </div>

            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($exam['start_time']); ?>" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($exam['end_time']); ?>" required>
            </div>

            <div class="form-group">
                <label for="course_id">Course:</label>
                <select id="course_id" name="course_id" required>
                    <?php
                    while ($row = $courses_result->fetch_assoc()) {
                        $selected = $exam['course_id'] == $row['course_id'] ? 'selected' : '';
                        echo "<option value='{$row['course_id']}' $selected>{$row['course_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="room">Room:</label>
                <input type="text" id="room" name="room" value="<?php echo htmlspecialchars($exam['room']); ?>" required>
            </div>

            <div class="form-group">
                <label for="instructor_id">Instructor:</label>
                <select id="instructor_id" name="instructor_id" required>
                    <?php
                    while ($row = $instructors_result->fetch_assoc()) {
                        $selected = $exam['instructor_id'] == $row['instructor_id'] ? 'selected' : '';
                        echo "<option value='{$row['instructor_id']}' $selected>{$row['instructor_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit">Update Exam</button>
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
