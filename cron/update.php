<?php
require '../vendor/autoload.php';
include '../config/dbconf.php';

$rows = $db->run('SELECT * FROM currency;');

$arr = [];
foreach ($rows as $row) {
    $arr[] = $row['name'];
}

$s = join(',', $arr);

$json = file_get_contents(
    'http://api.currencylayer.com/live?access_key=bef8fb3a7e2b87c2928cd7c6508ef983&currencies=' .
        $s .
        '&format=1'
);

$obj = json_decode($json, true);
$quotes = $obj['quotes'];
echo json_encode($quotes) . '<br>';

foreach ($rows as $row) {
    $r = 1 / $quotes['USD' . $row['name']];
    echo 'USD -> ' . $row['name'] . ' = ' . $r . '<br>';
    $db->update(
        'currency',
        ['relative' => $r],
        ['id' => $row['id']]
    );
}

echo 'Finished';
?>
