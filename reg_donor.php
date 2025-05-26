<?php
// Enable detailed error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost", "root", "", "blood_bank");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['donor_id']) || empty($_POST['password'])) {
        echo "<script>alert('Donor ID and Password are required.');</script>";
        exit();
    }

    // Collect form data
    $donor_id = $_POST['donor_id'];
    $name = $_POST['name'];
    $donation_date = $_POST['donation_date'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $father_name = $_POST['father_name'];
    $blood_group = $_POST['blood_group'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    // ✅ Age validation
    if ($age < 18 || $age > 65) {
        echo "<script>alert('Error: Age must be between 18 and 65 to register as a donor.'); window.history.back();</script>";
        exit();
    }

    // Check if donor_id already exists
    $check_stmt = $conn->prepare("SELECT donor_id FROM donors WHERE donor_id = ?");
    $check_stmt->bind_param("s", $donor_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('Error: Donor ID already exists. Please choose a different ID.');</script>";
        $check_stmt->close();
        exit();
    }
    $check_stmt->close();

    // Hash the password after validation
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Handle file upload
    $id_proof_path = "";
    if (isset($_FILES['id_proof']) && $_FILES['id_proof']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower(pathinfo($_FILES["id_proof"]["name"], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "<script>alert('Error: Only JPG, JPEG, PNG, and PDF files are allowed.');</script>";
            exit();
        }

        $newFileName = uniqid("id_proof_", true) . '.' . $fileExtension;
        $id_proof_path = $newFileName;
        move_uploaded_file($_FILES["id_proof"]["tmp_name"], $target_dir . $newFileName);
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO donors (donor_id, password, name, donation_date, age, gender, father_name, mobile, email, state, district, address, pincode, id_proof, blood_group) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ssssissssssssss", $donor_id, $password, $name, $donation_date, $age, $gender, $father_name, $mobile, $email, $state, $district, $address, $pincode, $id_proof_path, $blood_group);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful!'); window.location.href='login_donor.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Stream Blood Bank | Sanjai Designs</title>
    <link rel="stylesheet" href="./css/reg.css">
    <link rel="shortcut icon" href="./images/one blood logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,600,0,0" />
</head>
<body>
    <nav class="navbar container">
        <div class="logo"><a href="./index.html"><img src="./images/one blood logo.png" alt="Website logo"></a></div>
        <div class="nav-item-wrapper">
            <ul class="nav-items">
                <li><a href="./index.html">Home</a></li>             
                <li><a href="./searchbank.php">Donate Blood</a></li>
                <li class="dropdown">
                    <a href="#">Search For</a>
                    <div class="dropdown-content">
                        <a href="./search_donor.php">Donors</a>
                        <a href="./searchblood.php">Blood Availability</a> 
                        <a href="./searchcamp.php">Upcoming Camps</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Register For</a>
                    <div class="dropdown-content">
                        <a href="reg_camp.php">Donation Camp</a>
                        <a href="reg_bank.php">Blood Bank</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="nav-right">
            <a href="./login_donor.html"><button type="submit" class="login-btn primary-btn">Login</button></a>
            <a href="./reg_donor.php"><button type="submit" class="sign-up-btn secondary-btn">Register</button></a>
        </div>
    </nav>

    <form class="form" action="" method="post" enctype="multipart/form-data">
        <h2>DONOR SIGN-UP</h2>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="donation_date">Date of Previous Donation: *</label>
        <input type="date" id="donation_date" name="donation_date">

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required min="18" max="65">

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="father_name">Father Name:</label>
        <input type="text" id="father_name" name="father_name" required>

        <label for="mobile">Mobile:</label>
        <input type="tel" id="mobile" name="mobile" pattern="[0-9]{10}" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <label for="blood_group">Select Blood Group:</label>
        <select name="blood_group" required>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select><br><br>

        <label for="state">State:</label>
        <select id="state" name="state" required>
            <option value="" disabled selected>Select State</option>
        </select>

        <label for="district">District:</label>
        <select id="district" name="district" required>
            <option value="" disabled selected>Select District</option>
        </select>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address">

        <label for="pincode">Pin Code:</label>
        <input type="text" id="pincode" name="pincode" pattern="[0-9]{6}" required>

        <h2>Username and Password</h2>

        <label for="donor_id">User Name:</label>
        <input type="text" id="donor_id" name="donor_id" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <div class="file">
            <label for="id_proof">Passport Size photo:</label>
            <input type="file" name="id_proof" id="id_proof">
        </div>

        <div class="button-container">
            <button type="submit" class="sub2-bt">Submit</button>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const stateSelect = document.getElementById("state");
            const districtSelect = document.getElementById("district");

            const stateDistricts = {
                "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem"],
                "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik", "Thane"],
                "Karnataka": ["Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum"],
                "Kerala": ["Thiruvananthapuram", "Kochi", "Kozhikode", "Thrissur", "Kollam"],
                "West Bengal": ["Kolkata", "Darjeeling", "Howrah", "Durgapur", "Asansol"]
            };

            for (const state in stateDistricts) {
                let option = document.createElement("option");
                option.value = state;
                option.textContent = state;
                stateSelect.appendChild(option);
            }

            stateSelect.addEventListener("change", function () {
                districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';
                const selectedState = stateSelect.value;
                if (selectedState in stateDistricts) {
                    stateDistricts[selectedState].forEach(district => {
                        let option = document.createElement("option");
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                }
            });
        });
    </script>

<section class="footer">
    <div class="foot-wrap container">
        <div class="left-items-wrap">
            <div class="foot-links-wrap container">
                <div class="q-links-wrapper">
                    <h3 class="q-links"> Quick Links </h3>
                    <div> 
                        <a href="https://en.wikipedia.org/wiki/Blood_donation" target="_blank"> 
                            <div class="footer-link-wrapper"> 
                                <span class="material-symbols-outlined"> chevron_right </span> 
                                <span class="footer-link-text"> About Blood Donation </span> 
                            </div> 
                        </a>  
                    </div>
                    <div> 
                        <a href="./donate.html" target="_blank"> 
                            <div class="footer-link-wrapper"> 
                                <span class="material-symbols-outlined"> chevron_right </span> 
                                <span class="footer-link-text"> Donate </span> 
                            </div>
                        </a>
                    </div>
                    <div> 
                        <a href="./contact.html" target="_blank"> 
                            <div class="footer-link-wrapper"> 
                                <span class="material-symbols-outlined"> chevron_right </span> 
                                <span class="footer-link-text"> Contact Us </span> 
                            </div>
                        </a>
                    </div>
                </div>
                <div class="imp-links-wrapper">
                    <h3 class="imp-links"> Important Links </h3>
                    <div> 
                        <a href="" target="_blank"> 
                            <div class="footer-link-wrapper"> 
                                <span class="material-symbols-outlined"> chevron_right </span> 
                                <span class="footer-link-text"> Privacy </span> 
                            </div>
                        </a> 
                    </div>
                    <div> 
                        <a href="" target="_blank"> 
                            <div class="footer-link-wrapper"> 
                                <span class="material-symbols-outlined"> chevron_right </span> 
                                <span class="footer-link-text"> Cookies </span> 
                            </div>    
                        </a>
                    </div>
                    <div> 
                        <a href="" target="_blank"> 
                            <div class="footer-link-wrapper"> 
                                <span class="material-symbols-outlined"> chevron_right </span> 
                                <span class="footer-link-text"> Terms and Conditions </span> 
                            </div>
                        </a> 
                    </div>
                    <div>
    
                    </div>
                </div>
            </div>
            <div class="sm-links container">
                <a href=""> <img src="./images/facebook.png" alt=""> </a>
                <a href=""> <img src="./images/instagram.png" alt=""> </a>
                <a href=""> <img src="./images/twitter.png" alt=""> </a>
            </div>
        </div>
        <hr class="foot-breaker">
        <div class="right-text-wrap container">
            <div class="foot-l-text"> "One drop can save many lives" </div>
            <div > <button type="submit" class="now">Donate</button> </div>
        </div>
    </div>
    <p class="container cr-text"> life stream Blood Bank | sanjai designs | &copy; All rights reserved </p> 
</section>
</body> 
</html>
