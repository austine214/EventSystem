<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$event = $conn->query("SELECT * FROM events WHERE id = $id")->fetch_assoc();

if (isset($_POST['update_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $loc = mysqli_real_escape_string($conn, $_POST['location']);
    
    $image_sql = "";
    if (!empty($_FILES['event_image']['name'])) {
        $image_name = time() . "_" . $_FILES['event_image']['name'];
        move_uploaded_file($_FILES['event_image']['tmp_name'], "uploads/" . $image_name);
        $image_sql = ", image = '$image_name'";
    }

    $sql = "UPDATE events SET 
            title='$title', description='$desc', event_date='$date', 
            event_time='$time', location='$loc' $image_sql 
            WHERE id = $id";
            
    if ($conn->query($sql)) {
        header("Location: admin_dashboard.php?msg=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event | Admin</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 12px; max-width: 600px; margin: auto; border: 1px solid #eaecf4; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn { background: #4e73df; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; width: 100%; font-weight: 600; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #858796; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="card">
    <h3>Edit Event: <?php echo htmlspecialchars($event['title']); ?></h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
        <input type="time" name="event_time" value="<?php echo $event['event_time']; ?>" required>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        
        <p style="font-size: 12px; color: #666;">Change Image (leave blank to keep current):</p>
        <input type="file" name="event_image" accept="image/*">
        
        <button type="submit" name="update_event" class="btn">Update Event Details</button>
    </form>
    <a href="admin_dashboard.php" class="back-link">← Cancel and Go Back</a>
</div>

</body>
</html>