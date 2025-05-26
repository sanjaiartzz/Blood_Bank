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
            <a href="./login_camp.html"><button type="submit" class="login-btn primary-btn">Login</button></a>
            <a href="./reg_camp.php"><button type="submit" class="sign-up-btn secondary-btn">Register</button></a>
        </div>
    </nav>

    <div class="form">
        <h2>Camp Registration</h2>
        <form method="POST">
            <label>Organization Type: *</label>
            <select name="organization_type" required>
                <option value="">Select Organization</option>
                <option value="Indian Red Cross Society">Indian Red Cross Society</option>
                <option value="Rotary Blood Bank">Rotary Blood Bank</option>
                <option value="Lions Blood Bank">Lions Blood Bank</option>
                <option value="Apollo Blood Bank">Apollo Blood Bank</option>
                <option value="AIIMS Blood Bank">AIIMS Blood Bank</option>
                <option value="State Blood Transfusion Council">State Blood Transfusion Council</option>
                <option value="Sankalp India Foundation">Sankalp India Foundation</option>
                <option value="Think Foundation">Think Foundation</option>
                <option value="BloodConnect Foundation">BloodConnect Foundation</option>
                <option value="Other">Other</option>
            </select>

            <label>Organization Name: *</label>
            <input type="text" name="organization_name" required>

            <label>Organizer Name: *</label>
            <input type="text" name="organizer_name" required>

            <label>Organizer Mobile No: *</label>
            <input type="text" name="organizer_mobile" required>

            <label>Organizer Email Id: *</label>
            <input type="email" name="organizer_email" required>

            <label>Co-Organizer Name:</label>
            <input type="text" name="co_organizer_name">

            <label>Co-Organizer Mobile:</label>
            <input type="text" name="co_organizer_mobile">

            <label>Camp Name: *</label>
            <input type="text" name="camp_name" required>

            <label>Camp Address: *</label>
            <input type="text" name="camp_address" required>

            <label>State: *</label>
            <select name="state" id="state" required onchange="populateDistricts()">
                <option value="">Select State</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Tamil Nadu">Tamil Nadu</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="West Bengal">West Bengal</option>
            </select>

            <label>District: *</label>
            <select name="district" id="district" required>
                <option value="">Select District</option>
            </select>

            <label>Blood Bank: *</label>
            <select name="blood_bank" required>
                <option value="">Select Blood Bank</option>
                <option value="Indian Red Cross Blood Bank">Indian Red Cross Blood Bank</option>
                <option value="Rotary Blood Bank, Delhi">Rotary Blood Bank, Delhi</option>
                <option value="Apollo Blood Bank, Chennai">Apollo Blood Bank, Chennai</option>
                <option value="AIIMS Blood Bank, Delhi">AIIMS Blood Bank, Delhi</option>
            </select>

            <label>Camp Propose Date: *</label>
            <input type="date" name="camp_propose_date" required>

            <label>Start Time (24HH:MM): *</label>
            <input type="time" name="start_time" required>

            <label>End Time (24HH:MM): *</label>
            <input type="time" name="end_time" required>

            <label>Estimated Participants: *</label>
            <input type="number" name="estimated_participants" required>

            <h2>Camp Id and Password</h2>
            <label>Camp Id: *</label>
            <input type="text" name="username" required>

            <label>Password: *</label>
            <input type="password" name="password" required>

            <button type="submit" class="sub2-bt" name="submit">Submit</button>
        </form>
    </div>

    <script>
        function populateDistricts() {
            const state = document.getElementById("state").value;
            const districtSelect = document.getElementById("district");
            const districts = {
                "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik", "Aurangabad"],
                "Karnataka": ["Bangalore", "Mysore", "Hubli", "Mangalore", "Belgaum"],
                "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Salem", "Tiruchirappalli"],
                "Uttar Pradesh": ["Lucknow", "Kanpur", "Agra", "Varanasi", "Meerut"],
                "West Bengal": ["Kolkata", "Howrah", "Darjeeling", "Siliguri", "Asansol"]
            };

            districtSelect.innerHTML = "<option value=''>Select District</option>";
            if (districts[state]) {
                districts[state].forEach(district => {
                    let option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        }

        // Client-side date validation
        document.querySelector("form").addEventListener("submit", function (e) {
            const inputDate = new Date(document.querySelector("input[name='camp_propose_date']").value);
            const today = new Date();
            const maxDate = new Date();
            maxDate.setDate(today.getDate() + 30);
            if (inputDate < today || inputDate > maxDate) {
                alert("Camp Propose Date must be within 30 days from today.");
                e.preventDefault();
            }
        });
    </script>

    <?php
    if (isset($_POST['submit'])) {
        $conn = new mysqli("localhost", "root", "", "blood_bank");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $registrationDate = new DateTime(); // today
        $campProposeDate = new DateTime($_POST['camp_propose_date']);
        $interval = $registrationDate->diff($campProposeDate)->days;
        $isFuture = $campProposeDate > $registrationDate;

        if (!$isFuture || $interval > 30) {
            echo "<script>alert('Camp Propose Date must be within 30 days from today.');</script>";
        } else {
            $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO camps (organization_type, organization_name, organizer_name, organizer_mobile, organizer_email, co_organizer_name, co_organizer_mobile, camp_name, camp_address, state, district, blood_bank, camp_propose_date, start_time, end_time, estimated_participants, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssssssssss",
                $_POST['organization_type'],
                $_POST['organization_name'],
                $_POST['organizer_name'],
                $_POST['organizer_mobile'],
                $_POST['organizer_email'],
                $_POST['co_organizer_name'],
                $_POST['co_organizer_mobile'],
                $_POST['camp_name'],
                $_POST['camp_address'],
                $_POST['state'],
                $_POST['district'],
                $_POST['blood_bank'],
                $_POST['camp_propose_date'],
                $_POST['start_time'],
                $_POST['end_time'],
                $_POST['estimated_participants'],
                $_POST['username'],
                $password_hashed
            );

            $stmt->execute();
            echo "<script>alert('Camp registration successful!');</script>";
        }
    }
    ?>

   
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
