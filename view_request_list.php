<?php
require_once("conn.php");
include "header_footer_admin.html";

// search bar
$search_action = isset($_GET['action_taken']) ? $_GET['action_taken'] : '';

$search_query = "SELECT request_id, hospital_id, blood_group, quantity_require, requested_date, status FROM blood_request";
if ($search_action !== '') {
    $search_query .= " WHERE status = '$search_action'";
}

// accept, deny button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    // $new_status = $action === 'Accept' ? 'Accepted' : 'Denied';
    $new_status = '';
    if ($action === 'Approve') {
        $new_status = 'Approved';
    } elseif ($action === 'Reject') {
        $new_status = 'Rejected';
    } elseif ($action === 'Supply') {
        $new_status = 'Supplied';
    }

    $query = "UPDATE blood_request SET status = '$new_status' WHERE id = $request_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Request status updated successfully.');</script>";
    } else {
        echo "<script>alert('Error: Could not update request status.');</script>";
    }
}

// $query = "SELECT request_id, hospital_id, blood_group, quantity_require, requested_date, status FROM blood_request";
$result = mysqli_query($conn, $search_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display:table;
            width: 100%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
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
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            bbackground-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        button {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
        }
        button[name='action'][value='Approve'] {
            background-color: green;
            color: white;
            border: none;
        }
        button[name='action'][value='Reject'] {
            background-color: red;
            color: white;
            border: none;
        }
        button:hover {
            opacity: 0.8;
        }
        a{
            text-decoration:none;
            color:black;
        }
        /* table {
            margin-bottom: 30px; 
        } */
        /* body {
            margin-bottom: 50px; 
        } */
    </style>
</head>
<body>
    <div class="container">
    <center><h1>Requests</h1></center>
    <!-- <br> -->

    <div class="search-bar">
        <form method="get" action="">
            <select name="action_taken">
                <option value="">Search </option>
                <option value="Pending" <?php if ($search_action == "Pending") echo "selected"; ?>>Pending</option>
                <option value="Approved" <?php if ($search_action == "Approved") echo "selected"; ?>>Approved</option>
                <option value="Rejected" <?php if ($search_action == "Rejected") echo "selected"; ?>>Rejected</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
    <br>
    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Hospital ID</th>
                <th>Blood Group</th>
                <th>Quantity (Units)</th>
                <th>Requested Date</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Supply</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $sr_no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $req_id=$row["request_id"];
                    echo "<tr>
                        <td>{$sr_no}</td>
                        <td>{$row['hospital_id']}</td>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['quantity_require']}</td>
                        <td>{$row['requested_date']}</td>
                        <td>{$row['status']}</td>
                        <td>";
                        if ($row['status'] !== 'Completed') { // Hide buttons if status is 'Completed'
                            echo "<form action='handle_request.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                <button type='submit' name='action' value='Approve'><a href='handle_request.php?rid=$req_id&status=approve'>Approve</a></button>
                                <button type='submit' name='action' value='Reject'><a href='handle_request.php?rid=$req_id&status=reject'>Reject</a></button>
                            </form>";
                        }
                        echo "</td>
                        <td>";
                        if($row['status'] === 'Approved'){
                            // "<button type='submit' name='action' value='Supply'><a href='handle_request.php?rid=$req_id&status=supply'>Supply</a></button>";
                            // echo "<form method='POST' style='display:inline;'>
                            //         <input type='hidden' name='request_id' value='{$row['request_id']}'>
                            //         <button type='submit' name='action' value='Supply'><a href='supply.php?rid=$req_id&status=supply'>Supply</a></button>
                            //       </form>";
                            echo "<form method='GET' action='supply.php' style='display:inline;'>
                                <input type='hidden' name='hospital_id' value='{$row["hospital_id"]}'>
                                <input type='hidden' name='blood_group' value='{$row["blood_group"]}'>
                                <input type='hidden' name='quantity_require' value='{$row["quantity_require"]}'>
                                <input type='hidden' name='request_id' value='{$row["request_id"]}'>
                                <button type='submit'>Supply</button>
                              </form>";
                        }
                        echo "</td>
                    </tr>";
                    $sr_no++;
                }
            } else {
                echo "<tr><td colspan='7'>No blood requests found.</td></tr>";
            }
            ?>
            </div>
        </tbody>
    </table>
    <!-- <br><br><br><br> --> 
</body>
</html>