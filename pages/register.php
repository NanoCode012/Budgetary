<?php

$msgBox = '';

if (isset($_POST['register'])) {
    if (!isset($_POST['username']) || trim($_POST['username']) == '') {
        $msgBox = alertBox($m_emptyusername);
    } else {
        $_POST['username'] = trim($_POST['username']);
    }

    if ($msgBox == '') {
        if (!isset($_POST['password']) || trim($_POST['password']) == '') {
            $msgBox = alertBox($m_emptypass);
        } else {
            $_POST['password'] = trim($_POST['password']);
        }
    }

    if ($msgBox == '') {
        if (!isset($_POST['email']) || trim($_POST['email']) == '') {
            $msgBox = alertBox($m_emptyemail);
        } else {
            $_POST['email'] = trim($_POST['email']);
        }
    }
    
    if ($msgBox == '') {
        if (!isset($_POST['currency_id']) || trim($_POST['currency_id']) == '') {
            $msgBox = alertBox($m_emptycurrency);
        }
    }

    if ($msgBox == '') {
        $username = $mysqli->real_escape_string($_POST['username']);
        $password = $mysqli->real_escape_string($_POST['password']);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $email = $mysqli->real_escape_string($_POST['email']);
        $currency_id = $mysqli->real_escape_string($_POST['currency_id']);
    
        if ($stmt = $mysqli->prepare("SELECT id from user WHERE username = ?")) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->store_result();
            $stmt->fetch();
            if (($stmt->num_rows == 0)) {
                if ($stmt = $mysqli->prepare("INSERT INTO user (username, password, email, currency_id) VALUES (?,?,?,?)")) {
                    $stmt->bind_param("sssi", $username, $password_hash, $email, $currency_id);
                    if ($stmt->execute()) {
                        $msgBox = alertBox($m_registersuccess);
                        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
                    }
                    else {
                        $msgBox = alertBox($m_registererror);
                    }
                }
                else $msgBox = alertBox($m_registererror);
            } else {
                $msgBox = alertBox($m_registererror);
            }
        }
    }
}
?>

<?php 
if ($msgBox) {
    echo $msgBox;
} 
?>

<form method="post" action="" role="form">
    <fieldset>
        <div class="form-group">
            <label for="username"><?php echo $m_username; ?></label>
            <input class="form-control" placeholder="<?php echo
                                                            $m_username; ?>" name="username" type="text" autofocus>
        </div>
        <div class="form-group">
            <label for="password"><?php echo $m_password; ?></label>
            <input class="form-control" placeholder="<?php echo
                                                            $m_password; ?>" name="password" type="password" value="">
        </div>
        <div class="form-group">
            <label for="email"><?php echo $m_email; ?></label>
            <input class="form-control" placeholder="<?php echo
                                                            $m_email; ?>" name="email" type="email">
        </div>
        <div class="form-group">
            <label for="currency_id"><?php echo $m_currency; ?></label>

            <select name="currency_id">
                <?php
                $q = 'select id, name from currency;';
                if ($result = $mysqli->query($q)) {
                    while ($row = $result->fetch_array()) {
                        echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
                    }
                } else {
                    echo 'Query error: ' . $mysqli->error;
                }
                ?>
            </select>
        </div>

        <hr>
        <button type="submit" name="register" class="btn btn-info btn-block"><span class="glyphicon glyphicon-pencil"></span> <?php echo
                                                                                                                                $m_register; ?></button>

        <hr>
        <a href="?p=login" class="btn btn-success btn-block"><span class="glyphicon glyphicon-log-in"></span> <?php echo
                                                                                                                    $m_login; ?></a>
    </fieldset>
</form>