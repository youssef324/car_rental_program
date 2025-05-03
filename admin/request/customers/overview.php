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

    // DELETE customer
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM customers WHERE CustomerID = $id");
        $successMessage = "Customer deleted.";
    }

    // Fetch all customers
    $result = $conn->query("SELECT * FROM customers");
    $customers = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Customer Overview</title>
    <link rel="stylesheet" href="../../style/request-style.css">
</head>

<body>
    <div class="form-container">
        <h2>Customer Overview</h2>
        <?php if ($errorMessage): ?>
            <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php elseif ($successMessage): ?>
            <div class="success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($customers as $cust): ?>
                <tr>
                    <td><?= htmlspecialchars($cust['CustomerID'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($cust['FirstName'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($cust['PhoneNumber'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($cust['Email'] ?? 'N/A') ?></td>
                    <td>
                        <a href="?delete=<?= $cust['CustomerID'] ?>"
                            onclick="return confirm('Delete this customer?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>