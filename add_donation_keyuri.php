<?php
require_once("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_POST['donor_id'];
    $quantity = (int)$_POST['quantity'];
    $donation_date = $_POST['donation_date'];

    // Validate inputs
    if (empty($donor_id) || empty($quantity) || empty($donation_date)) {
        echo "<script>alert('Error: All fields are required.');</script>";
    } elseif ($donation_date > date('Y-m-d')) {
        echo "<script>alert('Error: Donation date cannot be in the future.');</script>";
    } elseif ($quantity <= 0 || $quantity > 50) {
        echo "<script>alert('Error: Quantity must be between 1 and 50 units.');</script>";
    } else {
        // Check if donor exists and retrieve blood group
        $donor_check_query = "SELECT donor_blood_group FROM donor WHERE donor_id = ?";
        $stmt = $conn->prepare($donor_check_query);
        $stmt->bind_param("i", $donor_id);
        $stmt->execute();
        $donor_result = $stmt->get_result();

        if ($donor_result->num_rows === 0) {
            echo "<script>alert('Error: Donor ID does not exist.');</script>";
        } else {
            $donor = $donor_result->fetch_assoc();
            $blood_group = $donor['donor_blood_group'];

            // Add donation record
            $insert_query = "INSERT INTO donation (donor_id, quantity, donation_date) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iis", $donor_id, $quantity, $donation_date);

            if ($stmt->execute()) {
                // Update blood stock
                $update_stock_query = "UPDATE stock SET quantity = quantity + ? WHERE blood_group = ?";
                $stock_stmt = $conn->prepare($update_stock_query);
                $stock_stmt->bind_param("is", $quantity, $blood_group);

                if ($stock_stmt->execute()) {
                    echo "<script>alert('Donation added successfully! Stock updated.'); window.location.href = 'view_donation.php';</script>";
                } else {
                    echo "<script>alert('Donation recorded, but stock update failed.');</script>";
                }
            } else {
                echo "<script>alert('Error: Could not insert donation. " . $conn->error . "');</script>";
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
<?php include 'header_footer_admin.html'; ?>
    <h2>Add Donation</h2>
    <form method="POST" action="">
        <label for="donor_id">Donor ID:</label>
        <input type="number" id="donor_id" name="donor_id" required><br><br>

        <label for="quantity">Quantity (units):</label>
        <input type="number" id="quantity" name="quantity" min="1" max="50" required><br><br>

        <label for="donation_date">Donation Date:</label>
        <input type="date" id="donation_date" name="donation_date" required><br><br>

        <button type="submit">Add Donation</button>
    </form>
</body>
</html>