<?php
/**
 * Script to extract addresses balances and create raw txs
 * may be called like this: php get_balances.php [upload_tx.json]
 * 
 * @author  Eugene Rupakov <eugene.rupakov@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0, AFL-2
 * @link    http://www.github.com/erupakov
 */

require_once 'vendor/autoload.php';

use Ethereum\Ethereum;
use Ethereum\EthBlockParam;
use Ethereum\EthD20;

const PUBFILENAME = 'upload_tx.json';
const GETH_URL = 'http://192.168.10.30:8545';

$fname = PUBFILENAME;
if ($argc>1) {
    $fname = $argv[1];
}

$rfile = @file_get_contents($fname);

if ($rfile===false) {
    echo "Addresses list file <$fname> open failed".PHP_EOL;
    return;
}

$resList = [];

$addresses = json_decode($rfile, true);

$geth= new Ethereum(GETH_URL);

foreach ($addresses as $addr) {
    if ($addr->blockchainType==1) { // Ethereum
// process each entry
    $ethAddr = new EthD20($addr['address']);
    $bal = $geth->eth_getBalance($ethAddr,new EthBlockParam());
    $addr['balance'] = $addr->matRate;
    $addr['required'] = $addr->amount;

    array_push($resList, $addr);
}

file_put_contents('balances.json', json_encode($resList));
echo "Success";
