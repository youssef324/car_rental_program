<?php
session_start();

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

    // DELETE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM cars WHERE CarID = $id");
        $successMessage = "Car deleted.";
    }

    // FETCH
    $result = $conn->query("SELECT * FROM cars");
    $cars = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Overview</title>
    <link rel="stylesheet" href="../../style/request-style.css">
</head>
<body>
    <div class="form-container">
        <h2>Car Overview</h2>
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php elseif ($successMessage): ?>
            <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Model</th>
                <th>Year</th>
                <th>Plate ID</th>
                <th>Type</th>
                <th>Price/Day</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?= $car['CarID'] ?></td>
                <td><?= $car['Model'] ?></td>
                <td><?= $car['Year'] ?></td>
                <td><?= $car['PlateID'] ?></td>
                <td><?= $car['Type'] ?></td>
                <td><?= $car['PricePerDay'] ?></td>
                <td>
                    <a href="?delete=<?= $car['CarID'] ?>" onclick="return confirm('Delete this car?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
