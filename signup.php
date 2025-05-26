<?php
// Enable detailed error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
$conn = new mysqli("localhost", "root", "", "blood_bank");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate donor_id and password
    if (empty($_POST['donor_id']) || empty($_POST['password'])) {
        die("Error: Donor ID and Password are required.");
    }

    // Collect form data
    $donor_id = $_POST['donor_id'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $_POST['name'];
    $previous_donations = $_POST['previous_donations'];
    $donation_date = isset($_POST['donation_date']) && !empty($_POST['donation_date']) ? $_POST['donation_date'] : NULL;
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $father_name = $_POST['father_name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    // Handle file upload securely
    $id_proof_path = "";
    if (isset($_FILES['id_proof']) && $_FILES['id_proof']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower(pathinfo($_FILES["id_proof"]["name"], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            die("Error: Only JPG, JPEG, PNG, and PDF files are allowed.");
        }
        
        $newFileName = uniqid("id_proof_", true) . '.' . $fileExtension;
        $id_proof_path = $target_dir . $newFileName;
        move_uploaded_file($_FILES["id_proof"]["tmp_name"], $id_proof_path);
    }

    // Check if donor_id already exists
    $check_stmt = $conn->prepare("SELECT donor_id FROM donors WHERE donor_id = ?");
    $check_stmt->bind_param("s", $donor_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("Error: Donor ID already exists. Please choose a different ID.");
    }
    $check_stmt->close();

    // Prepare SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO donors (donor_id, password, name, previous_donations, donation_date, age, gender, father_name, mobile, email, state, district, address, pincode, id_proof) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissssssssss", $donor_id, $password, $name, $previous_donations, $donation_date, $age, $gender, $father_name, $mobile, $email, $state, $district, $address, $pincode, $id_proof_path);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful!');</script>";
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
    <title> Life Stream Blood Bank | Sanjai Designs</title>
    <link rel="stylesheet" href="./css/reg.css">
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
                <a href="./searchblood.php">Blood Availability </a> 
                <a href="./searchcamp.php">Upcoming Camps</a></li>
                </div>
    </div>
                <div class="dropdown">
                <li class="nav-item-6"> <a href="#"> Register For</a></li>
                <div class="dropdown-content">
            <a href="camp_reg.php">Donation Camp</a>
            <a href="bank_reg.php">Blood Bank</a>
        </div>
    </div>
             </ul>
        </div>
        <div class="nav-right">
            <div> <a href="./login.html"> <button type="submit" class="login-btn primary-btn">Donor Login </button> </a> </div>
            <div> <a href="./signup.html"> <button type="submit" class="sign-up-btn secondary-btn"> Register </button> </a> </div>
        </div>
    </nav>
 
<form class="form" action="" method="post" enctype="multipart/form-data">
<h2>DONOR SIGN-UP</h2>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <div class="form-group">
                <label>Previous donations: *</label>
                <div class="radio-group">
                    <input type="radio" id="yes" name="previous_donations" value="Yes" required onclick="toggleDateField(true)">
                    <label for="yes">Yes</label>
                    <input type="radio" id="no" name="previous_donations" value="No" required onclick="toggleDateField(false)">
                    <label for="no">No</label>
                </div>
            </div>

            <div class="form-group hidden" id="donation_date_field">
                <label for="donation_date">Date of Previous Donation: *</label>
                <input type="date" id="donation_date" name="donation_date">
            </div>
    <label for="age">Age:</label>
    <input type="number" id="age" name="age" required>

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
        function toggleDateField(show) {
            const dateField = document.getElementById("donation_date_field");
            const dateInput = document.getElementById("donation_date");

            if (show) {
                dateField.classList.remove("hidden");
                dateInput.setAttribute("required", "true");
            } else {
                dateField.classList.add("hidden");
                dateInput.removeAttribute("required");
                dateInput.value = "";
            }
        }

    const stateDistricts = {
        "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem"],
        "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik", "Thane"],
        "Karnataka": ["Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum"],
        "Kerala": ["Thiruvananthapuram", "Kochi", "Kozhikode", "Thrissur", "Kollam"],
        "West Bengal": ["Kolkata", "Darjeeling", "Howrah", "Durgapur", "Asansol"]
    };

    document.addEventListener("DOMContentLoaded", function() {
        const stateSelect = document.getElementById("state");
        const districtSelect = document.getElementById("district");

        Object.keys(stateDistricts).forEach(state => {
            let option = document.createElement("option");
            option.value = state;
            option.textContent = state;
            stateSelect.appendChild(option);
        });

        stateSelect.addEventListener("change", function() {
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
