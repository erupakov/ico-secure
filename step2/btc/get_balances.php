<?php
/**
 * Script to extract addresses balances and create raw txs
 * may be called like this: php get_balances.php [publiclist.json]
 * 
 * @author  Eugene Rupakov <eugene.rupakov@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0, AFL-2
 * @link    http://www.github.com/erupakov
 */

require_once 'easybitcoin.php';

const PUBFILENAME = 'publiclist.json';
const USERNAME = 'taurus';
const USERPASS = 'Qwerty123';
const RECIPIENT_ADDRESS = 'n3ap6PJwboX1MnuSp5aZLK6Sq6dyr93fMQ'; // address to send BTC to
const TRANSACTION_FEE = 0.1 / 1000; // in mBTCs
const BITCOIND_PORT = 18332; // 18332 for testnet, 8332 for mainnet

$fname = PUBFILENAME;
if ($argc>1) {
    $fname = $argv[1];
}

$rfile = @file_get_contents($fname);

if ($rfile===false) {
    echo 'Addresses list file <$fname> open failed'.PHP_EOL;
    return;
}

$resList = [];

$addresses = json_decode($rfile, true);

$bitcoin = new Bitcoin(USERNAME, USERPASS, 'localhost', BITCOIND_PORT);

foreach ($addresses as $addr) {
// process each entry
    $bal = $bitcoin->getbalance($addr['address']);
    $addr['balance'] = $bal;

    if ($bal>0) {
        // list unspent txns
        $income_tx = $bitcoin->listunspent(1, 99999999, $addr['address']);
		print_r($income_tx);
        $input_txs = [];
        $itx_count = 0;

        foreach ($income_tx as $itx) { // prepare a list of all incoming transactions
            $k = ['txid'=>$itx['txid'], 'vout'=>$itx_count ];
            $input_txs[] = $k;
            $itx_count++;
        }

        // prepare raw tx
        $tx_chg = [ RECIPIENT_ADDRESS=>$bal-TRANSACTION_FEE, $addr['address']=>0 ];
		print_r($tx_chg);

        $rawtx = $bitcoin->createrawtransaction($input_txs, $tx_chg);

        $addr['tx'] = $rawtx;
        // only accounts with balance>0 count
	    array_push($resList, $addr);
    }
}

file_put_contents('balances.json', json_encode($resList));
echo "Success";
