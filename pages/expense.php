<!-- Bootstrap Table CSS -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css">

<div><a class="btn btn-primary" href="?p=modexpense&type=add" role="button">Add expense</a></div>
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
                <th scope="col" data-field="title" data-sortable="true">Title</th>
                <th scope="col" data-field="category" data-sortable="true">Category</th>
                <th scope="col" data-field="amount" data-sortable="true">Amount</th>
                <th scope="col" data-field="wallet" data-sortable="true">Wallet</th>
                <th scope="col" data-field="description" data-sortable="true">Description</th>
                <th scope="col" data-field="datetime" data-sortable="true">DateTime</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q =
                'select t.id, t.wallet_id, w.name AS wallet_name, t.title, t.category, t.amount, t.description, t.time_created ' .
                'from `transaction` t, wallet w where t.user_id = ' .
                $_SESSION['user_id'] .
                ' and t.wallet_id = w.id ' .
                'order by t.time_created DESC;';
            if ($result = $mysqli->query($q)) {
                while ($row = $result->fetch_array()) {
                    echo '<tr>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $row['category'] . '</td>';
                    echo '<td>' . $row['amount'] . '</td>';
                    echo '<td>' . $row['wallet_name'] . '</td>';
                    echo '<td>' . $row['description'] . '</td>';
                    echo '<td>' . $row['time_created'] . '</td>';
                    echo '<td>' .
                        editButton($row['id']) .
                        '&nbsp' .
                        deleteButton($row['id']) .
                        '</td>';
                    echo '</tr>';
                }
            } else {
                echo 'Query error: ' . $mysqli->error;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap Table JS -->
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
