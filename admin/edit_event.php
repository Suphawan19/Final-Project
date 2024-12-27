<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบ ID ของเหตุการณ์ที่ส่งมา
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // ดึงข้อมูลเหตุการณ์จากฐานข้อมูล
    $sql = "SELECT * FROM events WHERE id = $event_id";
    $result = $conn->query($sql);
    $event = $result->fetch_assoc();

    // ถ้าหากไม่พบเหตุการณ์
    if (!$event) {
        echo "Event not found!";
        exit;
    }
} else {
    echo "No event ID specified!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลที่ส่งมาจากฟอร์ม
    $title = $_POST['title'];
    $month = $_POST['month'];
    $set_event_date = $_POST['set_event_date'];
    $end_date = $_POST['end_date'];
    $event_time = $_POST['event_time'];
    $end_time = $_POST['end_time'];
    $role = $_POST['role'];
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];
    $event_type = $_POST['event_type'];
    $status = $_POST['status'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql_update = "UPDATE events SET title = '$title', month = '$month', Set_event_date = '$set_event_date', end_date = '$end_date', event_time = '$event_time', end_time = '$end_time', role = '$role', faculty = '$faculty', major = '$major', event_type = '$event_type', status = '$status' WHERE id = $event_id";

    if ($conn->query($sql_update) === TRUE) {
        // เมื่ออัปเดตสำเร็จ ให้รีไดเรกต์ไปยังหน้า events.php
        header('Location: event_display.php');
        exit; // จำเป็นต้องใช้ exit หลัง header เพื่อละการทำงานของ PHP ต่อ
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 50px auto;
        }

        h2 {
            color: #007bff;
            text-align: center;
        }

        .btn-submit {
            width: 100%;
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Edit Event</h2>
            <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST">
                <!-- ฟิลด์สำหรับแก้ไขข้อมูล -->
                <div class="mb-3">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($event['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="month" class="form-label">Month</label>
                    <input type="text" class="form-control" id="month" name="month" value="<?= htmlspecialchars($event['month']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="set_event_date" class="form-label">Start Date</label>
                    <input type="text" class="form-control" id="set_event_date" name="set_event_date" value="<?= date('d F Y', strtotime($event['set_event_date'])); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" value="<?= date('d F Y', strtotime($event['end_date'])); ?>" required>
                </div>


                <div class="mb-3">
                    <label for="event_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="event_time" name="event_time" value="<?= htmlspecialchars($event['event_time']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" value="<?= htmlspecialchars($event['end_time']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="student" <?= ($event['role'] === 'student') ? 'selected' : ''; ?>>Student</option>
                        <option value="staff" <?= ($event['role'] === 'staff') ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="faculty" class="form-label">Faculty</label>
                    <input type="text" class="form-control" id="faculty" name="faculty" value="<?= htmlspecialchars($event['faculty']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="major" class="form-label">Major</label>
                    <input type="text" class="form-control" id="major" name="major" value="<?= htmlspecialchars($event['major']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="event_type" class="form-label">Event Type</label>
                    <input type="text" class="form-control" id="event_type" name="event_type" value="<?= htmlspecialchars($event['event_type']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="pending" <?= ($event['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="sent" <?= ($event['status'] === 'sent') ? 'selected' : ''; ?>>Sent</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-submit">Update Event</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>