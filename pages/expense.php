<div><a class="btn btn-primary" href="?p=addexpense" role="button">Add expense</a></div>
<div class="table-responsive-sm">
    <table class='table'>
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Category</th>
                <th scope="col">Amount</th>
                <th scope="col">Wallet</th>
                <th scope="col">Description</th>
                <th scope="col">DateTime</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q = 'select t.id, t.wallet_id, w.name AS wallet_name, t.title, t.category, t.amount, t.description, t.time_created ' .
                'from `transaction` t, wallet w where t.user_id = ' . $_SESSION['user_id'] . ' and t.wallet_id = w.id ' .
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
                    echo '</tr>';
                }
            } else {
                echo 'Query error: ' . $mysqli->error;
            }
            ?>
        </tbody>
    </table>
</div>