<?php

session_start();

// Database connection
$host = 'localhost';
$db   = 'users_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Prevent SQL injection
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_password);
    $stmt->fetch();

    if ($password === $db_password) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "❌ Incorrect password.";
    }
} else {
    echo "❌ Username not found.";
}

$stmt->close();
$conn->close();
?>
