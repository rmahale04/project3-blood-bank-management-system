<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible=IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Blood Bank Management System</title>
    <link rel="stylesheet" href="login_page_style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html> -->
<?php
require_once("conn.php");

$usernameOrEmailErr = $passwordErr = $loginErr = "";
$usernameOrEmail = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Username or Email validation
    if (empty($_POST["username_or_email"])) {
        $usernameOrEmailErr = "Username or Email is required";
    } else {
        $usernameOrEmail = trim($_POST["username_or_email"]);
    }

    // Password validation
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no validation errors, proceed with login
    if (empty($usernameOrEmailErr) && empty($passwordErr)) {
        // Query to check username/email and password
        $query = "SELECT admin_username, admin_email, admin_password FROM admin 
                  WHERE admin_username = ? OR admin_email = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ss", $usernameOrEmail, $usernameOrEmail);
            // Execute the query
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            // Check if a user was found
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Fetch user details
                mysqli_stmt_bind_result($stmt, $dbUsername, $dbEmail, $dbPassword);
                mysqli_stmt_fetch($stmt);

                // Verify password
                if (password_verify($password, $dbPassword)) {
                    // Successful login, redirect to admin dashboard
                    session_start();
                    $_SESSION["admin_username"] = $dbUsername;
                    header("Location: home_page.html");
                    exit();
                } else {
                    $loginErr = "Invalid password";
                }
            } else {
                $loginErr = "No account found with that username or email";
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
    <title>Admin Login - Blood Bank Management System</title>
    <link rel="stylesheet" href="login_page_style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username_or_email" placeholder="Username or Email" 
                   value="<?php echo htmlspecialchars($usernameOrEmail); ?>">
            <span class="error"><?php echo $usernameOrEmailErr; ?></span>

            <input type="password" name="password" placeholder="Password">
            <span class="error"><?php echo $passwordErr; ?></span>

            <span class="error"><?php echo $loginErr; ?></span>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>