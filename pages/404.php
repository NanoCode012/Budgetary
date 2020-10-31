<?php

alertBox($m_pageunknown . ' ( ' . $page . ' ) ');
if (isset($_SERVER['HTTP_REFERER'])) 
{
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $_SERVER['HTTP_REFERER'] . '">';
}
else 
{
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
}

?>