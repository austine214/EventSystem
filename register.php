<?php
include 'db.php'; 
$message = ""; $message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing

    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $message = "Username already exists.";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO users (fullname, username, password, role) VALUES ('$fullname', '$username', '$password', 'user')";
        if ($conn->query($sql)) {
            $message = "Account created! You can now login.";
            $message_type = "success";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event System | Register</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 10px; border: 1px solid #eaecf4; width: 320px; text-align: center; }
        h2 { font-size: 18px; margin-bottom: 20px; color: #333; }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #4e73df; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .message { font-size: 13px; margin-bottom: 10px; padding: 8px; border-radius: 4px; }
        .error { background: #ffebeb; color: #d9534f; }
        .success { background: #eaffea; color: #28a745; }
        .footer { margin-top: 15px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Event Registration</h2>
        <?php if($message): ?> <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div> <?php endif; ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Create Account</button>
        </form>
        <div class="footer">Have an account? <a href="login.php">Login</a></div>
    </div>
</body>
</html>