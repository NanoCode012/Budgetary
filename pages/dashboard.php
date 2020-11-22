<?php 

$dict = [
    'frequency' => 'MONTHLY',
    'from-date' => null,
    'to-date' => null,
];

if (isset($_POST['save'])) {
    if (isset($_POST['manual-dates'])) {
        if (isset($_POST['from-date']) && isset($_POST['to-date']) && $_POST['from-date'] != '' && $_POST['to-date'] != '') {
            $dict['from-date'] = $_POST['from-date'];
            $dict['to-date'] = $_POST['to-date'];
            $dict['frequency'] = '';
        } else {
            $_SESSION['msgBox'] = error($m_missingdates);
            header('Location: ?p=dashboard');
            exit();
        }
    } else {
        $dict['frequency'] = $_POST['recurring-frequency'];
    }
}

$row = $db->row('CALL `Get dashboard`(?,?,?,?)', $_SESSION['user_id'], $dict['from-date'], $dict['to-date'], $dict['frequency']);
$expense_categories = $db->run('CALL `Get expense category used`(?,?,?,?)', $_SESSION['user_id'], $dict['from-date'], $dict['to-date'], $dict['frequency']);
$expense_time = $db->run('CALL `Get expense time used`(?,?,?,?)', $_SESSION['user_id'], $dict['from-date'], $dict['to-date'], $dict['frequency']);
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
                                        <p class="card-title"><?php if ($row['@cur_period_sum']) {echo number_format($row['@cur_period_sum'],2);} else {echo 'N/A';} ?>
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-refresh"></i>
                                Updated now
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
                                        <p class="card-title" style="font-size: 0.8em">
                                                        <?php $t = $row['percentage_increase']; 
                                                                if ($t != 'N/A') {
                                                                    echo number_format($t, 2) . '%';
                                                                } else echo $t; ?>
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar-o"></i>
                                 <?php if ($dict['frequency'] != '') {echo $dict['frequency'];} else {echo 'This period';}?>
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
                                        <i class="fas fa-hourglass-start text-danger"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Category</p>
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
                                Recently in this period
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
                                        <i class="fas fa-arrow-up text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Highest Expense</p>
                                        <p class="card-title"><?php echo number_format($row['@highest_expense'], 0); ?>
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fas fa-retweet"></i>
                                Largest in this period
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="card-title">Expense by Time</h5>
                            <!-- <p class="card-category">24 Hours performance</p> -->
                        </div>
                        <div class="card-body ">
                            <canvas id="barChart" width="400" height="100"></canvas>
                        </div>
                        <!-- <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-history"></i> Updated 3 minutes ago
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
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
                <div class="col-md-7">
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
                            <option value="DAILY">DAILY</option>
                            <option value="WEEKLY">WEEKLY</option>
                            <option value="MONTHLY" selected>MONTHLY</option>
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
                            <input name="from-date" class="form-control" data-provide="datepicker" data-date-format="yyyy/mm/dd">
                        </div>
                        <div class="input-group modal-to">
                            <div class="input-group-prepend">
                                <span class="input-group-text">To&nbsp;&nbsp;</span>
                            </div>
                            <input name="to-date" class="form-control" data-provide="datepicker" data-date-format="yyyy/mm/dd">
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

    var barDataContainer = <?= json_encode($expense_time) ?>;
    // console.log(barDataContainer);
    var categories = <?= json_encode($categories) ?>;
    var dataCategories = [];
    var category_map = {};
    var category_bgcol = [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ];
    var category_bdcol = [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ];
    let i = 0;
    categories.forEach(function(ele) {
        dataCategories.push({
            data: [], 
            label: ele,
            backgroundColor: category_bgcol[i],
            borderColor: category_bdcol[i],
            borderWidth: 1,
        })
        category_map[ele] = i;
        i++;
    });
    // console.log(dataCategories);
    // console.log(category_map);

    let date = barDataContainer[0]['date'];
    let count = 0;
    barDataContainer.forEach(function(row) {
        if (date != row['date']) {
            count++;
            dataCategories.forEach(function(r) {
                if (r['data'].length != count) {
                    // add dummy data
                    dataCategories[category_map[r['label']]]['data'].push({
                        x: new Date(date),
                        y: 0
                    });
                }
            });
            date = row['date'];
        }
        dataCategories[category_map[row['category']]]['data'].push({
            x: new Date(row['date']),
            y: row['used']
        });

    })
    // loop to equalize at last
    let max_count = 0;
    dataCategories.forEach(function(r){
        if (r['data'].length > max_count) max_count = r['data'].length;
    })
    if (count != max_count) {
        date = barDataContainer[barDataContainer.length-1]['date'];
        count = max_count;
        dataCategories.forEach(function(r) {
            if (r['data'].length != count) {
                // add dummy data
                dataCategories[category_map[r['label']]]['data'].push({
                    x: new Date(date),
                    y: 0
                });
            }
        });
    }

    // console.log(dataCategories);
    
    var config = {
            type:'bar',
            data:{
                datasets:dataCategories
            },
            options: {
                responsive: true,
                title:{
                    display:false,
                    text:"My Chart"
                },
                tooltips: {
                    mode: 'index'
                },
                hover: {
                    mode: 'index'
                },
                legend: {
                        position: 'bottom'
                },
                elements: { point: { radius: 0 }},
                scales: {
                    xAxes: [{
                        stacked: true,
                        type: 'time',
                        time: {
                            unit: 'day',
                            unitStepSize: 1,
                            displayFormats:{
                                // minutes:'HH:mm'
                                day: 'DD MMM'
                            }

                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        },
                        offset: true,
                        gridLines: {
                            display:false
                        }
                    }],
                    yAxes: [{
                        stacked:true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Expense'
                        }
                    }]
                }
            }
        };

    var ctx2 = document.getElementById('barChart').getContext('2d');
    var myBarChart = new Chart(ctx2, config);

    var donutDataContainer = <?= json_encode($expense_categories) ?>;
    var labelDonut = [];
    var dataDonut = [];
    // console.log(donutDataContainer);
    donutDataContainer.forEach(function (row) {
        labelDonut.push(row['category']);
        dataDonut.push(row['category_used']);
        return false;
    });
    
    var ctx1 = document.getElementById('donutChart').getContext('2d');
    var myDoughnutChart = new Chart(ctx1, {
        type: 'doughnut',
        data: {
                labels: labelDonut,
                datasets: [{
                    data: dataDonut,
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
        },
        options: {
            legend: {
                position: 'bottom'
            }
        }
        
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"
    integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
    crossorigin="anonymous"></script>