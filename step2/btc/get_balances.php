<?php
/**
 * Script to extract addresses balances and create raw txs
 */
require_once('easybitcoin.php');

const PUBFILENAME = 'publiclist.json';
const USERNAME = 'taurus';
const USERPASS = 'Qwerty123';
const RECIPIENT_ADDRESS = 'myBqKQCepHr6rpsWtTQimyXGFDiV6s41km'; // address to send BTC to
const TRANSACTION_FEE = 0.3 / 1000; // in mBTCs

$fname = PUBFILENAME;
if (argc>1) {
    $fname = $argv[1];
}

$rfile = @file_get_contents($fname);

if ($rfile===false) {
    echo 'Addresses list file <$fname> open failed'.PHP_EOL;
    return;
}

$resList = [];

$addresses = json_decode($rfile, true);

$bitcoin = new Bitcoin(USERNAME, USERPASS, 'localhost', 18332);

foreach ($addresses as $addr) {
// process each entry
    $bal = $bitcoin->getbalance($addr['address']);
    $addr['balance'] = $bal;

    if ($bal>0) {
        // list unspent txns
        $income_tx = $bitcoin->listunspent(1, 99999999, $addr['address']);
        $input_txs = [];
        $itx_count = 0;

        foreach ($income_tx as $itx) { // prepare a list of all incoming transactions
            $k = ['txid'=>$itx['txid'], 'vout'=>$itx_count ];
            $input_txs[] = $k;
            $itx_count++;
        }

        // prepare raw tx
        $tx_chg = [ RECIPIENT_ADDRESS=>$bal-TRANSACTION_FEE, $addr['address']=>0 ];
        $rawtx = $bitcoin->createrawtransaction($input_txs, $tx_chg);

        $addr['tx'] = $rawtx;

        // only filled accounts count
        array_push($resList, $addr);        
    }
}

file_put_contents('balances.json', json_encode($resList));
echo "Success";