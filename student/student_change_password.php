<?php
session_start();
include '../config.php';

// ตรวจสอบสิทธิ์ว่าเป็นนักศึกษา
if ($_SESSION['role'] !== 'student') {
    die("Access Denied");
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // ดึงข้อมูลรหัสผ่านเก่าจากฐานข้อมูล
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // ตรวจสอบรหัสผ่านเก่า
    if ($user && password_verify($oldPassword, $user['password'])) {
        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // อัปเดตรหัสผ่านใหม่
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $userId);
            if ($updateStmt->execute()) {
                $message = '<div class="alert alert-success">เปลี่ยนรหัสผ่านสำเร็จ</div>';
            } else {
                $message = '<div class="alert alert-danger">เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">รหัสผ่านใหม่ไม่ตรงกัน</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">รหัสผ่านเก่าไม่ถูกต้อง</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url(../images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
            font-family: 'Arial', sans-serif;
        }

        .card {
            border-radius: 20px;
            overflow: hidden;
            border: none;
        }

        .card-body {
            background: #ffffff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #f39c12;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ced4da;
            box-shadow: none;
            padding: 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 5px rgba(243, 156, 18, 0.5);
        }

        .toggle-password {
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: #f39c12;
        }

        .btn {
            background-color: #f39c12;
            color: white;
            font-size: 1.1rem;
            padding: 12px 20px;
            border-radius: 10px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #e67e22;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .alert {
            margin-bottom: 15px;
            font-size: 1rem;
            padding: 10px;
            border-radius: 8px;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
        }

        .alert-warning {
            background-color: #ffc107;
            color: black;
        }

        .container {
            max-width: 600px;
            margin-top: 80px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card mx-auto shadow-lg">
            <div class="card-body">
                <h2 class="text-center text-warning">Change Password</h2>
                <hr>
                <!-- Display Message -->
                <?= $message; ?>
                <form method="POST" action="student_change_password.php">
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Old Password</label>
                        <input type="password" name="old_password" id="old_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control pr-5" required>
                        <i class="fas fa-eye position-absolute toggle-password" id="eye-icon" style="top: 70%; right: 10px; transform: translateY(-50%);"></i>
                    </div>

                    <script>
                        const togglePassword = document.querySelector('.toggle-password');
                        const passwordField = document.getElementById('confirm_password');
                        const eyeIcon = document.getElementById('eye-icon');

                        togglePassword.addEventListener('click', function() {
                            // เปลี่ยนประเภทของฟิลด์รหัสผ่าน
                            if (passwordField.type === 'password') {
                                passwordField.type = 'text';
                                eyeIcon.classList.remove('fa-eye');
                                eyeIcon.classList.add('fa-eye-slash'); // เปลี่ยนไอคอนเป็นลูกกะตาปิด
                            } else {
                                passwordField.type = 'password';
                                eyeIcon.classList.remove('fa-eye-slash');
                                eyeIcon.classList.add('fa-eye'); // เปลี่ยนไอคอนกลับเป็นลูกกะตาเปิด
                            }
                        });
                    </script>
                    <button type="submit" class="btn w-100">Change Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>