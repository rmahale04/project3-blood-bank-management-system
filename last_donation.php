<?php
// session_start();
// require_once("conn.php");

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $donor_id = $_POST['donor_id'];  // Donor ID from the form submission
    
//     // Validate the donor ID (ensure it is a number)
//     if (!is_numeric($donor_id)) {
//         echo "<p>Invalid donor ID. Please enter a valid number.</p>";
//         exit;
//     }

//     // Query to get the last donation date for the donor
//     $sql = "SELECT MAX(donation_date) AS last_donation_date FROM donation WHERE donor_id = '$donor_id'";
//     $result = mysqli_query($conn, $sql);

//     // Check if we have any result
//     if (mysqli_num_rows($result) > 0) {
//         $row = mysqli_fetch_assoc($result);
//         $last_donation_date = $row['last_donation_date'];

//         // Generate the report
//         echo "<h2>Last Donation Report for Donor ID: $donor_id</h2>";
//         if ($last_donation_date) {
//             echo "<table>
//                     <thead>
//                         <tr>
//                             <th>Donor ID</th>
//                             <th>Last Donation Date</th>
//                         </tr>
//                     </thead>
//                     <tbody>
//                         <tr>
//                             <td>$donor_id</td>
//                             <td>$last_donation_date</td>
//                         </tr>
//                     </tbody>
//                 </table>";
//         } else {
//             echo "<p>No donation record found for Donor ID: $donor_id.</p>";
//         }
//     } else {
//         echo "<p>Error: Unable to fetch donation data.</p>";
//     }
// }
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last Donation Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            padding: 5px;
            width: 250px;
        }
        input[type="submit"] {
            padding: 6px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Generate Last Donation Report</h1>
    <form method="POST">
        <label for="donor_id">Enter Donor ID:</label>
        <input type="text" id="donor_id" name="donor_id" required>
        <input type="submit" value="Generate Report">
    </form>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Search and Last Donation Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            padding: 5px;
            width: 250px;
        }
        input[type="submit"] {
            padding: 6px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .search-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Donor Search and Last Donation Report</h1>
    
    <div class="search-container">
        <form action="" method="POST">
            <label for="search_name">Search Donor by Name:</label>
            <input type="text" id="search_name" name="search_name" placeholder="Enter donor name">
            <input type="submit" value="Search">
        </form>
    </div>

    <h2>Donors List</h2>
    <table>
        <thead>
            <tr>
                <th>Donor ID</th>
                <th>Name</th>
                <th>Last Donation Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once("conn.php");

            // Handle search query
            $search_name = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search_name'])) {
                $search_name = $_POST['search_name'];
            }

            // Query to fetch donors and their last donation dates
            $query = "SELECT d.donor_id, d.name, MAX(do.donation_date) AS last_donation_date 
                      FROM donor d
                      LEFT JOIN donation do ON d.donor_id = do.donor_id
                      WHERE d.name LIKE '%$search_name%'
                      GROUP BY d.donor_id, d.name";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $donor_id = $row['donor_id'];
                    $name = $row['name'];
                    $last_donation_date = $row['last_donation_date'] ? $row['last_donation_date'] : "No record";

                    echo "<tr>
                            <td>$donor_id</td>
                            <td>$name</td>
                            <td>$last_donation_date</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No donors found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
