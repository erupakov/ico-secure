<?php

const DB_USER = '';
const DB_PASS = '';
const DB_NAME = '';
const API_TOKEN = '';

$mysql_cli = new mysqli('localhost', DB_USER, DB_PASS, DB_NAME);

$result = [];
// get store existing products from manufacturer (DB)
$dbres = $mysql_cli->query('SELECT * from address where isDistributed=1 and blockchainType=0');
// read up current counters for brands
for ($row_no = 0; $row_no<$dbres->num_rows; $row_no++ ) {
	$dbres->data_seek($row_no);
	$row = $dbres->fetch_assoc();
	$address = $row['address'];
	// get txs for address
	$txs = file_get_contents('https://api.blockcypher.com/v1/btc/main/addrs/'.$address.'?token='.API_TOKEN);
	$jres = json_decode($txs,true);
	$tx_arr = [];
	if (array_key_exists('txrefs',$jres) {
		foreach ($jres['txrefs'] as $tx) {
			$tx_arr[$tx['tx_hash']] = $tx['value'];
		}
	}
	$result[$address] = $tx_arr;
	sleep(1);
}
$mysql_cli->close();

file_put_contents('btc_transactions.json', json_encode($result));
