<?php
if (isset($_SESSION['user_id'])) {
    if ($page != 'logout') {
        //Don't show navbar when logged out
        include 'includes/nav-loggedin.php';
    }
}
?>
