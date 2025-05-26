<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "blood_bank";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['state'])) {
    $state = $_POST['state'];

    $district_query = "SELECT DISTINCT district FROM blood_banks WHERE state=? ORDER BY district";
    $district_stmt = $conn->prepare($district_query);
    $district_stmt->bind_param("s", $state);
    $district_stmt->execute();
    $district_result = $district_stmt->get_result();

    echo "<option value=''>Select District</option>";
    while ($district_row = $district_result->fetch_assoc()) {
        echo "<option value='{$district_row['district']}'>{$district_row['district']}</option>";
    }

    $district_stmt->close();
}
$conn->close();
?>
