<?php
include '../config.php';

// ตรวจสอบว่ามี schedule_id หรือไม่
if (isset($_GET['schedule_id'])) {
    $schedule_id = intval($_GET['schedule_id']);

    // ดึงข้อมูลเดิมจากฐานข้อมูล
    $sql = "SELECT * FROM class_schedule WHERE schedule_id = $schedule_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $schedule = $result->fetch_assoc();
    } else {
        echo "Schedule not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// เมื่อผู้ใช้กดปุ่มบันทึกการแก้ไข
if (isset($_POST['submit'])) {
    $course_id = $conn->real_escape_string($_POST['course_name']);
    $instructor_id = $conn->real_escape_string($_POST['instructor_name']);
    $room = $conn->real_escape_string($_POST['room']);
    $day_of_week = $conn->real_escape_string($_POST['day_of_week']);
    $start_time = $conn->real_escape_string($_POST['start_time']);
    $end_time = $conn->real_escape_string($_POST['end_time']);

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql_update = "UPDATE class_schedule 
                   SET course_id = '$course_id', 
                       instructor_id = '$instructor_id', 
                       room = '$room', 
                       day_of_week = '$day_of_week', 
                       start_time = '$start_time', 
                       end_time = '$end_time' 
                   WHERE schedule_id = $schedule_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>
                alert('Schedule updated successfully!');
                window.location.href = 'class_schedule.php';
              </script>";
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
           /* Reset */
           * { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Arial, sans-serif;
    background-image: url(../images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #fff;
    margin-bottom: 30px;
}

form {
    max-width: 600px;
    background: #cf1d1d6b;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: auto;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #ffffff;
}

input, select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #fafafa;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    background: #ffc107;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background: #ffc107;
}
    </style>
</head>
<body>

    <div class="container">
    <form method="POST">
        <h1>Edit Schedule</h1>

        <!-- ฟอร์มสำหรับเลือกชื่อวิชา -->
        <label for="course_name">Course Name</label>
        <select id="course_name" name="course_name" required>
            <option value="">Select Course</option>
            <?php
            $sql_courses = "SELECT course_id, course_name FROM courses";
            $result_courses = $conn->query($sql_courses);
            if ($result_courses->num_rows > 0) {
                while ($row = $result_courses->fetch_assoc()) {
                    $selected = ($row['course_id'] == $schedule['course_id']) ? "selected" : "";
                    echo "<option value='" . $row['course_id'] . "' $selected>" . htmlspecialchars($row['course_name']) . "</option>";
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
                    $selected = ($row['instructor_id'] == $schedule['instructor_id']) ? "selected" : "";
                    echo "<option value='" . $row['instructor_id'] . "' $selected>" . htmlspecialchars($row['instructor_name']) . "</option>";
                }
            }
            ?>
        </select>

        <!-- ฟิลด์ห้องเรียน -->
        <label for="room">Room</label>
        <input type="text" id="room" name="room" value="<?= htmlspecialchars($schedule['room']); ?>" required>

        <!-- ฟอร์มเลือกวัน -->
        <label for="day_of_week">Day of the Week</label>
        <select id="day_of_week" name="day_of_week" required>
            <?php
            $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
            foreach ($days as $day) {
                $selected = ($day == $schedule['day_of_week']) ? "selected" : "";
                echo "<option value='$day' $selected>$day</option>";
            }
            ?>
        </select>

        <!-- เวลาเริ่มต้นและสิ้นสุด -->
        <label for="start_time">Start Time</label>
        <input type="time" id="start_time" name="start_time" value="<?= htmlspecialchars($schedule['start_time']); ?>" required>
        
        <label for="end_time">End Time</label>
        <input type="time" id="end_time" name="end_time" value="<?= htmlspecialchars($schedule['end_time']); ?>" required>

        <button type="submit" name="submit">Update Schedule</button>
    </form>
</body>
</html>