<?php
/**
 * Script to send raw txs to blockchain. Should be run on online machine with bitcoind
 * may be called like this: php send_txs.php [signed.json]
 * 
 * @author  Eugene Rupakov <eugene.rupakov@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0, AFL-2
 * @link    http://www.github.com/erupakov
 */

require_once 'easybitcoin.php';

const SIGNFILENAME = 'signed.json';
const USERNAME = 'taurus';
const USERPASS = 'Qwerty123';
const BITCOIND_PORT = 18332; // 18332 for testnet, 8332 for mainnet

$fname = SIGNFILENAME;

if ($argc>1) {
    $fname = $argv[1];
}

$s_file = @file_get_contents($fname);

if ($s_file===false) {
    echo 'Signed transaction list file <$fname> open failed'.PHP_EOL;
    return;
}

$resList = [];

$txns = json_decode($s_file, true);

$bitcoin = new Bitcoin(USERNAME, USERPASS, 'localhost', BITCOIND_PORT);

foreach ($txns as $txn) {
// process each entry
    $res = $bitcoin->sendrawtransaction($txn['tx']);
    echo $res.PHP_EOL;
    $n = ['address'=>$txn['address'], 'result'=>$res];
    array_push($resList, $n);
}

file_put_contents('sent_txns.json', json_encode($resList));
echo "Success";
