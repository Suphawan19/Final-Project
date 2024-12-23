<?php
include '../config.php';

// เช็คการส่งข้อมูลจากฟอร์ม
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
   

    // คำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql = "INSERT INTO students (first_name, last_name, email, phone_number, date_of_birth, gender, major, faculty) 
            VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$date_of_birth', '$gender', '$major', '$faculty')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_student.php"); // เปลี่ยนไปที่หน้ารายการนักเรียน
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
</head>
<body>
    <style>
        /* ตกแต่งพื้นหลังและฟอนต์ */
body {
    font-family: 'Arial', sans-serif;
    background-image: url(../images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
    margin: 0;
    padding: 0;
}

/* กล่องฟอร์ม */
form {
    width: 80%;
    max-width: 600px;
    margin: 50px auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* หัวข้อฟอร์ม */
h1 {
    text-align: center;
    font-size: 2em;
    margin-bottom: 30px;
    color: #333;
}

/* ป้ายของ input */
label {
    font-size: 1em;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
    display: block;
}

/* ช่องกรอกข้อมูล */
input, select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
}

/* ช่องกรอกข้อมูลเมื่อมีกระทำผิด */
input:invalid, select:invalid {
    border-color: gainsboro;
}

/* ปุ่มส่งข้อมูล */
button {
    width: 100%;
    padding: 12px;
    font-size: 1.2em;
    background-color:rgb(204, 48, 31);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color:rgb(148, 27, 27);
}

/* ปรับการแสดงผลให้สวยงาม */
input, select, button {
    font-size: 1.1em;
}

/* เพิ่มระยะห่างและจัดการปัญหาบรรทัด */
br {
    display: block;
    margin-bottom: 10px;
}
select {
    position: relative;
    z-index: 1;
}

    </style>

    <form method="POST">
        <h1>Add New Student</h1>
        <label>First Name:</label><br>
        <input type="text" name="first_name" required><br><br>

        <label>Last Name:</label><br>
        <input type="text" name="last_name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone Number:</label><br>
        <input type="text" name="phone_number" required><br><br>

        <label for="date_of_birth">Date of Birth (DD-Month-YYYY):</label>
    
    <select name="day" required>
        <?php
        // Create day options from 1 to 31
        for ($i = 1; $i <= 31; $i++) {
            echo "<option value=\"$i\">$i</option>";
        }
        ?>
    </select>
    
    <select name="month" required>
        <option value="January">January</option>
        <option value="February">February</option>
        <option value="March">March</option>
        <option value="April">April</option>
        <option value="May">May</option>
        <option value="June">June</option>
        <option value="July">July</option>
        <option value="August">August</option>
        <option value="September">September</option>
        <option value="October">October</option>
        <option value="November">November</option>
        <option value="December">December</option>
    </select>
    
    <select name="year" required>
        <?php
        // Create year options for the last 100 years
        $currentYear = date('Y');
        for ($i = $currentYear; $i >= 1900; $i--) {
            echo "<option value=\"$i\">$i</option>";
        }
        ?>
    </select>

        <label>Gender:</label><br>
        <select name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select><br><br>

         <!-- เลือกคณะ -->
         <label for="faculty">Faculty</label>
        <select id="faculty" name="faculty" required>
            <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
            <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
        </select>

        <!-- เลือกสาขา -->
        <label>Major:</label><br>
        <select name="major" required>
            <option value="Information Technology">Information Technology</option>
            <option value="Automotive Engineering Technology">Automotive Engineering Technology</option>
            <option value="Business Administration-Marketing">Business Administration-Marketing</option>
            ------------------------------------------------------------------------------------------------
            <option value="Faculty of Foreign Languages-Tourism">Faculty of Foreign Languages-Tourism</option>
            <option value="Travel and Tourism Service Management">Travel and Tourism Service Management</option>
            <option value="English language">English language</option>
            <option value="Chinese language">Chinese language</option>
        </select><br><br>

        

        <button type="submit">Add Student</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>