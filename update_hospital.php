<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="blood_request_style.css">
</head>
<body>
<?php
include 'header_footer_admin.html';
require_once("conn.php");


$hospital_id = $_REQUEST['hospital_id'];
$query = "SELECT * FROM hospital WHERE hospital_id='" . $hospital_id . "'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $record = $result->fetch_assoc();
} else {
    echo "hospital not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital_username = $_POST['hospital_username'];
    $hospital_email = $_POST['hospital_email'];
    $hospital_name = $_POST['hospital_name'];
    $hospital_address = $_POST['hospital_address'];
    $hospital_contact_no = $_POST['hospital_contact_no'];

    $query1 = "UPDATE hospital SET 
        hospital_username = '" . $hospital_username . "',
        hospital_email = '" . $hospital_email . "',
        hospital_name = '" . $hospital_name . "',
        hospital_address = '" . $hospital_address . "',
        hospital_contact_no = '" . $hospital_contact_no . "' 
        where hospital_id= '" . $hospital_id . "'";
    
    if ($conn->query($query1)) {
        echo "Donor details updated successfully!";
        header("Location: hospital_list.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<form action="" method="post"> 
<h2>Edit Hospital Details</h2>
    <div>
        <label for="hospital_username">Username:</label><br>
        <input type="text" id="hospital_username" name="hospital_username" value="<?php echo $record['hospital_username']; ?>" required><br>
    </div>
    <div>
        <label for="hospital_email">Email:</label><br>
        <input type="email" id="hospital_email" name="hospital_email" value="<?php echo $record['hospital_email']; ?>" required><br>
    </div>
    <div>
        <label for="hospital_name">Hospital Name:</label><br>
        <input type="text" id="hospital_name" name="hospital_name" value="<?php echo $record['hospital_name']; ?>" required><br>
    </div>
    <div>
        <label for="hospital_address">Address:</label><br>
        <input type="text" id="hospital_address" name="hospital_address" value="<?php echo $record['hospital_address']; ?>" required><br>
    </div>
    <div>
        <label for="hospital_contact_no">Contact Number:</label><br>
        <input type="text" id="hospital_contact_no" name="hospital_contact_no" value="<?php echo $record['hospital_contact_no']; ?>" required><br>
    </div>
    <div>
        <button type="submit">Save Changes</button>
    </div>
</form>
</body>
</html>