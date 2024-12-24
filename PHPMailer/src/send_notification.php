<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- ใช้ไอคอน -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #3c8dbc;
            margin-bottom: 30px;
        }

        .alert {
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert i {
            margin-right: 10px;
            font-size: 20px;
        }

        .notifications-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border-radius: 5px;
            overflow: hidden;
        }

        .notifications-table th, .notifications-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .notifications-table th {
            background-color: #3c8dbc;
            color: white;
        }

        .notifications-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .notifications-table tr:hover {
            background-color: #e9ecef;
        }

        .btn-back {
            display: inline-block;
            background-color: #3c8dbc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #3570b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sent Notifications</h2>

        <?php
        // ตัวอย่างโค้ดที่ใช้แสดงข้อความผลลัพธ์การส่งอีเมล
        // ถ้ามีการส่งอีเมลที่สำเร็จหรือล้มเหลวจะมีการแสดงข้อความนี้
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // สมมุติว่าเมื่อส่งอีเมลแล้ว จะมีการตรวจสอบว่าเป็นสำเร็จหรือไม่
            if ($emailSentCount > 0) {
                echo "<div class='alert alert-success'>
                        <i class='fas fa-check-circle'></i> Notifications sent successfully! $emailSentCount emails sent.
                      </div>";
            } else {
                echo "<div class='alert alert-danger'>
                        <i class='fas fa-times-circle'></i> Error: No emails were sent.
                      </div>";
            }
        }
        ?>

        <!-- ตารางการแสดงผลการส่งอีเมล -->
        <table class="notifications-table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>

        <!-- ปุ่มกลับไปที่หน้าก่อนหน้า -->
        <a href="../../admin/home_admin.php" class="btn-back">Back to Dashboard</a>
    </div>
</body>
</html>
