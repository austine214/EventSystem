<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit();
}

$msg = "";
if (isset($_POST['create_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $loc = mysqli_real_escape_string($conn, $_POST['location']);

    $image_name = "default_event.jpg"; 
    if (!empty($_FILES['event_image']['name'])) {
        $image_name = time() . "_" . $_FILES['event_image']['name'];
        move_uploaded_file($_FILES['event_image']['tmp_name'], "uploads/" . $image_name);
    }

    $sql = "INSERT INTO events (title, description, event_date, event_time, location, image) 
            VALUES ('$title', '$desc', '$date', '$time', '$loc', '$image_name')";
    if ($conn->query($sql)) { $msg = "Event Created Successfully!"; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Create Event</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; margin: 0; display: flex; }
        .main { margin-left: 250px; padding: 40px; width: 100%; }
        .card { background: white; padding: 30px; border-radius: 12px; border: 1px solid #eaecf4; max-width: 700px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; margin-top: 5px; }
        .btn { background: #4e73df; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: 600; margin-top: 15px; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main">
        <?php if($msg) echo "<p style='color:green'>$msg</p>"; ?>
        <div class="card">
            <h3>Add New IT Event</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <input type="text" name="title" placeholder="Event Title" required>
                    <input type="text" name="location" placeholder="Location" required>
                    <input type="date" name="event_date" required>
                    <input type="time" name="event_time" required>
                    <div style="grid-column: span 2;">
                        <label style="font-size: 12px;">Banner Image:</label>
                        <input type="file" name="event_image">
                    </div>
                    <textarea name="description" placeholder="Describe the event..." rows="4" style="grid-column: span 2;"></textarea>
                </div>
                <button type="submit" name="create_event" class="btn">Publish Event</button>
            </form>
        </div>
    </div>
</body>
</html>