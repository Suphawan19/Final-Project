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

// ดึงข้อมูลสำหรับตัวเลือก (Dropdown)
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

    // เพิ่มข้อมูลลงในตาราง Exams
    $sql = "INSERT INTO Exams (exam_date, start_time, end_time, course_id, room, instructor_id) 
            VALUES ('$exam_date', '$start_time', '$end_time', '$course_id', '$room', '$instructor_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: exam_schedule.php"); // Redirect after successful addition
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
    <title>Add Exam</title>
    <!-- เชื่อมต่อกับ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css/style_add_exam.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <div class="card">
            <h2>Add Exam Schedule</h2>

            <!-- แสดงข้อความหลังจากเพิ่มข้อมูลสำเร็จ -->
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $conn->affected_rows > 0): ?>
                <div class="alert alert-success">Exam added successfully!</div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="exam_date" class="form-label">Exam Date:</label>
                    <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="start_time" class="form-label">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="end_time" class="form-label">End Time:</label>
                    <input type="time" id="end_time" name="end_time" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="course_id" class="form-label">Course:</label>
                    <select id="course_id" name="course_id" class="form-control" required>
                        <option value="">Select Course</option>
                        <?php
                        if ($courses_result->num_rows > 0) {
                            while ($row = $courses_result->fetch_assoc()) {
                                echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="room" class="form-label">Room:</label>
                    <input type="text" id="room" name="room" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="instructor_id" class="form-label">Instructor:</label>
                    <select id="instructor_id" name="instructor_id" class="form-control" required>
                        <option value="">Select Instructor</option>
                        <?php
                        if ($instructors_result->num_rows > 0) {
                            while ($row = $instructors_result->fetch_assoc()) {
                                echo "<option value='{$row['instructor_id']}'>{$row['instructor_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn-add-exam">Add Exam</button>
            </form>
        </div>
    </div>

    <!-- เชื่อมต่อกับ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
