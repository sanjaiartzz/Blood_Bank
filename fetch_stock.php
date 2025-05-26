<?php
include "db.php";

$sql = "SELECT * FROM blood_stock";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'>
            <thead class='table-dark'>
                <tr>
                    <th>Blood Type</th>
                    <th>Available Units</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['blood_type']}</td>
                <td>{$row['available_units']}</td>
                <td>{$row['last_updated']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-center'>No data available.</p>";
}

$conn->close();
?>
