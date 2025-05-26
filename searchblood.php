<?php
$conn = new mysqli("localhost", "root", "", "blood_bank");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all distinct states
$states = $conn->query("SELECT DISTINCT state FROM blood_banks ORDER BY state ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Life Stream Blood Bank | Sanjai Designs</title>
    <link rel="stylesheet" href="./css/search.css" />
    <link rel="shortcut icon" href="./images/one blood logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,600,0,0"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,1,200"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
.popup-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #f44336;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 9999;
    font-weight: bold;
    animation: fadeout 3s forwards;
}

@keyframes fadeout {
    0% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; display: none; }
}
</style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar container">
        <div class="logo">
            <a href="./index.html"><img src="./images/one blood logo.png" alt="Website logo" /></a>
        </div>
        <div class="nav-item-wrapper">
            <ul class="nav-items">
                <li><a href="./index.html">Home</a></li>
                <li><a href="./searchbank.php">Donate Blood</a></li>

                <div class="dropdown">
                    <li><a href="#">Search For</a></li>
                    <div class="dropdown-content">
                        <a href="./search_donor.php">Donors</a>
                        <a href="./searchblood.php">Blood Availability</a>
                        <a href="./searchcamp.php">Upcoming Camps</a>
                    </div>
                </div>

                <div class="dropdown">
                    <li><a href="#">Register For</a></li>
                    <div class="dropdown-content">
                        <a href="reg_camp.php">Donation Camp</a>
                        <a href="reg_bank.php">Blood Bank</a>
                    </div>
                </div>
            </ul>
        </div>

        <div class="nav-right">
            <a href="./login_donor.html"><button class="login-btn primary-btn">Login</button></a>
            <a href="./reg_donor.php"><button class="sign-up-btn secondary-btn">Register</button></a>
        </div>
    </nav>

    <!-- Search Form -->
    <form class="row g-3" method="POST" action="">
        <h2 class="text-center">Blood Stock Search</h2>
        <div class="content row">
            <!-- State -->
            <div class="col-md-3">
                <select class="form-select" name="state" onchange="this.form.submit()">
                    <option selected disabled>Select State</option>
                    <?php while ($row = $states->fetch_assoc()) {
                        $selected = (isset($_POST['state']) && $_POST['state'] == $row['state']) ? 'selected' : '';
                        echo "<option value='{$row['state']}' $selected>{$row['state']}</option>";
                    } ?>
                </select>
            </div>

            <!-- District -->
            <div class="col-md-3">
                <select class="form-select" name="district">
                    <option selected disabled>Select District</option>
                    <?php
                    if (isset($_POST['state'])) {
                        $selected_state = $_POST['state'];
                        $districts = $conn->query("SELECT DISTINCT district FROM blood_banks WHERE state = '$selected_state' ORDER BY district ASC");
                        while ($row = $districts->fetch_assoc()) {
                            $selected = (isset($_POST['district']) && $_POST['district'] == $row['district']) ? 'selected' : '';
                            echo "<option value='{$row['district']}' $selected>{$row['district']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Blood Type -->
            <div class="col-md-3">
                <select class="form-select" name="blood_type" required>
                    <option selected disabled>Blood Type</option>
                    <?php
                    $types = ['O+', 'A+', 'B+', 'AB+', 'O-', 'A-', 'B-', 'AB-'];
                    foreach ($types as $type) {
                        $selected = (isset($_POST['blood_type']) && $_POST['blood_type'] == $type) ? 'selected' : '';
                        echo "<option value='$type' $selected>$type</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Search Button -->
            <div class="col-md-3">
                <button type="submit" class="btn btn-danger" name="search">Search</button>
            </div>
        </div>
    </form>

    <!-- Results -->
    <?php
    if (
        isset($_POST['search']) &&
        isset($_POST['state']) &&
        isset($_POST['district']) &&
        isset($_POST['blood_type'])
    ) {
        $state = $_POST['state'];
        $district = $_POST['district'];
        $blood_type = $_POST['blood_type'];

        $sql = "SELECT 
                    bb.name AS blood_bank_name,
                    bb.category,
                    bb.address,
                    bb.contact_no,
                    bs.units AS availability,
                    bs.last_updated,
                    bs.blood_group
                FROM 
                    blood_stock bs
                INNER JOIN 
                    blood_banks bb ON bs.bank_username = bb.username
                WHERE 
                    bb.state = ? AND 
                    bb.district = ? AND 
                    bs.blood_group = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $state, $district, $blood_type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<table class="table table-bordered table-striped mt-4">';
            echo '<thead class="table-danger">';
            echo '<tr><th>No</th><th>Blood Bank</th><th>Address</th><th>Contact</th><th>Category</th><th>Available units</th><th>Last Updated</th><th>Blood Group</th></tr>';
            echo '</thead><tbody>';
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$i}</td>
                    <td>{$row['blood_bank_name']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['contact_no']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['availability']}</td>
                    <td>{$row['last_updated']}</td>
                    <td>{$row['blood_group']}</td>
                </tr>";
                $i++;
            }
            echo '</tbody></table>';
        } else {
            echo '<div id="popup" class="popup-message">No data available</div>';
            echo '<script>
                setTimeout(function() {
                    var popup = document.getElementById("popup");
                    if (popup) {
                        popup.style.display = "none";
                    }
                }, 3000);
            </script>';
            
            
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <!-- Footer -->
    <section class="footer">
        <div class="foot-wrap container">
            <div class="left-items-wrap">
                <div class="foot-links-wrap container">
                    <div class="q-links-wrapper">
                        <h3 class="q-links">Quick Links</h3>
                        <a href="https://en.wikipedia.org/wiki/Blood_donation" target="_blank">
                            <div class="footer-link-wrapper">
                                <span class="material-symbols-outlined">chevron_right</span>
                                <span class="footer-link-text">About Blood Donation</span>
                            </div>
                        </a>
                        <a href="./donate.html" target="_blank">
                            <div class="footer-link-wrapper">
                                <span class="material-symbols-outlined">chevron_right</span>
                                <span class="footer-link-text">Donate</span>
                            </div>
                        </a>
                        <a href="./contact.html" target="_blank">
                            <div class="footer-link-wrapper">
                                <span class="material-symbols-outlined">chevron_right</span>
                                <span class="footer-link-text">Contact Us</span>
                            </div>
                        </a>
                    </div>

                    <div class="imp-links-wrapper">
                        <h3 class="imp-links">Important Links</h3>
                        <a href="#"><div class="footer-link-wrapper"><span class="material-symbols-outlined">chevron_right</span><span class="footer-link-text">Privacy</span></div></a>
                        <a href="#"><div class="footer-link-wrapper"><span class="material-symbols-outlined">chevron_right</span><span class="footer-link-text">Cookies</span></div></a>
                        <a href="#"><div class="footer-link-wrapper"><span class="material-symbols-outlined">chevron_right</span><span class="footer-link-text">Terms and Conditions</span></div></a>
                    </div>
                </div>

                <div class="sm-links container">
                    <a href="#"><img src="./images/facebook.png" alt="Facebook" /></a>
                    <a href="#"><img src="./images/instagram.png" alt="Instagram" /></a>
                    <a href="#"><img src="./images/twitter.png" alt="Twitter" /></a>
                </div>
            </div>

            <hr class="foot-breaker" />

            <div class="right-text-wrap container">
                <div class="foot-l-text">"One drop can save many lives"</div>
                <button class="now">Donate</button>
            </div>
        </div>

        <p class="container cr-text">Life Stream Blood Bank | Sanjai Designs | &copy; All rights reserved</p>
    </section>
</body>
</html>
