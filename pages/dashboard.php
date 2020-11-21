<?php 

$dict = [
    'frequency' => 'MONTHLY',
    'from-date' => null,
    'to-date' => null,
];

if (isset($_POST['save'])) {
    if (isset($_POST['manual-dates'])) {
        $dict['from-date'] = $_POST['from-date'];
        $dict['to-date'] = $_POST['to-date'];
        $dict['frequency'] = '';
    } else {
        $dict['frequency'] = $_POST['recurring-frequency'];
    }
}

$row = $db->row('CALL `Get dashboard`(?,?,?,?)', $_SESSION['user_id'], $dict['from-date'], $dict['to-date'], $dict['frequency']);
?>

<div class="wrapper ">
    <?php include 'includes/nav-side.php' ?>
    <div class="main-panel">
        <?php include 'includes/nav-top.php' ?>
        <div class="content">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-globe text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Total Expense</p>
                                        <p class="card-title"><?php echo $row['@total_expense']; ?>
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-refresh"></i>
                                Updated Now
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-money-coins text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Avg Expense</p>
                                        <p class="card-title"><?php if ($row['percentage_increase'] > 0) {echo '+ ';} else {echo '- ';}
                                                                    echo number_format($row['percentage_increase'], 2); ?>%
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar-o"></i>
                                This <?php if (isset($dict['frequency'])) echo $dict['frequency']; ?> period
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-vector text-danger"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Latest</p>
                                        <p class="card-title" style="font-size: 0.7em"><?php echo $row['@last_category']; ?>
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i>
                                Recently
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-favourite-28 text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">High Expense</p>
                                        <p class="card-title"><?php echo number_format($row['@highest_expense'], 0); ?>
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-refresh"></i>
                                Update now
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="card-title">Users Behavior</h5>
                            <p class="card-category">24 Hours performance</p>
                        </div>
                        <div class="card-body ">
                            <canvas id=chartHours width="400" height="100"></canvas>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-history"></i> Updated 3 minutes ago
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="card-title">Expense by Category</h5>
                            <!-- <p class="card-category">Last Campaign Performance</p> -->
                        </div>
                        <div class="card-body ">
                            <canvas id="donutChart" width="200" height="150"></canvas>
                        </div>
                        <div class="card-footer ">
                            <!-- <div class="legend">
                                <i class="fa fa-circle text-primary"></i> Opened
                                <i class="fa fa-circle text-warning"></i> Read
                                <i class="fa fa-circle text-danger"></i> Deleted
                                <i class="fa fa-circle text-gray"></i> Unopened
                            </div>
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar"></i> Number of emails sent
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-title">Budget</h5>
                            <!-- <p class="card-category">Progress bar</p> -->
                        </div>
                        <div class="card-body">
                            <?php 
                            $arr = array('primary', 'info', 'secondary', 'warning', 'success'); 
                            $budgets = $db->run('Call `Get all budget category info`(?)', $_SESSION['user_id']);
                            foreach(array_values($budgets) as $i => $budget) { 
                                $ratio = number_format($budget['used']*100/$budget['maximum'], 0);?>
                                <div class="progress-container progress-<?php echo $arr[$i]; ?>">
                                    <span class="progress-badge" ><?php echo $budget['category']; ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $ratio?>%;">
                                            <span class="progress-value"><?php echo $ratio?>%</span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- <br>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <br>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 70%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div> -->
                        </div>
                        <!-- <div class="card-footer">
                            <div class="chart-legend">
                                <i class="fa fa-circle text-info"></i> Tesla Model S
                                <i class="fa fa-circle text-warning"></i> BMW 5 Series
                            </div>
                            <hr />
                            <div class="card-stats">
                                <i class="fa fa-check"></i> Data information certified
                            </div>
                        </div> -->
                    </div>
                    
                </div>
                <canvas id="myChart" width="400" height="200"></canvas>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="title">Filter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form role="form" action="" method="post">
            <div class="modal-body">
                <fieldset>
                    <div class="form-group recur mb-3">
                        Pick any period or manually select time range:
                    </div>
                    <div class="form-group mb-3" id="recur-f">
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
                <button type="submit" name="save" class="btn btn-primary">Save changes</button>
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

    var ctx1 = document.getElementById('donutChart').getContext('2d');
    var myDoughnutChart = new Chart(ctx1, {
        type: 'doughnut',
        
        data: {
                labels: [
                    'Red',
                    'Yellow',
                    'Blue'
                ],
                datasets: [{
                    data: [10, 20, 30],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ]
            }],
        }
        
    });

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"
    integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
    crossorigin="anonymous"></script>