<?php
// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
$conn = new mysqli("localhost", "root", "", "blood_bank");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch donors from database
$sql = "SELECT name, blood_group, state, age, district, donation_date, mobile FROM donors";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Life Stream Blood Bank | Sanjai Designs</title>
    <link rel="stylesheet" href="./css/search.css">
    <link rel="shortcut icon" href="./images/one blood logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,600,0,0"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,1,200"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>

        h1 {
            text-align: center;
            color: red;
            font-size: 30px;
        }
        table {
            width: 60%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 1px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }
        table, th, td {
           border: 1px solid rgb(204, 204, 204);
            text-align: center;
        }
        th, td {
            padding: 10px;
            
        }
        th {
            background:rgb(255, 30, 0);
            color: white;
            border: 1px solid white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
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
            <div> <a href="./login_donor.html"> <button type="submit" class="login-btn primary-btn">Login </button> </a> </div>
            <div> <a href="./reg_donor.php"> <button type="submit" class="sign-up-btn secondary-btn"> Register </button> </a> </div>
        </div>
    </nav>
 
<h1>Donor List</h1>

<table>
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Blood Group</th>
            <th>State</th>
            <th>District</th>
            <th>Contact</th>
            <th>Last_donation</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['state']}</td>
                        <td>{$row['district']}</td>
                        <td>{$row['mobile']}</td>
                        <td>{$row['donation_date']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No donors found</td></tr>";
        }
        $conn->close();
        ?>
    </table>



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
