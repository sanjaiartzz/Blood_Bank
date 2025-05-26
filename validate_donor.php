<?php
include 'db.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = trim($_POST['donor_id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT password FROM donors WHERE donor_id = ?");
    $stmt->bind_param("s", $donor_id);
    $stmt->execute();
    $stmt->bind_result($stored_hash);
    $stmt->fetch();
    $stmt->close();

    if ($stored_hash && password_verify($password, $stored_hash)) {
        echo "valid";
    } else {
        echo "invalid";
    }

    $conn->close();
}
?>
