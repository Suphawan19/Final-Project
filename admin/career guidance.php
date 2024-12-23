<?php
// เชื่อมต่อฐานข้อมูล
include('../config.php');  // เรียกใช้ไฟล์ config.php ที่เก็บการเชื่อมต่อฐานข้อมูล

// ดึงข้อมูลอาชีพจากฐานข้อมูล
$query = "SELECT * FROM career_advice";
$result = $conn->query($query);

// เช็คหากไม่มีข้อมูล
if ($result->num_rows == 0) {
    echo "No career guidance available.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Guidance Training</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css/style_career guidance.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <header>
        <h1>Career Guidance & Training</h1>
        <p>Explore various career paths and training programs to help you grow</p>
        <a href="add_career.php" class="btn btn-warning">Add Career Advice</a> <!-- ปุ่มเพิ่มการอบรม/แนะแนว -->
    </header>

    <div class="container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <h2><?php echo htmlspecialchars($row['career_name']); ?></h2>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($row['career_description']); ?></p>
                <p><strong>Skills Needed:</strong> <span class="skills"><?php echo htmlspecialchars($row['skills_needed']); ?></span></p>
                <p><strong>Training Programs:</strong> <span class="training"><?php echo htmlspecialchars($row['training_programs']); ?></span></p>
                <p><strong>Advice:</strong> <span class="advice"><?php echo htmlspecialchars($row['advice']); ?></span></p>
                 <!-- ปุ่มลบ -->
            <a href="delete_career.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this career advice?');">Delete</a>
            </div>
        <?php endwhile; ?>
    </div>


</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>