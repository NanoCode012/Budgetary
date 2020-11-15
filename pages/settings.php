<?php

if (isset($_POST['email'])) {
    if (isset($_POST['password']) && trim($_POST['password']) != '') {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $db->update(
            'user',
            ['password' => $password_hash, 'email' => trim($_POST['email'])],
            ['id' => $_SESSION['user_id']]
        );
    }
    else {
        $db->update(
            'user',
            ['email' => trim($_POST['email'])],
            ['id' => $_SESSION['user_id']]
        );
    }
}

$row = $db->row(
    'SELECT u.*, COUNT(*) AS num_transaction ' .
        'FROM user u, transaction t WHERE u.id = ? AND u.id = t.user_id GROUP BY id',
    $_SESSION['user_id']
);
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
                                        <h5><?php echo $row[
                                            'num_transaction'
                                        ]; ?><br><small>Transactions</small></h5>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" placeholder="Email"
                                                value="<?php echo $row[
                                                    'email'
                                                ]; ?>">
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