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
$cust = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM customers WHERE CustomerID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $cust = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_customer'])) {
    $id = (int)$_POST['CustomerID'];
    $fname = $_POST['FirstName'];
    $lname = $_POST['LastName'];
    $email = $_POST['Email'];
    $phone = $_POST['phoneNumber'];

    try {
        $stmt = $conn->prepare("UPDATE customers SET FirstName=?, LastName=?, Email=?, phoneNumber=? WHERE CustomerID=?");
        $stmt->bind_param("ssssi", $fname, $lname, $email, $phone, $id);
        $stmt->execute();
        $successMessage = "Customer updated successfully!";
        
        $stmt = $conn->prepare("SELECT * FROM customers WHERE CustomerID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $cust = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } catch (Exception $e) {
        $errorMessage = "Update failed: " . $e->getMessage();
    }
}
?>

<div class="form-container">
    <h2>Edit Customer</h2>
    
    <?php if ($errorMessage): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php elseif ($successMessage): ?>
        <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($cust): ?>
    <form method="POST" class="admin-form">
        <input type="hidden" name="CustomerID" value="<?php echo $cust['CustomerID']; ?>">
        
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="FirstName" value="<?php echo htmlspecialchars($cust['FirstName']); ?>" class="admin-input" required>
        </div>
        
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="LastName" value="<?php echo htmlspecialchars($cust['LastName'] ?? ''); ?>" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="Email" value="<?php echo htmlspecialchars($cust['Email']); ?>" class="admin-input" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phoneNumber" value="<?php echo htmlspecialchars($cust['phoneNumber'] ?? ''); ?>" class="admin-input" required>
        </div>

        <button type="submit" name="update_customer" class="admin-btn">Save Changes</button>
        <a href="overview.php" class="btn-toggle" style="display:block; text-align:center; margin-top:15px;">Back to List</a>
    </form>
    <?php else: ?>
        <div class="error">Customer not found.</div>
    <?php endif; ?>
</div>

<?php 
$conn->close();
include '../../footer.php'; 
?>
