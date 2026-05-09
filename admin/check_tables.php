<?php
$conn = new mysqli("localhost", "root", "", "carrentalsystem");
$result = $conn->query("SHOW TABLES");
while($row = $result->fetch_row()) {
    echo $row[0] . "\n";
}
$conn->close();
?>
