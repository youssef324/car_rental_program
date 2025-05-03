<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentalsystem";

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

    $name = "admin";
    $user = "admin";
    $plainPassword = "admin@123";
    $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);
    $isAdmin = 1; // 1 for admin, 0 for regular user

    $stmt = $conn->prepare("INSERT INTO employees (name, user, PasswordHash, isAdmin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $user, $passwordHash, $isAdmin);
    $stmt->execute();

    echo "Admin user added successfully. Use username: $user and password: $plainPassword";

    $stmt->close();
    $conn->close();
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}
?>