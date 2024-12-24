<?php
include '../config.php';

// รับค่า id ของ student ที่จะทำการแก้ไข
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // ดึงข้อมูล student ที่จะทำการแก้ไข
    $sql = "SELECT * FROM students WHERE student_id = $student_id";
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // รับค่าจากฟอร์ม
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $date_of_birth = $_POST['date_of_birth'];
        $gender = $_POST['gender'];
        $faculty = $_POST['faculty'];
        $major = $_POST['major'];

        // คำสั่ง SQL สำหรับอัพเดทข้อมูล
        $sql_update = "UPDATE students SET 
                        first_name = '$first_name',
                        last_name = '$last_name',
                        email = '$email',
                        phone_number = '$phone_number',
                        date_of_birth = '$date_of_birth',
                        gender = '$gender',
                        faculty = '$faculty',
                        major = '$major'
                    WHERE student_id = $student_id";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: admin_student.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    echo "Student not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 2.2em;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 1.1em;
            margin-bottom: 5px;
            color: #333;
        }

        input, select {
            padding: 12px;
            font-size: 1em;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.6);
        }

        button[type="submit"] {
            padding: 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 5px 10px rgba(0, 128, 0, 0.1);
        }

        button[type="submit"]:hover {
            background-color: #45a049;
            box-shadow: 0px 8px 15px rgba(0, 128, 0, 0.2);
        }

        button[type="button"] {
            padding: 15px;
            font-size: 16px;
            background-color: #F44336;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0px 5px 10px rgba(244, 67, 54, 0.1);
        }

        button[type="button"]:hover {
            background-color: #d32f2f;
            box-shadow: 0px 8px 15px rgba(244, 67, 54, 0.2);
        }

        button[type="button"]:focus, button[type="submit"]:focus {
            outline: none;
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            background-color: #ff9800;
            color: white;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 1.1em;
            text-align: center;
        }

        .alert.success {
            background-color: #4CAF50;
        }

        .alert.error {
            background-color: #F44336;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 1.8em;
            }

            input, select, button[type="submit"], button[type="button"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student Information</h1>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male" <?php echo ($student['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($student['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
            </select>

            <label for="faculty">Faculty:</label>
            <select id="faculty" name="faculty" required>
                <option value="Faculty of Technology-Business" <?php echo ($student['faculty'] == 'Faculty of Technology-Business') ? 'selected' : ''; ?>>Faculty of Technology-Business</option>
                <option value="Faculty of Foreign Languages-Tourism" <?php echo ($student['faculty'] == 'Faculty of Foreign Languages-Tourism') ? 'selected' : ''; ?>>Faculty of Foreign Languages-Tourism</option>
            </select>

            <label for="major">Major:</label>
            <select id="major" name="major" required>
                <option value="Information Technology" <?php echo ($student['major'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                <option value="Automotive Engineering Technology" <?php echo ($student['major'] == 'Automotive Engineering Technology') ? 'selected' : ''; ?>>Automotive Engineering Technology</option>
                <option value="Business Administration-Marketing" <?php echo ($student['major'] == 'Business Administration-Marketing') ? 'selected' : ''; ?>>Business Administration-Marketing</option>
                <option value="Travel and Tourism Service Management" <?php echo ($student['major'] == 'Travel and Tourism Service Management') ? 'selected' : ''; ?>>Travel and Tourism Service Management</option>
                <option value="English language" <?php echo ($student['major'] == 'English language') ? 'selected' : ''; ?>>English language</option>
                <option value="Chinese language" <?php echo ($student['major'] == 'Chinese language') ? 'selected' : ''; ?>>Chinese language</option>
            </select>

            <button type="submit">Update Student</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
