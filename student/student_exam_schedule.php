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
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #eec51d;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 2em;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #8d2a2a;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .no-exams {
            text-align: center;
            font-size: 1.2em;
            color: #888;
        }
    </style>
</head>

<body>
    <header>
        <h1>Exam Schedule</h1>
    </header>

    <div class="container">
        <!-- The button to add exams should not be shown to students, so remove it for student view -->
        <!-- <a href="add_exam.php" class="btn">Add Exam</a> -->

        <table>
            <thead>
                <tr>
                    <th>Exam ID</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Course</th>
                    <th>Room</th>
                    <th>Instructor</th>
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
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='no-exams'>No exams scheduled</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
$conn->close();
?>