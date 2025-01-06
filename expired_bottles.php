<?php
require_once("conn.php");
include "header_footer_admin.html";

$current_date = date('Y-m-d');

$expired_query = "SELECT donation.donor_id, donor.donor_blood_group,donation.donation_id, donation.quantity, donation.expiry_date 
                  FROM donation 
                  JOIN donor ON donation.donor_id = donor.donor_id
                  WHERE donation.expiry_date < '$current_date' ";

// searching for detailed table(not for smaller, total table)
$search_blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
$search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

if(!empty($search_blood_group)) {
    $expired_query .= " AND donor.donor_blood_group LIKE '$search_blood_group'";
}
if (!empty($search_date)) {
    $expired_query .= " AND donation.expiry_date = '$search_date'";
}

$today_expiring_query = "SELECT donor.donor_blood_group, donation.donation_id, donation.quantity, donation.expiry_date
                         FROM donation
                         JOIN donor ON donation.donor_id = donor.donor_id
                         WHERE donation.expiry_date = '$current_date'
                         ORDER BY donor.donor_blood_group ASC";
$today_expiring_result = $conn->query($today_expiring_query);

$expired_query .= " ORDER BY donation.expiry_date ASC";


$expired_result = $conn->query($expired_query);

$total_count = "SELECT donor.donor_blood_group, SUM(donation.quantity) AS total_quantity
                FROM donation 
                JOIN donor ON donation.donor_id = donor.donor_id
                WHERE donation.expiry_date < '$current_date'
                GROUP BY donor.donor_blood_group
                ORDER BY donor.donor_blood_group ASC";
                  
$total_count_result = $conn->query($total_count);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Expired Blood Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            display: flex;
            gap: 20px;
        }
        .left-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 300px;
        }

        .right-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

        h2, h3 {
            text-align: center;
            color: #dc3545;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #dc3545;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        p {
            text-align: center;
            color: #555;
        }
        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        select, button {
            padding: 5px 10px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="search-bar">
        <form method="get" action="">
            <select name="blood_group">
                <option value="">Select Blood Type</option>
                <option value="A+" <?php if ($search_blood_group == "A+") echo "selected"; ?>>A+</option>
                <option value="A-" <?php if ($search_blood_group == "A-") echo "selected"; ?>>A-</option>
                <option value="B+" <?php if ($search_blood_group == "B+") echo "selected"; ?>>B+</option>
                <option value="B-" <?php if ($search_blood_group == "B-") echo "selected"; ?>>B-</option>
                <option value="AB+" <?php if ($search_blood_group == "AB+") echo "selected"; ?>>AB+</option>
                <option value="AB-" <?php if ($search_blood_group == "AB-") echo "selected"; ?>>AB-</option>
                <option value="O+" <?php if ($search_blood_group == "O+") echo "selected"; ?>>O+</option>
                <option value="O-" <?php if ($search_blood_group == "O-") echo "selected"; ?>>O-</option>
            </select>
            <button type="submit">Search</button>
            <input type="date" name="search_date" value="<?php echo $search_date; ?>" placeholder="Search by date">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="container">
    <div class="left-section">
    <h2>Expired Blood Donations</h2>
    <?php if ($total_count_result && $total_count_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Blood Group</th>
                        <th>Quantity (Units)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $total_count_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['donor_blood_group']; ?></td>
                            <td><?php echo $row['total_quantity']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No expired donations found.</p>
        <?php endif; ?>

        <br>
        
            <h2>Expired Blood Donations</h2>
            <?php if ($expired_result && $expired_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Blood Group</th>
                            <th>Quantity (Units)</th>
                            <th>Expiration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $expired_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['donation_id']; ?></td>
                                <td><?php echo $row['donor_blood_group']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['expiry_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No expired donations found.</p>
            <?php endif; ?>
        </div>

        <!-- Right Section: Blood Expiring Today -->
        <div class="right-section">
            <h3>Blood Donations Expiring Today</h3>
            <?php if ($today_expiring_result && $today_expiring_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Blood Group</th>
                            <th>Donation Id</th>
                            <th>Quantity (Units)</th>
                            <th>Expiration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $today_expiring_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['donor_blood_group']; ?></td>
                                <td><?php echo $row['donation_id']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['expiry_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No donations expiring today.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>

