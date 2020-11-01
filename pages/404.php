<?php

$redirect = 'index.php';
if (isset($_SERVER['HTTP_REFERER'])) {
    $redirect = $_SERVER['HTTP_REFERER'];
}
echo alertBox($m_pageunknown . ' ( ' . $page . ' ) ', $redirect);

?>
