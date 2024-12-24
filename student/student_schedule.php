<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// Default SQL query
$sql = "SELECT cs.schedule_id, cs.room, cs.day_of_week, cs.start_time, cs.end_time, 
                c.course_name, i.instructor_name, cs.faculty, cs.major
        FROM class_schedule cs
        LEFT JOIN courses c ON cs.course_id = c.course_id
        LEFT JOIN instructors i ON cs.instructor_id = i.instructor_id";

// Check if there is a search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query if a search term is entered
if ($search) {
    $sql .= " WHERE c.course_name LIKE ? OR i.instructor_name LIKE ? OR cs.faculty LIKE ? OR cs.major LIKE ?";
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters if the search term is present
if ($search) {
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
        <!-- Font Awesome for search icon -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css/stlye_student_schedule.css">
    
</head>
<body>

    <header>
        <h1>Class Schedule</h1>
    </header>

    <div class="container">
        <!-- Search Form -->
        <form action="" method="get" class="search-form">
            <input type="text" name="search" placeholder="Search exams..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>
        </form>

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
