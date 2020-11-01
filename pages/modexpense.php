<?php

$msgBox = '';
$v_wallet_id = '';
$v_wallet = '';
$v_title = '';
$v_category = '';
$v_amount = '';
$v_description = '';

if (isset($_POST['modify'])) {
    if (!isset($_POST['title']) || trim($_POST['title']) == '') {
        $msgBox = alertBox($m_emptytitle);
    } else {
        $_POST['title'] = trim($_POST['title']);
    }

    if ($msgBox == '') {
        if (!isset($_POST['category'])) {
            $msgBox = alertBox($m_emptycategory);
        }
    }

    if ($msgBox == '') {
        if (!isset($_POST['amount']) || trim($_POST['amount']) == '') {
            $msgBox = alertBox($m_emptyamount);
        } else {
            $_POST['amount'] = trim($_POST['amount']);
        }
    }

    if ($msgBox == '') {
        if (!isset($_POST['wallet_id'])) {
            $msgBox = alertBox($m_emptywallet);
        }
    }

    if ($msgBox == '') {
        if (isset($_POST['description'])) {
            $_POST['description'] = trim($_POST['description']);
        }
    }

    if ($msgBox == '') {
        $title = $mysqli->real_escape_string($_POST['title']);
        $category = $mysqli->real_escape_string($_POST['category']);
        $amount = $mysqli->real_escape_string($_POST['amount']);
        $wallet_id = $mysqli->real_escape_string($_POST['wallet_id']);
        $description = $mysqli->real_escape_string($_POST['description']);

        if (isset($_GET['type']) && $_GET['type'] == 'add') {
            if (
                $stmt = $mysqli->prepare(
                    'INSERT INTO `transaction` (user_id, wallet_id, title, category, amount, description) VALUES (?,?,?,?,?,?)'
                )
            ) {
                $stmt->bind_param(
                    'iissis',
                    $_SESSION['user_id'],
                    $wallet_id,
                    $title,
                    $category,
                    $amount,
                    $description
                );
                if ($stmt->execute()) {
                    $msgBox = alertBox($m_addsuccess, '?p=expense');
                } else {
                    $msgBox = alertBox($m_adderror);
                }
            } else {
                $msgBox = alertBox($m_adderror);
            }
        } elseif (isset($_GET['type']) && $_GET['type'] == 'edit') {
            $tid = $mysqli->real_escape_string($_GET['tid']);

            if (
                $stmt = $mysqli->prepare(
                    'UPDATE `transaction` SET wallet_id=?, title=?, category=?, amount=?, description=? WHERE id=? AND user_id=?;'
                )
            ) {
                $stmt->bind_param(
                    'issisii',
                    $wallet_id,
                    $title,
                    $category,
                    $amount,
                    $description,
                    $tid,
                    $_SESSION['user_id']
                );
                if ($stmt->execute()) {
                    $msgBox = alertBox($m_savesuccess, '?p=expense');
                } else {
                    $msgBox = alertBox($m_saveerror);
                }
            } else {
                $msgBox = alertBox($m_saveerror);
            }
        }
    }
} elseif (isset($_GET['type']) && isset($_GET['tid'])) {
    $tid = $mysqli->real_escape_string($_GET['tid']);

    if ($_GET['type'] == 'delete') {
        if (
            $stmt = $mysqli->prepare(
                'DELETE FROM `transaction` WHERE id=? AND user_id=?;'
            )
        ) {
            $stmt->bind_param('ii', $tid, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $msgBox = alertBox($m_deletesuccess, '?p=expense');
            } else {
                $msgBox = alertBox($m_deleteerror);
            }
        } else {
            $msgBox = alertBox($m_deleteerror);
        }
    } elseif ($_GET['type'] == 'edit') {
        if (
            $stmt = $mysqli->prepare(
                'SELECT t.wallet_id, w.name, t.title, t.category, t.amount, t.description ' .
                    'FROM `transaction` t, `wallet` w WHERE t.id=? AND t.user_id=? AND t.wallet_id = w.id;'
            )
        ) {
            $stmt->bind_param('ii', $tid, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result(
                $v_wallet_id,
                $v_wallet,
                $v_title,
                $v_category,
                $v_amount,
                $v_description
            );
            $stmt->store_result();
            $stmt->fetch();
        } else {
            echo 'Query error: ' . $mysqli->error;
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
            <label for="title"><?php echo $m_title; ?></label>
            <input class="form-control" placeholder="<?php echo $m_title; ?>" name="title" type="text" value="<?php echo $v_title; ?>" autofocus>
        </div>
        <div class="form-group">
            <label for="category"><?php echo $m_category; ?></label>
            <select class="form-control" name="category">
                <option value='FOOD' <?php if ($v_category == 'FOOD') {
                    echo 'selected';
                } ?>>FOOD</option>
                <option value='RENT' <?php if ($v_category == 'RENT') {
                    echo 'selected';
                } ?>>RENT</option>
                <option value='UTLITIES' <?php if ($v_category == 'UTLITIES') {
                    echo 'selected';
                } ?>>UTLITIES</option>
                <option value='SHOPPING' <?php if ($v_category == 'SHOPPING') {
                    echo 'selected';
                } ?>>SHOPPING</option>
                <option value='ONLINE' <?php if ($v_category == 'ONLINE') {
                    echo 'selected';
                } ?>>ONLINE</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount"><?php echo $m_amount; ?></label>
            <input class="form-control" placeholder="<?php echo $m_amount; ?>" name="amount" type="number" value="<?php echo $v_amount; ?>">
        </div>
        <div class="form-group">
            <label for="wallet_id"><?php echo $m_wallet; ?></label>
            <select class="form-control" name="wallet_id">
                <?php
                $q =
                    'select id, name from wallet where user_id = ' .
                    $_SESSION['user_id'] .
                    ';';
                if ($result = $mysqli->query($q)) {
                    while ($row = $result->fetch_array()) {
                        echo '<option value="' . $row[0] . '" ';
                        if ($v_wallet_id == $row[0]) {
                            echo 'selected';
                        }
                        echo ' >' . $row[1] . '</option>';
                    }
                } else {
                    echo 'Query error: ' . $mysqli->error;
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description"><?php echo $m_description; ?></label>
            <textarea class="form-control" placeholder="<?php echo $m_description; ?>" name="description" rows="3"><?php echo $v_description; ?></textarea>
        </div>

        <hr>
        <button type="submit" name="modify" class="btn btn-success btn-block">
            <?php if (isset($_GET['tid'])) {
                echo $m_save;
            } else {
                echo $m_add;
            } ?>
        </button>

        <hr>
        <a href="?p=expense" class="btn btn-info btn-block"><?php echo $m_back; ?></a>
    </fieldset>
</form>