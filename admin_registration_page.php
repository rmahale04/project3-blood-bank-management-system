<?php
require_once("conn.php");

$nameErr = $emailErr = $usernameErr = $passwordErr = $contactErr = $confirmPasswordErr = "";
$email = $username = $password = $confirmPassword = $contact = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Username validation
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = trim($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            $usernameErr = "Only letters, numbers, and underscores allowed";
        }
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Contact number validation
    if (empty($_POST["contact_no"])) {
        $contactErr = "Contact number is required";
    } else {
        $contact = trim($_POST["contact_no"]);
        if (!preg_match("/^\d{10}$/", $contact)) {
            $contactErr = "Contact number must be 10 digits long";
        }
    }

    // Password validation
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = trim($_POST["password"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
            $passwordErr = "Password must contain at least one uppercase letter, one lowercase letter, and one number";
        }
    }

    // Confirm Password validation
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Confirm Password is required";
    } else {
        $confirmPassword = trim($_POST["confirm_password"]);
        if ($confirmPassword != $password) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    // Check if the username or email already exists in the database
    if (empty($usernameErr) && empty($emailErr) && empty($contactErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        // Query to check if username or email already exists
        $query = "SELECT admin_username, admin_email FROM admin WHERE admin_username = ? OR admin_email = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            // Execute the query
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            // If a username or email already exists
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Check if the username already exists
                $usernameExists = false;
                $emailExists = false;
                mysqli_stmt_bind_result($stmt, $existingUsername, $existingEmail);

                while (mysqli_stmt_fetch($stmt)) {
                    if ($existingUsername == $username) {
                        $usernameErr = "Username is already taken";
                        $usernameExists = true;
                    }
                    if ($existingEmail == $email) {
                        $emailErr = "Email is already registered";
                        $emailExists = true;
                    }
                }

                // If both username and email are unique, proceed with registration
                if (!$usernameExists && !$emailExists) {
                    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Insert query
                    $insertQuery = "INSERT INTO admin (admin_username, admin_email, admin_password, admin_contact_no) 
                                    VALUES (?, ?, ?, ?)";
                    $insertStmt = mysqli_prepare($conn, $insertQuery);

                    if ($insertStmt) {
                        // Bind parameters for insert
                        mysqli_stmt_bind_param($insertStmt, "ssss", $username, $email, $hashPassword, $contact);
                        // Execute the insert query
                        if (mysqli_stmt_execute($insertStmt)) {
                            header("Location: admin_login_page.html");
                            exit();
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                        mysqli_stmt_close($insertStmt);
                    }
                }
            } else {
                // No duplicates found, proceed with registration
                $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert query
                $insertQuery = "INSERT INTO admin (admin_username, admin_email, admin_password, admin_contact_no) 
                                VALUES (?, ?, ?, ?)";
                $insertStmt = mysqli_prepare($conn, $insertQuery);

                if ($insertStmt) {
                    // Bind parameters for insert
                    mysqli_stmt_bind_param($insertStmt, "ssss", $username, $email, $hashPassword, $contact);
                    // Execute the insert query
                    if (mysqli_stmt_execute($insertStmt)) {
                        header("Location: admin_login_page.php");
                        exit();
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($insertStmt);
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing the query: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible=IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Blood Bank Management System</title>
    <link rel="stylesheet" href="registration_page_style.css">
</head>
<body>
    <div class="registration-container">
        <h2>Admin Registration</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="email" name="email" placeholder="Email" maxlength="60" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error"><?php echo $emailErr; ?></span>

            <input type="text" name="username" placeholder="Username" maxlength="20" value="<?php echo htmlspecialchars($username); ?>">
            <span class="error"><?php echo $usernameErr; ?></span>

            <input type="text" name="contact_no" placeholder="Contact Number" maxlength="10" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($contact); ?>"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>


            <span class="error"><?php echo $contactErr; ?></span>

            <input type="password" name="password" placeholder="Password" maxlength="60">
            <span class="error"><?php echo $passwordErr; ?></span>

            <input type="password" name="confirm_password" placeholder="Confirm Password" maxlength="16" minlength="8">
            <span class="error"><?php echo $confirmPasswordErr; ?></span>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
