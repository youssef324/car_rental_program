<?php
include '../../header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$errorMessage = "";
$successMessage = "";
$car = null;

// FETCH CURRENT CAR DATA
if (isset($_GET['id'])) {
    $carID = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM cars WHERE CarID = ?");
    $stmt->bind_param("i", $carID);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();
    $stmt->close();
}

// HANDLE UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_car'])) {
    $id = (int)$_POST['CarID'];
    $model = $_POST['Model'];
    $year = (int)$_POST['Year'];
    $plate = $_POST['PlateID'];
    $type = $_POST['Type'];
    $price = (float)$_POST['PricePerDay'];
    $status = $_POST['Status'];
    $image_url = $_POST['image_url'];

    try {
        $stmt = $conn->prepare("UPDATE cars SET Model=?, Year=?, PlateID=?, Type=?, PricePerDay=?, Status=?, image_url=? WHERE CarID=?");
        $stmt->bind_param("sisssdsi", $model, $year, $plate, $type, $price, $status, $image_url, $id);
        $stmt->execute();
        $successMessage = "Vehicle details updated successfully!";
        
        // Refresh car data
        $stmt = $conn->prepare("SELECT * FROM cars WHERE CarID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $car = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } catch (Exception $e) {
        $errorMessage = "Update failed: " . $e->getMessage();
    }
}
?>

<div class="form-container">
    <h2>Edit Vehicle Details</h2>
    
    <?php if ($errorMessage): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php elseif ($successMessage): ?>
        <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($car): ?>
    <div style="text-align: center; margin-bottom: 25px;">
        <?php 
        $img = $car['image_url'];
        if (strpos($img, 'http') !== 0) {
            $img = "../../../" . $img;
        }
        ?>
        <img src="<?php echo $img; ?>" alt="Car Preview" style="width: 200px; height: 120px; object-fit: cover; border-radius: 16px; border: 2px solid #358faa; box-shadow: 0 10px 20px rgba(53, 143, 170, 0.3);">
    </div>
    <form method="POST" class="admin-form">
        <input type="hidden" name="CarID" value="<?php echo $car['CarID']; ?>">
        
        <div class="form-group">
            <label>Model Name</label>
            <input type="text" name="Model" value="<?php echo htmlspecialchars($car['Model']); ?>" class="admin-input" required>
        </div>
        
        <div class="form-group">
            <label>Year</label>
            <input type="number" name="Year" value="<?php echo $car['Year']; ?>" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Plate ID</label>
            <input type="text" name="PlateID" value="<?php echo htmlspecialchars($car['PlateID']); ?>" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Type</label>
            <select name="Type" class="admin-select" required>
                <option value="Sports" <?php if($car['Type']=='Sports') echo 'selected'; ?>>Sports</option>
                <option value="Sedan" <?php if($car['Type']=='Sedan') echo 'selected'; ?>>Sedan</option>
                <option value="SUV" <?php if($car['Type']=='SUV') echo 'selected'; ?>>SUV</option>
                <option value="Hatchback" <?php if($car['Type']=='Hatchback') echo 'selected'; ?>>Hatchback</option>
                <option value="Luxury" <?php if($car['Type']=='Luxury') echo 'selected'; ?>>Luxury</option>
            </select>
        </div>

        <div class="form-group">
            <label>Daily Rate ($)</label>
            <input type="number" step="0.01" name="PricePerDay" value="<?php echo $car['PricePerDay']; ?>" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="Status" class="admin-select" required>
                <option value="active" <?php if($car['Status']=='active') echo 'selected'; ?>>Active</option>
                <option value="rented" <?php if($car['Status']=='rented') echo 'selected'; ?>>Rented</option>
                <option value="inactive" <?php if($car['Status']=='inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label>Image Path</label>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($car['image_url']); ?>" class="admin-input" required>
        </div>

        <button type="submit" name="update_car" class="admin-btn">Save Changes</button>
        <a href="overview.php" class="btn-toggle" style="display:block; text-align:center; margin-top:15px; background: rgba(255,255,255,0.05);">Cancel & Back</a>
    </form>
    <?php else: ?>
        <div class="error">Vehicle not found.</div>
        <a href="overview.php" class="admin-btn">Back to Overview</a>
    <?php endif; ?>
</div>

<?php 
$conn->close();
include '../../footer.php'; 
?>
