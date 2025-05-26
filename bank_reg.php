<?php
include './php/config.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash password for security
    $name = $_POST['name'];
    $parent_hospital = $_POST['parent_hospital'] ?? null;
    $short_name = $_POST['short_name'] ?? null;
    $category = $_POST['category'];
    $contact_person = $_POST['contact_person'];
    $email = $_POST['email'] ?? null;
    $contact_no = $_POST['contact_no'];
    $first_registration_date = $_POST['first_registration_date'];
    $licence_no = $_POST['licence_no'] ?? null;
    $from_date = $_POST['from_date'] ?? null;
    $to_date = $_POST['to_date'] ?? null;
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'] ?? null;
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    // Check if username exists
    $check_user = $conn->prepare("SELECT username FROM blood_banks WHERE username = ?");
    $check_user->bind_param("s", $username);
    $check_user->execute();
    $result = $check_user->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Error: Username already exists. Please choose a different username.');</script>";
    } else {
        // Insert new blood bank record
        $stmt = $conn->prepare("INSERT INTO blood_banks 
            (username, password, name, parent_hospital, short_name, category, contact_person, email, contact_no, 
            first_registration_date, licence_no, from_date, to_date, state, district, city, address, pincode) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssssssssssssss", 
            $username, $password, $name, $parent_hospital, $short_name, $category, $contact_person, $email, $contact_no, 
            $first_registration_date, $licence_no, $from_date, $to_date, $state, $district, $city, $address, $pincode
        );

        if ($stmt->execute()) {
            echo "<script>alert('Blood Bank Registered Successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }

    // Close statements and connection
    $check_user->close();
    $stmt->close();
    $conn->close();
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
             </u1>
        </div>
        <div class="nav-right">
            <div> <a href="./login.html"> <button type="submit" class="login-btn primary-btn">Donor Login </button> </a> </div>
            <div> <a href="./signup.php"> <button type="submit" class="sign-up-btn secondary-btn"> Register </button> </a> </div>
        </div>
    </nav>
    <section class="f">
    <div class="form">
    <h2>Blood Bank Details</h2>
    <form method="POST">

        <label>Blood Bank Name *</label>
        <input type="text" name="name" required>

        <label>Parent Hospital Name</label>
        <input type="text" name="parent_hospital">

        <label>Short Name</label>
        <input type="text" name="short_name">

        <label>Category *</label>
        <select name="category" required>
            <option value="">Select type</option>
            <option value="Government">Government</option>
            <option value="Private">Private</option>
        </select>

        <label>Contact Person *</label>
        <input type="text" name="contact_person" required>

        <label>Email</label>
        <input type="email" name="email">

        <label>Contact No. *</label>
        <input type="tel" name="contact_no" required>

        <label>First Registration Date *</label>
        <input type="date" name="first_registration_date" required>

        <label>Licence No.</label>
        <input type="text" name="licence_no">

        <label>From Date</label>
        <input type="date" name="from_date">

        <label>To Date</label>
        <input type="date" name="to_date">

        <h2>Blood Bank Address</h2>

        <label>State *</label>
        <input type="text" name="state" required>

        <label>District *</label>
        <input type="text" name="district" required>

        <label>City</label>
        <input type="text" name="city">

        <label>Address *</label>
        <input type="text" name="address" required>

        <label>Address2</label>
        <input type="text" name="address2">

        <label>Pincode *</label>
        <input type="text" name="pincode" required>

        <h2>Username and Password</h2>

        <label>Username *</label>
        <input type="text" name="username" required>

        <label>Password *</label>
        <input type="password" name="password" required>


        <div class="sub">
            <button type="submit" class="sub-bt">Submit</button>
        </div>
    </form>
</div>

    </div>
</section>
    
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
