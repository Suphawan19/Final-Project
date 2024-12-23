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

// ตัวแปรแสดงข้อความ
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'] ?? null;
    $user_id = $_POST['user_id'] ?? null;
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $faculty = $_POST['faculty'] ?? null;
    $major = $_POST['major'] ?? null;
    $position = $_POST['position'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $age = $_POST['age'] ?? null;
    $password = $_POST['password'] ?? null;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // เข้ารหัสเพียงครั้งเดียว

    // ตรวจสอบว่า user_id ซ้ำในฐานข้อมูลหรือไม่
    $check_sql = "SELECT user_id FROM users WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // ถ้ามี user_id นี้อยู่แล้ว
        $message = "Error: user_id '$user_id' already exists.";
    } else {
        // ถ้าไม่มี user_id นี้ในฐานข้อมูล, ทำการแทรกข้อมูล
        $sql = "INSERT INTO users (role, user_id, first_name, last_name, email, faculty, major, position, dob, age, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssss",
            $role,
            $user_id,
            $first_name,
            $last_name,
            $email,
            $faculty,
            $major,
            $position,
            $dob,
            $age,
            $hashed_password
        );

        if ($stmt->execute()) {
            $message = "User added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    $message = "Please fill in all required fields.";
}

echo $message;
?>


<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="../style.css/style_user_management.css">

    <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            const studentFields = document.querySelectorAll('.student-field');
            const staffFields = document.querySelectorAll('.staff-field');

            if (role === 'student') {
                studentFields.forEach(field => field.style.display = 'block');
                staffFields.forEach(field => field.style.display = 'none');
            } else if (role === 'staff') {
                studentFields.forEach(field => field.style.display = 'none');
                staffFields.forEach(field => field.style.display = 'block');
            } else {
                studentFields.forEach(field => field.style.display = 'none');
                staffFields.forEach(field => field.style.display = 'none');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleFields();
        });
    </script>

   
</head>

<body>

    <div class="container py-5">
        <div class="card mx-auto shadow-lg" style="max-width: 700px;">
            <div class="card-body">
                <h1 class="text-center text-gradient">Add User</h1>
                <hr>
                <!-- Display Message -->
                <?php if ($message): ?>
                    <div class="alert alert-info text-center"> <?= $message; ?> </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3 text-center">
                        <label class="form-label d-block">Role:</label>
                        <div class="role-select d-flex justify-content-center gap-4">
                            <div class="role-option text-center" onclick="setRole('student')">
                                <i class="fa fa-user-graduate role-icon" aria-hidden="true"></i>
                                <p>Student</p>
                            </div>
                            <div class="role-option text-center" onclick="setRole('staff')">
                                <i class="fa fa-user-tie role-icon" aria-hidden="true"></i>
                                <p>Staff</p>

                                <script>
                                    function setRole(selectedRole) {
                                        // Set hidden input value
                                        document.getElementById('role').value = selectedRole;

                                        // Highlight selected role
                                        const options = document.querySelectorAll('.role-option');
                                        options.forEach(option => option.classList.remove('active'));

                                        if (selectedRole === 'student') {
                                            options[0].classList.add('active');
                                        } else if (selectedRole === 'staff') {
                                            options[1].classList.add('active');
                                        }

                                        // Toggle fields based on role
                                        toggleFields();
                                    }

                                    document.addEventListener('DOMContentLoaded', () => {
                                        // Set default role on load if needed
                                        toggleFields();
                                    });
                                </script>
                            </div>
                        </div>

                        <!-- Hidden input to store the role -->
                        <input type="hidden" name="role" id="role" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User ID:</label>
                        <input type="text" name="user_id" id="user_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <!-- Student Fields -->
                    <div class="student-field mb-3" style="display: none;">
                        <label for="faculty" class="form-label">Faculty:</label>
                        <select name="faculty" id="faculty" class="form-select">
                            <option value="" disabled selected>Select Faculty</option>
                            <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
                            <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
                        </select>
                    </div>
                    <div class="student-field mb-3" style="display: none;">
                        <label for="major" class="form-label">Major:</label>
                        <select name="major" id="major" class="form-select">
                            <option value="" disabled selected>Select Major</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Automotive Engineering Technology">Automotive Engineering Technology</option>
                            <option value="Business Administration-Marketing">Business Administration-Marketing</option>

                            <option value="Travel and Tourism Service Management">Travel and Tourism Service Management</option>
                            <option value="English language">English language</option>
                            <option value="Chinese language">Chinese language</option>
                        </select>
                    </div>

                    <!-- Staff Fields -->
                    <div class="staff-field mb-3" style="display: none;">
                        <label for="position" class="form-label">Position:</label>
                        <input type="text" name="position" id="position" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth:</label>
                        <input type="text" name="dob" id="dob" class="form-control">
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            flatpickr("#dob", {
                                dateFormat: "Y-m-d", // รูปแบบวันที่ (YYYY-MM-DD)
                                altInput: true, // แสดงวันที่ในฟอร์แมตที่อ่านง่าย
                                altFormat: "F j, Y", // ตัวอย่าง: January 1, 2023
                                locale: "en", // บังคับภาษาอังกฤษ
                            });
                        });
                    </script>
                    <div class="mb-3">
                        <label for="age" class="form-label">Age:</label>
                        <input type="number" name="age" id="age" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add User</button>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>

</html>