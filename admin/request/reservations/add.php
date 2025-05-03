<?php
// add.php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = intval($_POST['customer_id']);
    $carID = intval($_POST['car_id']);
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if ($customerID && $carID && $startDate && $endDate) {
        $sql = "INSERT INTO reservations (CustomerID, CarID, StartDate, EndDate) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $customerID, $carID, $startDate, $endDate);

        if ($stmt->execute()) {
            $successMessage = "Reservation added successfully!";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}

// Fetch cars and customers for dropdowns
$cars = $conn->query("SELECT CarID, Model FROM cars");
$customers = $conn->query("SELECT CustomerID, FirstName, LastName FROM customers");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Reservation</title>
    <link rel="stylesheet" href="../style/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-top: 10px;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Add New Reservation</h2>

        <?php if ($errorMessage): ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
        <?php elseif ($successMessage): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="customer_id">Customer:</label>
            <select name="customer_id" id="customer_id" required>
                <option value="">Select Customer</option>
                <?php while ($row = $customers->fetch_assoc()): ?>
                    <option value="<?php echo $row['CustomerID']; ?>">
                        <?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="car_id">Car:</label>
            <select name="car_id" id="car_id" required>
                <option value="">Select Car</option>
                <?php while ($row = $cars->fetch_assoc()): ?>
                    <option value="<?php echo $row['CarID']; ?>">
                        <?php echo htmlspecialchars($row['Model']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" required>

            <button type="submit">Add Reservation</button>
        </form>
    </div>
</body>

</html>