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
            <div> <a href="./login_bank.html"> <button type="submit" class="login-btn primary-btn">Login </button> </a> </div>
            <div> <a href="./reg_bank.php"> <button type="submit" class="sign-up-btn secondary-btn"> Register </button> </a> </div>
        </div>
    </nav>
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "blood_bank";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct states
$states_query = "SELECT DISTINCT state FROM blood_banks ORDER BY state";
$states_result = $conn->query($states_query);

// Initialize variables
$search = "";
$state = "";
$district = "";
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['state']) && isset($_GET['district'])) {
    $state = $_GET['state'];
    $district = $_GET['district'];
    $search = trim($_GET['search'] ?? '');

    // SQL query
    $sql = "SELECT id, name, address, category, email, contact_no FROM blood_banks WHERE state=? AND district=?";
    
    if (!empty($search)) {
        $sql .= " AND name LIKE ?";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        if (!empty($search)) {
            $searchTerm = "%$search%";
            $stmt->bind_param("sss", $state, $district, $searchTerm);
        } else {
            $stmt->bind_param("ss", $state, $district);
        }
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<form class="row g-3" method="GET" action="">

    <h2 class="mb-4 text-center">Search Nearest Blood Banks</h2>

    <div class="content">
        <div class="col-md-4">
            <select name="state" id="state" class="form-control" required>
                <option value="">Select State</option>
                <?php while ($row = $states_result->fetch_assoc()) { ?>
                    <option value="<?= $row['state'] ?>" <?= ($state == $row['state']) ? 'selected' : '' ?>><?= $row['state'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-4">
            <select name="district" id="district" class="form-control" required>
                <option value="">Select District</option>
                <?php
                if (!empty($state)) {
                    $district_query = "SELECT DISTINCT district FROM blood_banks WHERE state=? ORDER BY district";
                    $district_stmt = $conn->prepare($district_query);
                    $district_stmt->bind_param("s", $state);
                    $district_stmt->execute();
                    $district_result = $district_stmt->get_result();

                    // Ensure that we have districts to show
                    if ($district_result->num_rows > 0) {
                        while ($district_row = $district_result->fetch_assoc()) {
                            echo "<option value='{$district_row['district']}' " . (($district == $district_row['district']) ? 'selected' : '') . ">{$district_row['district']}</option>";
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Blood Bank or Hospital Name (Optional)" value="<?= htmlspecialchars($search) ?>">
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn">Search</button>
        </div>
    </div>
</form>

<div class="table">
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>S.No.</th>
                    <th>Bank Name</th>
                    <th>Address</th>
                    <th>Category</th>
                    <th>Email</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$sno}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['contact_no']}</td>
                    </tr>";
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['state']) && isset($_GET['district'])): ?>
        <p class="mt-3 text-danger text-center">No results found</p>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#state").change(function(){
                var state = $(this).val();
                $.ajax({
                    url: "fetch_districts.php",
                    method: "POST",
                    data: { state: state },
                    success: function(data){
                        $("#district").html(data);
                    }
                });
            });
        });
    </script>
</div>

<?php $conn->close(); ?>


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
