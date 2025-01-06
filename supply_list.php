<?php
require_once("conn.php");

$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

$query = "SELECT supply.supply_id, supply.supply_date, supply.supply_quantity, hospital.hospital_name, hospital.hospital_id FROM supply 
          JOIN blood_request ON supply.request_id = blood_request.request_id 
          JOIN hospital ON blood_request.hospital_id = hospital.hospital_id";

if (!empty($from_date) && !empty($to_date)) {
    $query .= " WHERE supply.supply_date BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Supply List</h1>

    <form method="get" action="">
        <label for="from_date">From Date:</label>
        <input type="date" id="from_date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">

        <label for="to_date">To Date:</label>
        <input type="date" id="to_date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">

        <button type="submit">Search</button>
    </form>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Supply ID</th>
                    <th>Hospital ID</th>
                    <th>Hospital Name</th>
                    <th>Supply Date</th>
                    <th>Supply Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['supply_id']; ?></td>
                        <td><?php echo $row['hospital_id']; ?></td>
                        <td><?php echo $row['hospital_name']; ?></td>
                        <td><?php echo $row['supply_date']; ?></td>
                        <td><?php echo $row['supply_quantity']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No supplies found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
