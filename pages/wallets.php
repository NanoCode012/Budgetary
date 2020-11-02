<!-- Bootstrap Table CSS -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css">

<div><a class="btn btn-primary" href="?p=modwallets&type=add" role="button">Add wallet</a></div>
<div class="table-responsive-sm">
    <table 
        class='table table-striped table-hover' 
        data-toggle="table" 
        
        data-sort-name="name"
        data-sort-order="desc" 
        
        data-pagination="true" 
        data-page-size="10" 
        
        data-search="true">
        <thead class='thread thead-light'>
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
            if ($rows = $db->run($q, $_SESSION['user_id'])) {
                foreach ($rows as $row) {
                    echo '<tr>';
                    echo '<td>' . $row['wallet_name'] . '</td>';
                    echo '<td>' . $row['amount'] . '</td>';
                    echo '<td>' . $row['currency_name'] . '</td>';
                    echo '<td>' .
                        editButton('wallets', $row['id']) .
                        '&nbsp' .
                        deleteButton('wallets', $row['id']) .
                        '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap Table JS -->
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
