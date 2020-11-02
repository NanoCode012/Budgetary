<?php

$msgBox = '';

if (isset($_POST['register'])) {
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
        if (!isset($_POST['email']) || trim($_POST['email']) == '') {
            $msgBox = alertBox($m_emptyemail);
        } else {
            $email = trim($_POST['email']);
        }
    }

    if ($msgBox == '') {
        if (
            !isset($_POST['currency_id']) ||
            trim($_POST['currency_id']) == ''
        ) {
            $msgBox = alertBox($m_emptycurrency);
        } else {
            $currency_id = $_POST['currency_id'];
        }
    }

    if ($msgBox == '') {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if (
            $result = $db->cell(
                'SELECT COUNT(id) from user WHERE username = ?',
                $username
            )
        ) {
            if ($result == 0) {
                if (
                    $stmt = $db->insert('user', [
                        'username' => $username,
                        'password' => $password_hash,
                        'email' => $email,
                        'currency_id' => $currency_id,
                    ])
                ) {
                    $msgBox = alertBox($m_registersuccess, '?p=login');
                } else {
                    $msgBox = alertBox($m_registererror);
                }
            } else {
                $msgBox = alertBox($m_registererror);
            }
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
        <div class="form-group">
            <label for="email"><?php echo $m_email; ?></label>
            <input class="form-control" placeholder="<?php echo $m_email; ?>" name="email" type="email">
        </div>
        <div class="form-group">
            <label for="currency_id"><?php echo $m_currency; ?></label>

            <select name="currency_id">
                <?php
                $q = 'select id, name from currency;';
                if ($rows = $db->run($q)) {
                    foreach ($rows as $row) {
                        echo '<option value="' .
                            $row['id'] .
                            '">' .
                            $row['name'] .
                            '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <hr>
        <button type="submit" name="register" class="btn btn-info btn-block"> <?php echo $m_register; ?></button>

        <hr>
        <a href="?p=login" class="btn btn-success btn-block"> <?php echo $m_login; ?></a>
    </fieldset>
</form>