<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

if (isset($_GET['cancel_id'])) {
    $reg_id = intval($_GET['cancel_id']);
    
    $delete_query = "DELETE FROM registrations WHERE id = $reg_id AND user_id = $user_id";
    
    if ($conn->query($delete_query)) {
        $msg = "Registration cancelled successfully.";
    }
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

$my_events = $conn->query("
    SELECT r.id as reg_id, e.title, e.event_date, e.location, r.status 
    FROM registrations r 
    JOIN events e ON r.event_id = e.id 
    WHERE r.user_id = $user_id 
    ORDER BY e.event_date ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | EventHub</title>
    <style>
        :root { --primary: #4e73df; --bg: #f8f9fc; --danger: #e74a3b; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; color: #333; }
        .navbar { background: white; padding: 15px 50px; border-bottom: 1px solid #eaecf4; display: flex; justify-content: space-between; }
        .container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .card { background: white; padding: 25px; border-radius: 12px; border: 1px solid #eaecf4; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; background: #f8f9fc; font-size: 13px; color: #858796; }
        td { padding: 12px; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        
        .status { font-weight: bold; font-size: 12px; text-transform: uppercase; }
        .pending { color: #f6ad55; }
        .confirmed { color: #1cc88a; }
        
        .btn-cancel { 
            color: var(--danger); 
            text-decoration: none; 
            font-size: 12px; 
            border: 1px solid var(--danger); 
            padding: 4px 8px; 
            border-radius: 4px; 
            transition: 0.2s;
        }
        .btn-cancel:hover { background: var(--danger); color: white; }
        .alert { background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="navbar">
    <div style="font-weight: 800; color: var(--primary);">EventHub</div>
    <div>
        <a href="index.php" style="text-decoration: none; color: #666; margin-right: 20px;">Browse Events</a>
        <a href="logout.php" style="text-decoration: none; color: var(--danger);">Logout</a>
    </div>
</div>

<div class="container">
    <?php if($msg): ?>
        <div class="alert"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h2>
        <p style="color: #858796;">Manage your registered events below.</p>
    </div>

    <div class="card">
        <h3>My Registered Events</h3>
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($my_events->num_rows > 0): ?>
                    <?php while($row = $my_events->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                        <td><?php echo date("M d, Y", strtotime($row['event_date'])); ?></td>
                        <td>
                            <span class="status <?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="profile.php?cancel_id=<?php echo $row['reg_id']; ?>" 
                               class="btn-cancel" 
                               onclick="return confirm('Are you sure you want to cancel your registration for this event?')">
                               Cancel
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; color: #aaa; padding: 30px;">You haven't registered for any events yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>