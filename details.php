<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarRentalSystem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get CarID from URL
$carID = isset($_GET['CarID']) ? intval($_GET['CarID']) : 0;

// Fetch car details from Cars table
$sqlCarDetails = "SELECT Model, Year, PricePerDay, PlateID, Type, Status FROM Cars WHERE CarID = $carID";
$result = $conn->query($sqlCarDetails);

if ($result->num_rows > 0) {
    $car = $result->fetch_assoc();
} else {
    die("Car not found.");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $conn->real_escape_string($_POST['Start-date']);
    $returnDate = $conn->real_escape_string($_POST['Return-date']);
    $cardNumber = $conn->real_escape_string($_POST['Card-Number']);
    $expirationDate = $conn->real_escape_string($_POST['Expiration-Date']);
    $cvc = $conn->real_escape_string($_POST['CVC']);
    $customerID = 1; // Replace with logged-in user's ID

    // Calculate rental duration and total amount
    $start = strtotime($startDate);
    $end = strtotime($returnDate);
    $rentalDays = ceil(($end - $start) / (60 * 60 * 24)); // Convert seconds to days
    $totalAmount = $car['PricePerDay'] * $rentalDays;

    // Insert into Reservations table
    $sqlReservation = "INSERT INTO Reservations (CustomerID, CarID, CarModel, PlateID, StartDate, EndDate, Status) 
                       VALUES ($customerID, $carID, '{$car['Model']}', '{$car['PlateID']}', '$startDate', '$returnDate', 'Reserved')";

    if ($conn->query($sqlReservation) === TRUE) {
        $reservationID = $conn->insert_id;

        // Insert into Payments table
        $sqlPayment = "INSERT INTO Payments (ReservationID, PaymentDate, Amount, PaymentMethod, CardNumber, ExpirationDate, CVC) 
                       VALUES ($reservationID, NOW(), $totalAmount, 'Credit Card', '$cardNumber', '$expirationDate', '$cvc')";

        if ($conn->query($sqlPayment) === TRUE) {
            // Update car status to "Rented"
            $sqlUpdateCar = "UPDATE Cars SET Status = 'Rented' WHERE CarID = $carID";
            if ($conn->query($sqlUpdateCar) === TRUE) {
                echo "<script>alert('Reservation confirmed! Total Payment: $$totalAmount'); window.location.href = 'homep.html';</script>";
            } else {
                echo "Error updating car status: " . $conn->error;
            }
        } else {
            echo "Error inserting payment: " . $conn->error;
        }
    } else {
        echo "Error inserting reservation: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['Model']); ?> Details</title>
    <style>
          /* General Styles */
          body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #121212;
            color: #fff;
            padding-top: 30px;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        h1, h2 {
            text-align: center;
            color: #358faa;
        }

        p {
            font-size: 1.1em;
            margin: 10px 0;
        }

        label {
            font-size: 1em;
            color: #358faa;
            margin-bottom: 5px;
            display: block;
        }

        input {
            width: 85%; /* Adjusted for smaller size */
            max-width: 200px; /* Limit maximum size */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #262626;
            color: #fff;
        }

        .date-container {
            display: flex;
            justify-content: space-between; /* Align start and return dates side by side */
            gap: 10px;
            margin-bottom: 15px;
        }

        .date-container div {
            flex: 1; /* Ensures both inputs take equal space */
        }

        .expcvc {
            display: flex;
            justify-content: space-between; /* Align start and return dates side by side */
            gap: 10px;
            margin-bottom: 15px;
            margin-right: 60px;
        }

        .cardnum{
            width: 75%;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 1.2em;
            color: #121212;
            background-color: #358faa;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #007b8f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($car['Model']); ?></h1>
        <p><strong>Year:</strong> <?php echo htmlspecialchars($car['Year']); ?></p>
        <p><strong>Price per Day:</strong> $<?php echo htmlspecialchars($car['PricePerDay']); ?></p>
        <p><strong>Plate Number:</strong> <?php echo htmlspecialchars($car['PlateID']); ?></p>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['Type']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($car['Status']); ?></p>

        <h2>Reserve this Car</h2>
        <form method="POST">
        <div class="date-container">
                <div>
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="Start-date" required>
                </div>
                <div>
                    <label for="returnDate">Return Date:</label>
                    <input type="date" id="returnDate" name="Return-date" required>
                </div>
            </div>
            
            <!-- Payment Information -->
            <label for="cardNumber">Card Number:</label>
            <input class="cardnum" type="text" id="cardNumber" name="Card-Number" maxlength="19" placeholder="1234-1234-1234-1234" required >
            <div class="expcvc">
                <div>
            <label for="expirationDate">Expiration Date:</label>
            <input type="text" id="expirationDate" name="Expiration-Date" maxlength="5" placeholder="MM/YY" required>
                </div>
                <div>
            <label for="cvc">CVC:</label>
            <input type="text" id="cvc" name="CVC" maxlength="3" placeholder="123" required>
                 </div>    
            </div>
            <button type="submit">Confirm Reservation</button>
        </form>
    </div>

    <?php
    // Process reservation and payment
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $startDate = $conn->real_escape_string($_POST['Start-date']);
        $returnDate = $conn->real_escape_string($_POST['Return-date']);
        $cardNumber = $conn->real_escape_string($_POST['Card-Number']);
        $expirationDate = $conn->real_escape_string($_POST['Expiration-Date']);
        $cvc = $conn->real_escape_string($_POST['CVC']);
        $customerID = 1; // Replace with the logged-in user's ID

        // Reconnect to database
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert reservation
        $sqlReservation = "INSERT INTO Reservations (CustomerID, CarID, CarModel, PlateID, StartDate, EndDate, Status) 
        VALUES ($customerID, $carID, '{$car['Model']}', '{$car['PlateID']}', '$startDate', '$returnDate', 'Reserved')";


        if ($conn->query($sqlReservation) === TRUE) {
            $reservationID = $conn->insert_id;

            // Insert payment
            $sqlPayment = "INSERT INTO Payments (ReservationID, PaymentDate, Amount, PaymentMethod, CardNumber, ExpirationDate, CVC) 
                           VALUES ($reservationID, NOW(), {$car['PricePerDay']}, 'Credit Card', '$cardNumber', '$expirationDate', '$cvc')";

            if ($conn->query($sqlPayment) === TRUE) {
                // Update car status
                $sqlUpdateCar = "UPDATE Cars SET Status = 'Rented' WHERE CarID = $carID";
                $conn->query($sqlUpdateCar);
                echo "<script>alert('Reservation confirmed!'); window.location.href = 'homep.html';</script>";
            } else {
                echo "Error inserting payment: " . $conn->error;
            }
        } else {
            echo "Error inserting reservation: " . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
