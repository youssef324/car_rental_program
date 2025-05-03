<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteSql = "DELETE FROM reservations WHERE ReservationID = $deleteId";

    if ($conn->query($deleteSql) === TRUE) {
        $deleteMessage = "Reservation deleted successfully!";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}

// Fetch all reservations and join with the customers table to get the FirstName
$sql = "SELECT r.ReservationID, c.FirstName, r.CarID, r.ReservationDate, r.StartDate, r.EndDate, r.Status, r.CarModel, r.PlateID 
        FROM reservations r
        JOIN customers c ON r.CustomerID = c.CustomerID";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations Overview</title>
    <link rel="stylesheet" href="./admin/style/request-style.css">
</head>

<style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f0f2f5;
        padding: 20px;
    }

    .form-container {
        max-width: 1000px;
        margin: auto;
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    input,
    button {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-size: 16px;
    }

    button {
        background-color: #007aff;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #005bb5;
    }

    .error {
        background: #ffe0e0;
        padding: 10px;
        border-left: 4px solid red;
        margin-bottom: 10px;
    }

    .success {
        background: #e0ffe0;
        padding: 10px;
        border-left: 4px solid green;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th,
    table td {
        padding: 12px;
        border-bottom: 1px solid #ccc;
        text-align: left;
    }

    table a {
        color: red;
        text-decoration: none;
    }
</style>

<body>
    <div class="form-container">
        <h1>Reservations Overview</h1>

        <?php
        if (isset($deleteMessage)) {
            echo "<p class='success'>$deleteMessage</p>";
        }
        if (isset($errorMessage)) {
            echo "<p class='error'>$errorMessage</p>";
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Customer Name</th>
                    <th>Car ID</th>
                    <th>Reservation Date</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Car Model</th>
                    <th>Plate ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ReservationID"] . "</td>";
                        echo "<td>" . $row["FirstName"] . "</td>";
                        echo "<td>" . $row["CarID"] . "</td>";
                        echo "<td>" . $row["ReservationDate"] . "</td>";
                        echo "<td>" . $row["StartDate"] . "</td>";
                        echo "<td>" . $row["EndDate"] . "</td>";
                        echo "<td>" . $row["Status"] . "</td>";
                        echo "<td>" . $row["CarModel"] . "</td>";
                        echo "<td>" . $row["PlateID"] . "</td>";
                        echo "<td>
                                 
                                <a href='?delete_id=" . $row["ReservationID"] . "' onclick='return confirm(\"Are you sure you want to delete this reservation?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No reservations found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
// Close connection
$conn->close();
?>