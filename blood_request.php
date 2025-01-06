<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="blood_request_style.css">
    <title>Blood Request</title>
</head>
<body>
<?php include 'header_footer_admin.html';?>
    <form method="POST" action="">
    
   
    <label for="blood_group">Blood Type:</label>
        <select name="blood_group" id="blood_group" required>
            <option value="">Select Blood Type</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>
    <br><br>

    <label for="quantity">Quantity (Units):</label>
    <input type="number" name="quantity" id="quantity" min="1" required>
    <br><br>

    <button type="submit">Submit Request</button>
</div>
    </form>
</body>
</html>

<?php
    require_once("conn.php");
    session_start();
    if (!isset($_SESSION['hospital_id'])) {
        header("Location: hospital_login_page.php");  // Redirect to login page
        exit();
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
        $quantity = intval($_POST['quantity']);
        $hospital_id = 1;   

        if(!empty($blood_group) && !empty($quantity) && $quantity > 0){
            $query = "INSERT INTO blood_request (hospital_id, blood_group, quantity_require, requested_date, status)
                      VALUES ('$hospital_id', '$blood_group', '$quantity', CURDATE(), 'Pending')";
    
            $result = mysqli_query($conn, $query);
    
            if($result) {
                echo "<script>alert('Blood request submitted successfully.');</script>";
            }else {
                echo "<script>alert('Error: Could not submit request.');</script>";
            }
        }else{
            echo "<script>alert('Invalid input. Please try again.');</script>";
        }
    }
?>