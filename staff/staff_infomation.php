<?php
// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "user_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูล staff
$sql = "SELECT id, frist_name, last_name, position FROM staff";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Information</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../style.css/style_staff_infomation.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Staff Information</h1>
        <div class="search-bar mb-4">
            <input type="text" id="search" class="form-control me-2" placeholder="Search by name or position">
            <button class="btn btn-search" onclick="searchStaff()">
                <i class="bi bi-search"></i>
            </button>
        </div>
        <table class="table">
  <thead class="table-danger">
  <tr>
                    <th>id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
  </thead>
  <tbody>
  <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['frist_name']); ?></td>
                            <td><?= htmlspecialchars($row['last_name']); ?></td>
                            <td><?= htmlspecialchars($row['position']); ?></td>
                            <td>
                                <a href="staff_detail.php?id=<?= $row['id']; ?>" class="btn btn-view">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No staff found</td>
                    </tr>
                <?php endif; ?>
  </tbody>
</table>
        </thead>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchStaff() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");
            rows.forEach(row => {
                let firstName = row.cells[0].innerText.toLowerCase();
                let lastName = row.cells[1].innerText.toLowerCase();
                let position = row.cells[2].innerText.toLowerCase();
                row.style.display = (firstName.includes(input) || lastName.includes(input) || position.includes(input)) ? "" : "none";
            });
        }
    </script>
</body>

</html>
<?php $conn->close(); ?>
