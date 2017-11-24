# ico-secure
A set of scripts to provide secure funds gather during ICO

Scripts differentiate between Ethereum and Bitcoin blockchains (they are located in different folders inside each step).

## Setup
To setup machines you will need:

### Prerequisites

**Online machine**:
Bitcoin: synchronized bitcoind server with JSON-RPC enabled (localhost and default port will do).
Ethereum: synchronized geth with WS and JSON-RPC enabled (localhost and default port will do).

**Offline machine**:
Bitcoin: unsynchronized bitcoind server with JSON-RPC enabled (localhost and default port will do) -- it will be used for signing only.
Ethereum: unsynchronized geth with WS and JSON-RPC enabled (localhost and default port will do) -- it will be used for signing only.

**All machines**:
PHP 5.6+, Composer installed (Ubuntu: sudo apt-get install php7.0 composer)
Nodejs, npm installed (Ubuntu: sudo apt-get install nodejs-legacy npm)

### Preparation
In the following folders run 'composer update' command to fetch the dependencies:
step2/eth, step4/eth

## Bitcoin workflow is as following

### Step 1
Should be done on offline computer.
Scripts generate 2 lists: one secret list with private keys ( _secretlist.json_ ), it should be kept secret. Other is a list of addresses that should be sent to investors ( _publiclist.json_ ).

### Step 2 
Should be done on online computer with synchronized bitcoind installed when you need to gather funds from wallets.

Put _publiclist.json_ from step 1 in the folder step2/btc with scripts.
Edit script _get_balances.php_ and put RECEIVER_ADDR constant to address of wallet to transfer funds to (BTC only).
Enter appropriate user and password to connect to bitcoind server.

run: `php get_balances.php` and wait for script to complete.
The results of the script work will be available in the same folder as balances.json file, it will contain current balances and (for BTC) prepared output transactions.
Warning: only non-empty addresses will be dumped.

### Step 3
Should be done on offline computer with (possibly) unsynchronized bitcoind installed.

Edit script _sign_txs.php_ and enter appropriate user and password to connect to bitcoind server.
Transfer _balances.json_ file generated at step 2 from online machine to offline machine to folder step3/btc by any means (via USB flash for example).
Put _secretlist.json_ file from step 1 to step3/btc folder.

run: `php sign_txs.php`

The output with signed transaction will be in file _signed.json_ . Move this file to online machine.

### Step 4
Should be done on online computer with synchronized bitcoind installed.
Edit script _send_txs.php_ and enter appropriate user and password to connect to bitcoind server.

Move _signed.json_ file from step3 to step4/btc folder.
Run `php send_txs.php` and wait for script to push transactions to network.
The output with TXIDs will be available in _sent_txns.json_ file.

## Ethereum workflow is as following

### Step 1
Should be done on offline computer.
Scripts generate 2 lists: one secret list with private keys ( _secretlist.json_ ), it should be kept secret. Other is a list of addresses that should be sent to investors ( _publiclist.json_ ).

### Step 2 
Should be done on online computer with synchronized geth installed when you need to gather funds from wallets.

Put _publiclist.json_ from step 1 in the folder step2/btc with scripts.
Enter appropriate user and password to connect to geth server.

run: `php get_balances.php` and wait for script to complete.
The results of the script work will be available in the same folder as balances.json file, it will contain current balances.
Warning: only non-empty addresses will be dumped.

### Step 3
Should be done on offline computer with (possibly) unsynchronized geth installed.

Edit script _sign_txs.php_ and enter appropriate user and password to connect to geth server.
Transfer _balances.json_ file generated at step 2 from online machine to offline machine to folder step3/eth by any means (via USB flash for example).
Put _secretlist.json_ file from step 1 to step3/eth folder.

run: `node index.js`

The output with signed transaction will be in file _signed.json_ . Move this file to online machine.

### Step 4
Should be done on online computer with synchronized geth installed.
Edit script _send_txs.php_ and enter appropriate user and password to connect to geth server.

Move _signed.json_ file from step3 to step4/eth folder.
Run `php send_txs.php` and wait for script to push transactions to network.
The output with TXIDs will be available in _sent_txns.json_ file.
