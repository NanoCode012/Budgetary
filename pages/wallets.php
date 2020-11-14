<div class="wrapper ">
    <?php include 'includes/nav-side.php'; ?>
    <div class="main-panel">
        <?php include 'includes/nav-top.php'; ?>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> Wallets</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div><a class="btn btn-primary" href="?p=modexpense&type=add" role="button">Add
                                        wallet</a></div>
                                <table class="table table-borderless" data-toggle="table" data-sort-name="name"
                                    data-sort-order="desc" data-pagination="true" data-page-size="10"
                                    data-search="true">
                                    <thead class='text-primary'>
                                        <tr>
                                            <th scope="col" data-field="name" data-sortable="true">Name</th>
                                            <th scope="col" data-field="amount" data-sortable="true">Amount</th>
                                            <th scope="col" data-field="currency" data-sortable="true">Currency</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        $q =
                                            'SELECT w.id, w.name AS wallet_name, w.amount, w.currency_id, c.name AS currency_name, w.time_created ' .
                                            'from currency c, wallet w where w.user_id = ? and w.currency_id = c.id ' .
                                            'order by w.time_created ASC;';
                                        if (
                                            $rows = $db->run(
                                                $q,
                                                $_SESSION['user_id']
                                            )
                                        ) {
                                            foreach ($rows as $row) {
                                                echo '<tr>';
                                                echo '<td>' .
                                                    $row['wallet_name'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    $row['amount'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    $row['currency_name'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    editButton(
                                                        'wallets',
                                                        'wid',
                                                        $row['id']
                                                    ) .
                                                    '&nbsp' .
                                                    deleteButton(
                                                        'wallets',
                                                        'wid',
                                                        $row['id']
                                                    ) .
                                                    '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function () {
    $.notify({
        title: "Welcome:",
        message: "This plugin has been provided to you by Robert McIntosh aka mouse0270"
    },
    {   
        type: 'danger'
    }); 
});
</script>