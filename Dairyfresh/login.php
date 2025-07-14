<?php
session_start();
$conn = new mysqli("localhost", "root", "", "dairy_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['user_type'] = $user['user_type'];

        // Redirect based on user type
        if ($user['user_type'] === 'admin') {
            header("Location: admin.php");
        } elseif ($user['user_type'] === 'supplier') {
            header("Location: supplier.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dairy Fresh - Login</title>
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

<section class="login" style="text-align:center; padding:30px;">
    <h2>Login to Dairy Fresh</h2>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST" style="max-width:400px; margin:0 auto;">
        <input type="email" name="email" placeholder="Email address" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" class="btn">Login</button>
    </form>

    <p style="margin-top:15px;">Not registered? <a href="register.php">Register here</a></p>
</section>

<footer>
    <p>&copy; 2025 Dairy Fresh. All rights reserved.</p>
</footer>

</body>
</html>
