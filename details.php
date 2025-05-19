<?php
session_start(); // Start session to access logged-in user

// Redirect if not logged in
if (!isset($_SESSION['CustomerID'])) {
    header("Location: login.php");
    exit();
}

$customerID = $_SESSION['CustomerID']; // Get logged-in customer ID

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";

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
    $EndDate = $conn->real_escape_string($_POST['Return-date']);
    $cardNumber = $conn->real_escape_string($_POST['Card-Number']);
    $expirationDate = $conn->real_escape_string($_POST['Expiration-Date']);
    $cvc = $conn->real_escape_string($_POST['CVC']);

    // Calculate rental duration and total amount
    $start = strtotime($startDate);
    $end = strtotime($returnDate);
    $rentalDays = ceil(($end - $start) / (60 * 60 * 24));
    $totalAmount = $car['PricePerDay'] * $rentalDays;

    // Insert into Reservations table
    $sqlReservation = "INSERT INTO reservations (CustomerID, CarID, CarModel, PlateID, StartDate, EndDate, Status) 
    VALUES ($customerID, $carID, '{$car['Model']}', '{$car['PlateID']}', '$startDate', '$EndDate', 'Reserved')";


    if ($conn->query($sqlReservation) === TRUE) {
        $newReservationID = $conn->insert_id;

        // Insert into Payments table
        $sqlPayment = "INSERT INTO Payments (ReservationID, PaymentDate, Amount, PaymentMethod, CardNumber, ExpirationDate, CVC) 
                       VALUES ($newReservationID, NOW(), $totalAmount, 'Credit Card', '$cardNumber', '$expirationDate', '$cvc')";

        if ($conn->query($sqlPayment) === TRUE) {
            // Update car status to "Rented"
            $sqlUpdateCar = "UPDATE Cars SET Status = 'Rented' WHERE CarID = $carID";
            if ($conn->query($sqlUpdateCar) === TRUE) {
                echo "<script>alert('Reservation confirmed! Total Payment: $$totalAmount'); window.location.href = 'contacts.html';</script>";
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

        h1,
        h2 {
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
            width: 85%;
            /* Adjusted for smaller size */
            max-width: 200px;
            /* Limit maximum size */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #262626;
            color: #fff;
        }

        .date-container {
            display: flex;
            justify-content: space-between;
            /* Align start and return dates side by side */
            gap: 10px;
            margin-bottom: 15px;
        }

        .date-container div {
            flex: 1;
            /* Ensures both inputs take equal space */
        }

        .expcvc {
            display: flex;
            justify-content: space-between;
            /* Align start and return dates side by side */
            gap: 10px;
            margin-bottom: 15px;
            margin-right: 60px;
        }

        .cardnum {
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

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* Place the video behind other content */
            object-fit: cover;
            /* Ensure the video covers the entire screen */
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            /* Add spacing between buttons */
        }

        .button-container button {
            flex: 1;
            /* Ensure both buttons take equal space */
        }

        .sidebar {
            position: fixed;
            top: 12%;
            left: 0;
            width: 65px;
            height: 80%;
            background: linear-gradient(135deg, #358faa, #007b8f);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            font-size: 16px;
            transition: width 0.3s;
        }

        .sidebar:hover {
            width: 100px;
            /* Expand on hover */
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            margin: 20px -10px;
            display: block;
            text-align: center;
            width: 100%;
            transition: background-color 0.3s, transform 0.3s;
            padding: 10px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <nav class="nav">
            <a href="./profile.php" title="My Profile">
                <!-- mlayet ba2y el byanat fel data base 3yza page php -->
                <img src="Photos/account.png" alt="My Account" />
            </a>
            <a href="./Dashboard.php" title="Cars">
                <img src="Photos/car.png" alt="Cars" />
            </a>
            <a href="./contacts.html" title="Contacts">
                <img src="Photos/mail.png" alt="Contact Us" />
            </a>
            <a href="./about_us.html" title="About Us">
                <img src="Photos/about.png" alt="About Us" />
            </a>
            <a href="./index.html" title="Log Out">
                <img src="Photos/logout.png" alt="Log Out" />
            </a>
        </nav>
    </div>

    <div>
        <!-- Video Background -->
        <video class="video-background" autoplay loop muted>
            <source src="photos/bmw.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="container">
        <h1><?php echo htmlspecialchars($car['Model']); ?></h1>
        <p><strong>Year:</strong> <?php echo htmlspecialchars($car['Year']); ?></p>
        <p><strong>Price per Day:</strong> $<span
                id="pricePERDAY"><?php echo htmlspecialchars($car['PricePerDay']); ?></span></p>
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
            <input class="cardnum" type="text" id="cardNumber" name="Card-Number" maxlength="16"
                placeholder="1234-1234-1234-1234">
            <div class="expcvc">
                <div>
                    <label for="expirationDate">Expiration Date:</label>
                    <input type="text" id="expirationDate" name="Expiration-Date" maxlength="5" placeholder="MM/YY">
                </div>
                <div>
                    <label for="cvc">CVC:</label>
                    <input type="text" id="cvc" name="CVC" maxlength="3" placeholder="123">
                </div>
            </div>
            <p id="totalAmountFixed"><strong>Total Amount:</strong></p>
            <p><strong></strong> <span id="rentalDuration"></span></p>
            <div class="button-container">
                <button type="button" onclick="window.location.href='Dashboard.php'">Cancel</button>
                <button type="submit" onclick="validateVisa()">Confirm Reservation</button>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const startInput = document.getElementById('startDate');
                    const returnInput = document.getElementById('returnDate');
                    const result = document.getElementById('rentalDuration');
                    const pricePERDAY = document.getElementById('pricePERDAY').textContent;
                    const totalAmountFixed = document.getElementById('totalAmountFixed');

                    function calculateDays() {
                        const startDate = startInput.value;
                        const returnDate = returnInput.value;

                        if (!startDate || !returnDate) {
                            result.textContent = '';
                            return;
                        }

                        const start = new Date(startDate);
                        const end = new Date(returnDate);
                        const diffTime = end - start;
                        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        if (diffDays < 0) {
                            result.textContent = 'Return date must be after start date.';
                        } else {
                            result.textContent = `Number of days: ${diffDays}  day(s)`;

                        }

                        totalAmountFixed.textContent = `Total amount to pay: ${diffDays * pricePERDAY} $`;

                    }

                    startInput.addEventListener('input', calculateDays);
                    returnInput.addEventListener('input', calculateDays);
                });
            </script>

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
        $sqlReservation = "INSERT INTO Reservations (CustomerID, CarID, CarModel, PlateID, StartDate, returnDate, Status) 
        VALUES ($customerID, $carID, '{$car['Model']}', '{$car['PlateID']}', '$startDate', '$returnDate', 'Reserved')";


        if ($conn->query($sqlReservation) === TRUE) {
            $ReservationID = $conn->insert_id;

            // Insert payment
            $sqlPayment = "INSERT INTO Payments (ReservationID, PaymentDate, Amount, PaymentMethod, CardNumber, ExpirationDate, CVC) 
                           VALUES ($ReservationID, NOW(), {$car['PricePerDay']}, 'Credit Card', '$cardNumber', '$expirationDate', '$cvc')";

            if ($conn->query($sqlPayment) === TRUE) {
                // Update car status
                $sqlUpdateCar = "UPDATE Cars SET Status = 'Rented' WHERE CarID = $carID";
                $conn->query($sqlUpdateCar);    //redirect 3la page contact lma tt3amal el reservation
    
                echo "<script>alert('Reservation confirmed!'); window.location.href = 'Dashboard.php';</script>";
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