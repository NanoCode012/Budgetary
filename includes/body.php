<body>

<?php

if (file_exists("pages/" . $page . ".php")) {
    // To sync url with page
    if ($_GET['p'] != $page) header('Location: index.php?p=' . $page);
    else include("pages/" . $page . ".php");
} else {
    include("pages/404.php");
}


?>