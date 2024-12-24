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
    <link href="../style.css/style_add_career.css" rel="stylesheet">
</head>
<body>

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