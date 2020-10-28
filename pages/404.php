<?php

alertBox($m_pageunknown . ' ( ' . $page . ' ) ');
if (isset($_SERVER['HTTP_REFERER'])) header('Location: ' . $_SERVER['HTTP_REFERER']);
// else header('Location: index.php'); // Redirect to default page

?>