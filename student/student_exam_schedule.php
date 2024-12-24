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
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Get the search term

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
            Instructors i ON e.instructor_id = i.instructor_id";

if ($search) {
    $sql .= " WHERE e.exam_id LIKE ? OR c.course_name LIKE ? OR e.room LIKE ? OR i.instructor_name LIKE ?";
}

$sql .= " ORDER BY e.exam_date, e.start_time";

// Prepare the statement
$stmt = $conn->prepare($sql);

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
    <title>Exam Schedule</title>
    <!-- Font Awesome for search icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        /* Search box styling */
        .search-form {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .search-form input {
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 200px;
        }

        .search-form button {
            padding: 10px;
            margin-left: 10px;
            background-color: rgb(54, 121, 255);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: rgb(4, 189, 225);
        }

        .search-form .fa-search {
            font-size: 1.5em;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <header>
        <h1>Exam Schedule</h1>
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