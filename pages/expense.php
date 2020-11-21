<div class="wrapper ">
    <?php include 'includes/nav-side.php'; ?>
    <div class="main-panel">
        <?php include 'includes/nav-top.php'; ?>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- <div class="card-header">
                            <h4 class="card-title"> Expense</h4>
                        </div> -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="d-flex flex-row-reverse"><button type="button" class="btn btn-outline-primary" 
                                        data-toggle="modal" data-target="#Modal" data-type="create">Add expense
                                        </button></div>
                                <table class="table table-borderless" data-toggle="table" data-sort-name="name"
                                    data-sort-order="desc" data-pagination="true" data-page-size="10"
                                    data-search="true" data-search-selector="#searchInput" data-header-style="headerStyle">
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
                                            'select t.id, t.wallet_id, w.name AS wallet_name, t.title, t.category, t.amount, t.description, t.time_created, DATE_FORMAT(t.time_created, "%d/%m/%Y") AS time_created_form ' .
                                            'from `transaction` t, wallet w where t.user_id = ? ' .
                                            'and t.wallet_id = w.id ' .
                                            'order by t.time_created DESC;';
                                        if (
                                            $rows = $db->run(
                                                $q,
                                                $_SESSION['user_id']
                                            )
                                        ) {
                                            foreach ($rows as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['title']; ?></td>
                                                <td><?php echo $row['category']; ?></td>
                                                <td><?php echo $row['amount']; ?></td>
                                                <td><?php echo $row['wallet_name']; ?></td>
                                                <td><?php echo $row['description']; ?></td>
                                                <td><?php echo $row['time_created_form']; ?></td>
                                                <td>
                                                    <?php echo editButton($row) .
                                                                    '&nbsp' . deleteButton($row['id'], '?p=modexpense') ;?>
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
        <form role="form" action="?p=modexpense" method="post">
            <div class="modal-body">
                <fieldset>
                    <div class="form-group mb-3 modal-title">
                        <label for="title">Title</label>
                        <input class="form-control" placeholder="Title" name="title" type="text">
                    </div>
                    <div class="form-group mb-3 modal-category">
                    <label for="category">Category</label>
                        <select class="form-control" name="category">
                        <?php foreach ($categories as $category) {?>
                            <option><?php echo $category; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mb-3 modal-amount">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" placeholder="Amount" name="amount" type="text">
                    </div>
                    <div class="form-group mb-3 modal-wallet">
                        <label for="wallet_id"><?php echo $m_wallet; ?></label>
                        <select class="form-control" name="wallet_id">
                            <?php
                            $q = 'select id, name from wallet where user_id = ? ;';
                            $rows = $db->run($q, $_SESSION['user_id']);
                            foreach ($rows as $row) { ?>
                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo $row['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group recur mb-3">
                        <label for="recurring"><?php echo $m_recurring; ?></label>
                        <input type="checkbox" id="recur" name="recurring" value="Yes">
                    </div>
                    <div class="form-group mb-3" id="recur-f" hidden>
                        <label for="recurring-frequency"><?php echo $m_recurringfrequency; ?></label>
                        <select class="form-control" name="recurring-frequency">
                            <option value="DAILY" selected>DAILY</option>
                            <option value="WEEKLY">WEEKLY</option>
                            <option value="MONTHLY">MONTHLY</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="recur-t" hidden>
                        <label for="recurring-times"><?php echo $m_recurringtimes; ?></label>
                        <input class="form-control" placeholder="<?php echo $m_recurringtimes; ?>" name="recurring-times" type="number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="manual-dates"><?php echo $m_manualdates; ?></label>
                        <input type="checkbox" id="manual-dates" name="manual-dates" value="Yes">
                    </div>

                    <div class="form-group mb-3" id="range" hidden>
                        <div class="input-group modal-from">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $m_date; ?></span>
                            </div>
                            <input name="from-date" class="form-control" data-provide="datepicker" data-date-format="yyyy/mm/dd" value="">
                        </div>
                    </div>
                    <div class="form-group modal-description mb-3">
                        <label for="description"><?php echo $m_description; ?></label>
                        <textarea class="form-control" placeholder="<?php echo $m_description; ?>" name="description" rows="3"><?php //echo $v_description; ?></textarea>
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
        $('#recur').click(function() {
            var checked = $('#recur').is(':checked');
            $('#recur-f').prop('hidden', !checked);
            $('#recur-t').prop('hidden', !checked);
        });

        $('#manual-dates').on('click change', function() {
            var checked = $('#manual-dates').is(':checked');
            $('#range').prop('hidden', !checked);
        });

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
                modal.find('.modal-title input').val(data['title'])
                modal.find('.modal-category select').val(data['category']).attr('selected','selected');
                modal.find('.modal-amount input').val(data['amount'])
                modal.find('.modal-wallet select').val(data['wallet_id']).attr('selected','selected');
                modal.find('.modal-description textarea').val(data['description'])
                modal.find('.recur').hide();
                $('#manual-dates').prop( "checked", true );
                $('#range').prop('hidden', false);
                modal.find('.modal-from input').val(data['time_created'])
            }
            else if (type == 'create'){
                modal.find('.modal-footer input').val('')
                modal.find('.modal-title input').val('')
                modal.find('.modal-category select')[0].selectedIndex = 0;
                modal.find('.modal-amount input').val('')
                modal.find('.modal-wallet select')[0].selectedIndex = 0;
                modal.find('.modal-description textarea').val('')
                modal.find('.recur').show();
                $('#manual-dates').prop( "checked", false );
                $('#range').prop('hidden', true);
                modal.find('.modal-from input').val('')
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