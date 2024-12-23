<?php
include('config.php'); // เชื่อมต่อฐานข้อมูล

session_start();

// ฟังก์ชันเพื่อเข้ารหัสรหัสผ่านแบบ hash
function encode_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// ฟังก์ชันเพื่อเปรียบเทียบรหัสผ่าน
function verify_password($input_password, $stored_password, $use_hash = true) {
    if ($use_hash) {
        return password_verify($input_password, $stored_password);
    } else {
        return $input_password === $stored_password;
    }
}

// ตรวจสอบการเข้าสู่ระบบ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($user_id) && !empty($password)) {
        // ค้นหาผู้ใช้จากฐานข้อมูล
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // ตรวจสอบว่ารหัสผ่านเป็นแบบปกติหรือ hash
            if (strlen($user['password']) < 60) { // ถ้าความยาวของรหัสผ่านน้อยกว่า 60 ตัวอักษร หมายความว่าเป็นรหัสผ่านแบบปกติ
                // ตรวจสอบรหัสผ่านแบบปกติ
                if ($password === $user['password']) {
                    // เข้าสู่ระบบสำเร็จ
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['first_name'] = $user['first_name'];

                    // อัปเดตรหัสผ่านในฐานข้อมูลเป็นแบบ hash
                    $new_hashed_password = encode_password($password);
                    $update_sql = "UPDATE users SET password = ? WHERE user_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $new_hashed_password, $user_id);
                    $update_stmt->execute();

                    // Redirect ตามบทบาท
                    if ($user['role'] == 'admin') {
                        header("Location: ../system test/admin/home_admin.php");
                    } elseif ($user['role'] == 'staff') {
                        header("Location: ../system test/staff/home_staff.php");
                    } elseif ($user['role'] == 'student') {
                        header("Location: ../system test/student/home_student.php");
                    }
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                // ใช้ password_verify สำหรับรหัสผ่านแบบ hash
                if (password_verify($password, $user['password'])) {
                    // เข้าสู่ระบบสำเร็จ
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['first_name'] = $user['first_name'];

                    // Redirect ตามบทบาท
                    if ($user['role'] == 'admin') {
                        header("Location: ../system test/admin/home_admin.php");
                    } elseif ($user['role'] == 'staff') {
                        header("Location: ../system test/staff/home_staff.php");
                    } elseif ($user['role'] == 'student') {
                        header("Location: ../system test/student/home_student.php");
                    }
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            }
        } else {
            $error = "User ID not found.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   
</head>
<body>
    <style>
        
        body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    /* เลเยอร์พื้นหลัง */
    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('images/dai-hoc-phu-xuan-2023-mau-do.jpeg');
        background-size: cover;
        background-position: center;
        filter: blur(5px); /* ทำให้ภาพพื้นหลังเบลอ */
        z-index: -1; /* ทำให้เลเยอร์พื้นหลังอยู่ด้านล่างของเนื้อหา */
    }

    .container {
        max-width: 400px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.8); /* พื้นหลังโปร่งใส */
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        z-index: 1; /* ให้ container อยู่ด้านบน */
    }
        .form-control {
            border-radius: 50px;
            padding-left: 40px;
        }
        .input-group-text {
            background-color: #ff0000;
            color: white;
            border-radius: 50px 0 0 50px;
        }
        .btn-primary {
            background-color: #ff0000;
            border-radius: 50px;
            border-color: #fff;
        }
        .btn-primary:hover {
            background-color: #000000;
        }
        .login-icon {
            font-size: 30px;
            margin-right: 10px;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
   
   <div class="container ">
    <h1 class="text-center mb-4">
        <i class="bi bi-person-circle"></i>
    </h1>

    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

    <form action="login.php" method="post" class="mt-4">
        <!-- User ID -->
        <div class="mb-3">
            <label for="user_id" class="form-label">User ID:</label>
            <div class="input-group">
                <!-- Icon for User ID -->
                <div class="input-group-text">
                    <i class="bi bi-person"></i>
                </div>
                <input type="text" id="user_id" name="user_id" class="form-control" placeholder="Enter your user ID" required>
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <div class="input-group">
                <!-- Icon for Password -->
                <div class="input-group-text">
                    <i class="bi bi-lock"></i>
                </div>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<!-- Import Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
