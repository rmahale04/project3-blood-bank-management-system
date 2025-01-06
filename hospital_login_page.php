<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Login - Blood Bank Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b34141;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            border: 1px solid #c5535f;
        }

        .login-container form {
            width: 100%;
            display: flex;
            flex-direction: column; 
            align-items: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #721c24;
        }
        .login-container input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #c5535f;
            border-radius: 4px;
            outline: none;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #b30000;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-container button:hover {
            background-color: #a71d2a;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
        .success {
            color: green;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Hospital Login</h2>
        <?php
        session_start();
        require_once("conn.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $hospital_identifier = trim($_POST["hospital_identifier"]); // Username or email
            $hospital_password = trim($_POST["hospital_password"]);

            $errors = [];

            // Validate input fields
            if (empty($hospital_identifier)) {
                $errors[] = "Username or Email is required.";
            }

            if (empty($hospital_password)) {
                $errors[] = "Password is required.";
            }

            if (empty($errors)) {
                // Prepare SQL query to check for username or email
                $query = "SELECT hospital_id, hospital_password FROM hospital WHERE hospital_username = ? OR hospital_email = ?";
                $stmt = mysqli_prepare($conn, $query);

                if ($stmt) {
                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, "ss", $hospital_identifier, $hospital_identifier);

                    // Execute the query
                    mysqli_stmt_execute($stmt);

                    // Get the result
                    // $result = mysqli_stmt_get_result($stmt);

                    // if (mysqli_num_rows($result) == 1) {
                    //     $row = mysqli_fetch_assoc($result);

                    //     // Verify password
                    //     if (password_verify($hospital_password, $row['hospital_password'])) {
                    //         echo "<p class='success'>Login successful! Redirecting...</p>";
                    //         // Redirect to hospital dashboard
                    //         header(location:"hospital_home_page.html");
                    //         exit;
                    //     } else {
                    //         echo "<p class='error'>Invalid password. Please try again.</p>";
                    //     }
                    // } else {
                    //     echo "<p class='error'>Invalid username or email. Please try again.</p>";
                    // }

                    mysqli_stmt_bind_result($stmt, $hospital_id, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        // Verify password
                        if (password_verify($hospital_password, $hashed_password)) {

                            $_SESSION['hospital_id'] = $hospital_id; // Store hospital_id in session
                            echo "<p class='success'>Login successful! Redirecting...</p>";
                                if(isset($_SESSION['hospital_id'])){
                                    // echo $_SESSION["hospital_id"];
                                    // Redirect to hospital dashboard
                                    header("Location: hospital_home_page.php");
                                    exit();
                                }
                        } else {
                            echo "<p class='error'>Invalid password. Please try again.</p>";
                        }
                    } else {
                        echo "<p class='error'>Invalid username or email. Please try again.</p>";
                    }

                    // Close the statement
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<p class='error'>Error preparing the query: " . mysqli_error($conn) . "</p>";
                }
            } else {
                foreach ($errors as $error) {
                    echo "<p class='error'>$error</p>";
                }
            }

            mysqli_close($conn);
        }
        ?>
        <form action="" method="post">
            <input type="text" name="hospital_identifier" placeholder="Username or Email" maxlength="20" required>
            <input type="password" name="hospital_password" placeholder="Password" maxlength="16" minlength="8" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
