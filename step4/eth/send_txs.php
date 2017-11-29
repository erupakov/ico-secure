<?php
/**
 * Script to extract addresses balances and create raw txs
 * may be called like this: php get_balances.php [publiclist.json]
 * 
 * @author  Eugene Rupakov <eugene.rupakov@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0, AFL-2
 * @link    http://www.github.com/erupakov
 */

require_once 'vendor/autoload.php';

const SIGNFILENAME = 'signed.json';
const GETH_URL = 'http://localhost:8545';

$fname = SIGNFILENAME;
if (argc>1) {
    $fname = $argv[1];
}

$s_file = @file_get_contents($fname);

if ($s_file===false) {
    echo 'Signed transactions list file <$fname> open failed'.PHP_EOL;
    return;
}

$resList = [];

$addresses = json_decode($s_file, true);

$geth= new Ethereum(GETH_URL);

foreach ($addresses as $addr) {
// process each entry
    $res = $geth->client->eth_sendRawTransaction($addr['tx']);
    $addr['result'] = $res;

    array_push($resList, $addr);
}

file_put_contents('sent_txns.json', json_encode($resList));
echo "Success";
