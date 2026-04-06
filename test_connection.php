<?php
include 'db.php';

echo "<div style='font-family: Arial; padding: 20px; border-radius: 10px; border: 1px solid #ddd; max-width: 500px; margin: 50px auto;'>";
echo "<h2 style='color: #333;'>Database Connection Test</h2>";

if (!$conn) {
    echo "<p style='color: red;'>❌ <strong>Error:</strong> Connection variable \$conn is not defined.</p>";
} else {
    if ($conn->connect_error) {
        echo "<p style='color: red;'>❌ <strong>Connection Failed:</strong> " . $conn->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ <strong>Success:</strong> Connected to database <u>" . $dbname . "</u> successfully!</p>";

        $result = $conn->query("SELECT COUNT(*) as total FROM users");
        
        if ($result) {
            $data = $result->fetch_assoc();
            echo "<p style='color: #4e73df;'>📊 <strong>System Status:</strong> Found " . $data['total'] . " registered user(s) in your database.</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ <strong>Warning:</strong> Connection is good, but the 'users' table might not exist yet. Run your SQL script!</p>";
        }
    }
}

echo "<hr><p style='font-size: 12px; color: #777;'>Test completed at: " . date("Y-m-d H:i:s") . "</p>";
echo "<a href='index.php' style='text-decoration:none; color:#4e73df; font-weight:bold;'>← Back to Home</a>";
echo "</div>";
?>