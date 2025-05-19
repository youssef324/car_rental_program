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

<!DOCTYPE html>
<html>
<head>
    <title>Add Car</title>
    <link rel="stylesheet" href="../../style/request-style.css">
</head>
<body>
    <div class="form-container">
        <h2>Add Car</h2>
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php elseif ($successMessage): ?>
            <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="Model" placeholder="Model" required>
            <input type="number" name="Year" placeholder="Year" required>
            <input type="text" name="PlateID" placeholder="Plate ID" required>
            <input type="text" name="Type" placeholder="Type" required>
            <input type="number" step="0.01" name="PricePerDay" placeholder="Price Per Day" required>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <button type="submit">Add</button>
        </form>
    </div>
</body>
</html>
