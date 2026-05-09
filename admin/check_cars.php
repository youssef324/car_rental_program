<?php
$conn = new mysqli("localhost", "root", "", "carrentalsystem");
$res = $conn->query("SELECT CarID, Model, image_url FROM cars LIMIT 5");
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['CarID'] . " | Model: " . $row['Model'] . " | Image: " . $row['image_url'] . "\n";
}
?>
