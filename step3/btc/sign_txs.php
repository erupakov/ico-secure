<?php
/**
 * Script to sign raw txs
 * may be called like this: php sign_txs.php [balances.json] [secretlist.json]
 * 
 * @author  Eugene Rupakov <eugene.rupakov@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0, AFL-2
 * @link    http://www.github.com/erupakov
 */

require_once 'easybitcoin.php';

const TXNFILENAME = 'balances.json';
const SECFILENAME = 'secretlist.json';
const USERNAME = 'taurus';
const USERPASS = 'Qwerty123';
const BITCOIND_PORT = 8332; // 18332 for testnet, 8332 for mainnet

$txn_fname = TXNFILENAME;
$sec_fname = SECFILENAME;

if ($argc>1) {
    $txn_fname = $argv[1];
}

if ($argc>2) {
    $sec_fname = $argv[2];
}

$txn_file = @file_get_contents($txn_fname);

if ($txn_file===false) {
    echo 'Transactions list file <$txn_fname> open failed'.PHP_EOL;
    return;
}

$sec_file = @file_get_contents($sec_fname);
if ($sec_file===false) {
    echo 'Secret keys list file <$sec_fname> open failed'.PHP_EOL;
    return;
}

$resList = [];

$txns = json_decode($txn_file, true);
$pkeys = json_decode($sec_file, true);

$sorted_pvt_keys = [];
// make indexed array for private keys
foreach ($pkeys as $p) {
    $sorted_pvt_keys[$p['address']] = $p['private'];
}

$bitcoin = new Bitcoin(USERNAME, USERPASS, 'localhost', BITCOIND_PORT);

foreach ($txns as $txn) {
// process each entry
    $pk = $sorted_pvt_keys[$txn['address']];
    $res = $bitcoin->signrawtransaction($txn['tx'], null, [$pk], 'ALL');

	print_r($res);

	if ($res===false) {
        echo 'Error signing TXN for address '.$txn['address'].':'.$bitcoin->error.PHP_EOL;
        continue;
	}

    if ($res['complete']) {
        $n = ['address'=>$txn['address'], 'tx'=>$res['hex']];
        array_push($resList, $n);
    } else {
        echo 'Error signing TXN for address '.$txn['address'].':'.$bitcoin->error.PHP_EOL;
        continue;
    }
}

file_put_contents('signed.json', json_encode($resList));
echo "Success";