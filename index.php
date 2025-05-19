<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #358faa;
            display: flex;
            height: 100vh;
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }

        .left-side {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }

        .right-side {
            background-color: #121212;
            width: 35%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .right-side img {
            width: 100%;
            height: auto;
            max-height: 100%;
            object-fit: cover;
        }

        .login-box {
            padding: 2em;
            border-radius: 10px;
            width: 350px;
            background-color: #1e1e1e;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            text-align: center;
            margin-top: 200px;
        }

        .login-box h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #358faa;
        }

        .login-box label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            color: #358faa;
        }

        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            background-color: #262626;
            color: #358faa;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .login-box button[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 1.1em;
            color: #121212;
            background-color: #358faa;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 15px 0;
        }

        .login-box button[type="submit"]:hover {
            opacity: 0.8;
        }

        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #358faa;
            font-size: 0.9em;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background-color: #333;
            margin: 0 10px;
        }

        .divider a {
            color: whitesmoke;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php
    session_start(); // Start the session
    
    // Initialize variables
    $errorMessage = "";

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "carrentalsystem";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        // Query to check user existence
        $sql = "SELECT * FROM Customers WHERE Email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['PasswordHash'])) {
                $_SESSION['CustomerID'] = $user['CustomerID']; // Store user ID
                header("Location: Dashboard.php"); // Redirect to dashboard
                exit();
            } else {
                $errorMessage = "Incorrect Password or Email";
            }
        } else {
            $errorMessage = "No Account Found!";
        }
    }

    $conn->close();
    ?>

    <video class="video-background" autoplay loop muted>
        <source src="photos/bmw.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="left-side">
        <div class="login-box">
            <h2>Welcome!</h2>
            <?php
            if (!empty($errorMessage)) {
                echo "<p style='color: red;'>$errorMessage</p>";
            }
            ?>
            <form method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="name@example.com" />
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="********" />
                <button type="submit" onclick="isValid()">Login</button>
            </form>
            <div class="divider">
                <span><br>Don't have an account? <a href="signup.php"><br>Sign up</a></span>
            </div>
        </div>
    </div>

    <script src="./main.js">
        if (validateEmail()) {
            alert("Email is valid!");
        } else {
            alert("Email is invalid!");
        }
    </script>
</body>

</html>