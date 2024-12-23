<?php
// เชื่อมต่อฐานข้อมูล
include('../config.php');

// ตรวจสอบว่าได้มีการส่งข้อมูลฟอร์มเข้ามาหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $career_name = $_POST['career_name'];
    $career_description = $_POST['career_description'];
    $skills_needed = $_POST['skills_needed'];
    $training_programs = $_POST['training_programs'];
    $advice = $_POST['advice'];

    // สร้างคำสั่ง SQL สำหรับเพิ่มข้อมูล
    $query = "INSERT INTO career_advice (career_name, career_description, skills_needed, training_programs, advice) 
              VALUES ('$career_name', '$career_description', '$skills_needed', '$training_programs', '$advice')";

    if ($conn->query($query) === TRUE) {
        echo "New career advice added successfully!";
        header('Location: career guidance.php'); // กลับไปที่หน้า Career Guidance
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Career Advice</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css/style_career guidance.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
        /* การตั้งค่าทั่วไป */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

/* ส่วนหัว */
header {
    background-color: #7b2b2b;
    color: white;
    padding: 20px 0;
    text-align: center;
}

header h1 {
    font-size: 36px;
    margin: 0;
}

/* กรอบฟอร์ม */
.container {
    width: 60%;
    margin: 20px auto;
    padding: 30px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* สไตล์ฟอร์ม */
.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    font-size: 18px;
    margin-bottom: 5px;
    color: #333;
}

input[type="text"], textarea {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea {
    height: 120px;
}

button {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
}

    </style>
    <header>
        <h1>Add Career Advice</h1>
    </header>

    <div class="container">
        <form method="POST" action="add_career.php">
            <div class="form-group">
                <label for="career_name">Career Name</label>
                <input type="text" id="career_name" name="career_name" required class="form-control">
            </div>
            <div class="form-group">
                <label for="career_description">Career Description</label>
                <textarea id="career_description" name="career_description" required class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="skills_needed">Skills Needed</label>
                <input type="text" id="skills_needed" name="skills_needed" required class="form-control">
            </div>
            <div class="form-group">
                <label for="training_programs">Training Programs</label>
                <input type="text" id="training_programs" name="training_programs" required class="form-control">
            </div>
            <div class="form-group">
                <label for="advice">Advice</label>
                <textarea id="advice" name="advice" required class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Add Career Advice</button>
        </form>
    </div>



</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>