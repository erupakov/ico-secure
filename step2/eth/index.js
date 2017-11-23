#!/usr/bin/env node
/* jshint esversion: 6 */
'use strict';

var fs = require('fs');
var Web3 = require('web3')

var resList = new Array();

// set provider for all later instances to use
if (typeof web3 !== 'undefined') {
	var web3 = new Web3(web3.currentProvider);
} else {
	var web3 = new Web3(Web3.givenProvider || 'ws://localhost:8546');
}

let getAddressBalance = (addr) => {
	return web3.eth.getBalance(addr)
		.then(function (res) {
			console.log(addr + ':' + res);
			var el = { 'address': addr, 'balance': res };
			resList.push(el);
		}, function (err) {
			console.log('Error getting balance for ' + addr + ':' + err);
		});
};

let fileReadComplete = (err, contents) => {
	var addrList = JSON.parse(contents);
	for (var i = 0; i<addrList.length; i++) {
		getAddressBalance(addrList[i].address);
	};

	fs.writeFile('balances.json',JSON.stringify( resList) );
}

fs.readFile('publiclist.json', 'utf8', fileReadComplete);
