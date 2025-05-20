<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['CustomerID'])) {
    header("Location: index.php");
    exit();
}

$CustomerID = $_SESSION['CustomerID'];
$error = '';
$success = false;

// Get current user information
$query = "SELECT FirstName, LastName, Email, phoneNumber FROM customers WHERE CustomerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $CustomerID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    
    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phoneNumber)) {
        $error = "Please fill in all required fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif (empty($phoneNumber)) {
        $error = "Phone number is required.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phoneNumber)) {
        $error = "Please enter a valid phone number.";
    } else {
        // Update user information
        $update_query = "UPDATE customers SET FirstName = ?, LastName = ?, Email = ?, phoneNumber = ? WHERE CustomerID = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssi", $first_name, $last_name, $email, $phoneNumber, $CustomerID);
        if ($update_stmt->execute()) {
            $success = true;
            $user['FirstName'] = $first_name;
            $user['LastName'] = $last_name;
            $user['Email'] = $email;
            $user['phoneNumber'] = $phoneNumber;
        } else {
            $error = "Error updating information. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Update Account Information</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Nunito", sans-serif;
      }
      body {
        background-color: #121212;
        color: #1a1a1a;
        padding: 40px;
      }
      .sidebar {
        position: fixed;
        top: 12%;
        left: 0;
        width: 70px;
        height: 80%;
        background: linear-gradient(135deg, #358faa, #358faa);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 0 10px 10px 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transition: width 0.3s;
        padding-top: 20px;
        z-index: 10;
      }
      .sidebar:hover {
        width: 100px;
      }

      .nav a {
        text-decoration: none;
        margin: 10px 0;
        display: flex;
        padding-bottom: 50px;
        padding-top: 20px;
        justify-content: center;
        align-items: center;
        width: 100%;
        transition: transform 0.3s;
      }
      .nav a:hover {
        transform: scale(1.1);
      }

      .nav img {
        width: 40px;
        height: 40px;
        object-fit: contain;
        display: block;
        margin: auto;
        transition: transform 0.3s;
      }
      .account-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }
      .account-header img {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        object-fit: fill;
        margin-left: 20px;
        border: 4px solid #358faa;
      }
      .container {
        display: flex;
        max-width: 750px;
        margin: 100px auto;
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
      }
      .form-section {
        flex: 1;
        padding: 40px;
      }
      .form-section h2 {
        color: #358faa;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
      }
      .form-section p {
        color: #666;
        margin-bottom: 30px;
        font-size: 14px;
      }
      form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
      }
      form .full-width {
        grid-column: 1 / -1;
      }

      label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        font-size: 14px;
      }
      input {
        width: 100%;
        padding: 14px 18px;
        border: 1px solid #ddd;
        border-radius: 50px;
        outline: none;
        font-size: 14px;
      }
      .submit-btn {
        grid-column: 1 / -1;
        display: flex;
        justify-content: center;
        margin-top: 20px;
      }
      .submit-btn button {
        background-color: #358faa;
        color: white;
        border: none;
        padding: 14px 32px;
        font-size: 16px;
        border-radius: 30px;
        cursor: pointer;
        transition: 0.3s;
        margin-left: 50px;
      }
      .submit-btn button:hover {
        background-color: #001633;
      }

      .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
      }
      .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
      }
      .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
      }
      @media (max-width: 900px) {
        .container {
          flex-direction: column;
          margin-left: 100px;
          width: 85%;
        }
        form {
          grid-template-columns: 1fr;
        }
      }
    </style>
  </head>
  <body>
    <div class="sidebar">
      <nav class="nav">
        <a href="profile.php" title="My Profile">
          <img src="Photos/account.png" alt="My Account" />
        </a>
        <a href="Dashboard.php" title="Cars">
          <img src="Photos/car.png" alt="Cars" />
        </a>
        <a href="contacts.html" title="Contacts">
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
    <h1 style="text-align: center; margin-bottom: -60px; color: #358faa">
      Update Account Information
    </h1>

    <div class="container">
      <div class="form-section">
        <?php if ($success): ?>
          <div class="alert alert-success">
            Your account information has been updated successfully!
          </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>
        <div class="account-header">
          <div>
            <h2>Personal Information</h2>
            <p>Update your account details below.</p>
          </div>
          <img src="Photos/911.jpeg" alt="Profile Photo" />
        </div>
        <form method="POST" action="update_info.php">
          <div>
            <label for="first-name">First Name *</label>
            <input type="text" id="first-name" name="first_name" 
                   value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
          </div>
          <div>
            <label for="last-name">Last Name *</label>
            <input type="text" id="last-name" name="last_name" 
                   value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
          </div>
          <div class="full-width">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($user['Email']); ?>" required>
          </div>
          <div class="full-width">
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" 
                   value="<?php echo htmlspecialchars($user['phoneNumber']); ?>">
          </div>
          <div class="submit-btn">
            <button type="button" onclick="window.location.href='profile.php'">Cancel</button>
            <button type="submit" onclick="window.location.href='profile.php'">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>