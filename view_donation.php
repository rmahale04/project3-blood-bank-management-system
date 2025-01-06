<?php
require_once("conn.php");
// include "header_footer_admin.html";
// include "blood_request_style.css";

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$till_date = isset($_POST['till_date']) ? $_POST['till_date'] : '';

// Fetch all donation records from the database
$query = "SELECT d.donor_id, d.quantity, d.donation_date, don.donor_blood_group
          FROM donation d
          JOIN donor don ON d.donor_id = don.donor_id";
        //   ORDER BY d.donation_date DESC";

if ($from_date && $till_date) {
    $query .= " WHERE d.donation_date BETWEEN '$from_date' AND '$till_date'";
}

$query .= " ORDER BY d.donation_date DESC";

$result = $conn->query($query);

if ($result === false) {
    echo "Error: Could not fetch donation records. " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Donations</title>
    <!-- <link rel="stylesheet" href="show_list_style.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #dc3545;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }

        table td {
            color: #333;
        }

        table tr:nth-child(even) {
            bbackground-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* table {
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
        } */
        .search-container, .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .search-container input, .search-container button, .button-container button {
            margin: 5px;
        }
        .search-container input {
            width: 200px;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .search-container button, .button-container button {
            padding: 10px 20px;
            background-color:  #a71d2a;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .search-container button:hover, .button-container button:hover {
            background-color:  #a71d2a;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        .button-container button a {
            color: white;
            text-decoration: none;
        }

        .button-container button:hover {
            background-color: #a71d2a;
        } */
        /* button {
            width: 100%;
            padding: 10px;
            background-color: #b30000;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }
        /* .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        select, button {
            padding: 5px 10px;
            margin: 5px;
        } */
    </style>
</head>
<body>
<?php include 'header_footer_admin.html';?>
<div class="container">
    <center><h2>View Donations</h2></center>
    <!-- <br> -->
    <div class="search-container">
        <form method="POST" action="">
            From: <input type="date" name="from_date" value="<?php echo $from_date; ?>" />
            Till: <input type="date" name="till_date" value="<?php echo $till_date; ?>" />
            <button type="submit">Search</button>
        </form>
    </div>
    <!-- <br> -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Donor ID</th>
                <th>Blood Group</th>
                <th>Donation Quantity (Units)</th>
                <th>Donation Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['donor_id']; ?></td>
                    <td><?php echo $row['donor_blood_group']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['donation_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <center><p style="color: red;">No donation records found.</p></center>
    <?php endif; ?>

    <!-- <button class="add-donation-btn"><a href="add_donation.php">Add Donation</a></button> -->
    <div class="button-container">
        <button><a href="add_donation.php">Add Donation</a></button>
    </div>
    </div>
    <br>
</body>
</html>