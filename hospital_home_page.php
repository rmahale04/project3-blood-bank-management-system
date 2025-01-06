<?php
session_start();
require_once("conn.php");

// if (!isset($_SESSION['hospital_id'])) {
//     header("Location: hospital_login_page.php");
//     exit();
// }

// $hospital_id = $_SESSION['hospital_id'];

if (isset($_SESSION['hospital_id'])) {
    $hospital_id = $_SESSION['hospital_id'];

    // Fetch the hospital name from the database
    $query = "SELECT hospital_name FROM hospital WHERE hospital_id = '$hospital_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $hospital_name = $row['hospital_name'];
    } else {
        echo "<p class='error'>Error fetching hospital name.</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Blood Bank Management</title>
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
        <!-- <a href="view_responses.html">View Responses</a> -->
        <!-- <a href="contact_admin.html">Contact Admin</a> -->
    </nav>
    <div class="content">
        <div class="section">
            <center>
                <?php 
                    if (!empty($hospital_name)) {
                        echo "<h3>".$hospital_name."</h3>";
                    }
                ?>
            </center>
            <h2>Welcome to the Hospital Portal</h2>
            <p>Use the navigation above to manage blood requests, view donation history, and more.</p>
        </div>
    </div>
    
    <footer>
        <p>&copy; Blood Bank Management System</p>
    </footer>
</body>
</html>
