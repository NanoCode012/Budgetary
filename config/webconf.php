<?php session_start();

// If logged in
if (isset($_SESSION['user_id'])) {
    if (!isset($_GET['p'])) {
        $page = 'dashboard';
    } else {
        $page = $_GET['p'];
    }
}
// If not logged in
else {
    if (
        isset($_GET['p']) &&
        in_array($_GET['p'], ['login', 'logout', 'register', 'loginmanager'])
    ) {
        $page = $_GET['p'];
    } else {
        $page = 'login';
    }
}

$servertitle = 'Budgetary' . ' | ' . ucwords($page);

?>
