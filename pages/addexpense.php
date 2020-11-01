<?php

$msgBox = '';

if (isset($_POST['add'])) {
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
        if (!isset($_POST['wallet'])) {
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
        $wallet = $mysqli->real_escape_string($_POST['wallet']);
        $description = $mysqli->real_escape_string($_POST['description']);

        if (
            $stmt = $mysqli->prepare(
                'INSERT INTO `transaction` (user_id, wallet_id, title, category, amount, description) VALUES (?,?,?,?,?,?)'
            )
        ) {
            $stmt->bind_param(
                'iissis',
                $_SESSION['user_id'],
                $wallet,
                $title,
                $category,
                $amount,
                $description
            );
            if ($stmt->execute()) {
                $msgBox = alertBox($m_addsuccess);
                echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?p=expense">';
            } else {
                $msgBox = alertBox($m_adderror);
            }
        } else {
            $msgBox = alertBox($m_adderror);
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
            <input class="form-control" placeholder="<?php echo $m_title; ?>" name="title" type="text" autofocus>
        </div>
        <div class="form-group">
            <label for="category"><?php echo $m_category; ?></label>
            <select class="form-control" name="category">
                <option value='FOOD'>FOOD</option>
                <option value='RENT'>RENT</option>
                <option value='UTLITIES'>UTLITIES</option>
                <option value='SHOPPING'>SHOPPING</option>
                <option value='ONLINE'>ONLINE</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount"><?php echo $m_amount; ?></label>
            <input class="form-control" placeholder="<?php echo $m_amount; ?>" name="amount" type="number">
        </div>
        <div class="form-group">
            <label for="wallet"><?php echo $m_wallet; ?></label>
            <select class="form-control" name="wallet">
                <?php
                $q =
                    'select id, name from wallet where user_id = ' .
                    $_SESSION['user_id'] .
                    ';';
                if ($result = $mysqli->query($q)) {
                    while ($row = $result->fetch_array()) {
                        echo '<option value="' .
                            $row[0] .
                            '">' .
                            $row[1] .
                            '</option>';
                    }
                } else {
                    echo 'Query error: ' . $mysqli->error;
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description"><?php echo $m_description; ?></label>
            <textarea class="form-control" placeholder="<?php echo $m_description; ?>" name="description" rows="3"></textarea>
        </div>

        <hr>
        <button type="submit" name="add" class="btn btn-success btn-block"><?php echo $m_add; ?></button>
                                                                                                                                <hr>
        <a href="?p=expense" class="btn btn-info btn-block"><?php echo $m_back; ?></a>
    </fieldset>
</form>