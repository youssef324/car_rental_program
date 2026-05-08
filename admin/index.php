<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUser = $_POST["username"];
    $inputPass = $_POST["password"];

    try {
        // Enable MySQLi exceptions
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // DB connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8mb4");

        // Prepare statement
        $stmt = $conn->prepare("SELECT EmployeeID, name, PasswordHash, isAdmin FROM employees WHERE user = ?");
        $stmt->bind_param("s", $inputUser);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($empID, $name, $hash, $isAdmin);
            $stmt->fetch();

            if (password_verify($inputPass, $hash)) {
                if ($isAdmin == 1) {
                    $_SESSION['admin_name'] = $name;
                } else {
                    $message = "You do not have access to this area.";
                }
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "User not found.";
        }

        $stmt->close();
        $conn->close();
    } catch (mysqli_sql_exception $e) {
        // Log actual error and show generic message to user
        error_log("MySQL Error: " . $e->getMessage());
        $message = "Internal server error. Please try again later.";
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage());
        $message = "Unexpected error occurred.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style/LoginStyle.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php if (!isset($_SESSION['admin_name'])): ?>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
    <?php else: 
        // Re-establish connection for dashboard stats if not already done
        $conn = new mysqli($servername, $username, $password, $dbname);
        $totalCars = $conn->query("SELECT COUNT(*) FROM cars")->fetch_row()[0];
        $totalBookings = $conn->query("SELECT COUNT(*) FROM reservations")->fetch_row()[0];
        $totalCustomers = $conn->query("SELECT COUNT(*) FROM customers")->fetch_row()[0];
        $totalRevenue = $conn->query("SELECT SUM(Amount) FROM payments")->fetch_row()[0] ?? 0;
        $activeCars = $conn->query("SELECT COUNT(*) FROM cars WHERE Status = 'active'")->fetch_row()[0];
        $conn->close();
    ?>
        <div class="inner-header">
            <div class="inner-container">
        <body>
            <h1>IMPACT MAKERS Admin</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="request/cars/index.php">Cars</a></li>
                    <li><a href="request/reservations/index.php">Bookings</a></li>
                    <li><a href="request/customers/index.php">Users</a></li>
                    <a class="logout-btn" href="logout.php">logout</a>
                </ul>
            </nav>
        </div>
    </div>

    <div class="child-1 child">
        <div class="container">
            <h2>Dashboard Overview</h2>
            <div class="dashboard-grid">
                <div class="stat-card">
                    <h3>Total Cars</h3>
                    <p class="stat-value"><?php echo $totalCars; ?></p>
                    <p class="stat-label"><?php echo $activeCars; ?> Active</p>
                </div>
                <div class="stat-card">
                    <h3>Bookings</h3>
                    <p class="stat-value"><?php echo $totalBookings; ?></p>
                    <p class="stat-label">Total Reservations</p>
                </div>
                <div class="stat-card">
                    <h3>Customers</h3>
                    <p class="stat-value"><?php echo $totalCustomers; ?></p>
                    <p class="stat-label">Registered Users</p>
                </div>
                <div class="stat-card">
                    <h3>Revenue</h3>
                    <p class="stat-value">$<?php echo number_format($totalRevenue, 2); ?></p>
                    <p class="stat-label">Total Earnings</p>
                </div>
            </div>
        </div>
    </div>
    <div class="child-2 child">
        <div class="container">
            <h2>Bookings summary</h2>
            <p>Manage your car rental system efficiently.</p>
            <!-- byzhr goz2 mn site tany -->
            <iframe src="request/reservations/overview.php" frameborder="0"></iframe> 
        </div>

    <?php endif; ?>
</body>
</html>
