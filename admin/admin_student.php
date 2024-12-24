<?php
include '../config.php';

// Query to fetch data from students table, ordered by student_id in ascending order
$sql = "SELECT * FROM students ORDER BY student_id ASC";
$result = $conn->query($sql);

// Check for search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the query if a search term is entered
if ($search) {
    $sql = "SELECT * FROM students WHERE first_name LIKE ? OR last_name LIKE ? ORDER BY student_id ASC";  // Order by student_id
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../style.css/style_admin_student.css" rel="stylesheet">
</head>
<body>

<div class="student-container">
    <h1 class="header">Student Information</h1>

    <a href="add_student.php"><button class="btn">Add New Student</button></a>

    <!-- Search Form with Icon -->
    <form action="" method="get" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-outline-primary">
            <i class="bi bi-search"></i> <!-- Bootstrap Icon for Search -->
        </button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
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
                            <!-- Edit and Delete buttons -->
                            <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
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
