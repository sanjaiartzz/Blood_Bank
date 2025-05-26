<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bloodType = $_POST["bloodType"];
    $units = intval($_POST["units"]);

    if (empty($bloodType) || empty($units)) {
        echo "All fields are required!";
        exit();
    }

    $sql = "INSERT INTO blood_stock (blood_type, available_units) 
            VALUES ('$bloodType', $units) 
            ON DUPLICATE KEY UPDATE available_units = available_units + $units";

    if ($conn->query($sql) === TRUE) {
        echo "Stock updated successfully!";
    } else {
        echo "Error updating stock: " . $conn->error;
    }
}

$conn->close();
?>
