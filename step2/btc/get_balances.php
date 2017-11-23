<?php
require_once('easybitcoin.php');
const SCRIPT_NAME = 'publiclist.json';

$file_to_parse = SCRIPT_NAME;

if ($argc>1) {
    $file_to_parse = $argv[1];
}

$rfile = @file_get_contents( $file_to_parse);

if ($rfile===FALSE) {
    echo "Error opening $file_to_parse, exiting".PHP_EOL;
    return;
}

$addresses = json_decode($rfile, true);

// Initialize Bitcoin connection/object
$bitcoin = new Bitcoin('taurus','Qq12345','localhost',18332);

$result_array = [];

echo 'Getting balances, please wait...'.PHP_EOL;

foreach($addresses as $a) {
    $r = $bitcoin->getbalance($a['address']);
    $a['balance'] = $r;
    array_push($result_array,$a);
}

$res = json_encode($result_array);

file_put_contents('balances.json',$res);

echo "Success".PHP_EOL;