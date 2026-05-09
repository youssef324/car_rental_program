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

if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $deleteSql = "DELETE FROM reservations WHERE ReservationID = $deleteId";

    if ($conn->query($deleteSql) === TRUE) {
        $deleteMessage = "Reservation deleted successfully!";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}

$sql = "SELECT r.ReservationID, c.FirstName, r.CarID, r.ReservationDate, r.StartDate, r.EndDate, r.Status, r.CarModel, r.PlateID 
        FROM reservations r
        JOIN customers c ON r.CustomerID = c.CustomerID";
$result = $conn->query($sql);
?>

<div class="form-container">
    <h2>Reservations Overview</h2>

    <?php if (isset($deleteMessage)): ?>
        <p class='success'><?php echo $deleteMessage; ?></p>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <p class='error'><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Car ID</th>
                    <th>Model</th>
                    <th>Plate</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ReservationID"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["FirstName"]) . "</td>";
                        echo "<td>" . $row["CarID"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["CarModel"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["PlateID"]) . "</td>";
                        echo "<td>" . $row["StartDate"] . "</td>";
                        echo "<td>" . $row["EndDate"] . "</td>";
                        echo "<td><span class='role-badge staff'>" . htmlspecialchars($row["Status"]) . "</span></td>";
                        echo "<td>
                                <a href='?delete_id=" . $row["ReservationID"] . "' class='btn-toggle' onclick='return confirm(\"Are you sure you want to delete this reservation?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align:center;'>No reservations found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$conn->close();
include '../../footer.php'; 
?>