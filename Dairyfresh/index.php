<?php
session_start();
$conn = new mysqli("localhost", "root", "", "dairy_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$products = [];
$result = $conn->query("SELECT * FROM items");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Fresh - Milk Delivery</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <nav class="navbar">
        <!-- ✅ Logo linking to home -->
        <div class="logo"><a href="index.php" style="text-decoration:none; color:inherit;">Dairy Fresh</a></div>
        <ul class="nav-links">
            <li><a href="#hero">Home</a></li>
            <li><a href="#products">Products</a></li>
            <li><a href="#contact">Contact Us</a></li>
            <li><a href="#cart">Cart (<span id="cart-count">0</span>)</a></li>
            
            <!-- ✅ login/logout based on session -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Login/Register</a></li>
            <?php endif; ?>

            <!-- ✅ panel link based on user role -->
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                <li><a href="admin.php">Admin Panel</a></li>
            <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'supplier'): ?>
                <li><a href="supplier.php">Supplier Panel</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- ✅ Hero Banner -->
<section id="hero" style="text-align:center; padding: 30px;">
    <img src="images/banner1.jpg" alt="DairyFresh Offer" style="max-width:100%; border-radius: 10px;">
</section>

<!-- ✅ Products -->
<section id="products" class="products">
    <h2>Our Products</h2>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: auto;">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['desc']); ?></p>
                <p>Weight: <?php echo htmlspecialchars($product['weight']); ?></p>
                <p>Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
                <button class="btn add-to-cart"
                        data-id="<?php echo $product['id']; ?>"
                        data-name="<?php echo htmlspecialchars($product['name']); ?>"
                        data-price="<?php echo $product['price']; ?>">
                    Add to Cart
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ✅ Cart -->
<section id="cart" class="cart">
    <h2>Your Cart</h2>
    <div id="cart-items"></div>
    <p>Total: ₹<span id="cart-total">0</span></p>
    <button class="btn" id="checkout-btn">Proceed to Checkout</button>
</section>

<!-- ✅ Contact Form -->
<section id="contact" class="contact">
    <h2>Contact Us</h2>
    <form id="contact-form" action="contact.php" method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phoneno" placeholder="Phone Number" required>
        <textarea name="feed" placeholder="Your Feedback" required></textarea>
        <button type="submit" class="btn">Submit</button>
    </form>
</section>

<!-- ✅ Delivery -->
<section id="delivery" class="delivery">
    <h2>Delivery Options</h2>
    <form id="delivery-form">
        <label for="delivery-slot">Select Delivery Slot:</label>
        <select id="delivery-slot" name="delivery_slot">
            <option value="morning">Morning (6 AM - 8 AM)</option>
            <option value="evening">Evening (6 PM - 8 PM)</option>
        </select>
        <button type="submit" class="btn">Confirm Delivery</button>
    </form>
</section>

<!-- ✅ Payment -->
<section id="payment" class="payment">
    <h2>Payment Options</h2>
    <form id="payment-form">
        <label for="payment-method">Payment Method:</label>
        <select id="payment-method" name="payment_method">
            <option value="card">Credit/Debit Card</option>
            <option value="upi">UPI</option>
            <option value="cod">Cash on Delivery</option>
        </select>
        <div id="card-details" class="hidden">
            <input type="text" id="card-number" placeholder="Card Number" >
            <input type="text" id="card-expiry" placeholder="MM/YY" >
            <input type="text" id="card-cvc" placeholder="CVC" >
        </div>
        <div id="upi-details" class="hidden">
            <input type="text" id="upi-id" placeholder="UPI ID" >
        </div>
        <button type="submit" class="btn">Pay Now</button>
    </form>
</section>

<!-- ✅ Footer -->
<footer>
    <p>&copy; 2025 Dairy Fresh. All rights reserved.</p>
    <ul>
        <li><a href="privacy.php">Privacy Policy</a></li>
        <li><a href="terms.php">Terms & Conditions</a></li>
        <li><a href="#contact">Contact Us</a></li>
    </ul>
</footer>

<script src="script.js"></script>
</body>
</html>
