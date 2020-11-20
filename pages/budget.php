<div class="wrapper ">
    <?php include 'includes/nav-side.php'; ?>
    <div class="main-panel" style="height: 100vh;">
        <?php include 'includes/nav-top.php'; ?>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- <div class="card-header">
                            <h4 class="card-title"> Budget</h4>
                        </div> -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="d-flex flex-row-reverse"><button type="button" class="btn btn-outline-primary" 
                                        data-toggle="modal" data-target="#Modal" data-type="create">Add budget
                                        </button></div>
                                <table class="table table-borderless" data-toggle="table" data-pagination="true" data-page-size="10"
                                    data-search="true" data-search-selector="#searchInput" data-header-style="headerStyle">
                                    <thead class='text-primary'>
                                        <tr>
                                            <th scope="col" data-field="category" data-sortable="true">Category</th>
                                            <th scope="col" data-field="maximum" data-sortable="true">Maximum</th>
                                            <th scope="col" data-field="currency" data-sortable="true">Frequency</th>
                                            <th scope="col" data-field="start-time" data-sortable="true">Start time</th>
                                            <th scope="col" data-field="end-time" data-sortable="true">End time</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        $q =
                                            'SELECT id, category, maximum, frequency, start_time, DATE_FORMAT(start_time, "%d/%m/%Y") AS start_time_form, end_time, DATE_FORMAT(end_time, "%d/%m/%Y") AS end_time_form '.
                                            'FROM budget WHERE user_id=? AND end_time > NOW();';
                                        if (
                                            $rows = $db->run(
                                                $q,
                                                $_SESSION['user_id']
                                            )
                                        ) {
                                            foreach ($rows as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['category']; ?></td>
                                                <td><?php echo $row['maximum']; ?></td>
                                                <td><?php echo $row['frequency']; ?></td>
                                                <td><?php echo $row['start_time_form']; ?></td>
                                                <td><?php echo $row['end_time_form']; ?></td>
                                                <td>
                                                    <?php echo editButton($row) .
                                                                    '&nbsp' . deleteButton($row['id'], '?p=modbudget') ;?>
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
        <form role="form" action="?p=modbudget" method="post">
            <div class="modal-body">
                <fieldset>
                    <div class="form-group mb-3 modal-category">
                        <label for="category">Category</label>
                        <select class="form-control" name="category">
                        <?php foreach ($categories as $category) {?>
                            <option><?php echo $category; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mb-3 modal-maximum">
                        <label for="maximum">Maximum</label>
                        <input class="form-control" placeholder="Maximum" name="maximum" type="number">
                    </div>
                    <div class="form-group mb-3 modal-frequency">
                        <label for="frequency"><?php echo $m_recurringfrequency; ?></label>
                        <select class="form-control" name="frequency">
                            <option value="DAILY" selected>DAILY</option>
                            <option value="WEEKLY">WEEKLY</option>
                            <option value="MONTHLY">MONTHLY</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="manual-dates"><?php echo $m_manualdates; ?></label>
                        <input type="checkbox" id="manual-dates" name="manual-dates" value="Yes">
                    </div>

                    <div class="form-group mb-3" id="range" hidden>
                        <label for="time-range"><?php echo $m_timerange; ?></label>
                        <div class="input-group modal-from">
                            <div class="input-group-prepend">
                                <span class="input-group-text">From</span>
                            </div>
                            <input name="from-date" class="form-control" data-provide="datepicker" data-date-format="yyyy/mm/dd" value="">
                        </div>
                        <div class="input-group modal-to">
                            <div class="input-group-prepend">
                                <span class="input-group-text">To&nbsp;&nbsp;</span>
                            </div>
                            <input name="to-date" class="form-control" data-provide="datepicker" data-date-format="yyyy/mm/dd" value="">
                        </div>
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
                modal.find('.modal-category select').val(data['category']).attr('selected','selected');
                modal.find('.modal-maximum input').val(data['maximum'])
                modal.find('.modal-frequency select').val(data['frequency']).attr('selected','selected');
                modal.find('.modal-from input').val(data['start_time'])
                modal.find('.modal-to input').val(data['end_time'])
                $('#manual-dates').prop( "checked", true );
                $('#range').prop('hidden', false);
            }
            else if (type == 'create'){
                modal.find('.modal-footer input').val('')
                modal.find('.modal-category select')[0].selectedIndex = 0;
                modal.find('.modal-maximum input').val('')
                modal.find('.modal-frequency select')[0].selectedIndex = 0;
                modal.find('.modal-from input').val('')
                modal.find('.modal-to input').val('')
                $('#manual-dates').prop( "checked", false );
                $('#range').prop('hidden', true);
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