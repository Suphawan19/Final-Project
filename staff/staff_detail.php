<?php
// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "user_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับ ID จาก query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM staff WHERE id = $id";
$result = $conn->query($sql);
$staff = $result->fetch_assoc();

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['new_photo'])) {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["new_photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid image
    $check = getimagesize($_FILES["new_photo"]["tmp_name"]);
    if ($check !== false) {
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES["new_photo"]["tmp_name"], $target_file)) {
            // Update the photo in the database
            $sql = "UPDATE staff SET photo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", basename($_FILES["new_photo"]["name"]), $id);
            $stmt->execute();
            $stmt->close();
            // Refresh the page to reflect the new image
            header("Location: staff_detail.php?id=$id");
            exit();
        }
    } else {
        echo "File is not an image.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 50px auto;
            max-width: 900px;
            background: #ffed6b29;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #951c1c;
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .header h2 {
            margin: 0;
        }

        .profile {
            text-align: center;
            margin-top: -60px;
            margin-bottom: 20px;
        }

        .profile img {
            width: 260px;
            height: 260px;
            margin: 80px;
            object-fit: cover;
            border-radius: 20%;
            border: 5px solid white;
            background-color: #f1f1f1;
        }

        .details {
            margin-top: 10px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #ffbb00;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            background-color: #010304;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #951c1c;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        form label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        form input[type="file"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        form input[type="file"]:hover {
            border-color: #951c1c;
        }

        form button {
            padding: 12px 25px;
            background-color:rgb(197, 20, 20);
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        form button:hover {
            background-color:rgb(179, 0, 0);
        }

        form button:focus {
            outline: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .profile img {
                width: 120px;
                height: 120px;
            }

            th,
            td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($staff): ?>
        <div class="header">
            <h2>Staff Details</h2>
        </div>
        <div class="profile">
            <img src="../uploads/<?= htmlspecialchars($staff['photo']); ?>" alt="Staff Photo">
        </div>
              <!-- Form for uploading new profile photo -->
              <form method="POST" enctype="multipart/form-data">
            <label for="new_photo">Upload New Photo:</label>
            <input type="file" name="new_photo" id="new_photo" required>
            <button type="submit" class="btn btn-warning">Update Photo</button>
        </form>

        <div class="details">
            <table>
                <tr><th>First Name</th><td><?= htmlspecialchars($staff['frist_name']); ?></td></tr>
                <tr><th>Last Name</th><td><?= htmlspecialchars($staff['last_name']); ?></td></tr>
                <tr><th>Position</th><td><?= htmlspecialchars($staff['position']); ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($staff['email']); ?></td></tr>
                <tr><th>Phone</th><td><?= htmlspecialchars($staff['phone']); ?></td></tr>
            </table>
            
            <a href="../staff/staff_infomation.php" class="btn-back">← Back to List</a>
        </div>
    <?php else: ?>
        <p>Staff not found.</p>
        <a href="../staff/staff_infomation.php" class="btn-back">← Back to List</a>
    <?php endif; ?>
</div>

</body>
</html>

