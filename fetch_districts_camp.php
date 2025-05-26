<?php
if (isset($_POST['state'])) {
    $conn = new mysqli('localhost', 'root', '', 'blood_bank');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $state = $_POST['state'];
    $stmt = $conn->prepare("SELECT DISTINCT district FROM camps WHERE state = ?");
    $stmt->bind_param("s", $state);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value=''>Select District</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['district'] . "'>" . $row['district'] . "</option>";
    }

    $stmt->close();
    $conn->close();
}
?>
