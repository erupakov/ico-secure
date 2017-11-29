# ICO secure step3
==================

## Prerequisites
You will need node 6.x or later installed.
Read [here|https://www.digitalocean.com/community/tutorials/node-js-ubuntu-16-04-ru] how to install fresh nodejs version on Ubuntu.
Before starting step3 get the package dependencies by doing `npm install`.
This will download all needed packages. This should be done before step1, connected to Internet.
Or you just can transfer node_modules folder with packages from online machine.

## Overview

This step should be taken on offline computer with _secretlist.json_ file involved (it was created on step1).
Place _balances.json_ file from step2 into the same folder (via flash drive for example)
and run `node index.js`.

The output will be _signed.json_ file with signed transactions ready to be sent on step4.
Save this file on flash drive to transfer it on online computer to send to blockchain.

