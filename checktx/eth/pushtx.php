<?php

const API_TOKEN = 'f8ca05ae14a44449baacc3a163e0d4b4';

// get store existing products from manufacturer (DB)
$file = json_decode(file_get_contents('eth_transactions.json'), true);

// read up current counters for brands
foreach ($file as $addr=>$t) {
	// get txs for address
	$txs = file_get_contents('https://api.blockcypher.com/v1/eth/main/addrs/'.$addr.'?token='.API_TOKEN);
	$jres = json_decode($txs,true);
	$tx_arr = [];
	if (array_key_exists('txrefs',$jres)) {
		foreach ($jres['txrefs'] as $tx) {
			$tx_arr[$tx['tx_hash']] = $tx['value']/1e18;

			// get tx
			$jtxs = json_decode( file_get_contents('https://api.blockcypher.com/v1/eth/main/txs/'.$tx['tx_hash'].'?token='.API_TOKEN),true);

			$txdata = [];
			$txdata['to_address'] = $addr;

			if ($addr==$jtxs['addresses'][0]) {
				$txdata['from_address'] = $jtxs['addresses'][1];
			} else {
				$txdata['from_address'] = $jtxs['addresses'][0];
			}

			$txdata['txid'] = '0x'.$tx['tx_hash'];
			$txdata['confirmations'] = 0;
			$txdata['amount'] = $tx['value'];

			//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,'https://my.qilin.market/Token/Serve/ethIncome');
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$txdata);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 0);
	
			//execute post
			$result = curl_exec($ch);

			//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,'http://my.fishbank.io/api/eth/callback');
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$txdata);
	
			//execute post
			$result = curl_exec($ch);

		}
	}
	sleep(1);
}
