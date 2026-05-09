<?php
include '../../header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";

$errorMessage = "";
$successMessage = "";

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $model = $_POST['Model'];
        $year = $_POST['Year'];
        $plate = $_POST['PlateID'];
        $type = $_POST['Type'];
        $price = $_POST['PricePerDay'];
        $image_url = $_POST['image_url'];

        $stmt = $conn->prepare("INSERT INTO cars (Model, Year, PlateID, Type, PricePerDay, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissds", $model, $year, $plate, $type, $price, $image_url);
        $stmt->execute();

        $successMessage = "Car added successfully!";
    }
} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
}
?>

<div class="form-container">
    <h2>Add New Vehicle</h2>
    
    <?php if ($errorMessage): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php elseif ($successMessage): ?>
        <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-form">
        <div class="form-group">
            <label>Model Name</label>
            <input type="text" name="Model" placeholder="e.g. Mercedes Mclaren SLR" class="admin-input" required>
        </div>
        
        <div class="form-group">
            <label>Manufacturing Year</label>
            <input type="number" name="Year" placeholder="e.g. 2024" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Plate Number</label>
            <input type="text" name="PlateID" placeholder="e.g. ABC-1234" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Vehicle Type</label>
            <select name="Type" class="admin-select" required>
                <option value="Sports">Sports</option>
                <option value="Sedan">Sedan</option>
                <option value="SUV">SUV</option>
                <option value="Hatchback">Hatchback</option>
                <option value="Luxury">Luxury</option>
            </select>
        </div>

        <div class="form-group">
            <label>Daily Rental Rate ($)</label>
            <input type="number" step="0.01" name="PricePerDay" placeholder="e.g. 500" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" placeholder="photos/car_name.png" class="admin-input" required>
        </div>

        <button type="submit" class="admin-btn">Confirm & Add Vehicle</button>
    </form>
</div>

<?php include '../../footer.php'; ?>
