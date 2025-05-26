<?php
// Database connection (Ensure this is correctly configured)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood_bank";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Life Stream Blood Bank | Sanjai Designs</title>
    <link rel="stylesheet" href="./css/camp.css">
    <link rel="shortcut icon" href="./images/one blood logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,600,0,0"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,1,200"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <nav class="navbar container">
        <div class="logo"> <a href="./index.html"> <img src="./images/one blood logo.png" alt="Website logo"> </a> </div>
        <div class="nav-item-wrapper">
             <ul class="nav-items">
                <li class="nav-item-1"> <a href="./index.html"> Home </a> </li>             
               <li class="nav-item-3"> <a href="./searchbank.php"> Donate Blood </a> </li>
              
               <div class="dropdown">
                <li class="nav-item-6"> <a href="#"> Search For</a></li>
                <div class="dropdown-content">
                <a href="./search_donor.php">Donors</a>
                <a href="./searchblood.php">Blood Availability </a> 
                <a href="./searchcamp.php">Upcoming Camps</a></li>
                </div>
    </div>
                <div class="dropdown">
                <li class="nav-item-6"> <a href="#"> Register For</a></li>
                <div class="dropdown-content">
            <a href="reg_camp.php">Donation Camp</a>
            <a href="reg_bank.php">Blood Bank</a>
        </div>
    </div>
             </u1>
        </div>
        <div class="nav-right">
            <div> <a href="./login_camp.html"> <button type="submit" class="login-btn primary-btn">Login </button> </a> </div>
            <div> <a href="./reg_camp.php"> <button type="submit" class="sign-up-btn secondary-btn"> Register </button> </a> </div>
        </div>
    </nav>
 <form class="form" method="POST">
        <h2>Camp Schedule</h2>
        <div class="content">
            <select class="camp" id="state" name="state" onchange="fetchDistricts(this.value)">
                <option value="">Select State</option>
                <?php
                $states = $conn->query("SELECT DISTINCT state FROM camps");
                if ($states->num_rows > 0) {
                    while ($row = $states->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['state']) . "'>" . htmlspecialchars($row['state']) . "</option>";
                    }
                }
                ?>
            </select>
            <select class="camp" id="district" name="district">
            <option value="">Select District</option>
            </select>
            <input type="date" id="date" name="date">
            <button type="submit" class="btn" name="search">Search</button>
        </div>
    </form>
    <?php
    if (isset($_POST['search'])) {
        $state = $_POST['state'] ?? '';
        $district = $_POST['district'] ?? '';
        $date = $_POST['date'] ?? '';

        $sql = "SELECT * FROM camps WHERE 1=1";
        $params = [];
        $types = "";

        if (!empty($state)) {
            $sql .= " AND state=?";
            $params[] = $state;
            $types .= "s";
        }
        if (!empty($district)) {
            $sql .= " AND district=?";
            $params[] = $district;
            $types .= "s";
        }
        if (!empty($date)) {
            $sql .= " AND camp_propose_date=?";
            $params[] = $date;
            $types .= "s";
        }

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
     
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>S.No</th>
                        <th>Camp Date</th>
                        <th>Camp Name</th>
                        <th>Address</th>
                        <th>State</th>
                        <th>Contact</th>
                        <th>Action</th>
                    </tr>";
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>{$i}</td>
                <td>{$row['camp_propose_date']}</td>
                <td>{$row['camp_name']}</td>
                <td>{$row['camp_address']}</td>
                <td>{$row['state']}</td>
                <td>{$row['organizer_mobile']}</td>
                <td>
                    <input  type='text' placeholder='Enter Donor ID' id='donor_id_$i'>
                    <input  type='password' placeholder='Password' id='password_$i'>
                    <button  onclick=\"registerCamp('{$row['camp_name']}', '{$row['camp_propose_date']}', '{$row['username']}', document.getElementById('donor_id_$i').value, document.getElementById('password_$i').value)\">
                        Register
                    </button>
                </td>
            </tr>";
                $i++;
            }
            echo "</table>";
        } else {
            echo "<p>No camps found</p>";
        }
        $stmt->close();
    }
    $conn->close();
    ?>
    <script>
    function fetchDistricts(state) {
        if (state === "") {
            document.getElementById("district").innerHTML = "<option value=''>Select District</option>";
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_districts_camp.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("district").innerHTML = xhr.responseText;
            }
        };
        xhr.send("state=" + state);
    }

    function registerCamp(campName, campDate, organizerEmail, donorId, password) {
        if (donorId.trim() === "" || password.trim() === "") {
            alert("Please enter your Donor ID and Password.");
            return;
        }

        fetch("validate_donor.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "donor_id=" + encodeURIComponent(donorId) + "&password=" + encodeURIComponent(password)
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "valid") {
                fetch("register_camp_request.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "camp_name=" + encodeURIComponent(campName) +
                          "&camp_date=" + encodeURIComponent(campDate) +
                          "&organizer_email=" + encodeURIComponent(organizerEmail) +
                          "&donor_id=" + encodeURIComponent(donorId)
                })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                })
                .catch(err => {
                    console.error("Registration error:", err);
                    alert("Registration failed!");
                });
            } else {
                alert("Invalid Donor ID or Password. Please check and try again.");
            }
        })
        .catch(error => {
            console.error("Validation error:", error);
            alert("Something went wrong while validating the Donor ID.");
        });
    }
    </script>
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

