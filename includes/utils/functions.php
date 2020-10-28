<?php 
function alertBox($message) {
    echo '<script language="javascript">';
    echo 'alert("' . $message . '")';
    echo '</script>';

    return 'alert';
}
?>