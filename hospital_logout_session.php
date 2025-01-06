<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: hospital_login_page.php"); // Redirect to the home or login page after logout
exit();
?>
