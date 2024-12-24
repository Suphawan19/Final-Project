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

// ดึงข้อมูลจากตาราง Exams
$sql = "SELECT 
            e.exam_id,
            e.exam_date,
            e.start_time,
            e.end_time,
            c.course_name,
            e.room,
            i.instructor_id, 
            i.instructor_name
        FROM 
            Exams e
        JOIN 
            Courses c ON e.course_id = c.course_id
        LEFT JOIN 
            Instructors i ON e.instructor_id = i.instructor_id
        ORDER BY 
            e.exam_date, e.start_time";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css/style_exam_schedule.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="table-container">
        <h1>Exam Schedule</h1>
        <div class="text-end mb-3">
            <a href="add_exam.php" class="btn btn-add btn-sm">Add Exam</a>
        </div>
            <table class="table table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Exam ID</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Course</th>
                        <th>Room</th>
                        <th>Instructor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['exam_id']}</td>
                                    <td>{$row['exam_date']}</td>
                                    <td>{$row['start_time']}</td>
                                    <td>{$row['end_time']}</td>
                                    <td>{$row['course_name']}</td>
                                    <td>{$row['room']}</td>
                                    <td>{$row['instructor_name']}</td>
                                    <td>
                                        <a href='edit_exam.php?exam_id={$row['exam_id']}' class='btn btn-edit btn-sm'>Edit</a>
                                        <a href='delete_exam.php?exam_id={$row['exam_id']}' class='btn btn-delete btn-sm' onclick=\"return confirm('Are you sure you want to delete this exam?');\">Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='no-data'>No exams scheduled</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>
</html>
