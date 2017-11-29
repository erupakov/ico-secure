#!/usr/bin/env node
/* jshint esversion: 6 */
'use strict'

var Transaction = require('ethereumjs-tx')

// create a blank transaction
var tx = new Transaction(null, 1) // mainnet Tx EIP155

// So now we have created a blank transaction but Its not quiet valid yet. We
// need to add some things to it. Lets start:
// notice we don't set the `to` field because we are creating a new contract.
tx.nonce = 0
tx.gasLimit = 21000
tx.value = 0
tx.data = ''
tx.to =''
tx.from = ''

var privateKey = new Buffer('e331b6d69882b4cb4ea581d88e0b604039a3de5967688d3dcffdd2270c0fd109', 'hex')
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

console.log('---Serialized TX----')
console.log(tx.serialize().toString('hex'))
console.log('--------------------')
