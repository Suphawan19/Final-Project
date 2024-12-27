<?php
include '../config.php';

// Get student ID from URL
$student_id = isset($_GET['student_id']) ? urldecode($_GET['student_id']) : null;

if ($student_id) {
    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

// Handle photo update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['new_photo'])) {
    $photo = $_FILES['new_photo']['name'];
    $photo_tmp = $_FILES['new_photo']['tmp_name'];
    $photo_extension = pathinfo($photo, PATHINFO_EXTENSION);
    $photo_new_name = uniqid('', true) . '.' . $photo_extension;
    $photo_path = '../uploads/' . $photo_new_name;

    if (move_uploaded_file($photo_tmp, $photo_path)) {
        // Update the photo in the database
        $sql_update = "UPDATE students SET photo = ? WHERE student_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $photo_new_name, $student_id);
        $stmt_update->execute();
        $stmt_update->close();
        
        // Redirect to the current page to show the updated photo
        header("Location: student_detail.php?student_id=" . urlencode($student_id));
        exit();
    } else {
        echo "Error uploading photo.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
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
            background: white;
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
/* Add to your existing CSS */

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
    border-color:rgb(255, 4, 0);
}

form button {
    padding: 12px 25px;
    background-color:rgb(167, 44, 22);
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
    background-color:rgb(248, 2, 2);
}

form button:focus {
    outline: none;
}

form .form-group {
    margin-bottom: 15px;
}


        .details {
            margin-top: 10px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
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

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .profile img {
                width: 120px;
                height: 120px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($student): ?>
        <div class="header">
            <h2>Student Details</h2>
        </div>
        <div class="profile">
            <?php
            // Display the student's photo or a default photo
            $photo = !empty($student['photo']) ? $student['photo'] : 'default.jpg'; // Use default image if no photo is available
            ?>
            <img src="../uploads/<?= htmlspecialchars($photo); ?>" alt="Student Photo" width="150">
        </div>

        <!-- Form for updating the student's photo -->
        <form method="POST" enctype="multipart/form-data">
            <label for="new_photo">Update Photo:</label><br>
            <input type="file" name="new_photo" id="new_photo" required><br><br>
            <button type="submit" class="btn btn-warning">Update Photo</button>
        </form>

        <div class="details">
            <table>
                <tr><th>Student ID</th><td><?php echo htmlspecialchars($student['student_id']); ?></td></tr>
                <tr><th>First Name</th><td><?php echo htmlspecialchars($student['first_name']); ?></td></tr>
                <tr><th>Last Name</th><td><?php echo htmlspecialchars($student['last_name']); ?></td></tr>
                <tr><th>Email</th><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
                <tr><th>Phone Number</th><td><?php echo htmlspecialchars($student['phone_number']); ?></td></tr>
                <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($student['date_of_birth']); ?></td></tr>
                <tr><th>Gender</th><td><?php echo htmlspecialchars($student['gender']); ?></td></tr>
                <tr><th>Faculty</th><td><?php echo htmlspecialchars($student['faculty']); ?></td></tr>
                <tr><th>Major</th><td><?php echo htmlspecialchars($student['major']); ?></td></tr>
            </table>
            
            <a href="../student/student_information.php" class="btn-back">← Back to List</a>
        </div>
    <?php else: ?>
        <p>Student not found.</p>
        <a href="../student/student_information.php" class="btn-back">← Back to List</a>
    <?php endif; ?>
</div>

</body>
</html>
