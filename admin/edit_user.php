<?php
// เปิดการแสดงข้อผิดพลาด
error_reporting(E_ALL);
ini_set('display_errors', 1);

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db"; // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$user_id = $_GET['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $new_password = $_POST['password'] ?? null; // Password field for admin update
    $age = $_POST['age'] ?? null; // Age field (can be null for admin and staff)
    
    // If password is provided, hash it
    if ($new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    // If updating admin password, modify the SQL query
    if ($role === 'admin' && $new_password) {
        // Update query with password change
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $role, $hashed_password, $user_id);
    } else {
        // Update query with or without password change (for student, staff, etc.)
        if ($role === 'student' && $age) {
            $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, age = ?, role = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $first_name, $last_name, $email, $age, $role, $user_id);
        } else {
            $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $role, $user_id);
        }
    }

    if ($stmt->execute()) {
        $message = "User updated successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// ดึงข้อมูลผู้ใช้ที่ต้องการแก้ไข
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-body {
            padding: 2rem;
        }

        h2 {
            font-weight: 700;
            color: #343a40;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control, .form-select {
            border: 2px solid #ced4da;
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #e52e71;
            box-shadow: 0 0 5px rgba(191, 21, 21, 0.5);
        }

        .btn {
            border-radius: 20px;
            font-size: 1.2rem;
            padding: 0.6rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary { background: linear-gradient(90deg, #090908, #e52e71);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #e52e71, #221e1b);
        }

        .alert {
            font-size: 1rem;
            font-weight: 500;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="card mx-auto shadow-lg" style="max-width: 600px;">
            <div class="card-body">
                <h2 class="text-center">Edit User</h2>
                <?php if ($message): ?>
                    <div class="alert alert-info text-center"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $user['first_name'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $user['last_name'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>" required>
                    </div>
                    <?php if ($user['role'] === 'student'): ?>
                        <div class="mb-3">
                            <label for="age" class="form-label">Age:</label>
                            <input type="number" name="age" id="age" class="form-control" value="<?= $user['age'] ?>" required>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <!-- Password Field for Admin -->
                    <?php if ($user['role'] === 'admin'): ?>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password :</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary w-100">Update User</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
