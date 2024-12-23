<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าได้ส่ง id ผ่าน URL หรือไม่
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // ดึงข้อมูลจากฐานข้อมูลที่มี id ตรงกับที่ส่งมา
    $sql = "SELECT cs.schedule_id, cs.room, cs.day_of_week, cs.start_time, cs.end_time, 
                   c.course_name, i.instructor_name, cs.faculty, cs.major
            FROM class_schedule cs
            LEFT JOIN courses c ON cs.course_id = c.course_id
            LEFT JOIN instructors i ON cs.instructor_id = i.instructor_id
            WHERE cs.schedule_id = '$schedule_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Schedule not found.";
        exit();
    }
} else {
    echo "Invalid ID.";
    exit();
}

// ตรวจสอบการส่งฟอร์ม
if (isset($_POST['submit'])) {
    $course_name = $_POST['course_name'];
    $instructor_name = $_POST['instructor_name'];
    $room = $_POST['room'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql_update = "UPDATE class_schedule SET 
                   course_name='$course_name',
                   instructor_name='$instructor_name',
                   room='$room',
                   day_of_week='$day_of_week',
                   start_time='$start_time',
                   end_time='$end_time',
                   faculty='$faculty',
                   major='$major'
                   WHERE schedule_id = '$schedule_id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p>Schedule updated successfully! <a href='class_schedule.php'>Back to Schedule</a></p>";
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
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo htmlspecialchars($row['course_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="instructor_name" class="form-label">Instructor Name</label>
                <input type="text" class="form-control" id="instructor_name" name="instructor_name" value="<?php echo htmlspecialchars($row['instructor_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="room" class="form-label">Room</label>
                <input type="text" class="form-control" id="room" name="room" value="<?php echo htmlspecialchars($row['room']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="day_of_week" class="form-label">Day of the Week</label>
                <select class="form-control" id="day_of_week" name="day_of_week" required>
                    <option value="Monday" <?php echo ($row['day_of_week'] == 'Monday' ? 'selected' : ''); ?>>Monday</option>
                    <option value="Tuesday" <?php echo ($row['day_of_week'] == 'Tuesday' ? 'selected' : ''); ?>>Tuesday</option>
                    <option value="Wednesday" <?php echo ($row['day_of_week'] == 'Wednesday' ? 'selected' : ''); ?>>Wednesday</option>
                    <option value="Thursday" <?php echo ($row['day_of_week'] == 'Thursday' ? 'selected' : ''); ?>>Thursday</option>
                    <option value="Friday" <?php echo ($row['day_of_week'] == 'Friday' ? 'selected' : ''); ?>>Friday</option>
                    <option value="Saturday" <?php echo ($row['day_of_week'] == 'Saturday' ? 'selected' : ''); ?>>Saturday</option>
                    <option value="Sunday" <?php echo ($row['day_of_week'] == 'Sunday' ? 'selected' : ''); ?>>Sunday</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo htmlspecialchars($row['start_time']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo htmlspecialchars($row['end_time']); ?>" required>
            </div>

            <label>Faculty:</label><br>
        <select name="faculty" required>
            <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
            <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
        </select><br><br>

        <label>Major:</label><br>
        <select name="major" required>
            <option value="Information Technology">Information Technology</option>
            <option value="Automotive Engineering Technology">Automotive Engineering Technology</option>
            <option value="Business Administration-Marketing">Business Administration-Marketing</option>

            <option value="Travel and Tourism Service Management">Travel and Tourism Service Management</option>
            <option value="English language">English language</option>
            <option value="Chinese language">Chinese language</option>

        </select>

            <button type="submit" name="submit" class="btn btn-primary">Update Schedule</button>
        </form>
    </div>

</body>
</html>