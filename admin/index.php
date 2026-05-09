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
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8mb4");

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
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// IF NOT LOGGED IN - SHOW LOGIN PAGE
if (!isset($_SESSION['admin_name'])):
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Impact Makers</title>
    <link rel="stylesheet" href="style_admin/LoginStyle.css">
</head>
<body>
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
</body>
</html>

<?php 
else: 

    $conn = new mysqli($servername, $username, $password, $dbname);
    

    if (isset($_GET['toggle_admin'])) {
        $empID = (int)$_GET['toggle_admin'];
        $conn->query("UPDATE employees SET isAdmin = 1 - isAdmin WHERE EmployeeID = $empID");
        header("Location: index.php");
        exit();
    }

    $totalCars = $conn->query("SELECT COUNT(*) FROM cars")->fetch_row()[0];
    $totalEmployees = $conn->query("SELECT COUNT(*) FROM employees")->fetch_row()[0];
    $totalOffices = $conn->query("SELECT COUNT(*) FROM offices")->fetch_row()[0];
    $totalRevenue = $conn->query("SELECT SUM(Amount) FROM payments")->fetch_row()[0] ?? 0;
    $activeCars = $conn->query("SELECT COUNT(*) FROM cars WHERE Status = 'active'")->fetch_row()[0];

    include 'header.php'; 
?>
        
    <header class="main-header">
        <div class="header-top">
            <p>IMPACT MAKERS ADMIN</p>
            <h1 class="welcome-msg">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
        </div>
        
        <div class="admin-filters">
            <input type="text" placeholder="Search fleet..." class="admin-search" id="adminSearch" />
        </div>
    </header>

    <section class="stats-section">
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total Employees</h3>
                <p class="stat-value"><?php echo $totalEmployees; ?></p>
                <p class="stat-label">System Staff</p>
            </div>
            <div class="stat-card">
                <h3>Total Offices</h3>
                <p class="stat-value"><?php echo $totalOffices; ?></p>
                <p class="stat-label">Service Locations</p>
            </div>
            <div class="stat-card">
                <h3>Fleet Size</h3>
                <p class="stat-value"><?php echo $totalCars; ?></p>
                <p class="stat-label"><?php echo $activeCars; ?> Active Vehicles</p>
            </div>
            <div class="stat-card">
                <h3>Total Money</h3>
                <p class="stat-value">$<?php echo number_format($totalRevenue, 0); ?></p>
                <p class="stat-label">Total Revenue</p>
            </div>
        </div>
    </section>

    <section class="employee-management">
        <div class="section-title">
            <h2>Manage Staff & Admins</h2>
        </div>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $employeesResult = $conn->query("SELECT * FROM employees");
                    while ($emp = $employeesResult->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($emp['name']); ?></td>
                        <td><?php echo htmlspecialchars($emp['user']); ?></td>
                        <td>
                            <span class="role-badge <?php echo $emp['isAdmin'] ? 'admin' : 'staff'; ?>">
                                <?php echo $emp['isAdmin'] ? 'Admin' : 'Staff'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?toggle_admin=<?php echo $emp['EmployeeID']; ?>" class="btn-toggle">
                                <?php echo $emp['isAdmin'] ? 'Revoke Admin' : 'Assign Admin'; ?>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="fleet-overview">
        <div class="section-title">
            <h2>Fleet Visual Overview</h2>
            <a href="request/cars/add.php" class="add-btn">+ Add Car</a>
        </div>
        <div class="car-grid">
            <?php
            $carsResult = $conn->query("SELECT * FROM cars");
            if ($carsResult->num_rows > 0):
                while ($car = $carsResult->fetch_assoc()):
            ?>
            <div class="car-card" data-model="<?php echo strtolower($car['Model']); ?>">
                <div class="car-badge <?php echo strtolower($car['Status'] ?? 'active'); ?>">
                    <?php echo ucfirst($car['Status'] ?? 'Active'); ?>
                </div>
                <?php 
                $img_src = $car['image_url'];
                if (strpos($img_src, 'http') !== 0) {
                    $img_src = "../" . $img_src;
                }
                ?>
                <img src="<?php echo $img_src; ?>" alt="<?php echo $car['Model']; ?>" class="car-image">
                <div class="car-info">
                    <h3><?php echo $car['Model']; ?></h3>
                    <div class="car-meta">
                        <span>📅 <?php echo $car['Year']; ?></span>
                        <span>💰 $<?php echo number_format($car['PricePerDay']); ?>/day</span>
                    </div>
                    <div class="car-actions">
                        <a href="request/cars/edit.php?id=<?php echo $car['CarID']; ?>" class="btn-manage">Manage</a>
                        <a href="request/cars/overview.php?delete=<?php echo $car['CarID']; ?>" class="btn-delete" onclick="return confirm('Delete this vehicle?')">🗑️</a>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
            endif;
            $conn->close();
            ?>
        </div>
    </section>

    <script>
        document.getElementById('adminSearch').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            const cards = document.querySelectorAll('.car-card');
            cards.forEach(card => {
                const model = card.dataset.model;
                card.style.display = model.includes(search) ? 'block' : 'none';
            });
        });
    </script>
<?php 
    include 'footer.php';
endif; 
?>
