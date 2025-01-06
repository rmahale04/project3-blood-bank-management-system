<?php
echo "<script>
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'admin_logout_session.php';
    } else {
        window.location.href = 'admin_home_page.php'; 
    }
</script>";

?>
