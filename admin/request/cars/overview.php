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

    
    if (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $conn->query("DELETE FROM cars WHERE CarID = $id");
        $successMessage = "Car deleted.";
    }

    
    $result = $conn->query("SELECT * FROM cars");
    $cars = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
}
?>

<div class="form-container">
    <h2>Car Overview</h2>
    <?php if ($errorMessage): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php elseif ($successMessage): ?>
        <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Model</th>
                <th>Year</th>
                <th>Plate ID</th>
                <th>Type</th>
                <th>Price/Day</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?= $car['CarID'] ?></td>
                <td>
                    <?php 
                    $img = $car['image_url'];
                    if (strpos($img, 'http') !== 0) {
                        $img = "../../../" . $img;
                    }
                    ?>
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($car['Model']) ?>" style="width: 80px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                </td>
                <td><?= htmlspecialchars($car['Model']) ?></td>
                <td><?= $car['Year'] ?></td>
                <td><?= htmlspecialchars($car['PlateID']) ?></td>
                <td><?= htmlspecialchars($car['Type']) ?></td>
                <td>$<?= number_format($car['PricePerDay']) ?></td>
                <td><span class="role-badge <?= strtolower($car['Status'] ?? 'active') ?>"><?= htmlspecialchars($car['Status'] ?? 'Active') ?></span></td>
                <td>
                    <a href="edit.php?id=<?= $car['CarID'] ?>" class="btn-manage" style="padding: 6px 12px; font-size: 0.8rem; margin-right: 5px;">Manage</a>
                    <a href="?delete=<?= $car['CarID'] ?>" class="btn-toggle" style="padding: 6px 12px; font-size: 0.8rem;" onclick="return confirm('Delete this car?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../footer.php'; ?>
