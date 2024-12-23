<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ฟังก์ชันส่งอีเมลแจ้งเตือนด้วย PHPMailer
function sendEmailNotification($email, $message)
{
    $mail = new PHPMailer(true);

    try {
        // ตั้งค่า SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'admin@example.com'; // ใส่อีเมลของคุณ
        $mail->Password   = 'uoqd padn ystn ivpm';   // ใช้ App Password ถ้าเปิด 2-Step Verification
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // ตั้งค่าอีเมล
        $mail->setFrom('plalook999@gmail.com', 'student');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "New Event Notification";
        $mail->Body    = $message;

        $mail->send();
        return true; // ส่งอีเมลสำเร็จ
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
        return false; // ส่งอีเมลล้มเหลว
    }
}

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $title = $_POST['title'];
    $event_type = $_POST['event_type'];
    $role = $_POST['role'];
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];
    $status = 'pending';
    $month = $_POST['month'];
    $event_time = $_POST['event_time'];
    $end_time = $_POST['end_time'];

    // จัดรูปแบบวันที่
    $start_date = "{$_POST['start_year']}-{$_POST['start_month']}-{$_POST['start_day']}";
    $end_date = "{$_POST['end_year']}-{$_POST['end_month']}-{$_POST['end_day']}";

    // เพิ่มข้อมูลลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO events (title, Set_event_date, end_date, event_type, role, faculty, major, event_time, end_time, month, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $title, $start_date, $end_date, $event_type, $role, $faculty, $major, $event_time, $end_time, $month, $status);

    if ($stmt->execute()) {
        $message = "<h3>New Event Created</h3>
                    <p><b>Event:</b> $title</p>
                    <p><b>Start Date:</b> $start_date</p>
                    <p><b>End Date:</b> $end_date</p>
                    <p><b>Month:</b> " . date("F", mktime(0, 0, 0, $month, 10)) . "</p>
                    <p><b>Start Time:</b> $event_time</p>
                    <p><b>End Time:</b> $end_time</p>";

        // ดึงอีเมลผู้ใช้ที่ตรงกับ faculty, major และ role = 'Student'
        $sql_users = "SELECT email FROM users 
                      WHERE role = 'Student' 
                      AND faculty = ? 
                      AND major = ?";
        $stmt_users = $conn->prepare($sql_users);
        $stmt_users->bind_param("ss", $faculty, $major);
        $stmt_users->execute();
        $result_users = $stmt_users->get_result();

        $emailSentCount = 0;
        if ($result_users->num_rows > 0) {
            while ($row = $result_users->fetch_assoc()) {
                // ส่งอีเมลแจ้งเตือน
                if (sendEmailNotification($row['email'], $message)) {
                    $emailSentCount++;
                }
            }
        }

        echo "<div class='alert alert-success'>
                Event added successfully! Notifications sent: $emailSentCount emails.
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $stmt_users->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url(../images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
        }

        .form-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
            max-width: 700px;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: bold;
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="form-container">
            <div class="header-title">
                <i class="fa-solid fa-calendar-plus"></i> Add New Event
            </div>
            <form method="POST" action="../admin/addevent_admin.php">
                <div class="mb-3">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="event_type" class="form-label">Event Type</label>
                    <select class="form-select" id="event_type" name="event_type">
                        <option value="">Select Type</option>
                        <option value="Seminar">Seminar</option>
                        <option value="Activity">Activity</option>
                        <option value="Meeting">Meeting</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="Student">Student</option>
                        <option value="Staff">Staff</option>
                        <option value="All">All</option>
                    </select>
                </div>

                <!-- Month -->
                <div class="mb-3">
                    <label for="month" class="form-label">Month</label>
                    <select class="form-select" id="month" name="month" required>
                        <option value="">Select Month</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>"><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <div class="d-flex">
                        <select class="form-select me-2" name="start_day" required>
                            <option value="">Day</option>
                            <?php for ($i = 1; $i <= 31; $i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                        <select class="form-select me-2" name="start_month" required>
                            <option value="">Month</option>
                            <?php 
                            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                            foreach ($months as $index => $month) echo "<option value='".($index+1)."'>$month</option>";
                            ?>
                        </select>
                        <select class="form-select" name="start_year" required>
                            <option value="">Year</option>
                            <?php for ($year = date("Y"); $year >= date("Y") - 50; $year--) echo "<option value='$year'>$year</option>"; ?>
                        </select>
                    </div>
                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <div class="d-flex">
                        <select class="form-select me-2" name="end_day" required>
                            <option value="">Day</option>
                            <?php for ($i = 1; $i <= 31; $i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                        <select class="form-select me-2" name="end_month" required>
                            <option value="">Month</option>
                            <?php 
                            foreach ($months as $index => $month) echo "<option value='".($index+1)."'>$month</option>";
                            ?>
                        </select>
                        <select class="form-select" name="end_year" required>
                            <option value="">Year</option>
                            <?php for ($year = date("Y"); $year >= date("Y") - 50; $year--) echo "<option value='$year'>$year</option>"; ?>
                        </select>
                    </div>
                </div>

                <!-- Start Time -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="event_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="event_time" name="event_time" required>
                    </div>

                    <!-- End Time -->
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="faculty" class="form-label">Faculty</label>
                    <select class="form-select" id="faculty" name="faculty" required>
                        <option value="">Select Faculty</option>
                        <option value="Faculty of Technology-Business">Faculty of Technology-Business</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="major" class="form-label">Major</label>
                    <select class="form-select" id="major" name="major" required>
                        <option value="">Select Major</option>
                        <option value="Information Technology">Information Technology</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
