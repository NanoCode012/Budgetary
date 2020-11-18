<link rel="stylesheet" href="assets/css/login.css">
<div class="login">
    <div class="login__content">
        <div class="login__img">
            <img src="assets/img/chart3D.png" alt="">
        </div>

        <div class="login__forms">
            <form action="?p=loginmanager" class="login__registre" method="post" id="login-in">
                <h1 class="login__title">Sign In</h1>

                <span class="login__box">
                    <i class='fas fa-user login-icon'></i>
                    <input type="text" name="username" placeholder="Username" class="login__input">
                </span>

                <div class="login__box">
                    <i class='fas fa-key login-icon'></i>
                    <input type="password" name="password" placeholder="Password" class="login__input">
                </div>

                <a href="#" class="login__forgot">Forgot password?</a>

                <button type="submit" name="login" class="btn btn-primary">Sign In</button>

                <div>
                    <span class="login__account">Don't have an Account?</span>
                    <a href="#"><span class="login-signup" id="sign-up">Sign Up</span></a>
                </div>
            </form>

            <!-- CREATE ACC -->

            <form action="?p=loginmanager" method="post" role="" class="login__create none" id="login-up">
                <h1 class="login__title">Create Account</h1>

                <div class="login__box">
                    <i class='fas fa-user login-icon'></i>
                    <input type="username" name="username" placeholder="Username" class="login__input">
                </div>

                <div class="login__box">
                    <i class='far fa-envelope login-icon'></i>
                    <input type="email" name="email" placeholder="Email" class="login__input">
                </div>

                <div class="login__box">
                    <i class='fas fa-key login-icon'></i>
                    <input type="password" name="password" placeholder="Password" class="login__input">
                </div>

                <div class="login__box">
                    <i class='fas fa-key login-icon'></i>
                    <select name="currency_id" class="login__input">
                        <?php
                        $q = 'select id, name from currency;';
                        $rows = $db->run($q); 
                        foreach ($rows as $row) { ?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                        <?php } ?>
                    </select>
                </div>

                <a href="#" class="login__forgot">Forgot password?</a>

                <button name="register" type="submit" class="btn btn-primary">Sign Up</button>

                <div>
                    <span class="login__account">Already have an Account?</span>
                    <a href="#"><span class="login-signin" id="sign-in">Sign In</span></a>
                </div>

            </form>


        </div>
    </div>
</div>



<script src="assets/js/login.js"></script>
</body>

</html>