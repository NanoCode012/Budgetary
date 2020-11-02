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
        $title = trim($_POST['title']);
    }

    if ($msgBox == '') {
        if (!isset($_POST['category'])) {
            $msgBox = alertBox($m_emptycategory);
        } else {
            $category = trim($_POST['category']);
        }
    }

    if ($msgBox == '') {
        if (!isset($_POST['amount']) || trim($_POST['amount']) == '') {
            $msgBox = alertBox($m_emptyamount);
        } else {
            $amount = trim($_POST['amount']);
        }
    }

    if ($msgBox == '') {
        if (!isset($_POST['wallet_id'])) {
            $msgBox = alertBox($m_emptywallet);
        } else {
            $wallet_id = trim($_POST['wallet_id']);
        }
    }

    if ($msgBox == '') {
        if (isset($_POST['description'])) {
            $description = trim($_POST['description']);
        }
    }

    if ($msgBox == '') {
        if (isset($_GET['type']) && $_GET['type'] == 'add') {
            if (
                $stmt = $db->insert('transaction', [
                    'user_id' => $_SESSION['user_id'],
                    'wallet_id' => $wallet_id,
                    'title' => $title,
                    'category' => $category,
                    'amount' => $amount,
                    'description' => $description,
                ])
            ) {
                $msgBox = alertBox($m_addsuccess, '?p=expense');
            } else {
                $msgBox = alertBox($m_adderror);
            }
        } elseif (isset($_GET['type']) && $_GET['type'] == 'edit') {
            $tid = trim($_GET['tid']);

            if (
                $stmt = $db->update(
                    'transaction',
                    [
                        'wallet_id' => $wallet_id,
                        'title' => $title,
                        'category' => $category,
                        'amount' => $amount,
                        'description' => $description,
                    ],
                    ['id' => $_GET['tid'], 'user_id' => $_SESSION['user_id']]
                )
            ) {
                $msgBox = alertBox($m_savesuccess, '?p=expense');
            } else {
                $msgBox = alertBox($m_saveerror);
            }
        }
    }
} elseif (isset($_GET['type']) && isset($_GET['tid'])) {
    if ($_GET['type'] == 'delete') {
        if (
            $stmt = $db->delete('transaction', [
                'id' => $_GET['tid'],
                'user_id' => $_SESSION['user_id'],
            ])
        ) {
            $msgBox = alertBox($m_deletesuccess, '?p=expense');
        } else {
            $msgBox = alertBox($m_deleteerror);
        }
    } elseif ($_GET['type'] == 'edit') {
        if (
            $results = $db->run(
                'SELECT t.wallet_id, w.name, t.title, t.category, t.amount, t.description ' .
                    'FROM `transaction` t, `wallet` w WHERE t.id=? AND t.user_id=? AND t.wallet_id = w.id;',
                $_GET['tid'],
                $_SESSION['user_id']
            )
        ) {
            $result = $results[0];
            $v_wallet_id = $result['wallet_id'];
            $v_wallet = $result['name'];
            $v_title = $result['title'];
            $v_category = $result['category'];
            $v_amount = $result['amount'];
            $v_description = $result['description'];
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
                $q = 'select id, name from wallet where user_id = ? ;';
                if ($rows = $db->run($q, $_SESSION['user_id'])) {
                    foreach ($rows as $row) {
                        echo '<option value="' . $row['id'] . '" ';
                        if ($v_wallet_id == $row['name']) {
                            echo 'selected';
                        }
                        echo ' >' . $row['name'] . '</option>';
                    }
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