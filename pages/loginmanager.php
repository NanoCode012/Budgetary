<?php

if (isset($_POST['login'])) {
    if (!isset($_POST['username']) || trim($_POST['username']) == '') {
        $msgBox = error($m_emptyusername);
    } else {
        $username = trim($_POST['username']);
    }

    if (!isset($msgBox)) {
        if (!isset($_POST['password']) || trim($_POST['password']) == '') {
            $msgBox = error($m_emptypass);
        } else {
            $password = trim($_POST['password']);
        }
    }

    if (!isset($msgBox)) {
        if (
            $result = $db->row(
                'SELECT id, password, currency_id from user WHERE username = ?',
                $username
            )
        ) {
            $id = $result['id'];
            $password_hash = $result['password'];
            $currency_id = $result['currency_id'];
            if (password_verify($password, $password_hash)) {
                session_destroy();
                session_start();
                $_SESSION['user_id'] = $id;
                $_SESSION['currency_id'] = $currency_id;
                header('Location: index.php?p=dashboard');
                exit();
            } else {
                $msgBox = error($m_loginerror);
            }
        } else {
            $msgBox = error($m_loginerror);
        }
    }
} elseif (isset($_POST['register'])) {
    $dict = [
        'username' => '0',
        'password' => '0',
        'email' => '0',
        'currency_id' => '0',
    ];
    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $msgBox = error($key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }
    if (!isset($msgBox)) {
        $password_hash = password_hash($dict['password'], PASSWORD_DEFAULT);

        $result = $db->cell(
            'SELECT COUNT(id) from user WHERE username = ?',
            $dict['username']
        );

        if ($result == 0) {
            if (
                $stmt = $db->insert('user', [
                    'username' => $dict['username'],
                    'password' => $password_hash,
                    'email' => $dict['email'],
                    'currency_id' => $dict['currency_id'],
                ])
            ) {
                $msgBox = success($m_registersuccess);
            } else {
                $msgBox = error($m_registererror);
            }
        } else {
            $msgBox = error($m_registererror);
        }
    }
} else if (isset($_POST['reset'])) {
    $dict = [
        'username' => '0',
        'password' => '0',
    ];

    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $msgBox = error($key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    if (!isset($msgBox)) {
        $password_hash = password_hash($dict['password'], PASSWORD_DEFAULT);
        $result = $db->cell(
            'SELECT COUNT(id) from user WHERE username = ?',
            $dict['username']
        );

        if ($result == 1) {
            if (
                $stmt = $db->update('user', 
                ['password' => $password_hash], 
                ['username'=>$dict['username']])
            ) {
                $msgBox = success($m_resetsuccess);
            } else {
                $msgBox = error($m_reseterror);
            }
        } else {
            $msgBox = error($m_reseterror);
        }
    }


}
if (isset($msgBox)) {
    $_SESSION['msgBox'] = $msgBox;
}
header( "Location: ?p=login", true, 303 );
exit();
?>
