<?php include '../config.php'; ?>
<?php
    if (isset($_POST['submit'])) {
        $course_id = $_POST['course_name'];
        $instructor_id = $_POST['instructor_name'];
        $room = $_POST['room'];
        $day_of_week = $_POST['day_of_week'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $faculty = $_POST['faculty'];
        $major = $_POST['major'];

        // เพิ่มข้อมูลลงฐานข้อมูล
        $sql = "INSERT INTO class_schedule (course_id, instructor_id, room, day_of_week, start_time, end_time, faculty, major) 
                VALUES ('$course_id', '$instructor_id', '$room', '$day_of_week', '$start_time', '$end_time', '$faculty', '$major')";

        if ($conn->query($sql) === TRUE) {
            echo "Schedule added successfully!";
            header("Location: class_schedule.php");
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
                    echo "<option value='" . $row['course_id'] . "'>" . $row['course_name'] . "</option>";
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
                    echo "<option value='" . $row['instructor_id'] . "'>" . $row['instructor_name'] . "</option>";
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
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>

        <!-- เลือกคณะ -->
        <label for="faculty">Faculty</label>
        <select id="faculty" name="faculty" required>
            <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
            <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
        </select>

        <!-- เลือกสาขา -->
        <label>Major:</label><br>
        <select name="major" required>
            <option value="Information Technology">Information Technology</option>
            <option value="Automotive Engineering Technology">Automotive Engineering Technology</option>
            <option value="Business Administration-Marketing">Business Administration-Marketing</option>
            ------------------------------------------------------------------------------------------------
            <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
            <option value="Travel and Tourism Service Management">Travel and Tourism Service Management</option>
            <option value="English language">English language</option>
            <option value="Chinese language">Chinese language</option>

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