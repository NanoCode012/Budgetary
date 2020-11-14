<?php
if (isset($_SESSION['msgBox'])) {
    echo $_SESSION['msgBox'];
    unset($_SESSION['msgBox']);
}
?>
