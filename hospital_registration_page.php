<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="blood_request_style.css">
    <title>Hospital Registration - Blood Bank Management System</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: beige;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #b91d1d;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        nav {
            background-color: #800000;
            position: fixed;
            top: 50px; /* Directly below the header */
            left: 0;
            right: 0;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
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

        footer {
            background-color: #b91d1d;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .content {
            margin-top: 110px; /* Header height (50px) + Nav height (60px) */
            padding: 20px;
        }
        .search-bar {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-bar input[type="text"] {
            padding: 5px;
            border: none;
            border-radius: 4px;
            width: 200px;
            margin-right: 5px;
        }

        .search-bar button {
            padding: 5px 10px;
            border: none;
            background-color: #b91d1d;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: rgb(177, 46, 46);
        }

        .section {
            background-color: white;
            margin: 20px auto;
            padding: 20px;
            /* width: 80%; */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        
        .section h2 {
            color: #b30000;
        }
    </style>
</head>
<body>
    <header>
        <h1>Blood Bank Management System</h1>
    </header>

   <nav>
            <a href="admin_home_page.php">Home</a>
            <a href="add_donor.php">Add Donor</a>
            <a href="add_donation.php">Add Donation</a>
            <a href="view_donation.php">Donations</a>
            <a href="hospital_registration_page.php">Add Hospital</a>
            <a href="view_request_list.php">Manage Requests</a>
            <a href="hospital_list.php">Hospitals</a>
            <a href="donor_list.php">Donors</a>
            <a href="stock.php">Stock</a>
            <a href="supply_list.php">Supply</a>
            <!-- <div class="search-bar">
                <input type="text" placeholder="Search...">
                <button type="button">Search</button>
            </div> -->
    </nav>
    
    <div class="content"></div>
    <div class="registration-container">
        <?php //include 'header_footer_admin.html';?>
        <h2>Add Hospital</h2>
        <?php
            require_once("conn.php");

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $hospital_username = trim($_POST["hospital_username"]);
                $hospital_email = trim($_POST["hospital_email"]);
                $hospital_contact_no = trim($_POST["hospital_contact_no"]);
                $hospital_name = trim($_POST["hospital_name"]);
                $hospital_address = trim($_POST["hospital_address"]);
                $hospital_city = trim($_POST["hospital_city"]);
                $hospital_pin_code = trim($_POST["hospital_pin_code"]);
                $hospital_password = trim($_POST["hospital_password"]);
                $hospital_confirm_password = trim($_POST["hospital_confirm_password"]);

                $errors = [];

                if (empty($hospital_username)) {
                    $errors[] = "Username is required.";
                } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $hospital_username)) {
                    $errors[] = "Username must be 3-20 characters and contain only letters, numbers, and underscores.";
                }

                if (empty($hospital_email)) {
                    $errors[] = "Email is required.";
                } elseif (!filter_var($hospital_email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Invalid email format.";
                }

                if (empty($hospital_contact_no)) {
                    $errors[] = "Contact number is required.";
                } elseif (!preg_match("/^\d{10}$/", $hospital_contact_no)) {
                    $errors[] = "Contact number must be a valid 10-digit number.";
                }

                if (empty($hospital_password)) {
                    $errors[] = "Password is required.";
                } elseif (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/", $hospital_password)) {
                    $errors[] = "Password must be 8-16 characters long, include at least one letter, one number, and one special character.";
                }

                if ($hospital_password !== $hospital_confirm_password) {
                    $errors[] = "Passwords do not match.";
                }

                if (empty($hospital_name)) {
                    $errors[] = "Hospital name is required.";
                }

                if (empty($hospital_address)) {
                    $errors[] = "Hospital address is required.";
                }
                
                if (empty($hospital_city)) {
                    $errors[] = "City is required.";
                } elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $hospital_city)) {
                    $errors[] = "City must only contain letters and spaces, and be 2-50 characters long.";
                }
            
                // Validate pincode
                if (empty($hospital_pin_code)) {
                    $errors[] = "Pincode is required.";
                } elseif (!preg_match("/^\d{6}$/", $hospital_pin_code)) {
                    $errors[] = "Pincode must be a valid 6-digit number.";
                }
                // If no errors, insert data into the database
                if (empty($errors)) {
                    // Hash the password before storing it
                    $hashed_password = password_hash($hospital_password, PASSWORD_DEFAULT);

                    // Prepare SQL query
                    $query = "INSERT INTO hospital (hospital_username, hospital_email, hospital_password, hospital_contact_no, hospital_name, hospital_address, hospital_city, hospital_pin_code) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $query);

                    if ($stmt) {
                        // Bind parameters
                        mysqli_stmt_bind_param($stmt, "ssssssss", $hospital_username, $hospital_email, $hashed_password, $hospital_contact_no, $hospital_name, $hospital_address, $hospital_city, $hospital_pin_code);
                        // Execute the query
                        if (mysqli_stmt_execute($stmt)) {
                            echo "<p class='success'>Registration successful.</p>";
                            header("Location:hospital_list.php");
                        } else {
                            echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
                        }

                        // Close the statement
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<p class='error'>Error preparing the query: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    // Display validation errors
                    foreach ($errors as $error) {
                        echo "<p class='error'>$error</p>";
                    }
                }

                // Close the database connection
                mysqli_close($conn);
            }
?>
        <form action="" method="post">
            <label for="fname">Hospital Name:</label>
            <input type="text" name="hospital_name" placeholder="Hospital's Name" maxlength="100" minlength="3" required><br>

            <label for="fname">Username:</label>
            <input type="text" name="hospital_username" placeholder="Username" maxlength="20" required><br>
            
            <label for="fname">Email:</label>
            <input type="email" name="hospital_email" placeholder="Email" maxlength="60" required><br>
            <!-- <input type="text" name="hospital_contact_no" placeholder="Contact Number" required> -->
            
            <label for="fname">Contact no.:</label>
            <input type="text" id="hospital_contact_no" name="hospital_contact_no" placeholder="Contact No." pattern="[0-9]+"  maxlength="10" 
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" value="<?php echo $formData["hospital_contact_no"] ?? ""; ?>" required>
            <br>
            <!-- <input type="text" name="hospital_address" placeholder="hospital address" required> -->
            
            <label for="fname">Address:</label>
            <input type="text" name="hospital_address" placeholder="Hospital's Address" maxlength="200" minlength="10" required><br>
            
            <label for="fname">City:</label>
            <input type="text" name="hospital_city" placeholder="City" maxlength="100" minlength="3" required><br>
            
            <label for="fname">Pincode:</label>
            <input type="text" id="hospital_pin_code" name="hospital_pin_code" placeholder="6-digit postal code" maxlength="6" pattern="[0-9]{6}" value="<?php echo $formData["hospital_pin_code"] ?? ""; ?>"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
            <br>

            <label for="fname">Password:</label>
            <input type="password" name="hospital_password" placeholder="Password" maxlength="16" minlength="8" required><br>

            <label for="fname">Confirm Password:</label>
            <input type="password" name="hospital_confirm_password" placeholder="Confirm Password" maxlength="16" minlength="8" required>
            <br><br>
            
            <button type="submit">Add Hospital</button>
        </form>
    </div>
</body>
</html>
