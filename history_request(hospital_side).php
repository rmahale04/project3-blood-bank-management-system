<?php
    session_start();
    require_once("conn.php");

    if(empty($_SESSION['hospital_id'])) {
        echo "<script>alert('Please log in to view your request history.'); window.location.href = 'hospital_login_page.php';</script>";
        exit();
    }

    $hospital_id = $_SESSION['hospital_id'];

    // pending, approved, denied
    $query = "SELECT * FROM blood_request WHERE hospital_id = '$hospital_id' AND status IN ('Pending', 'Approved', 'Denied') ORDER BY requested_date DESC";
    $result = mysqli_query($conn, $query);

    // finished (supplied or completed)
    $query_completed_requests = "
        SELECT br.*, s.supply_quantity 
        FROM blood_request br
        LEFT JOIN supply s ON br.request_id = s.request_id 
        WHERE br.hospital_id = '$hospital_id' AND br.status IN ('Completed') 
        ORDER BY br.requested_date DESC
    ";
    $result_completed_requests = mysqli_query($conn, $query_completed_requests);

    // received quantity query
    // ...

    if(!$result || !$result_completed_requests) {
        echo "<script>alert('Error fetching request history.');</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="blood_request_style.css">
    <title>Request Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: beige;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #b91d1d;
            color: #ffffff;
            padding: 10px;
            text-align: center;
        }
        nav {
            background-color: #800000;
            padding: 10px;
            display: flex;
            justify-content: space-around;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 0 10px;
        }
        nav a:hover {
            background-color: rgb(177, 46, 46);
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .section {
            background-color: white;
            margin: 0px auto;
            padding: 20px;
            text-align: center;
            width: 80%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #b30000;
        }

        .request-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .request-table th, .request-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .request-table th {
            background-color: #b91d1d;
            color: white;
        }

        .request-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .request-table tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            background-color: #b91d1d;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hospital Blood Bank Management System</h1>
    </header>
    <nav>
        <a href="hospital_home_page.php">Home</a>
        <a href="request_blood.php">Request Blood</a>
        <a href="history_request(hospital_side).php">Request Status</a>
        <a href="hospital_login_page.php">Login</a>
        <a href="hospital_logout.php">Log out</a>
    </nav>

    <div class="content">
        <div class="section">
            <h2>Your Blood Request History</h2>

            <!-- Table for All Requests -->
            <h3>Pending, Approved, or Denied Requests</h3>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Requested Date</th>
                            <th>Blood Group</th>
                            <th>Quantity Requested</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $requested_date = strtotime($row['requested_date']);
                                        echo date('d-m-Y', $requested_date); 
                                    ?>
                                </td>
                                <td><?php echo $row['blood_group']; ?></td>
                                <td><?php echo $row['quantity_require']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No blood request history available.</p>
            <?php endif; ?>

            <!-- Table for Completed/Supplied Requests -->
            <h3>Completed/Supplied Requests</h3>
            <?php if(mysqli_num_rows($result_completed_requests) > 0): ?>
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Requested Date</th>
                            <th>Blood Group</th>
                            <th>Quantity Requested</th>
                            <th>Received Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result_completed_requests)): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $requested_date = strtotime($row['requested_date']);
                                        echo date('d-m-Y', $requested_date); 
                                    ?>
                                </td>
                                <td><?php echo $row['blood_group']; ?></td>
                                <td><?php echo $row['quantity_require']; ?></td>
                                <td><?php echo $row['supply_quantity'] ? $row['supply_quantity'] : 'Not supplied yet'; ?></td> <!-- Display the received quantity -->
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No completed or supplied requests found.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; Hospital Blood Bank Management System</p>
    </footer>
</body>
</html>

<?php
    mysqli_close($conn);
?>