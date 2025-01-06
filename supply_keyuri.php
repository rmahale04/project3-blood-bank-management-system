<?php
require_once("conn.php");
include "header_footer_admin.html";

// Handle blood supply action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supply'])) {
    $blood_group = $_POST['blood_group'];
    $quantity_supplied = (int)$_POST['quantity'];
    $hospital_id = $_POST['hospital_id'];

    // Check if sufficient stock is available
    $stock_check_query = "SELECT quantity FROM stock WHERE blood_group = '$blood_group'";
    $stock_result = $conn->query($stock_check_query);

    if ($stock_result->num_rows > 0) {
        $stock_row = $stock_result->fetch_assoc();
        if ($stock_row['quantity'] >= $quantity_supplied) {
            // Update stock
            $update_stock_query = "UPDATE stock SET quantity = quantity - $quantity_supplied WHERE blood_group = '$blood_group'";
            if ($conn->query($update_stock_query) === TRUE) {
                echo "<script>alert('Blood supply recorded successfully.');</script>";
            } else {
                echo "<script>alert('Error updating stock.');</script>";
            }
        } else {
            echo "<script>alert('Insufficient stock for $blood_group.');</script>";
        }
    } else {
        echo "<script>alert('Blood group not found in stock.');</script>";
    }
}

// Fetch blood requests with hospital names and stock
$query = "SELECT br.request_id, br.hospital_id, h.hospital_name, br.blood_group, br.quantity_require, s.quantity AS available_quantity
          FROM blood_request br
          JOIN hospital h ON br.hospital_id = h.hospital_id
          LEFT JOIN stock s ON br.blood_group = s.blood_group";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Supply</title>
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
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Blood Supply</h2>
        <table>
            <thead>
                <tr>
                    <th>Hospital Name</th>
                    <th>Blood Group</th>
                    <th>Requested Blood (Units)</th>
                    <th>Available Quantity</th>
                    <th>Supply</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['hospital_name']}</td>
                            <td>{$row['blood_group']}</td>
                            <td>{$row['quantity_require']}</td>
                            <td>{$row['available_quantity']}</td>
                            <td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='blood_group' value='{$row['blood_group']}'>
                                    <input type='hidden' name='hospital_id' value='{$row['hospital_id']}'>
                                    <input type='number' name='quantity' placeholder='Units' min='1' required>
                                    <button type='submit' name='supply'>Supply</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No blood requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
