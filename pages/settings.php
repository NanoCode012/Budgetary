<?php

if (isset($_POST['email'])) {
    if (isset($_POST['password']) && trim($_POST['password']) != '') {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $db->update(
            'user',
            [
                'password' => $password_hash,
                'email' => trim($_POST['email']),
                'currency_id' => $_POST['currency_id'],
            ],
            ['id' => $_SESSION['user_id']]
        );
    } else {
        $db->update(
            'user',
            [
                'email' => trim($_POST['email']),
                'currency_id' => $_POST['currency_id'],
            ],
            ['id' => $_SESSION['user_id']]
        );
    }
}

$row = $db->row('SELECT * FROM user WHERE id = ?', $_SESSION['user_id']);
$num_transaction = $db->cell(
    'SELECT COUNT(*) FROM transaction WHERE `user_id`=?',
    $_SESSION['user_id']
);

$q = 'select id, name from currency;';
$currencies = $db->run($q);
?>
<div class="wrapper ">
    <?php include 'includes/nav-side.php'; ?>
    <div class="main-panel">
        <?php include 'includes/nav-top.php'; ?>
        <div class="content">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card card-user">
                        <div class="image">
                            <img src="./assets/img/damir-bosnjak.jpg" alt="...">
                        </div>
                        <div class="card-body">
                            <div class="author">
                                <a href="#">
                                    <img class="avatar" src="assets\img\faces\pngwing.com.png" alt="...">
                                    <h5 class="title"><?php echo $row[
                                        'username'
                                    ]; ?></h5>
                                </a>
                                <p class="description">
                                    @<?php echo $row['username']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="button-container">
                                <div class="row">
                                    <div class="col align-self-center">
                                        <h5><?php echo $num_transaction; ?><br>
                                        <small>Transactions</small></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Edit Profile</h5>
                        </div>
                        <div class="card-body">
                            <form action="" role="" method="post"
                                oninput='passwordConfirmation.setCustomValidity(passwordConfirmation.value != password.value ? "Passwords do not match." : "")'>
                                <div class="row">
                                    <div class="col-md-4 pr-1">
                                        <div class="form-group">
                                            <label>Username (disabled)</label>
                                            <input type="text" class="form-control" disabled="" name="username" placeholder="Username"
                                                value="<?php echo $row[
                                                    'username'
                                                ]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 px-1">
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" class="form-control" placeholder="New Password"
                                                name="password">
                                        </div>
                                    </div>
                                    <div class="col-md-4 pl-1">
                                        <div class="form-group">
                                            <label for="confirm-password">Confirm Password</label>
                                            <input type="password" class="form-control" placeholder="Confirm Password"
                                                name="passwordConfirmation">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 pr-1">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" placeholder="Email"
                                                value="<?php echo $row[
                                                    'email'
                                                ]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 pl-1">
                                        <div class="form-group">
                                            <label>Default currency</label>
                                            <select class="form-control" name="currency_id">
                                                <?php foreach (
                                                    $currencies
                                                    as $currency
                                                ) { ?>
                                                    <option value=<?php
                                                    echo '"' .
                                                        $currency['id'] .
                                                        '"';
                                                    if (
                                                        $currency['id'] ==
                                                        $row['currency_id']
                                                    ) {
                                                        echo 'selected';
                                                    }
                                                    ?>>
                                                    <?php echo $currency[
                                                        'name'
                                                    ]; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="update ml-auto mr-auto">
                                        <button type="submit" class="btn btn-primary btn-round">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="title">Feedback</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form role="form" action="?p=rating" method="post">
            <div class="modal-body">
                <fieldset>
                    <div class="form-group mb-3">
                        <p>How would you rate our service?</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="rating">Rating (1 = Poor, 5 = Excellent)</label>
                        <div id="react-dom"></div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <input name="rating" hidden>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button name="review" id="send" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- Core React -->
<script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

<!-- Custom React JS -->
<script type="text/babel" src="assets/js/react.js"></script>

<script>
    $(document).ready(function(){
        $('#send').on('click', ()=> {
            let $v = $('.boards').children('.selected').html();
            $('.modal-footer input').val($v);
        })
        
    })
</script>