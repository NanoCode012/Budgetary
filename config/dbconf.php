<?php
$filename = '../../secret/budgetary-dbconfig.php';

if (file_exists($filename)) {
    require_once $filename;
} else {
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'root';
    $accdb = 'manager';
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    $db = \ParagonIE\EasyDB\Factory::fromArray([
        'mysql:host=' . $dbhost . ';dbname=' . $accdb,
        $dbuser,
        $dbpass,
    ]);
} catch (Exception $e) {
    exit('Caught exception when trying to connect to db ' . $e->getMessage());
}

?>
