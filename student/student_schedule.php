<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

$sql = "SELECT cs.schedule_id, cs.room, cs.day_of_week, cs.start_time, cs.end_time, 
                c.course_name, i.instructor_name, cs.faculty, cs.major
        FROM class_schedule cs
        LEFT JOIN courses c ON cs.course_id = c.course_id
        LEFT JOIN instructors i ON cs.instructor_id = i.instructor_id
        ORDER BY cs.day_of_week, cs.start_time";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Custom CSS for the class schedule page */
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

        th, td {
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

        /* Ensure consistent alignment for table headers and cells */
        table th, table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <header>
        <h1>Class Schedule</h1>
    </header>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Instructor Name</th>
                    <th>Room</th>
                    <th>Day of the Week</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Faculty</th>
                    <th>Major</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['course_name']) . "</td>
                                <td>" . htmlspecialchars($row['instructor_name']) . "</td>
                                <td>" . htmlspecialchars($row['room']) . "</td>
                                <td>" . htmlspecialchars($row['day_of_week']) . "</td>
                                <td>" . htmlspecialchars($row['start_time']) . "</td>
                                <td>" . htmlspecialchars($row['end_time']) . "</td>
                                <td>" . htmlspecialchars($row['faculty']) . "</td>
                                <td>" . htmlspecialchars($row['major']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='no-exams'>No schedules available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
