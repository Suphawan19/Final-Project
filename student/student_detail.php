<?php
include '../config.php';

// Get student ID from URL
$student_id = isset($_GET['student_id']) ? urldecode($_GET['student_id']) : null;

if ($student_id) {
    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 50px auto;
            max-width: 900px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #951c1c;
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .header h2 {
            margin: 0;
        }

        .profile {
            text-align: center;
            margin-top: -60px;
            margin-bottom: 20px;
        }

        .profile img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            background-color: #f1f1f1;
        }

        .details {
            margin-top: 20px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #ffbb00;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            background-color: #010304;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #951c1c;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .profile img {
                width: 120px;
                height: 120px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($student): ?>
        <div class="header">
            <h2>Student Details</h2>
        </div>
        <div class="details">
            <table>
                <tr><th>Student ID</th><td><?php echo htmlspecialchars($student['student_id']); ?></td></tr>
                <tr><th>First Name</th><td><?php echo htmlspecialchars($student['first_name']); ?></td></tr>
                <tr><th>Last Name</th><td><?php echo htmlspecialchars($student['last_name']); ?></td></tr>
                <tr><th>Email</th><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
                <tr><th>Phone Number</th><td><?php echo htmlspecialchars($student['phone_number']); ?></td></tr>
                <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($student['date_of_birth']); ?></td></tr>
                <tr><th>Gender</th><td><?php echo htmlspecialchars($student['gender']); ?></td></tr>
                <tr><th>Faculty</th><td><?php echo htmlspecialchars($student['faculty']); ?></td></tr>
                <tr><th>Major</th><td><?php echo htmlspecialchars($student['major']); ?></td></tr>
            </table>
            
            <a href="index.php" class="btn-back">← Back to List</a>
        </div>
    <?php else: ?>
        <p>Student not found.</p>
        <a href="index.php" class="btn-back">← Back to List</a>
    <?php endif; ?>
</div>

</body>
</html>
