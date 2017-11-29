# ICO secure step3
==================

## Prerequisites
You will need PHP 5.6 or later and composer installed.

Before starting step3 get the package dependencies by doing `composer update`.
This will download all needed packages. This should be done before step1, connected to Internet.
Or you just can transfer vendor folder with packages from online machine.

## Overview

This step should be taken on offline computer with _secretlist.json_ file involved (it was created on step1).
Place _balances.json_ file from step2 into the same folder (via flash drive for example)
and run `php sign_txs.php`.

The output will be _signed.json_ file with signed transactions ready to be sent on step4.
Save this file on flash drive to transfer it on online computer to send to blockchain.
