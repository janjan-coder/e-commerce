<?php
// Only run if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DB config
    $host = 'localhost';
    $db   = 'users_db';
    $user = 'root';
    $pass = '';

    // Connect to DB
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users_db WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "❌ Username already taken.";
    } else {
        // Insert new user with plain password
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "✅ Registration successful. <a href='login.php'>Login here</a>";
        } else {
            echo "❌ Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <link rel="stylesheet" href="your-style.css"> <!-- optional -->
</head>
<body>
    <div class="container" id="FormRegister">
        <h2>Create a New Account</h2>

        <?php if (!empty($message)) echo "<p>$message</p>"; ?>

        <form action="createUser.php" method="post" class="my-2">
            <label class="form-label">Username</label>
            <input name="username" class="form-control-md rounded" placeholder="username" required><br>

            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control-md rounded" placeholder="password" required><br>

            <button type="submit" class="rounded mx-auto d-block">Register</button>
            <a href="login.php">Already have an account? Login</a>
        </form>
    </div>
</body>
</html>
