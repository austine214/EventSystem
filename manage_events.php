<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}


if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM events WHERE id = $id");
    header("Location: manage_events.php"); 
    exit();
}


$events = $conn->query("
    SELECT e.*, (SELECT COUNT(*) FROM registrations WHERE event_id = e.id) as reg_count 
    FROM events e 
    ORDER BY e.event_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Events</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; margin: 0; display: flex; }
        .main { margin-left: 250px; padding: 40px; width: 100%; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .img-sm { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
        .btn-edit { color: #4e73df; text-decoration: none; font-weight: 600; margin-right: 10px; }
        .btn-del { color: #e74a3b; text-decoration: none; font-weight: 600; }
        .reg-badge { background: #f0f2f5; padding: 4px 10px; border-radius: 12px; font-size: 12px; color: #555; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main">
        <h3>Manage Existing Events</h3>
        <table>
            <thead>
                <tr>
                    <th>Img</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Registrations</th> <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($e = $events->fetch_assoc()): ?>
                <tr>
                    <td><img src="uploads/<?php echo $e['image']; ?>" class="img-sm"></td>
                    <td><strong><?php echo htmlspecialchars($e['title']); ?></strong></td>
                    <td><?php echo date("M d, Y", strtotime($e['event_date'])); ?></td>
                    <td>
                        <span class="reg-badge">
                            👥 <?php echo $e['reg_count']; ?> Registered
                        </span>
                    </td>
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