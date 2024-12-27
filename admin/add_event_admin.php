<?php
include '../config.php';  // ตรวจสอบให้แน่ใจว่าไฟล์นี้เชื่อมต่อกับฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $title = $_POST['title'];
    $month = $_POST['month'];
    $set_event_date = $_POST['set_event_date'];
    $end_date = $_POST['end_date'];
    $event_time = $_POST['event_time'];
    $end_time = $_POST['end_time'];
    $role = $_POST['role'];
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];
    $event_type = $_POST['event_type'];
    $description = $_POST['description'];

    // การเตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql = "INSERT INTO events (title, month, set_event_date, end_date, event_time, end_time, role, faculty, major, event_type,  description)
            VALUES ('$title', '$month', '$set_event_date', '$end_date', '$event_time', '$end_time', '$role', '$faculty', '$major', '$event_type', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "New event added successfully";
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
        input[type="text"], input[type="date"], input[type="time"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
        }
        select:focus, input:focus {
            border-color: #5b9bd5;
            outline: none;
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
        }
        button:hover {
            background-color: #45a049;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .mb-3 {
            margin-bottom: 15px;
        }
        #studentFields, #staffField {
            display: none;
        }
        .hidden {
            display: none;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        .form-control:focus {
            border-color: #5b9bd5;
        }
    </style>
    <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            document.getElementById('studentFields').style.display = (role === 'student') ? 'block' : 'none';
            document.getElementById('staffField').style.display = (role === 'staff') ? 'block' : 'none';
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Add New Event</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="set_event_date">Set Event Date:</label>
                <input type="text" id="set_event_date" name="set_event_date" required>
            </div>

            <!-- End Date with Flatpickr -->
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="text" id="end_date" name="end_date">
            </div>

            <div class="form-group">
                <label for="event_type">Event Type:</label>
                <select id="event_type" name="event_type" required>
                    <option value="">Select Event Type</option>
                    <option value="Activity">Activity</option>
                    <option value="Seminar">Seminar</option>
                    <option value="exam">exam</option>
                    <option value="Career guidance training">Career guidance training</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <textarea name="description" placeholder="Event Description" required></textarea>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" onchange="toggleFields()" required>
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                    <option value="all">All</option>
                </select>
            </div>

            <!-- Student Fields -->
            <div id="studentFields">
                <div class="form-group">
                    <label for="faculty">Faculty:</label>
                    <select class="form-control" id="faculty" name="faculty">
                        <option value="">Select Faculty</option>
                        <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
                        <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="major">Major:</label>
                    <select class="form-control" id="major" name="major">
                        <option value="">Select Major</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Automotive Engineering Technology">Automotive Engineering Technology</option>
                        <option value="Business Administration-Marketing">Business Administration-Marketing</option>
                        <option value="Travel and Tourism Service Management">Travel and Tourism Service Management</option>
                        <option value="English Language">English Language</option>
                        <option value="Chinese Language">Chinese Language</option>
                    </select>
                </div>
            </div>

            <!-- Staff Fields -->
            <div id="staffField">
                <div class="form-group">
                    <label for="position">Position:</label>
                    <select class="form-control" id="position" name="position">
                        <option value="">Select Position</option>
                        <option value="Faculty of Technology and Business">Faculty of Technology and Business</option>
                        <option value="Faculty of Foreign Languages ​​and Tourism">Faculty of Foreign Languages ​​and Tourism</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="event_time">Event Start Time:</label>
                <input type="time" id="event_time" name="event_time" required>
            </div>

            <div class="form-group">
                <label for="end_time">Event End Time:</label>
                <input type="time" id="end_time" name="end_time">
            </div>

            <div class="form-group">
                <label for="month">Month:</label>
                <select class="form-control" id="month" name="month" required>
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
            </div>



            <button type="submit">Add Event</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#set_event_date", {
                dateFormat: "Y-m-d",  // Format for backend processing (YYYY-MM-DD)
                locale: "en",         // English locale
                allowInput: true,     // Allows typing the date
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('set_event_date').value = dateStr; // Set the value as 'YYYY-MM-DD'
                }
            });

            flatpickr("#end_date", {
                dateFormat: "Y-m-d",  // Format for backend processing (YYYY-MM-DD)
                locale: "en",         // English locale
                allowInput: true,     // Allows typing the date
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('end_date').value = dateStr; // Set the value as 'YYYY-MM-DD'
                }
            });
        });
    </script>

</body>
</html>
