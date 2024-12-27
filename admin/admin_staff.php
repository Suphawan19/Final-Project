<?php
include '../config.php';

// Query to fetch data from staff table, ordered by id in ascending order
$sql = "SELECT * FROM staff ORDER BY id ASC";
$result = $conn->query($sql);

// Check for search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the query if a search term is entered
if ($search) {
    $sql = "SELECT * FROM staff WHERE frist_name LIKE ? OR last_name LIKE ? ORDER BY id ASC";
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
    <title>Staff Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../style.css/style_admin_staff.css" rel="stylesheet">
</head>
<body>

<div class="staff-container">
    <h1 class="header">Staff Management</h1>

    <a href="add_staff.php"><button class="btn btn-success">Add New Staff</button></a>

    <!-- Search Form with Icon -->
    <form action="" method="get" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-outline-primary">
            <i class="bi bi-search"></i>
        </button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['frist_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['position']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <!-- Edit and Delete buttons -->
                            <a href="edit_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this staff?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No staff found</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
