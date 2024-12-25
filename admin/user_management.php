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
    $year = ($role === 'student') ? ($_POST['year'] ?? null) : null; // เฉพาะนักศึกษาเท่านั้นที่มีชั้นปี
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
        $sql = "INSERT INTO users (role, user_id, first_name, last_name, email, faculty, major, position, dob, age, password, year)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssss",
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
            $hashed_password,
            $year
        );

        if ($stmt->execute()) {
            $message = "User added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        function setRole(selectedRole) {
            document.getElementById('role').value = selectedRole;
            const options = document.querySelectorAll('.role-option');
            options.forEach(option => option.classList.remove('active'));

            if (selectedRole === 'student') {
                options[0].classList.add('active');
            } else if (selectedRole === 'staff') {
                options[1].classList.add('active');
            }
            toggleFields();
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleFields();
            flatpickr("#dob", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                locale: "en"
            });
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
                            </div>
                        </div>
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
                        </select>
                    </div>
                    <div class="student-field mb-3" style="display: none;">
                        <label for="year" class="form-label">Year:</label>
                        <input type="number" name="year" id="year" class="form-control">
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
        
        <!-- Displaying the User List -->
        <div class="card mt-5">
            <div class="card-body">
                <h2 class="text-center">User List</h2>
                <div class="mb-3">
                    <input type="text" id="search" class="form-control" placeholder="Search Users..." onkeyup="searchUsers()">
                </div>

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        <?php
                        // Fetch all users from the database
                        $result = $conn->query("SELECT * FROM users");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                  
                                    </td>
                                    <td>{$row['user_id']}</td>
                                    <td>{$row['first_name']}</td>
                                    <td>{$row['last_name']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['role']}</td>
                                    <td>{$row['age']}</td>
                                      <td>
                                         <a href='edit_user.php?id={$row['user_id']}' class='btn btn-warning btn-sm'>
                            <i class='fa fa-edit'></i> Edit
                        </a>
                        <a href='delete_user.php?id={$row['user_id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this user?');\">
                            <i class='fa fa-trash'></i> Delete
                        </a>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchUsers() {
            const searchQuery = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('#user-table-body tr');

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const userData = Array.from(cells).slice(1).map(cell => cell.textContent.toLowerCase());
                const isMatch = userData.some(data => data.includes(searchQuery));

                if (isMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
