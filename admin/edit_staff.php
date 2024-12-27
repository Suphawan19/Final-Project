<?php
include '../config.php';

$id = $_GET['id'] ?? 0;

// Fetch staff data
$sql = "SELECT * FROM staff WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$staff = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frist_name = $_POST['frist_name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $photo = isset($_FILES['photo']) && $_FILES['photo']['error'] === 0 ? $_FILES['photo']['name'] : $staff['photo'];

    if ($photo && $photo !== $staff['photo']) {
        $uploadDir = '../uploads/';
        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photo);
    }

    $sql = "UPDATE staff SET frist_name = ?, last_name = ?, position = ?, email = ?, phone = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $frist_name, $last_name, $position, $email, $phone, $photo, $id);

    if ($stmt->execute()) {
        header("Location: admin_staff.php");
        exit();
    } else {
        $error = "Error updating staff: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Edit Staff</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="frist_name" class="form-label">First Name</label>
                <input type="text" name="frist_name" class="form-control" value="<?= $staff['frist_name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?= $staff['last_name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" name="position" class="form-control" value="<?= $staff['position'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= $staff['email'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= $staff['phone'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Staff</button>
            <a href="admin_staff.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
