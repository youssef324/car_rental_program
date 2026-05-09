<?php
include '../../header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = intval($_POST['customer_id']);
    $carID = intval($_POST['car_id']);
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if ($customerID && $carID && $startDate && $endDate) {
        $sql = "INSERT INTO reservations (CustomerID, CarID, StartDate, EndDate) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $customerID, $carID, $startDate, $endDate);

        if ($stmt->execute()) {
            $successMessage = "Reservation added successfully!";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}

$cars = $conn->query("SELECT CarID, Model FROM cars");
$customers = $conn->query("SELECT CustomerID, FirstName, LastName FROM customers");
?>

<div class="form-container">
    <h2>Create New Reservation</h2>

    <?php if ($errorMessage): ?>
        <div class="error"><?php echo $errorMessage; ?></div>
    <?php elseif ($successMessage): ?>
        <div class="success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form">
        <div class="form-group">
            <label for="customer_id">Select Customer</label>
            <select name="customer_id" id="customer_id" class="admin-select" required>
                <option value="">-- Choose Member --</option>
                <?php while ($row = $customers->fetch_assoc()): ?>
                    <option value="<?php echo $row['CustomerID']; ?>">
                        <?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="car_id">Select Vehicle</label>
            <select name="car_id" id="car_id" class="admin-select" required>
                <option value="">-- Choose Car --</option>
                <?php while ($row = $cars->fetch_assoc()): ?>
                    <option value="<?php echo $row['CarID']; ?>">
                        <?php echo htmlspecialchars($row['Model']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="admin-input" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="admin-input" required>
        </div>

        <button type="submit" class="admin-btn">Confirm Booking</button>
    </form>
</div>

<?php 
$conn->close();
include '../../footer.php'; 
?>