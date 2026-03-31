<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Confirmation Logic
if (isset($_GET['confirm_id'])) {
    $reg_id = intval($_GET['confirm_id']);
    $conn->query("UPDATE registrations SET status = 'confirmed' WHERE id = $reg_id");
    header("Location: manage_registrations.php?msg=confirmed");
    exit();
}

$regs = $conn->query("SELECT r.id, u.fullname, e.title, r.reg_date, r.status 
                      FROM registrations r 
                      JOIN users u ON r.user_id = u.id 
                      JOIN events e ON r.event_id = e.id 
                      ORDER BY r.reg_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrations | Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <div class="card">
        <h3>Manage Registrations</h3>
        <table>
            <thead>
                <tr><th>Participant</th><th>Event</th><th>Date</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while($r = $regs->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($r['title']); ?></td>
                    <td><?php echo date("M d", strtotime($r['reg_date'])); ?></td>
                    <td><span style="color: <?php echo ($r['status'] == 'confirmed') ? '#16a34a' : '#f6ad55'; ?>;">● <?php echo ucfirst($r['status']); ?></span></td>
                    <td>
                        <?php if($r['status'] == 'pending'): ?>
                            <a href="manage_registrations.php?confirm_id=<?php echo $r['id']; ?>" class="btn-confirm">Confirm</a>
                        <?php else: ?>
                            <span style="color: #858796;">Verified ✓</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>