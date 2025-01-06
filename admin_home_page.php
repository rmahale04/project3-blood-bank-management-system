<?php
require_once("conn.php");
session_start();
if(empty($_SESSION['admin_username'])) {
    echo "<script>alert('Please log in to view your request history.'); window.location.href = 'admin_login_page.php';</script>";
    exit();
}

$dbUsername = $_SESSION['admin_username'];

$low_stock_message = "";
$low_stock_query = "SELECT blood_group FROM stock WHERE quantity < 3";
$low_stock_result = $conn->query($low_stock_query);

if ($low_stock_result && $low_stock_result->num_rows > 0) {
    $low_stock_blood_groups = [];
    while ($row = $low_stock_result->fetch_assoc()) {
        $low_stock_blood_groups[] = $row['blood_group'];
    }
    $low_stock_message = "Warning: The following blood groups are running low (less than 3 units): " . implode(", ", $low_stock_blood_groups);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Management System</title>
    <style>
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin-right: 30px;
        }
        .btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<?php include 'header_footer_admin.html';?>

    <header>
        <h1>Blood Bank Management System</h1>
    </header>
    <?php if (!empty($low_stock_message)): ?>
    <p style="color: red; font-weight: bold;"><?php echo $low_stock_message; ?></p>
<?php endif; ?>
        <div class="section">
            <center>
                <h2>Welcome to the Admin Portal</h2>
                <p>Use the navigation above to register or manage donors, manage hospital requests, and accept or reject requests from hospitals.</p>
                <a href="expired_bottles.php" class="btn">View Expired Blood Quantities</a>
                <a href="stock.php" class="btn">Total Available Stock</a>
            </center>
        </div>
    
</body>
</html>
