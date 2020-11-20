<?php 

if (isset($_POST['delete'])) {
    if (
        $stmt = $db->delete('transaction', [
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
        'title' => '0',
        'category' => '0',
        'amount' => '0',
        'wallet_id' => '0',
        'description' => '0',
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
        // Custom time
        if (isset($_POST['manual-dates'])) {
            if (isset($_POST['from-date'])) {
                $dict['from-date'] = $_POST['from-date'];
            } else {
                $msgBox = error($m_missingdates);
            }
        } else {
            // Once
            $dict['from-date'] = null;
        }

        if (isset($_POST['create'])) {
            try {
                $db->run(
                    "CALL `Add transaction`(?,?,?,?,?,?,?,?,?,?)",
                    $_SESSION['user_id'],
                    $dict['wallet_id'],
                    $dict['title'],
                    $dict['category'],
                    $dict['amount'],
                    $dict['description'],
                    (isset($_POST['recurring']) ? 1 : 0),
                    (isset($_POST['recurring']) ? $_POST['recurring-frequency']: ' '),
                    (isset($_POST['recurring']) ? $_POST['recurring-times'] : 0),
                    $dict['from-date']
                );
                $msgBox = success($m_addsuccess);
            } catch (PDOException $exception) {
                $msgBox = error($m_adderror);
            }
            echo $dict['from-date'];
        }
        else if (isset($_POST['edit'])){ //Edit
            if ( 
                $stmt = $db->update(
                    'transaction',
                    [
                        'wallet_id' => $dict['wallet_id'],
                        'title' => $dict['title'],
                        'category' => $dict['category'],
                        'amount' => $dict['amount'],
                        'description' => $dict['description'],
                        'time_created' => $dict['from-date'],
                    ],
                    ['id' => $_POST['id'], 'user_id' => $_SESSION['user_id']]
                )
            ) {
                $msgBox = success($m_savesuccess);
            } else  {
                $msgBox = error($m_saveerror);
            }
        }
    }
}

if ($_POST){
    if (isset($msgBox))  $_SESSION['msgBox'] =  $msgBox;
    header( "Location: ?p=expense", true, 303 );
}
exit();
?>