#!/usr/bin/env node
/* jshint esversion: 6 */
'use strict'

var fs = require('fs');
var Transaction = require('ethereumjs-tx')
var addressTo = '0xbc1c59c7c663aef34e8b6e6ec517c7e8f7bcdd8a'; // where to send funds to

var txArray = JSON.parse(fs.readFileSync('balances.json', 'utf8'));
var privArray = JSON.parse(fs.readFileSync('secretlist.json', 'utf8'));
var signedArray = [];

txArray.forEach(element => {
    var privKey = privArray.find( v => v.address == element.address);

    if (undefined != privKey) { // process transaction
        var entry = { "address": element.address, 
            "tx": createTransaction(privKey.private, addressTo, element.balance[0] )};
        signedArray.push(entry);
    }
});

fs.writeFileSync('signed.json',JSON.stringify(signedArray));
console.log('Success');
process.exit();

function createTransaction(pKey, to, balance) {
    // create a blank transaction
    var tx = new Transaction(null, 1) // mainnet Tx EIP155

    // So now we have created a blank transaction but Its not quiet valid yet. We
    // need to add some things to it. Lets start:
    // notice we don't set the `to` field because we are creating a new contract.
    tx.nonce = 0
    tx.gasLimit = 21000
    tx.value = balance - 21000;
    tx.data = ''
    tx.to = to

    var privateKey = new Buffer(pKey, 'hex')
    tx.sign(privateKey)
    // We have a signed transaction, Now for it to be fully fundable the account that we signed
    // it with needs to have a certain amount of wei in to. To see how much this
    // account needs we can use the getUpfrontCost() method.
    var feeCost = tx.getUpfrontCost()
    tx.gas = feeCost
    console.log('Total Amount of wei needed:' + feeCost.toString())

    // if your wondering how that is caculated it is
    // bytes(data length) * 5
    // + 500 Default transaction fee
    // + gasAmount * gasPrice

    // lets serialize the transaction

    return tx.serialize().toString('hex')
}
