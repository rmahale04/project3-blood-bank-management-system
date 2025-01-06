<?php
require_once("conn.php");
include "header_footer_admin.html";

$search_blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';

$sql = "SELECT blood_group, quantity FROM stock";

if(!empty($search_blood_group)) {
    $sql .= " WHERE blood_group LIKE '$search_blood_group'";
}

$stock_result = $conn->query($sql); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buttonPressed'])) {
    $current_date = date('Y-m-d');
    $expired_donations_query = "SELECT donation.donation_id,donation.donor_id, donor.donor_blood_group, donation.quantity, donation.expiry_date 
                                FROM donation 
                                JOIN donor ON donation.donor_id = donor.donor_id 
                                WHERE donation.expiry_date < '".$current_date."' And donation.updated_status=0 ";

    $expired_donations_result = $conn->query($expired_donations_query);

    if ($expired_donations_result && $expired_donations_result->num_rows > 0) {
        

        while ($donation = $expired_donations_result->fetch_assoc()) {
           
            $blood_group = $donation['donor_blood_group'];
            $quantity = $donation['quantity'];

            $update_stock_query = "UPDATE stock 
                                   SET quantity =  quantity-$quantity
                                   WHERE blood_group = '$blood_group'";

            
            if ($conn->query($update_stock_query) === TRUE) {
                $query="update donation set updated_status=1 where donation_id='".$donation["donation_id"]."' ";
                mysqli_query($conn,$query);
            } else {
                echo "<script>alert('Failed to update stock for $blood_group.');</script>";
            }
        }
    } else {
        echo "<script>alert('No expired donations to process.');</script>";
    }
}



?>
    
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Stock Management</title>
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
        .stock-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .stock-table th, .stock-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .stock-table th {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
        .stock-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .stock-table tr:hover {
            background-color: #f1f1f1;
        }
        .button-container{
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Blood Stock</h2>

        <div class="search-bar">
            <center>
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
            </form>
            </center>
        </div>

        <table class="stock-table">
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Quantity (Units)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($stock_result->num_rows > 0): ?>
                    <?php while ($row = $stock_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['blood_group']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No stock data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
    </div>
    <div class="button-container">
        <form method="POST" action="">
            <input type="submit" name="buttonPressed" id="dailyButton" value="Update Stock">
        </form>
        <p id="message"></p>
    </div>

    
</body>
</html>

<?php
$conn->close();
?>