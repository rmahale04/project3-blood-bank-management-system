<?php
require_once("conn.php");
include "header_footer_admin.html";

// Fetch total blood donated by blood group from the donor table
$query = "SELECT donor_blood_group, SUM(quantity_donated) AS total_blood 
          FROM donor 
          GROUP BY donor_blood_group";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    // If there was an error with the query, show an error message
    echo "<p>Error with query: " . mysqli_error($conn) . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Blood Donated by Blood Group</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Total Blood Donated by Blood Group</h1>

    <table>
        <thead>
            <tr>
                <th>Blood Group</th>
                <th>Total Blood Donated (Units)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['total_blood']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No donation records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
