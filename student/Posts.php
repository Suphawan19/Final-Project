<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

session_start(); // เริ่ม session เพื่อดึง user_id
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in!"); // ถ้าผู้ใช้ไม่ได้ล็อกอิน
}

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $upload_dir = '../uploads/';
    $image_path = '';

    // ตรวจสอบและจัดการการอัปโหลดไฟล์รูปภาพ
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { // แก้ไขเป็น 'image' ให้ตรงกับชื่อใน HTML
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;

        // ย้ายไฟล์ไปยังโฟลเดอร์อัปโหลด
        if (move_uploaded_file($file_tmp, $target_file)) {
            $image_path = $file_name; // เก็บชื่อไฟล์ในฐานข้อมูล
        } else {
            echo "Error uploading file.";
            exit;
        }
    }

    // เพิ่มข้อมูลลงในฐานข้อมูล โดยเพิ่ม user_id
    $sql = "INSERT INTO posts (title, description, image_path, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $description, $image_path, $user_id); // ใช้ user_id ที่ได้จาก session

    if ($stmt->execute()) {
        echo "<script>alert('Post created successfully!'); window.location.href='home_student.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Custom Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url(../images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background: #ffffffc4;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            border: 1px solid #ddd;
        }

        .form-title {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            color: #333;
        }

        .form-container label {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-container input:focus,
        .form-container textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-container textarea {
            resize: vertical;
        }

        .form-container input[type="file"] {
            padding: 0;
        }

        .btn-custom {
            background-color: #ffd417;
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #793232d1;
        }

        .image-preview {
            margin-top: 20px;
            text-align: center;
        }

        .image-preview img {
            max-width: 100%;
            border-radius: 8px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            background-color: #793232d1;
            color: white;
            padding: 20px 0;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .btn-custom {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Create a New Post</h2>
            <form action="../student/save_post.php" method="POST" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>
                
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
                
                <label for="image">Image:</label>
                <input type="file" name="image" id="image">
                
                <div class="image-preview" id="imagePreview"></div>
                
                <button type="submit" class="btn-custom">Post</button>
            </form>
        </div>
    </div>

    <script>
        let imageInput = document.querySelector("#image");
        let previewImg = document.createElement("img");
        previewImg.id = "previewImg";
        previewImg.style.maxWidth = "100%";
        document.querySelector("#imagePreview").appendChild(previewImg);

        imageInput.onchange = evt => {
            const [file] = imageInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        };
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="footer">
        <p>&copy; 2024 Phu Xuan University. All rights reserved.</p>
    </footer>

</body>

</html>
