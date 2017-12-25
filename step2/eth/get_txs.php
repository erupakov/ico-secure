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

use Ethereum\Ethereum;
use Ethereum\EthBlockParam;
use Ethereum\EthD20;
use Ethereum\EthQ;
use Ethereum\EthB;
use Ethereum\Transaction;
use Ethereum\Block;

const GETH_URL = 'http://192.168.12.14:8545';

$resList = [];

$geth = new Ethereum(GETH_URL);

$blocks = $geth->eth_blockNumber();
print_r($blocks);

$blkNum = gmp_intval($blocks->value->value);

print_r($blkNum);

for ($i = 5; $i >= 0; $i--) {
    $d_blkNum = new EthBlockParam($blkNum-$i);
    $blk = $geth->eth_getBlockByNumber($d_blkNum, new EthB(1));

    foreach ($blk->transactions as $tx) {
	$addr_from = $tx->from->toString();
	$addr_to = $tx->to->toString();
	$addr_value = $tx->value->toString();
	$addr_txhash = $tx->txhash->toString();
	print_r('TX: ['.$addr_from.'],['.$addr_to.']['.$addr_hash.']='.$addr_value.PHP_EOL);
    }
}

echo "Success";