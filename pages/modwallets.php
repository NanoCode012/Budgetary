<?php

if (isset($_POST['delete'])) {
    if (
        $stmt = $db->delete('wallet', [
            'id' => $_POST['id'],
            'user_id' => $_SESSION['user_id'],
        ])
    ) {
        $msgBox = success($m_deletesuccess);
    } else {
        $msgBox = error($m_deleteerror);
    }
} 

if (isset($_POST['create']) || isset($_POST['edit'])){
    $dict = [
        'name' => '0',
        'amount' => '0',
        'currency_id' => '0'
    ];

    foreach ($dict as $key => $value) {
        if ((!isset($_POST[$key]) || trim($_POST[$key]) == '') && ($key != 'description')) {
            $msgBox = error($key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    if (!isset($msgBox)) {
        if (isset($_POST['create'])) {
            if (
                $stmt = $db->insert('wallet', [
                    'user_id' => $_SESSION['user_id'],
                    'name' => $dict['name'],
                    'amount' => $dict['amount'],
                    'currency_id' => $dict['currency_id'],
                ])
            ) {
                $msgBox = success($m_addsuccess);
            } else {
                $msgBox = error($m_adderror);
            }
        }
        else if (isset($_POST['edit'])) {
            if (
                $stmt = $db->update(
                    'wallet',
                    [
                        'name' => $dict['name'],
                        'amount' => $dict['amount'],
                        'currency_id' => $dict['currency_id'],
                    ],
                    ['id' => $_POST['id'], 'user_id' => $_SESSION['user_id']]
                )
            ) {
                $msgBox = success($m_savesuccess);
            } else {
                $msgBox = error($m_saveerror);
            }
        }
    }
}
if ($_POST){
    if (isset($msgBox))  $_SESSION['msgBox'] =  $msgBox;
    header( "Location: ?p=wallets", true, 303 );
}
exit();
?>

