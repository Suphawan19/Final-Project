<?php
include '../config.php';

// Query ดึงข้อมูลจากตาราง students
$sql = "SELECT * FROM students ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>
<style>
 /* CSS สำหรับหน้า student list */
body {
    font-family: 'Arial', sans-serif;
    background-image: url(../images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
    margin: 0;
    padding: 0;
}

.student-container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    font-size: 2.2em;
    font-weight: bold;
    margin-bottom: 30px;
    color: #333;
}

.btn {
    margin-bottom: 20px;
    font-size: 1em;
    background-color: #8d2a2a;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #dc3545;
    color: #fff;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px 15px;
    text-align: center; /* ทำให้ข้อความอยู่กลาง */
    border: 1px solid #ddd;
    vertical-align: middle; /* จัดข้อความให้อยู่กลางแนวตั้ง */
}

table th {
    background-color: #8d2a2a;
    color: #ffffff;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.9em;
}


table tr:hover {
    background-color: #fff;
}

.btn-warning{
    background-color:rgba(231, 189, 4, 0.72);
    color: #ffffff;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.85em;
    transition: background-color 0.3s;
}

.btn-warning:hover {
    background-color:rgb(246, 216, 19);
}

.btn-danger {
    background-color: #dc3545;
    color: #ffffff;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.85em;
    transition: background-color 0.3s;
}

.btn-danger:hover {
    background-color: #8d2a2a;
}

a.btn {
    margin-right: 10px; /* ระยะห่างระหว่างปุ่ม */
}

/* เพิ่มการจัดเรียงข้อความให้สวยงาม */
table th, table td {
    text-align: center; /* จัดข้อความให้อยู่กลาง */
    vertical-align: middle; /* จัดข้อความให้อยู่กลางแนวตั้ง */
}

</style>
<div class="student-container">
    <h1 class="header">Student Information</h1>

    <a href="add_student.php"><button class="btn">Add New Student</button></a>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Faculty</th>
                    <th>Major</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['faculty']); ?></td>
                        <td><?php echo htmlspecialchars($row['major']); ?></td>
                        <td>
                            <!-- ปุ่มแก้ไข -->
                            <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-warning ">Edit</a>
                            <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" 
                            class="btn btn-danger " 
                            onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No students found</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>