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
        $conn->query("DELETE FROM customers WHERE CustomerID = $id");
        $successMessage = "Customer deleted successfully.";
    }

    $result = $conn->query("SELECT * FROM customers");
    $customers = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
}
?>

<div class="form-container">
    <h2>Customer Management</h2>

    <?php if ($errorMessage): ?>
        <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php elseif ($successMessage): ?>
        <div class="success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $cust): ?>
                <tr>
                    <td><?= htmlspecialchars($cust['CustomerID'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($cust['FirstName'] . ' ' . ($cust['LastName'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($cust['phoneNumber'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($cust['Email'] ?? 'N/A') ?></td>
                    <td>
                        <a href="edit.php?id=<?= $cust['CustomerID'] ?>" class="btn-manage" style="padding: 6px 12px; font-size: 0.8rem; margin-right: 5px;">Edit</a>
                        <a href="?delete=<?= $cust['CustomerID'] ?>" class="btn-toggle" style="padding: 6px 12px; font-size: 0.8rem;" onclick="return confirm('Delete this customer?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../footer.php'; ?>