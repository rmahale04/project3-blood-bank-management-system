<?php
require_once("conn.php");
include "header_footer_admin.html";

// $hospital_id = isset($_GET['hospital_id']) ? $_GET['hospital_id'] : '';
// $hospital_name = isset($_GET['hospital_name']) ? $_GET['hospital_name'] : '';
// $blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
// $quantity_require = isset($_GET['quantity_require']) ? $_GET['quantity_require'] : '';

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supply'])) {
//     $blood_group = $_POST['blood_group'];
//     $quantity_supplied = (int)$_POST['quantity'];
//     $hospital_id = $_POST['hospital_id'];

//     // Check if sufficient stock is available
//     $stock_check_query = "SELECT quantity FROM stock WHERE blood_group = '$blood_group'";
//     $stock_result = $conn->query($stock_check_query);

//     if ($stock_result->num_rows > 0) {
//         $stock_row = $stock_result->fetch_assoc();
//         if ($stock_row['quantity'] >= $quantity_supplied) {
//             // Update stock
//             $update_stock_query = "UPDATE stock SET quantity = quantity - $quantity_supplied WHERE blood_group = '$blood_group'";
//             if ($conn->query($update_stock_query) === TRUE) {
//                 echo "<script>alert('Blood supply recorded successfully.');</script>";
//             } else {
//                 echo "<script>alert('Error updating stock.');</script>";
//             }
//         } else {
//             echo "<script>alert('Insufficient stock for $blood_group.');</script>";
//         }
//     } else {
//         echo "<script>alert('Blood group not found in stock.');</script>";
//     }
// }
if (isset($_GET['hospital_id'], $_GET['blood_group'], $_GET['quantity_require'], $_GET['request_id'])) {
    $blood_group = $_GET['blood_group'];
    $quantity_require = $_GET['quantity_require'];
    $hospital_id = $_GET['hospital_id'];
    $request_id = $_GET['request_id'];

    $hospital_query = "SELECT hospital_name FROM hospital WHERE hospital_id = '".$hospital_id."' ";
    $hospital_result = mysqli_query($conn, $hospital_query);

    if ($hospital_result && mysqli_num_rows($hospital_result) > 0) {
        $hospital_data = mysqli_fetch_assoc($hospital_result);
        $hospital_name = $hospital_data['hospital_name'];
    } else {
        $hospital_name = "Unknown Hospital";
    }
}else {
    echo "Invalid request.";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supply'])) {
    $quantity_supplied = (int)$_POST['quantity'];

    $stock_check_query = "SELECT quantity FROM stock WHERE blood_group = '$blood_group'";
    $stock_result = $conn->query($stock_check_query);

    if ($stock_result->num_rows > 0) {
        $stock_row = $stock_result->fetch_assoc();
        if ($stock_row['quantity'] >= $quantity_supplied) {
            // Update supply and stock
            $supply_query = "INSERT INTO supply (request_id, supply_date, supply_quantity) 
                             VALUES ('$request_id', NOW(), '$quantity_supplied')";
            $update_stock_query = "UPDATE stock SET quantity = quantity - $quantity_supplied 
                                   WHERE blood_group = '$blood_group'";
            $update_request_query = "UPDATE blood_request SET status = 'Completed' WHERE request_id = '$request_id'";

            if ($conn->query($supply_query) === TRUE && $conn->query($update_stock_query) === TRUE && $conn->query($update_request_query) === TRUE) {
                echo "<script>alert('Blood supply recorded successfully.');</script>";
                echo "<script>window.location.href = 'view_request_list.php';</script>";
            } else {
                echo "<script>alert('Error processing the request. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Insufficient stock for $blood_group.');</script>";
        }
    } else {
        echo "<script>alert('Blood group not found in stock.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Supply</title>
    <link rel="stylesheet" href="blood_request_style.css">
    <style>
        /* table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        } */
         /*
        h2{
            text-align: center;
            color: #dc3545;
        }
        .container {
            width: 50%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group input[readonly] {
            background-color: #f2f2f2;
        }
        */
        btn {
            width: 100%;
            padding: 10px;
            background-color: #b30000;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            /* transition: background-color 0.3s ease, transform 0.2s ease; */
        }
        btn:hover {
            background-color: #a71d2a;
            /* transform: scale(1.05); */
        }
        
    </style>
</head>
<body>
    <div class="container">
        <center><h1>Blood Supply</h1></center>
        <!-- <h2>Blood Supply</h2> -->
        <form method="POST" style="width: 60%; margin: auto;">
            <div class="form-group">
                <label>Hospital Name:</label>
                <input type="text" name="hospital_name" value="<?php echo $hospital_name; ?>" readonly>
                <br><br>
                <label>Blood Group:</label></td>
                <input type="text" name="blood_group" value="<?php echo $blood_group; ?>" readonly>
                <br><br>
                <label>Requested Quantity (Units):</label>
                <input type="number" name="quantity_require" value="<?php echo $quantity_require; ?>" readonly>
                <br><br>
                <label>Available Quantity:</label>
                    <?php
                    // Fetch the available quantity from stock
                    $stock_check_query = "SELECT quantity FROM stock WHERE blood_group = '$blood_group'";
                    $stock_result = $conn->query($stock_check_query);
                    if ($stock_result->num_rows > 0) {
                        $stock_row = $stock_result->fetch_assoc();
                        echo "<input type='number' name='available_quantity' value='" . $stock_row['quantity'] . "' readonly>";
                    } else {
                        echo "<input type='number' name='available_quantity' value='0' readonly>";
                    }
                    ?>
                <br><br>
                <label>Supply Quantity (Units):</label>
                <input type="number" name="quantity" min="1" max="<?php echo $quantity_require; ?>" required>
            </div>
            <br><br>
            <div class="btn">
                <button type="submit" name="supply">Supply</button>
            </div>
        </form>
       
    </div>
</body>
</html>

<?php
$conn->close();
?>