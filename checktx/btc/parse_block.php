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

const USERNAME = 'bitcoin';
const USERPASS = 'Warhead_123';
const BITCOIND_HOST = '192.168.12.11';
const BITCOIND_PORT = '8332'; // 18332 for testnet, 8332 for mainnet

$resList = [];

$bitcoin = new Bitcoin(USERNAME, USERPASS, BITCOIND_HOST, BITCOIND_PORT);

$blk_hash = $bitcoin->getbestblockhash();

$blk = $bitcoin->getblock($blk_hash,2);

foreach ($addresses as $addr) {
	// process each entry
    // list unspent txns
    $income_tx = $bitcoin->listunspent(1, 99999999, [$addr['address']]);
    $input_txs = [];
	$bal = 0;

	if ($income_tx) {
        foreach ($income_tx as $itx) { // prepare a list of all incoming transactions
        	$k = ['txid'=>$itx['txid'], 'vout'=>0 ];
	        $input_txs[] = $k;
			$bal += $itx['amount'];
        }
		$bal = round($bal,8);

	    // prepare raw tx
	    $tx_chg = [ RECIPIENT_ADDRESS=>$bal-TRANSACTION_FEE, $addr['address']=>0 ];

		print_r($tx_chg);
	    $rawtx = $bitcoin->createrawtransaction( $input_txs, $tx_chg);
		if ($rawtx===false) {
			echo 'Error creating transaction: '.$bitcoin->error.PHP_EOL;
			print_r($input_txs);
			print_r($tx_chg);
		}

	    $addr['tx'] = $rawtx;
	    $addr['balance'] = $bal;
	    array_push($resList, $addr);
	}
}

file_put_contents('balances.json', json_encode($resList));
echo "Success";

