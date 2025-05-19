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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    $password_query = "SELECT PasswordHash FROM customers WHERE CustomerID = ?";
    $password_stmt = $conn->prepare($password_query);
    $password_stmt->bind_param("i", $CustomerID);
    $password_stmt->execute();
    $password_result = $password_stmt->get_result();
    $db_password = $password_result->fetch_assoc()['PasswordHash'];
    
    if (password_verify($current_password, $db_password)) {
        if ($new_password === $confirm_password) {
            // Update password
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_query = "UPDATE customers SET PasswordHash = ? WHERE CustomerID = ?";
            $update_password_stmt = $conn->prepare($update_password_query);
            $update_password_stmt->bind_param("si", $new_password_hash, $CustomerID);
            
            if ($update_password_stmt->execute()) {
                $success = true;
            }
        } else {
            $error = "New passwords don't match";
        }
    } else {
        $error = "Current password is incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Change Password</title>
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
        gap: 20px;
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
      Change Password
    </h1>

    <div class="container">
      <div class="form-section">
        <?php if ($success): ?>
          <div class="alert alert-success">
            Your password has been changed successfully!
          </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <div class="account-header">
          <div>
            <h2>Password Change</h2>
            <p>Please enter your current password and your new password.</p>
          </div>
          <img src="Photos/911.jpeg" alt="Profile Photo" />
        </div>
        
        <form method="POST" action="change_password.php">
          <div>
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" placeholder="Enter your current password" required>
          </div>
          <div>
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter your new password" required>
          </div>
          <div>
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required>
          </div>
          <div class="submit-btn">
            <button type="button" onclick="window.location.href='profile.php'"> cancel</button> 
            <button type="submit" onclick="isValid()" onclick="window.location.href='index.php'">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>