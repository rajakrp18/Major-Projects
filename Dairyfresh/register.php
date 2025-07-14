<?php
session_start();
$conn = new mysqli("localhost", "root", "", "dairy_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phoneno']);
    $password = trim($_POST['password']);
    $job = trim($_POST['job']);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "❌ Email already registered. Please use another.";
    } else {
        // Insert user (note: store hashed password in real apps)
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $job);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $message = "❌ Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Dairy Fresh</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo"><a href="index.php" style="text-decoration:none; color:inherit;">Dairy Fresh</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
</header>

<section class="register" style="text-align:center; padding: 30px;">
    <h2>Create Your Account</h2>

    <?php if (!empty($message)): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST" style="max-width:400px; margin:0 auto;">
        <input type="text" name="name" placeholder="Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="phoneno" placeholder="Phone Number" required><br><br>
        <select name="job" required>
            <option value="" disabled selected>Choose Role</option>
            <option value="customer">Customer</option>
            <option value="supplier">Supplier</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" class="btn">Register</button>
    </form>

    <p style="margin-top:15px;">Already registered? <a href="login.php">Login here</a></p>
</section>

<footer>
    <p>&copy; 2025 Dairy Fresh. All rights reserved.</p>
</footer>

</body>
</html>
