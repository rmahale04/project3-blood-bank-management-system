<?php 
require_once("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_POST['donor_id'];
    $quantity = (int)$_POST['quantity'];
    $donation_date = $_POST['donation_date'];
    $current_date = date('Y-m-d');

    if (empty($donor_id) || empty($quantity) || empty($donation_date)) {
        echo "<script>alert('Error: All fields are required.');</script>";
    } elseif ($donation_date !== $current_date) {
        echo "<script>alert('Error: Donation date must be today\'s date ($current_date).');</script>";
    } elseif ($quantity < 1 || $quantity > 2) {
        echo "<script>alert('Error: Donation quantity must be 1 or 2 units.');</script>";
    } else {
        $donor_check_query = "SELECT donor_blood_group FROM donor WHERE donor_id = '$donor_id'";
        $donor_result = $conn->query($donor_check_query);

        if ($donor_result === false) {
            echo "<script>alert('Error: Could not execute donor check query. " . $conn->error . "');</script>";
        } elseif ($donor_result->num_rows == 0) {
            echo "<script>alert('Error: Donor ID does not exist.');</script>";
        } else {
            $donor = $donor_result->fetch_assoc();
            $blood_group = $donor['donor_blood_group'];

            // fetching last donation date
            $last_donation_query = "SELECT MAX(donation_date) AS last_donation 
                                    FROM donation 
                                    WHERE donor_id = '$donor_id'";
            $last_donation_result = $conn->query($last_donation_query);

            if ($last_donation_result === false) {
                echo "<script>alert('Error: Could not fetch last donation date. " . $conn->error . "');</script>";
            } else {
                $last_donation = $last_donation_result->fetch_assoc()['last_donation'];

                // checking donation interval
                if ($last_donation && (strtotime($current_date) < strtotime($last_donation . ' +56 days'))) {
                    $next_allowed_date = date('Y-m-d', strtotime($last_donation . ' +56 days'));
                    echo "<script>alert('Error: Donor cannot donate again before $next_allowed_date.');</script>";
                } else {
                    // Check the number of donations in the last year
                    $one_year_ago = date('Y-m-d', strtotime('-1 year'));
                    $donation_count_query = "SELECT COUNT(*) AS donation_count 
                                             FROM donation 
                                             WHERE donor_id = '$donor_id' AND donation_date >= '$one_year_ago'";
                    $donation_count_result = $conn->query($donation_count_query);

                    if ($donation_count_result === false) {
                        echo "<script>alert('Error: Could not fetch donation count. " . $conn->error . "');</script>";
                    } else {
                        $donation_count = $donation_count_result->fetch_assoc()['donation_count'];

                        if ($donation_count >= 6) {
                            echo "<script>alert('Error: Donor can only donate up to 6 times in a year.');</script>";
                        } else {
                            // Calculate expiry date (35 days from donation date)
                            $expiry_date = date('Y-m-d', strtotime($donation_date . ' +35 days'));

                            // Insert donation and update stock
                            $insert_query = "INSERT INTO donation (donor_id, quantity, donation_date, expiry_date) 
                                             VALUES ('$donor_id', '$quantity', '$current_date', '$expiry_date')";

                            if ($conn->query($insert_query) === TRUE) {
                                $stock_query = "SELECT quantity FROM stock WHERE blood_group = '$blood_group'";
                                $stock_result = $conn->query($stock_query);

                                if ($stock_result && $stock_result->num_rows > 0) {
                                    $stock = $stock_result->fetch_assoc();
                                    $new_quantity = $stock['quantity'] + $quantity;

                                    $update_stock_query = "UPDATE stock
                                                           SET quantity = $new_quantity 
                                                           WHERE blood_group = '$blood_group'";
                                    if ($conn->query($update_stock_query) === TRUE) {
                                        echo "<script>alert('Donation added successfully!');
                                              window.location.href = 'view_donation.php';</script>";
                                    } else {
                                        echo "<script>alert('Error: Could not update stock. " . $conn->error . "');</script>";
                                    }
                                } else {
                                    echo "<script>alert('Error: Could not fetch stock information.');</script>";
                                }
                            } else {
                                echo "<script>alert('Error: Could not insert donation. " . $conn->error . "');</script>";
                            }
                        }
                    }
                }
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Donation</title>
    <link rel="stylesheet" href="blood_request_style.css">
</head>
<body>
<?php include 'header_footer_admin.html';?>
    <h2>Add Donation</h2>
    <form method="post" action="">
        <label for="donor_id">Donor ID:</label>
        <input type="number" id="donor_id" name="donor_id" required><br><br>

        <label for="quantity">Quantity (units):</label>
        <input type="number" id="quantity" name="quantity" min="1" max="2" required><br><br>

        <label for="donation_date">Donation Date:</label>
        <?php
            $current_date = date('Y-m-d');
            $one_year_ago = date('Y-m-d', strtotime('-1 year'));
        ?>
        <input type="date" id="donation_date" name="donation_date" required min="<?php echo $one_year_ago; ?>" max="<?php echo $current_date; ?>"><br><br>

        <button type="submit">Add Donation</button>
    </form>
</body>
</html>