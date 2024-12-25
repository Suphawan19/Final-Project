<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ค้นหาข้อมูล
$search_query = "";
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search_term = $_POST['search'];
    $search_query = " WHERE instructor_name LIKE '%$search_term%'";
}

// อ่านข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM instructors" . $search_query;
$result = $conn->query($sql);

// ฟังก์ชันการเพิ่มข้อมูล
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // การเพิ่มข้อมูล
    if (isset($_POST['add'])) {
        $instructor_name = $_POST['instructor_name'];
        if (!empty($instructor_name)) {
            $sql = "INSERT INTO instructors (instructor_name) VALUES ('$instructor_name')";
            $conn->query($sql);

            // รีเฟรชหน้าเพื่อแสดงข้อมูลใหม่
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // การแก้ไขข้อมูล
    elseif (isset($_POST['edit'])) {
        $instructor_id = $_POST['instructor_id'];
        $instructor_name = $_POST['instructor_name'];
        if (!empty($instructor_name)) {
            $sql = "UPDATE instructors SET instructor_name = '$instructor_name' WHERE instructor_id = $instructor_id";
            $conn->query($sql);
        }
    }

    // การลบข้อมูล
    elseif (isset($_POST['delete'])) {
        $instructor_id = $_POST['instructor_id'];
        $sql = "DELETE FROM instructors WHERE instructor_id = $instructor_id";
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #2d3e50, #1e272e);
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group label {
            color: #333;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn {
            border-radius: 5px;
            font-size: 14px;
        }

        .input-group {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this instructor?');
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Instructor Management</h2>

    <form method="POST" class="mb-3 d-flex">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by instructor name" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <form method="POST" class="mb-3">
        <div class="form-group">
            <label for="instructor_name">Instructor Name</label>
            <input type="text" name="instructor_name" class="form-control" id="instructor_name" required>
        </div>
        <button type="submit" name="add" class="btn btn-success mt-3">Add Instructor</button>
    </form>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Instructor ID</th>
                <th>Instructor Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['instructor_id']; ?></td>
                    <td><?php echo $row['instructor_name']; ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="instructor_id" value="<?php echo $row['instructor_id']; ?>">
                            <input type="text" name="instructor_name" value="<?php echo $row['instructor_name']; ?>" class="form-control mb-2">
                            <button type="submit" name="edit" class="btn btn-warning">Edit</button>
                        </form>
                        <form method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            <input type="hidden" name="instructor_id" value="<?php echo $row['instructor_id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
