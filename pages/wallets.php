<div class="wrapper ">
    <?php include 'includes/nav-side.php'; ?>
    <div class="main-panel">
        <?php include 'includes/nav-top.php'; ?>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- <div class="card-header">
                            <h4 class="card-title"> Wallets</h4>
                        </div> -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="d-flex flex-row-reverse"><button type="button" class="btn btn-outline-primary" 
                                        data-toggle="modal" data-target="#Modal" data-type="create">Add wallet
                                        </button></div>
                                <table class="table table-borderless" data-toggle="table" data-pagination="true" data-page-size="10"
                                    data-search="true" data-search-selector="#searchInput" data-header-style="headerStyle">
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
                                            foreach ($rows as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['wallet_name']; ?></td>
                                                <td><?php echo $row['amount']; ?></td>
                                                <td><?php echo $row['currency_name']; ?></td>
                                                <td>
                                                    <?php echo editButton($row) .
                                                                    '&nbsp' . deleteButton($row['id'], '?p=modwallets') ;?>
                                                </td>
                                            </tr>
                                        <?php } } ?>
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
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form role="form" action="?p=modwallets" method="post">
            <div class="modal-body">
                <fieldset>
                    <div class="form-group mb-3 modal-name">
                        <label for="name">Name</label>
                        <input class="form-control" placeholder="Name" name="name" type="text">
                    </div>
                    <div class="form-group mb-3 modal-amount">
                        <label for="amount">Amount</label>
                        <input class="form-control" placeholder="Amount" name="amount" type="number">
                    </div>
                    <div class="form-group mb-3 modal-currency">
                        <label for="currency_id">Currency</label>
                        <select class="form-control" name="currency_id">
                        <?php
                        $q = 'select id, name from currency';
                        $currencies = $db->run($q);
                        foreach ($currencies as $currency) { ?>
                            <option value="<?php echo $currency['id']; ?>">
                                <?php echo $currency['name']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <input name="id" hidden>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>
<script>
    $(function() {

        $('#Modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var data = button.data('service') // Extract info from data-* attributes
            var type = button.data('type')
            var modal = $(this)
            
            // Type: Edit
            modal.find('#title').text(capitalizeFirstLetter(type));
            modal.find('.modal-footer .btn-primary').attr('name', type)
            if (type == 'edit') {
                modal.find('.modal-footer input').val(data['id'])
                modal.find('.modal-name input').val(data['wallet_name'])
                modal.find('.modal-currency select').val(data['currency_id']).attr('selected','selected');
                modal.find('.modal-amount input').val(data['amount'])
            }
            else if (type == 'create'){
                modal.find('.modal-footer input').val('')
                modal.find('.modal-name input').val('')
                modal.find('.modal-currency select')[0].selectedIndex = 0;
                modal.find('.modal-amount input').val('')
            }
        });
    });

    function headerStyle(column) {
    return {
        classes: 'bg-primary',
        css: {color: 'white'}
    }
  }
</script>