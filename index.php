<?php
session_start();
include 'db.php';

$query = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
$result = $conn->query($query);

$msg = "";
if (isset($_GET['register_id'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $event_id = intval($_GET['register_id']);

    $check = $conn->query("SELECT id FROM registrations WHERE user_id = $user_id AND event_id = $event_id");
    
    if ($check->num_rows > 0) {
        $msg = "already";
    } else {
        $insert = "INSERT INTO registrations (user_id, event_id, status) VALUES ($user_id, $event_id, 'pending')";
        if ($conn->query($insert)) {
            $msg = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EventHub | Browse Events</title>
    <style>
        :root { --primary: #6366f1; --bg: #f8f9fc; --border: #eaecf4; --text: #3a3b45; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; color: var(--text); }
        
        .navbar { background: white; padding: 15px 50px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: var(--text); font-size: 14px; font-weight: 500; }
        
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .hero { text-align: center; margin-bottom: 50px; }
        .hero h1 { font-size: 32px; margin-bottom: 10px; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

        .event-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
        .event-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; transition: 0.3s; display: flex; flex-direction: column; }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        
        /* Image Styling */
        .event-banner { width: 100%; height: 180px; object-fit: cover; border-bottom: 1px solid var(--border); }
        
        .event-content { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
        .event-content h3 { margin: 0 0 10px 0; font-size: 18px; color: #2e59d9; }
        .event-meta { font-size: 13px; color: #858796; margin-bottom: 15px; line-height: 1.6; }
        
        .btn-reg { display: block; width: 100%; padding: 10px; background: var(--primary); color: white; text-align: center; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; margin-top: auto; }
        .btn-reg:hover { background: #2e59d9; }
    </style>
</head>
<body>

<div class="navbar">
    <div style="font-weight: 800; font-size: 20px; color: var(--primary);">EventHub</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php">My Profile</a>
            <a href="logout.php" style="color: #e74a3b;">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="hero">
        <h1>Discover Upcoming Events</h1>
        <p>Browse and register for events online in just one click.</p>
    </div>

    <?php if($msg == "success"): ?>
        <div class="alert alert-success">Registration successful! Check your profile for status updates.</div>
    <?php elseif($msg == "already"): ?>
        <div class="alert alert-info">You are already registered for this event.</div>
    <?php endif; ?>

    <div class="event-grid">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($event = $result->fetch_assoc()): ?>
                <div class="event-card">
                    <img src="uploads/<?php echo !empty($event['image']) ? $event['image'] : 'default_event.jpg'; ?>" 
                         class="event-banner" 
                         alt="Event Image">
                    
                    <div class="event-content">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <div class="event-meta">
                            📅 <?php echo date("M d, Y", strtotime($event['event_date'])); ?><br>
                            ⏰ <?php echo date("h:i A", strtotime($event['event_time'])); ?><br>
                            📍 <?php echo htmlspecialchars($event['location']); ?>
                        </div>
                        <p style="font-size: 13px; color: #666; margin-bottom: 20px;">
                            <?php echo substr(htmlspecialchars($event['description']), 0, 80); ?>...
                        </p>
                        <a href="index.php?register_id=<?php echo $event['id']; ?>" class="btn-reg">Register Now</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center; color: #aaa;">No upcoming events found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>