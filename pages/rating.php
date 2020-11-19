<?php
if (isset($_POST['review'])) {
    $user_review = new Firebase('reviews');
    $users = new Firebase('users');
    if ($users->get($_SESSION['user_id']) == 'complete') {
        $msgBox = error($m_addalready);
    } else {
        if ($user_review->increment($_POST['rating'])) {
            $users->insert([$_SESSION['user_id'] => 'complete']);
            $msgBox = success($m_addsuccess);
        } else {
            $msgBox = error($m_adderror);
        }
    }
} 

if ($_POST){
    if (isset($msgBox))  $_SESSION['msgBox'] =  $msgBox;
    header( "Location: ?p=settings", true, 303 );
}
exit();

?>
