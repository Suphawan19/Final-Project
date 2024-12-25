<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ค้นหาข้อมูล
$search_query = "";
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search_term = $_POST['search'];
    $search_query = " WHERE course_name LIKE '%$search_term%'";
}

// อ่านข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM courses" . $search_query;
$result = $conn->query($sql);

// ฟังก์ชันการเพิ่มข้อมูล
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // การเพิ่มข้อมูล
    if (isset($_POST['add'])) {
        $course_name = $_POST['course_name'];
        if (!empty($course_name)) {
            $sql = "INSERT INTO courses (course_name) VALUES ('$course_name')";
            $conn->query($sql);

            // รีเฟรชหน้าเพื่อแสดงข้อมูลใหม่
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // การแก้ไขข้อมูล
    elseif (isset($_POST['edit'])) {
        $course_id = $_POST['course_id'];
        $course_name = $_POST['course_name'];
        if (!empty($course_name)) {
            $sql = "UPDATE courses SET course_name = '$course_name' WHERE course_id = $course_id";
            $conn->query($sql);
        }
    }

    // การลบข้อมูล
    elseif (isset($_POST['delete'])) {
        $course_id = $_POST['course_id'];
        $sql = "DELETE FROM courses WHERE course_id = $course_id";
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg,rgb(65, 63, 67),rgba(105, 22, 22, 0.83));
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group label {
            color: #333;
        }

        .table {
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn {
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #2575fc;
            border-color: #2575fc;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .input-group {
            max-width: 500px;
            margin: 0 auto;
            
        }
    </style>
    <script>
        // ฟังก์ชันยืนยันการลบ
        function confirmDelete() {
            return confirm('Are you sure you want to delete this course?');
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Course Management</h2>

    <!-- ฟอร์มค้นหา -->
    <form method="POST" class="mb-3 d-flex">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by course name" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- ฟอร์มสำหรับการเพิ่มข้อมูล -->
    <form method="POST" class="mb-3">
        <div class="form-group">
            <label for="course_name">Course Name</label>
            <input type="text" name="course_name" class="form-control" id="course_name" required>
        </div>
        <button type="submit" name="add" class="btn btn-success mt-3">Add Course</button>
    </form>

    <!-- ตารางแสดงข้อมูล -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['course_id']; ?></td>
                    <td><?php echo $row['course_name']; ?></td>
                    <td>
                        <!-- ฟอร์มแก้ไข -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                            <input type="text" name="course_name" value="<?php echo $row['course_name']; ?>" class="form-control mb-2">
                            <button type="submit" name="edit" class="btn btn-warning">Edit</button>
                        </form>
                        <!-- ฟอร์มลบ -->
                        <form method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
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
