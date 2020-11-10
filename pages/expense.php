<div class="wrapper ">
    <?php include 'includes/nav-side.php'; ?>
    <div class="main-panel">
        <?php include 'includes/nav-top.php'; ?>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> Expense</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div><a class="btn btn-primary" href="?p=modexpense&type=add" role="button">Add
                                        expense</a></div>
                                <table class="table table-borderless" data-toggle="table" data-sort-name="name"
                                    data-sort-order="desc" data-pagination="true" data-page-size="10"
                                    data-search="true">
                                    <thead class='text-primary'>
                                        <tr>
                                            <th scope="col" data-field="title" data-sortable="true">Title</th>
                                            <th scope="col" data-field="category" data-sortable="true">Category</th>
                                            <th scope="col" data-field="amount" data-sortable="true">Amount</th>
                                            <th scope="col" data-field="wallet" data-sortable="true">Wallet</th>
                                            <th scope="col" data-field="description" data-sortable="true">
                                                Description
                                            </th>
                                            <th scope="col" data-field="datetime" data-sortable="true">DateTime</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q =
                                            'select t.id, t.wallet_id, w.name AS wallet_name, t.title, t.category, t.amount, t.description, t.time_created ' .
                                            'from `transaction` t, wallet w where t.user_id = ? ' .
                                            'and t.wallet_id = w.id ' .
                                            'order by t.time_created DESC;';
                                        if (
                                            $rows = $db->run(
                                                $q,
                                                $_SESSION['user_id']
                                            )
                                        ) {
                                            foreach ($rows as $row) {
                                                echo '<tr>';
                                                echo '<td>' .
                                                    $row['title'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    $row['category'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    $row['amount'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    $row['wallet_name'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    $row['description'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    $row['time_created'] .
                                                    '</td>';
                                                echo '<td>' .
                                                    editButton(
                                                        'expense',
                                                        'tid',
                                                        $row['id']
                                                    ) .
                                                    '&nbsp' .
                                                    deleteButton(
                                                        'expense',
                                                        'tid',
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