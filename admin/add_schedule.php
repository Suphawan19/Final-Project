<?php include '../config.php'; ?>

<?php
if (isset($_POST['submit'])) {
    // รับค่าจากฟอร์ม
    $course_id = $conn->real_escape_string($_POST['course_name']);
    $instructor_id = $conn->real_escape_string($_POST['instructor_name']);
    $room = $conn->real_escape_string($_POST['room']);
    $day_of_week = $conn->real_escape_string($_POST['day_of_week']);
    $start_time = $conn->real_escape_string($_POST['start_time']);
    $end_time = $conn->real_escape_string($_POST['end_time']);

    // เพิ่มข้อมูลลงฐานข้อมูล
    $sql = "INSERT INTO class_schedule (course_id, instructor_id, room, day_of_week, start_time, end_time) 
            VALUES ('$course_id', '$instructor_id', '$room', '$day_of_week', '$start_time', '$end_time')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Schedule added successfully!');
                window.location.href = 'class_schedule.php';
              </script>";
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
    <title>Add Schedule</title>
    <link href="../style.css/style_add_schedule.css" rel="stylesheet">
</head>
<body>
    <form method="POST">
        <h1>Add New Schedule</h1>

        <!-- ฟอร์มสำหรับเลือกชื่อวิชา -->
        <label for="course_name">Course Name</label>
        <select id="course_name" name="course_name" required>
            <option value="">Select Course</option>
            <?php
            $sql_courses = "SELECT course_id, course_name FROM courses";
            $result_courses = $conn->query($sql_courses);
            if ($result_courses->num_rows > 0) {
                while ($row = $result_courses->fetch_assoc()) {
                    echo "<option value='" . $row['course_id'] . "'>" . htmlspecialchars($row['course_name']) . "</option>";
                }
            }
            ?>
        </select>

        <!-- ฟอร์มสำหรับเลือกชื่ออาจารย์ -->
        <label for="instructor_name">Instructor Name</label>
        <select id="instructor_name" name="instructor_name" required>
            <option value="">Select Instructor</option>
            <?php
            $sql_instructors = "SELECT instructor_id, instructor_name FROM instructors";
            $result_instructors = $conn->query($sql_instructors);
            if ($result_instructors->num_rows > 0) {
                while ($row = $result_instructors->fetch_assoc()) {
                    echo "<option value='" . $row['instructor_id'] . "'>" . htmlspecialchars($row['instructor_name']) . "</option>";
                }
            }
            ?>
        </select>

        <!-- ฟิลด์ห้องเรียน -->
        <label for="room">Room</label>
        <input type="text" id="room" name="room" required>

        <!-- ฟอร์มเลือกวัน -->
        <label for="day_of_week">Day of the Week</label>
        <select id="day_of_week" name="day_of_week" required>
            <option value="">Select Day</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>

        <!-- เวลาเริ่มต้นและสิ้นสุด -->
        <label for="start_time">Start Time</label>
        <input type="time" id="start_time" name="start_time" required>
        
        <label for="end_time">End Time</label>
        <input type="time" id="end_time" name="end_time" required>

        <button type="submit" name="submit">Add Schedule</button>
    </form>
</body>
</html>
