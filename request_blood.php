<?php
    session_start();
    require_once("conn.php");
    // include "header_footer_admin.html";

    if(empty($_SESSION['hospital_id'])) {
        echo $_SESSION["hospital_id"];
        echo "<script>alert('Please log in to submit a blood request.'); window.location.href = 'hospital_login_page.php';</script>";
        exit();
    }

    $hospital_id = $_SESSION['hospital_id'];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
        $quantity = intval($_POST['quantity']);
        // $hospital_id = 1;   

        if(!empty($blood_group) && !empty($quantity) && $quantity >= 1 && $quantity <=20){
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="blood_request_style.css">
    <title>Blood Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:beige;
            margin: 0;
            padding: 0;
        }
        header {
            background-color:  #b91d1d;;
            color: #ffffff;
            padding: 10px;
            text-align: center;
        }
        nav {
            background-color: #800000;
            padding: 10px;
            display: flex;
            justify-content: space-around;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 0 10px;
        }
        nav a:hover {
            background-color: rgb(177, 46, 46);
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .section {
            background-color: white;
            margin: 0px auto;
            padding: 20px;
            text-align: center;
            /* width: 80%; */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #b30000;
        }

        footer {
            background-color: #b91d1d;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hospital Blood Bank Management System</h1>
    </header>
    <nav>
        <a href="hospital_home_page.php">Home</a>
        <a href="request_blood.php">Request Blood</a>
        <a href="history_request(hospital_side).php">Request Status</a>
        <a href="hospital_login_page.php">Login</a>
        <a href="hospital_logout.php">Log out</a>
    </nav>

    <footer>
        <p>&copy; Hospital Blood Bank Management System</p>
    </footer>
    <h2>Request Blood from Blood Bank</h2>
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
    <input type="number" name="quantity" id="quantity" min="1" max="20" required>
    <br><br>

    <button type="submit">Submit Request</button>
    <br><br><br>
</div>
    </form>
</body>
</html>


