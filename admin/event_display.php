<?php
include '../config.php';

// คำค้นหาและกรองข้อมูลตาม role
$role_filter = '';
$search_query = "";

if (isset($_GET['role'])) {
    $role = $_GET['role'];
    if ($role == 'student') {
        $role_filter = " WHERE role = 'student'";
    } elseif ($role == 'staff') {
        $role_filter = " WHERE role = 'staff'";
    }
}

// ใช้คำค้นหาเพื่อกรองข้อมูล
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $search_query = " AND (title LIKE '%$search_term%' OR month LIKE '%$search_term%' OR faculty LIKE '%$search_term%' OR major LIKE '%$search_term%')";
}

// Query ดึงข้อมูลเหตุการณ์จากฐานข้อมูล
$sql_events = "SELECT * FROM events $role_filter $search_query ORDER BY set_event_date DESC";
$result_events = $conn->query($sql_events);
$current_date = date('Y-m-d'); // วันที่ปัจจุบันในรูปแบบ Y-m-d
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Display</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url(../images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
            margin: 0;
        }

        .container {
            margin-top: 50px;
        }

        h1 {
            color: rgb(253, 0, 0);
            text-align: center;
            margin-bottom: 20px;
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-add {
            margin-bottom: 15px;
            background-color: #28a745;
            color: white;
        }

        .btn-add:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="table-container">
            <!-- ปุ่มเพิ่มเหตุการณ์ -->
            <a href="add_event_admin.php" class="btn btn-add">
                <i class="fas fa-plus-circle"></i> Add New Event
            </a>
            <h1>📅 Events Management</h1>

            <!-- ฟอร์มค้นหาและเลือก Role -->
            <form method="GET" class="mb-3 d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="ms-2">
                    <select name="role" class="form-control" onchange="this.form.submit()">
                        <option value="all" <?= isset($_GET['role']) && $_GET['role'] == 'all' ? 'selected' : '' ?>>All</option>
                        <option value="student" <?= isset($_GET['role']) && $_GET['role'] == 'student' ? 'selected' : '' ?>>Student</option>
                        <option value="staff" <?= isset($_GET['role']) && $_GET['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                    </select>
                </div>
            </form>

            <!-- ตารางแสดงข้อมูลเหตุการณ์ -->
            <table id="eventsTable" class="display table table-striped table-bordered">
                <thead class="table-danger">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Month</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Time</th>
                        <th>Role</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_events->num_rows > 0):
                        while ($event = $result_events->fetch_assoc()):
                            $status = $event['status'];
                            $end_date = $event['end_date'];

                            // อัปเดตสถานะเป็น 'sent' หากวันที่ปัจจุบันเท่าหรือมากกว่าวันที่สิ้นสุด
                            if ($status === 'pending' && $current_date >= $end_date) {
                                $status = 'sent';

                                // อัปเดตสถานะในฐานข้อมูล
                                $update_status = "UPDATE events SET status = 'sent' WHERE id = " . $event['id'];
                                $conn->query($update_status);
                            }
                    ?>
                            <tr>
                                <td><?= $event['id'] ?></td>
                                <td><?= htmlspecialchars($event['title']) ?></td>
                                <td><?= htmlspecialchars($event['month']) ?></td>
                                <td><?= htmlspecialchars($event['set_event_date']) ?></td>
                                <td><?= htmlspecialchars($event['end_date']) ?></td>
                                <td><?= htmlspecialchars($event['event_time']) ?> - <?= htmlspecialchars($event['end_time']) ?></td>
                                <td><?= htmlspecialchars($event['role']) ?></td>
                                <td><?= htmlspecialchars($event['event_type']) ?></td>
                                <td>
                                    <?php if ($status === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($status === 'sent'): ?>
                                        <span class="badge bg-success">Sent</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="../admin/edit_event.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="../admin/delete_event.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-danger ms-2">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="12" class="text-center">No events found.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#eventsTable').DataTable();
        });
    </script>
</body>

</html>
