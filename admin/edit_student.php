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
            header("Location: student.php");
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
</head>
<body>
    <style>
/* edit_student.php */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f9;
    margin: 100px;
    padding: 50px;
}

.container {
    width: 60%;
    margin: 50px auto;
    padding: 30px;
    background-color: #ffffff;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    border-top: 4px solid #4CAF50; /* เพิ่มเส้นหัวมุมที่ด้านบน */
}

h1 {
    text-align: center;
    font-size: 2.5em;
    color: #333;
    font-weight: 600;
    margin-bottom: 30px;
    text-transform: uppercase;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 1.1em;
    margin: 10px 0 5px;
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

/* เพิ่มแถบข้อความแจ้งเตือน */
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

@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    h1 {
        font-size: 2em;
    }

    input, select, button[type="submit"], button[type="button"] {
        font-size: 14px;
    }
}
    </style>
    <h1>Edit Student Information</h1>

    <form method="POST">
        <label>First Name:</label><br>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required><br><br>

        <label>Last Name:</label><br>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required><br><br>

        <label>Phone Number:</label><br>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required><br><br>

        <label>Date of Birth:</label><br>
        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required><br><br>

        <label>Gender:</label><br>
        <select name="gender" required>
            <option value="male" <?php echo ($student['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo ($student['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
        </select><br><br>

        <label>Faculty:</label><br>
        <select name="faculty" required>
            <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
            <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
        </select><br><br>

        <label>Major:</label><br>
        <select name="major" required>
            <option value="Information Technology">Information Technology</option>
            <option value="Automotive Engineering Technology">Automotive Engineering Technology</option>
            <option value="Business Administration-Marketing">Business Administration-Marketing</option>

            <option value="Travel and Tourism Service Management">Travel and Tourism Service Management</option>
            <option value="English language">English language</option>
            <option value="Chinese language">Chinese language</option>

        </select><br><br>

        <button type="submit">Update Student</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>