<script type="text/javascript">
    var gk_isXlsx = false;
    var gk_xlsxFileLookup = {};
    var gk_fileData = {};
    function filledCell(cell) {
        return cell !== '' && cell != null;
    }
    function loadFileData(filename) {
        if (gk_isXlsx && gk_xlsxFileLookup[filename]) {
            try {
                var workbook = XLSX.read(gk_fileData[filename], { type: 'base64' });
                var firstSheetName = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[firstSheetName];

                // Convert sheet to JSON to filter blank rows
                var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1, blankrows: false, defval: '' });
                // Filter out blank rows (rows where all cells are empty, null, or undefined)
                var filteredData = jsonData.filter(row => row.some(filledCell));

                // Heuristic to find the header row by ignoring rows with fewer filled cells than the next row
                var headerRowIndex = filteredData.findIndex((row, index) =>
                    row.filter(filledCell).length >= filteredData[index + 1]?.filter(filledCell).length
                );
                // Fallback
                if (headerRowIndex === -1 || headerRowIndex > 25) {
                    headerRowIndex = 0;
                }

                // Convert filtered JSON back to CSV
                var csv = XLSX.utils.aoa_to_sheet(filteredData.slice(headerRowIndex)); // Create a new sheet from filtered array of arrays
                csv = XLSX.utils.sheet_to_csv(csv, { header: 1 });
                return csv;
            } catch (e) {
                console.error(e);
                return "";
            }
        }
        return gk_fileData[filename] || "";
    }
</script>
<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = new mysqli("localhost", "root", "", "dairy_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}

$suppliers = $conn->query("SELECT * FROM supplier");
$items = $conn->query("SELECT * FROM items");
$orders = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dairy Fresh</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Dairy Fresh - Admin</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="admin-panel">
        <h2>Admin Panel</h2>
        <h3>Manage Milk Rates</h3>
        <form action="update_rates.php" method="POST">
            <select name="item_id">
                <?php while ($item = $items->fetch_assoc()): ?>
                <option value="<?php echo $item['id']; ?>">
                    <?php echo htmlspecialchars($item['name']); ?>
                </option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="price" placeholder="New Price" required>
            <button type="submit" class="btn">Update Price</button>
        </form>

        <h3>Manage Inventory</h3>
        <form action="update_inventory.php" method="POST">
            <select name="item_id">
                <?php $items->data_seek(0); while ($item = $items->fetch_assoc()): ?>
                <option value="<?php echo $item['id']; ?>">
                    <?php echo htmlspecialchars($item['name']); ?>
                </option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="item_quan" placeholder="New Quantity" required>
            <button type="submit" class="btn">Update Inventory</button>
        </form>

        <h3>Manage Suppliers</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
            </tr>
            <?php while ($supplier = $suppliers->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php echo $supplier['id']; ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($supplier['name']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($supplier['phoneNo']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($supplier['email']); ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>View Reports</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Status</th>
            </tr>
            <?php while ($order = $orders->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php echo $order['id']; ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($order['customer_name']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($order['item_name']); ?>
                </td>
                <td>
                    <?php echo $order['quantity']; ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($order['status']); ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <footer>
        <p>&copy; 2025 Dairy Fresh. All rights reserved.</p>
    </footer>
</body>

</html>