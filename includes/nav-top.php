<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="javascript:;"><?php echo ucwords($page); ?></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <form <?php if (! in_array($page, ['wallets', 'expense', 'budget'])) echo 'hidden';?>>
                <div class="input-group no-border">
                    <input id="searchInput" type="text" value="" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="nc-icon nc-zoom-split"></i>
                        </div>
                    </div>
                </div>
            </form>
            <?php if (in_array($page, ['settings', ])) { ?>
            <div>
                <?php 
                    $review = new Firebase('reviews');
                    
                    $rating = 0;
                    $total_num = 0;
                    $arr = $review->get_keypairs();
                    foreach ($arr as $key => $val) {
                        $rating += $key * $val;
                        $total_num += $val;
                    }
                    echo number_format($rating / $total_num, 1);
                ?>
                <i class="fas fa-star"></i>
                <button type="button" class="btn btn-outline-primary my-0 p-lg-1" data-toggle="modal" data-target="#Modal" 
                data-type="create"><i class="far fa-smile py-0 fa-2x"></i>
                </button>
            </div>
            <?php } ?>
            <?php if (in_array($page, ['dashboard', ])) { echo '<div class="d-flex flex-row-reverse"><button type="button" class="btn btn-outline-primary p-1" style="border-radius: 1em"
                                        data-toggle="modal" data-target="#Modal" data-type="create">Filter
                                        </button></div>'; echo $db->cell('SELECT c.name FROM currency c, user u WHERE c.id = u.currency_id AND u.id = ?', $_SESSION['user_id']); } ?>
            <!-- <ul class="navbar-nav">
                <li class="nav-item btn-rotate dropdown">
                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nc-icon nc-bell-55"></i>
                        <p>
                            <span class="d-lg-none d-md-block">Some Actions</span>
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
            </ul> -->
        </div>
    </div>
</nav>
<!-- End Navbar -->