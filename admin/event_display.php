<?php
include '../config.php';

// Query ดึงข้อมูลเหตุการณ์จากฐานข้อมูล
$sql_events = "SELECT * FROM events ORDER BY Set_event_date DESC";
$result_events = $conn->query($sql_events);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Management</title>
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
            color:rgb(253, 0, 0);
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
    <h1>📅 Events Management</h1>
    <div class="table-container">
        <!-- ปุ่มเพิ่มเหตุการณ์ -->
        <a href="addevent_admin.php" class="btn btn-add">
            <i class="fas fa-plus-circle"></i> Add New Event
        </a>

        <!-- ตารางแสดงข้อมูลเหตุการณ์ -->
        <table id="eventsTable" class="display table table-striped table-bordered">
            <thead class="table-warning">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Month</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Time</th>
                    <th>Role</th>
                    <th>Faculty</th>
                    <th>Major</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_events->num_rows > 0): ?>
                    <?php while ($event = $result_events->fetch_assoc()): ?>
                        <tr>
                            <td><?= $event['id'] ?></td>
                            <td><?= htmlspecialchars($event['title']) ?></td>
                            <td><?= htmlspecialchars($event['month']) ?></td>
                            <td><?= htmlspecialchars($event['Set_event_date']) ?></td>
                            <td><?= htmlspecialchars($event['end_date']) ?></td>
                            <td><?= htmlspecialchars($event['event_time']) ?> - <?= htmlspecialchars($event['end_time']) ?></td>
                            <td><?= htmlspecialchars($event['role']) ?></td>
                            <td><?= htmlspecialchars($event['faculty']) ?></td>
                            <td><?= htmlspecialchars($event['major']) ?></td>
                            <td><?= htmlspecialchars($event['event_type']) ?></td>
                            <td>
                                <?php if ($event['status'] === 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Sent</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this event?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center">No events found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    // DataTable Initialization
    $(document).ready(function() {
        $('#eventsTable').DataTable({
            "order": [[ 3, "desc" ]],
            "pageLength": 10
        });
    });
</script>
</body>
</html>
