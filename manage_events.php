<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit();
}

// DELETE LOGIC
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM events WHERE id = $id");
    header("Location: manage_events.php"); exit();
}

$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Events</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; margin: 0; display: flex; }
        .main { margin-left: 250px; padding: 40px; width: 100%; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .img-sm { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
        .btn-edit { color: #4e73df; text-decoration: none; font-weight: 600; margin-right: 10px; }
        .btn-del { color: #e74a3b; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main">
        <h3>Manage Existing Events</h3>
        <table>
            <thead>
                <tr><th>Img</th><th>Title</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while($e = $events->fetch_assoc()): ?>
                <tr>
                    <td><img src="uploads/<?php echo $e['image']; ?>" class="img-sm"></td>
                    <td><?php echo $e['title']; ?></td>
                    <td><?php echo $e['event_date']; ?></td>
                    <td>
                        <a href="edit_event.php?id=<?php echo $e['id']; ?>" class="btn-edit">Edit</a>
                        <a href="manage_events.php?delete_id=<?php echo $e['id']; ?>" class="btn-del" onclick="return confirm('Confirm Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>