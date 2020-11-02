<?php

$msgBox = '';

if (isset($_POST['login'])) {
    if (!isset($_POST['username']) || trim($_POST['username']) == '') {
        $msgBox = alertBox($m_emptyusername);
    } else {
        $username = trim($_POST['username']);
    }

    if ($msgBox == '') {
        if (!isset($_POST['password']) || trim($_POST['password']) == '') {
            $msgBox = alertBox($m_emptypass);
        } else {
            $password = trim($_POST['password']);
        }
    }

    if ($msgBox == '') {
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
            } else {
                $msgBox = alertBox($m_loginerror);
            }
        } else {
            $msgBox = alertBox($m_loginerror);
        }
    }
}
?>

<?php if ($msgBox) {
    echo $msgBox;
} ?>

<form method="post" action="" role="form">
    <fieldset>
        <div class="form-group">
            <label for="username"><?php echo $m_username; ?></label>
            <input class="form-control" placeholder="<?php echo $m_username; ?>" name="username" type="text" autofocus>
        </div>
        <div class="form-group">
            <label for="password"><?php echo $m_password; ?></label>
            <input class="form-control" placeholder="<?php echo $m_password; ?>" name="password" type="password" value="">
        </div>

        <hr>
        <button type="submit" name="login" class="btn btn-success btn-block"><?php echo $m_login; ?></button>
        <hr>
        <a href="?p=register" class="btn btn-info btn-block"><?php echo $m_register; ?></a>
    </fieldset>
</form>