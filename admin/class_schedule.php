<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

$sql = "SELECT 
            cs.schedule_id, 
            c.course_name, 
            i.instructor_name, 
            cs.room, 
            cs.day_of_week, 
            cs.start_time, 
            cs.end_time
        FROM class_schedule cs
        LEFT JOIN courses c ON cs.course_id = c.course_id
        LEFT JOIN instructors i ON cs.instructor_id = i.instructor_id";

$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css/style_class_schedule.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <div class="table-container">
            <h1>Class Schedule</h1>
            <div class="btn-container mb-3">
                <a href="add_schedule.php" class="btn btn-success btn-lg">Add New Schedule</a>
            </div>
            <table class="table table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Course Name</th>
                        <th>Instructor Name</th>
                        <th>Room</th>
                        <th>Day of the Week</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
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
                                    <td class='action-buttons text-center'>
                                        <a href='edit_schedule.php?schedule_id=" . $row['schedule_id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='delete_schedule.php?schedule_id=" . $row['schedule_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this schedule?\")'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-muted'>No schedules available</td></tr>";
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
