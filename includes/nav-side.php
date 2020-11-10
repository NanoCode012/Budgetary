<div class="sidebar" data-color="white" data-active-color="primary">
    <div class="logo">
        <a href="https://www.creative-tim.com" class="simple-text logo-mini">
            <!-- <div class="logo-image-small">
            <img src="./assets/img/logo-small.png">
          </div> -->
            <!-- <p>CT</p> -->
        </a>
        <a href="https://budgetary.site" class="simple-text logo-normal">
            Budgetary
            <!-- <div class="logo-image-big">
            <img src="../assets/img/logo-big.png">
          </div> -->
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li <?php if ($page == 'dashboard') echo 'class="active"'; ?>>
                <a href="?p=dashboard">
                    <i class="nc-icon nc-bank"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li <?php if ($page == 'expense') echo 'class="active"'; ?>>
                <a href="?p=expense">
                    <i class="nc-icon nc-diamond"></i>
                    <p>Expense</p>
                </a>
            </li>
            <li <?php if ($page == 'wallets') echo 'class="active"'; ?>>
                <a href="?p=wallets">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>Wallet</p>
                </a>
            </li>
            <li <?php if ($page == 'settings') echo 'class="active"'; ?>>
                <a href="?p=settings">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>Settings</p>
                </a>
            </li>
            <li <?php if ($page == 'logout') echo 'class="active"'; ?>>
                <a href="?p=logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </div>
</div>