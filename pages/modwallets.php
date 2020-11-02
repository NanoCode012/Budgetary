<?php

$msgBox = '';
$v_wallet_name = '';
$v_amount = '';
$v_currency_id = '';
$v_currency_name = '';

$back_rdr = '?p=wallets';

if (isset($_POST['modify'])) {
    if (!isset($_POST['wallet']) || trim($_POST['wallet']) == '') {
        $msgBox = alertBox($m_emptywallet);
    } else {
        $wallet_name = trim($_POST['wallet']);
    }

    if ($msgBox == '') {
        if (!isset($_POST['amount']) || trim($_POST['amount']) == '') {
            $msgBox = alertBox($m_emptyamount);
        } else {
            $amount = trim($_POST['amount']);
        }
    }

    if ($msgBox == '') {
        if (!isset($_POST['currency_id'])) {
            $msgBox = alertBox($m_emptycurrency);
        } else {
            $currency_id = trim($_POST['currency_id']);
        }
    }

    if ($msgBox == '') {
        if (isset($_GET['type']) && $_GET['type'] == 'add') {
            if (
                $stmt = $db->insert('wallet', [
                    'user_id' => $_SESSION['user_id'],
                    'name' => $wallet_name,
                    'amount' => $amount,
                    'currency_id' => $currency_id,
                ])
            ) {
                $msgBox = alertBox($m_addsuccess, $back_rdr);
            } else {
                $msgBox = alertBox($m_adderror);
            }
        } elseif (isset($_GET['type']) && $_GET['type'] == 'edit') {
            $tid = trim($_GET['tid']);

            if (
                $stmt = $db->update(
                    'wallet',
                    [
                        'name' => $wallet_name,
                        'amount' => $amount,
                        'currency_id' => $currency_id,
                    ],
                    ['id' => $_GET['tid'], 'user_id' => $_SESSION['user_id']]
                )
            ) {
                $msgBox = alertBox($m_savesuccess, $back_rdr);
            } else {
                $msgBox = alertBox($m_saveerror);
            }
        }
    }
} elseif (isset($_GET['type']) && isset($_GET['tid'])) {
    if ($_GET['type'] == 'delete') {
        if (
            $stmt = $db->delete('wallet', [
                'id' => $_GET['tid'],
                'user_id' => $_SESSION['user_id'],
            ])
        ) {
            $msgBox = alertBox($m_deletesuccess, $back_rdr);
        } else {
            $msgBox = alertBox($m_deleteerror);
        }
    } elseif ($_GET['type'] == 'edit') {
        if (
            $results = $db->run(
                'SELECT w.name AS wallet_name, w.amount, w.currency_id, c.name AS currency_name ' .
                    'FROM currency c, wallet w where w.id = ? and w.user_id = ? and w.currency_id = c.id;',
                $_GET['tid'],
                $_SESSION['user_id']
            )
        ) {
            $result = $results[0];
            $v_wallet_name = $result['wallet_name'];
            $v_amount = $result['amount'];
            $v_currency_id = $result['currency_id'];
            $v_currency_name = $result['currency_name'];
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
            <label for="wallet"><?php echo $m_walletname; ?></label>
            <input class="form-control" placeholder="<?php echo $m_walletname; ?>" name="wallet" type="text" value="<?php echo $v_wallet_name; ?>" autofocus>
        </div>
        <div class="form-group">
            <label for="amount"><?php echo $m_amount; ?></label>
            <input class="form-control" placeholder="<?php echo $m_amount; ?>" name="amount" type="number" value="<?php echo $v_amount; ?>">
        </div>
        <div class="form-group">
            <label for="currency_id"><?php echo $m_currency; ?></label>

            <select class="form-control" name="currency_id">
            <?php
            $q = 'select id, name from currency;';
            if ($rows = $db->run($q)) {
                foreach ($rows as $row) {
                    echo '<option value="' . $row['id'] . '" ';
                    if ($v_currency_id == $row['id']) {
                        echo 'selected';
                    }
                    echo ' >' . $row['name'] . '</option>';
                }
            }
            ?>
            </select>
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
        <a href=<?php echo $back_rdr; ?> class="btn btn-info btn-block"><?php echo $m_back; ?></a>
    </fieldset>
</form>