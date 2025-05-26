<?php
include 'db.php'; // adjust path if needed

date_default_timezone_set("Asia/Kolkata");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_POST['donor_id'];
    $camp_name = $_POST['camp_name'];
    $camp_date = $_POST['camp_date'];
    $organizer_email = $_POST['organizer_email'];
    $request_status = "pending";
    $request_date = date("Y-m-d");

    // Check if donor already registered for the specific camp on this date
    $checkSameCamp = $conn->prepare("SELECT * FROM camp_requests WHERE donor_id = ? AND camp_name = ? AND camp_date = ?");
    $checkSameCamp->bind_param("sss", $donor_id, $camp_name, $camp_date);
    $checkSameCamp->execute();
    $resultSameCamp = $checkSameCamp->get_result();

    if ($resultSameCamp->num_rows > 0) {
        echo "You have already registered for this camp.";
        exit;
    }

    // Check if donor already registered for any other camp on the same date
    $checkSameDate = $conn->prepare("SELECT * FROM camp_requests WHERE donor_id = ? AND camp_date = ?");
    $checkSameDate->bind_param("ss", $donor_id, $camp_date);
    $checkSameDate->execute();
    $resultSameDate = $checkSameDate->get_result();

    if ($resultSameDate->num_rows > 0) {
        echo "You have already registered for another camp on this date.";
        exit;
    }

    // Get password from donors table
    $stmt = $conn->prepare("SELECT password FROM donors WHERE donor_id = ?");
    $stmt->bind_param("s", $donor_id);
    $stmt->execute();
    $stmt->bind_result($password);
    $stmt->fetch();
    $stmt->close();

    if ($password) {
        $request_date = date("Y-m-d h:i:s A"); // 12-hour format with AM/PM as text

        $insert = $conn->prepare("INSERT INTO camp_requests (donor_id, camp_name, request_status, request_date, camp_date, organizer_email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sssssss", $donor_id, $camp_name, $request_status, $request_date, $camp_date, $organizer_email, $password);
        
        if ($insert->execute()) {
            echo "Registered successfully!";
        } else {
            echo "Error: " . $insert->error;
        }
        $insert->close();
    } else {
        echo "Donor not found.";
    }

    $conn->close();
}
?>
