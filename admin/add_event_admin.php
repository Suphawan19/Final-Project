<?php
// เชื่อมต่อฐานข้อมูล
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // เก็บข้อมูลจากแบบฟอร์ม
    $title = $_POST['title'];
    $set_event_date = $_POST['set_event_date'];
    $end_date = $_POST['end_date'];
    $event_type = $_POST['event_type'];
    $description = $_POST['description'];
    $role = $_POST['role'];
    $position = $_POST['position'] ?? null;
    $event_time = $_POST['event_time'];
    $end_time = $_POST['end_time'];
    $month = $_POST['month'];
    $status = $_POST['status'];
    $faculty = $_POST['faculty'] ?? null;
    $major = $_POST['major'] ?? null;
    $student_id = $_POST['student_id'] ?? null;

    // ตั้งค่า student_id เป็น NULL ถ้า role ไม่ใช่ student
    if ($role != 'student') {
        $student_id = null;
    }

    // ตรวจสอบว่าถ้าเป็น student ต้องมี student_id ที่ถูกต้อง
    if ($role == 'student' && !empty($student_id)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            echo "<script>alert('Error: Student ID does not exist in the database.'); window.history.back();</script>";
            exit;
        }
    }

    // เพิ่มข้อมูลลงในตาราง events
    $stmt = $conn->prepare("INSERT INTO events (title, set_event_date, end_date, event_type, description, role, position, event_time, end_time, month, status, faculty, major, student_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $title, $set_event_date, $end_date, $event_type, $description, $role, $position, $event_time, $end_time, $month, $status, $faculty, $major, $student_id);

    if ($stmt->execute()) {
        $event_id = $conn->insert_id; // เก็บ ID ของเหตุการณ์ที่เพิ่มใหม่

        // ดึงอีเมลตาม Role
        $emails = [];
        if ($role == 'all') {
            $query = "SELECT email FROM students UNION SELECT email FROM staff";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                $emails[] = $row['email'];
            }
        } elseif ($role == 'student') {
            $query = "SELECT email FROM students WHERE faculty = ? AND major = ? AND student_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $faculty, $major, $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $emails[] = $row['email'];
            }
        } elseif ($role == 'staff') {
            $query = "SELECT email FROM staff WHERE position = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $position);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $emails[] = $row['email'];
            }
        }

        // ส่งอีเมลแจ้งเตือน
        if (!empty($emails)) {
            $subject = "New Event Notification: $title";
            $message = "Event Details:\n\nTitle: $title\nDescription: $description\nDate: $set_event_date to $end_date";

            foreach ($emails as $email) {
                if (!empty($email)) {
                    mail($email, $subject, $message, "From: admin@example.com");
                }
            }
        }

        // บันทึกการแจ้งเตือนในตาราง notifications
        $notification_message = "Event '$title' has been scheduled from $set_event_date to $end_date.";
        $stmt = $conn->prepare("INSERT INTO notifications (event_id, role, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $event_id, $role, $notification_message);
        $stmt->execute();

        echo "<script>alert('Event added successfully and notifications sent.'); window.location.href='event_display.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        button:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Add New Event</h1>
        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="set_event_date">Set Event Date:</label>
            <input type="text" id="set_event_date" name="set_event_date" required>

            <label for="end_date">End Date:</label>
            <input type="text" id="end_date" name="end_date">

            <label for="event_type">Event Type:</label>
            <select id="event_type" name="event_type" required>
                <option value="">Select Event Type</option>
                <option value="Activity">Activity</option>
                <option value="Seminar">Seminar</option>
                <option value="Exam">Exam</option>
                <option value="Career Guidance Training">Career Guidance Training</option>
                <option value="Other">Other</option>
            </select>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="role">Role:</label>
            <select id="role" name="role" required onchange="toggleFields()">
                <option value="">Select Role</option>
                <option value="student">Student</option>
                <option value="staff">Staff</option>
                <option value="all">All</option>
            </select>

            <div id="student-fields">
                <label for="faculty">Faculty:</label>
                <select id="faculty" name="faculty" required onchange="updateMajorOptions()">
                    <option value="Faculty of Technology-Business" <?php echo (isset($faculty) && $faculty == 'Faculty of Technology-Business') ? 'selected' : ''; ?>>Faculty of Technology-Business</option>
                    <option value="Faculty of Foreign Languages-Tourism" <?php echo (isset($faculty) && $faculty == 'Faculty of Foreign Languages-Tourism') ? 'selected' : ''; ?>>Faculty of Foreign Languages-Tourism</option>
                </select>

                <label for="major">Major:</label>
                <select id="major" name="major" required>
                    <!-- Major options will be populated dynamically based on the selected faculty -->
                </select>

                <script>
                    function updateMajorOptions() {
                        var faculty = document.getElementById('faculty').value;
                        var majorSelect = document.getElementById('major');

                        // Clear current options
                        majorSelect.innerHTML = '';

                        if (faculty == 'Faculty of Technology-Business') {
                            // Add relevant majors for Faculty of Technology-Business
                            var options = [
                                'Information Technology',
                                'Automotive Engineering Technology',
                                'Business Administration-Marketing'
                            ];
                        } else if (faculty == 'Faculty of Foreign Languages-Tourism') {
                            // Add relevant majors for Faculty of Foreign Languages-Tourism
                            var options = [
                                'Travel and Tourism Service Management',
                                'English language',
                                'Chinese language'
                            ];
                        } else {
                            var options = [];
                        }

                        // Add options to the major select
                        options.forEach(function(major) {
                            var option = document.createElement('option');
                            option.value = major;
                            option.textContent = major;
                            majorSelect.appendChild(option);
                        });

                        // Set the selected major if it's already in the student data
                        <?php if (isset($student['major'])): ?>
                            var selectedMajor = "<?php echo $student['major']; ?>";
                            if (options.includes(selectedMajor)) {
                                majorSelect.value = selectedMajor;
                            }
                        <?php endif; ?>
                    }

                    // Call the function to populate the majors based on the initial faculty value
                    updateMajorOptions();
                </script>

                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id">
            </div>

            <div id="staff-fields">
                <label for="position">Position:</label>
                <select id="position" name="position">
                    <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
                    <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
                </select>
            </div>


            <label for="event_time">Event Start Time:</label>
            <input type="time" id="event_time" name="event_time" required>

            <label for="end_time">Event End Time:</label>
            <input type="time" id="end_time" name="end_time">

            <label for="month">Month:</label>
            <select id="month" name="month" required>
                <option value="">Select Month</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>

            <button type="submit">Add Event</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function toggleFields() {
            var role = document.getElementById('role').value;
            document.getElementById('student-fields').style.display = role === 'student' ? 'block' : 'none';
            document.getElementById('staff-fields').style.display = role === 'staff' ? 'block' : 'none';
        }
        toggleFields(); // Call on page load

        flatpickr("#set_event_date", {
            dateFormat: "Y-m-d"
        });
        flatpickr("#end_date", {
            dateFormat: "Y-m-d"
        });
    </script>
</body>

</html>