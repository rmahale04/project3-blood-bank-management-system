<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donor</title>
    <link rel="stylesheet" href="blood_request_style.css">
</head>
<body>
<?php
include 'header_footer_admin.html';
require_once("conn.php");


$donor_id = $_REQUEST['donor_id'];
$query = "SELECT * FROM donor WHERE donor_id='" . $donor_id . "'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $record = $result->fetch_assoc();
} else {
    echo "Donor not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_first_name = $_POST['donor_first_name'];
    $donor_last_name = $_POST['donor_last_name'];
    $dob = $_POST['dob'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];
    $donor_blood_group = $_POST['donor_blood_group'];
    $donor_email = $_POST['donor_email'];
    $donor_phone_no = $_POST['donor_phone_no'];
    // $donor_address = $_POST['donor_address'];    
    $house_number = $_POST['house_number'];
    $locality = $_POST['locality'];
    $city = $_POST['city'];
    $pin_code = $_POST['pin_code'];
    $donor_status = $_POST['donor_status'];
    $status_remark = $_POST['status_remark'];

    $query1 = "UPDATE donor SET 
        donor_first_name = '" . $donor_first_name . "',
        donor_last_name = '" . $donor_last_name . "',
        dob = '" . $dob . "',
        weight = '" . $weight . "',
        gender = '" . $gender . "',
        donor_blood_group = '" . $donor_blood_group . "',
        donor_email = '" . $donor_email . "',
        donor_phone_no = '" . $donor_phone_no . "',
        house_number = '".$house_number."',
        locality = '".$locality."',
        city = '".$city."',
        pin_code = '".$pin_code."',
        donor_status = '" . $donor_status . "',
        status_remark = '" . $status_remark . "'
        WHERE donor_id = '" . $donor_id . "'";
    
    if ($conn->query($query1)) {
        echo "Donor details updated successfully!";
        header("Location: donor_list.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<form action="" method="post">
    <h2>Edit Donor Details</h2>
    <div>
        <label for="donor_first_name">First Name:</label><br>
        <input type="text" id="donor_first_name" name="donor_first_name" value="<?php echo $record['donor_first_name']; ?>" required><br>
    </div>
    <div>
        <label for="donor_last_name">Last Name:</label><br>
        <input type="text" id="donor_last_name" name="donor_last_name" value="<?php echo $record['donor_last_name']; ?>" required><br>
    </div>
    <div>
        <label for="dob">Date of Birth:</label><br>
        <input type="date" id="dob" name="dob" value="<?php echo $record['dob']; ?>" required><br>
    </div>
    <div>
        <label for="weight">Weight:</label><br>
        <input type="number" id="weight" name="weight" value="<?php echo $record['weight']; ?>" required><br>
    </div>
    <div>
        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender" required>
            <option value="male" <?php echo $record['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo $record['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
            <option value="others" <?php echo $record['gender'] == 'others' ? 'selected' : ''; ?>>Others</option>
        </select><br>
    </div>
    <div>
        <label for="donor_blood_group">Blood Group:</label><br>
        <input type="text" id="donor_blood_group" name="donor_blood_group" value="<?php echo $record['donor_blood_group']; ?>" required><br>
    </div>
    <div>
        <label for="donor_email">Email:</label><br>
        <input type="email" id="donor_email" name="donor_email" value="<?php echo $record['donor_email']; ?>" required><br>
    </div>
    <div>
        <label for="donor_phone_no">Phone Number:</label><br>
        <input type="text" id="donor_phone_no" name="donor_phone_no" value="<?php echo $record['donor_phone_no']; ?>" required><br>
    </div>
    <div>
        <label for="house_number">House No.:</label><br>
        <input type="text" name="house_number" id="house_number" value="<?php echo $record['house_number']; ?>" required><br>
    </div>
    <div>
        <label for="house_number">Locality:</label><br>
        <input type="text" name="locality" id="locality" value="<?php echo $record['locality']; ?>" required><br>
    </div>
    <div>
        <label for="house_number">City:</label><br>
        <input type="text" name="city" id="city" value="<?php echo $record['city']; ?>" required><br>
    </div>
    <div>
        <label for="house_number">Pincode:</label><br>
        <input type="text" name="pin_code" id="pin_code" value="<?php echo $record['pin_code']; ?>" required><br>
    </div>
    <div>
        <label for="donor_status">Status:</label><br>
        <select id="donor_status" name="donor_status" required>
            <option value="active" <?php echo $record['donor_status'] == 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo $record['donor_status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            <option value="blacklisted" <?php echo $record['donor_status'] == 'blacklisted' ? 'selected' : ''; ?>>Blacklisted</option>
        </select><br>
    </div>
    <div>
        <label for="status_remark">Status Remark:</label><br>
        <input type="text" id="status_remark" name="status_remark" value="<?php echo $record['status_remark']; ?>"><br>
    </div>
    <div>
        <button type="submit">Save Changes</button>
    </div>
</form>
</body>
</html>
