<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event System | Login</title>
    <style>
        :root { --primary: #4e73df; --bg: #f8f9fc; --border: #eaecf4; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 12px; border: 1px solid var(--border); width: 100%; max-width: 350px; }
        h2 { text-align: center; margin-bottom: 25px; color: #333; font-size: 22px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        button { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 15px; margin-top: 10px; }
        button:hover { opacity: 0.9; }
        .error-msg { background: #fff5f5; color: #e53e3e; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 15px; text-align: center; border: 1px solid #feb2b2; }
        .footer { text-align: center; margin-top: 25px; font-size: 13px; color: #666; }
        a { color: var(--primary); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="login-card">
    <?php if(isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
    <div style="background: #eaffea; color: #28a745; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 15px; text-align: center; border: 1px solid #9ae6b4;">
        You have been logged out.
    </div>
<?php endif; ?>
    <h2>Login</h2>

    <?php if($message): ?>
        <div class="error-msg"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
    </form>

    <div class="footer">
        New here? <a href="register.php">Create Account</a>
    </div>
</div>

</body>
</html>