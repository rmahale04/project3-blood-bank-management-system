<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="blood_request_style.css">
    <title>Add Donor</title>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<?php include 'header_footer_admin.html';?>
<?php
    require_once("conn.php");

    $errors = [
        "fname" => "",
        "mname" => "",
        "lname" => "",
        "dob" => "",
        "weight" => "",
        "gender" => "",
        "blood_group" => "",
        "email" => "",
        "phone_no" => "",
        // "address" => "",
        "house_number" => "",
        "locality" => "",
        "city" => "",
        "pin_code" => "",
    ];

    $formData = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($_POST as $key => $value) {
            $formData[$key] = htmlspecialchars(trim($value));
        }

        $dob = $formData['dob'] ?? null;
        // $weight = intval($formData['weight'] ?? 0);
        $weight = floatval($formData['weight'] ?? 0);
        $birthDate = new DateTime($dob);
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y;

        if (empty($formData["fname"])) {
            $errors["fname"] = "First name is required.";
        }
        if (empty($formData["mname"])) {
            $errors["mname"] = "Middle name is required.";
        }
        if (empty($formData["lname"])) {
            $errors["lname"] = "Last name is required.";
        }
        if (empty($dob)) {
            $errors["dob"] = "Date of birth is required.";
        } elseif ($age < 18 || $age > 60) {
            $errors["dob"] = "Age must be between 18 and 60 years.";
        }
        if ($weight < 45) {
            $errors["weight"] = "Weight must be at least 45 kg.";
        }
        if (empty($formData["gender"])) {
            $errors["gender"] = "Gender is required.";
        }
        if (empty($formData["blood_group"])) {
            $errors["blood_group"] = "Blood group is required.";
        }
        if (empty($formData["email"])) {
            $errors["email"] = "Email is required.";
        } elseif (!filter_var($formData["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format.";
        } else {
            $check_email_query = "SELECT * FROM donor WHERE donor_email = '" . mysqli_real_escape_string($conn, $formData["email"]) . "'";
            $check_email_result = mysqli_query($conn, $check_email_query);
            if (mysqli_num_rows($check_email_result) > 0) {
                $errors["email"] = "Email already exists.";
            }
        }
        if (empty($formData["phone_no"])) {
            $errors["phone_no"] = "Phone number is required.";
        } elseif (!preg_match("/^[0-9]{10}$/", $formData["phone_no"])) {
            $errors["phone_no"] = "Invalid phone number.";
        } else {
            $check_phone_query = "SELECT * FROM donor WHERE donor_phone_no = '" . mysqli_real_escape_string($conn, $formData["phone_no"]) . "'";
            $check_phone_result = mysqli_query($conn, $check_phone_query);
            if (mysqli_num_rows($check_phone_result) > 0) {
                $errors["phone_no"] = "Phone number already exists.";
            }
        }
        // if (empty($formData["address"])) {
        //     $errors["address"] = "Address is required.";
        // }
        if (empty($formData["house_number"])) {
            $errors["house_number"] = "House number is required.";
        }
        if (empty($formData["locality"])) {
            $errors["locality"] = "Locality is required.";
        }
        if (empty($formData["city"])) {
            $errors["city"] = "City is required.";
        }
        if (empty($formData["pin_code"])) {
            $errors["pin_code"] = "PIN code is required.";
        } elseif (!preg_match("/^[0-9]{6}$/", $formData["pin_code"])) {
            $errors["pin_code"] = "PIN code must be a 6-digit number.";
        }
        

        if (!array_filter($errors)) {
            $query = "INSERT INTO donor(donor_first_name, donor_middle_name, donor_last_name, dob, weight, gender, donor_blood_group, donor_email, donor_phone_no, house_number, locality, city, pin_code)
                      VALUES ('" . mysqli_real_escape_string($conn, $formData["fname"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["mname"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["lname"]) . "', 
                              '" . mysqli_real_escape_string($conn, $dob) . "', 
                              $weight, 
                              '" . mysqli_real_escape_string($conn, $formData["gender"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["blood_group"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["email"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["phone_no"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["house_number"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["locality"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["city"]) . "', 
                              '" . mysqli_real_escape_string($conn, $formData["pin_code"]) . "')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo "<script>
                        alert('Donor added successfully.');
                        setTimeout(function() {
                            window.location.href = 'add_donor.php';
                        }, 1500); 
                      </script>";
                // header("Location: add_donor.php");  // Redirect after 3 seconds
            } else {
                echo "<script>alert('Error: Could not add donor.');</script>";
            }
        }
    }
    ?>

    <form action="" method="post">
        <h2>Add Donor</h2>

        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" placeholder="First Name" value="<?php echo $formData["fname"] ?? ""; ?>">
        <span class="error"><?php echo $errors["fname"]; ?></span>
        <br>

        <label for="mname">Middle Name:</label>
        <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?php echo $formData["mname"] ?? ""; ?>">
        <span class="error"><?php echo $errors["mname"]; ?></span>
        <br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" placeholder="Last Name" value="<?php echo $formData["lname"] ?? ""; ?>">
        <span class="error"><?php echo $errors["lname"]; ?></span>
        <br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo $formData["dob"] ?? ""; ?>">
        <span class="error"><?php echo $errors["dob"]; ?></span>
        <br>

        <label for="weight">Weight (kg):</label>
        <input type="text" id="weight" name="weight" placeholder="Weight" 
            pattern="^\d+(\.\d{1,2})?$" 
            oninput="this.value = this.value.replace(/[^0-9\.]/g, '').replace(/^(\d+(\.\d{0,2})?).*$/, '$1')" 
            value="<?php echo $formData["weight"] ?? ""; ?>">
        <span class="error"><?php echo $errors["weight"]; ?></span>
        <br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="">Select Gender</option>
            <option value="Male" <?php echo ($formData["gender"] ?? "") == "Male" ? "selected" : ""; ?>>Male</option>
            <option value="Female" <?php echo ($formData["gender"] ?? "") == "Female" ? "selected" : ""; ?>>Female</option>
            <option value="Other" <?php echo ($formData["gender"] ?? "") == "Other" ? "selected" : ""; ?>>Other</option>
        </select>
        <span class="error"><?php echo $errors["gender"]; ?></span>
        <br>

        <label for="blood_group">Blood Group:</label>
        <select id="blood_group" name="blood_group">
            <option value="">Select Blood Group</option>
            <option value="A+" <?php echo ($formData["blood_group"] ?? "") == "A+" ? "selected" : ""; ?>>A+</option>
            <option value="A-" <?php echo ($formData["blood_group"] ?? "") == "A-" ? "selected" : ""; ?>>A-</option>
            <option value="B+" <?php echo ($formData["blood_group"] ?? "") == "B+" ? "selected" : ""; ?>>B+</option>
            <option value="B-" <?php echo ($formData["blood_group"] ?? "") == "B-" ? "selected" : ""; ?>>B-</option>
            <option value="AB+" <?php echo ($formData["blood_group"] ?? "") == "AB+" ? "selected" : ""; ?>>AB+</option>
            <option value="AB-" <?php echo ($formData["blood_group"] ?? "") == "AB-" ? "selected" : ""; ?>>AB-</option>
            <option value="O+" <?php echo ($formData["blood_group"] ?? "") == "O+" ? "selected" : ""; ?>>O+</option>
            <option value="O-" <?php echo ($formData["blood_group"] ?? "") == "O-" ? "selected" : ""; ?>>O-</option>
        </select>
        <span class="error"><?php echo $errors["blood_group"]; ?></span>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email address" value="<?php echo $formData["email"] ?? ""; ?>">
        <span class="error"><?php echo $errors["email"]; ?></span>
        <br>

        <label for="phone_no">Phone Number:</label>
        <input type="text" id="phone_no" name="phone_no" placeholder="Contact No." pattern="[0-9]+"  maxlength="10" 
        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" value="<?php echo $formData["phone_no"] ?? ""; ?>">
        <span class="error"><?php echo $errors["phone_no"]; ?></span>
        <br>

        <!-- <label for="address">Address:</label>
        <textarea id="address" name="address" rows="3"><?php echo $formData["address"] ?? ""; ?></textarea>
        <span class="error"><?php /*echo $errors["address"]; */?></span>
        <br><br> -->

        <!-- <h2>Address</h2> -->
        <label for="house_number">House Number:</label>
        <input type="text" id="house_number" name="house_number" placeholder="House number & identifiable landmarks" value="<?php echo $formData["house_number"] ?? ""; ?>" required>
        <span class="error"><?php echo $errors["house_number"]; ?></span>
        <br>

        <label for="locality">Locality:</label>
        <input type="text" id="locality" name="locality" placeholder="Neighborhood or area" value="<?php echo $formData["locality"] ?? ""; ?>" required>
        <span class="error"><?php echo $errors["locality"]; ?></span>
        <br>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" placeholder="City name" value="<?php echo $formData["city"] ?? ""; ?>" required>
        <span class="error"><?php echo $errors["city"]; ?></span>
        <br>

        <label for="pin_code">PIN Code:</label>
        <input type="text" id="pin_code" name="pin_code" placeholder="6-digit postal code" maxlength="6" pattern="[0-9]{6}" value="<?php echo $formData["pin_code"] ?? ""; ?>" required>
        <span class="error"><?php echo $errors["pin_code"]; ?></span>
        <br><br>

        <button type="submit">Add Donor</button>
    </form>
    <br>
    <br>
    <br>
</body>
</html>