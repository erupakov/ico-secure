#!/usr/bin/env node
/* jshint esversion: 6 */
'use strict';

const program = require('commander'),
		chalk = require("chalk"),
		Web3 = require('web3'),
	    exec = require('child_process').exec,
	    pkg = require('./package.json');

// set provider for all later instances to use
if (typeof web3 !== 'undefined') {
    var web3 = new Web3(web3.currentProvider);
} else {
    var web3 = new Web3(Web3.givenProvider || 'ws://localhost:8546');
}

let checkBalances = (list, options) => {
	const cmd = 'ls';
	let params = [];
	if (options.all) params.push('a');
	if (options.long) params.push('l');
	let fullCommand = params.length 
	                  ? cmd + ' -' + params.join('')
	                  : cmd
	if (directory) fullCommand += ' ' + directory;
	let output = (error, stdout, stderr) => {
	    if (error) console.log(chalk.red.bold.underline("exec error:") + error);
	    if (stdout) console.log(chalk.green.bold.underline("Result:") + stdout);
	    if (stderr) console.log(chalk.red("Error: ") + stderr);
	};
	exec(fullCommand, execCallback);
}

let getAddressBalance = (addr) => {
	return web3.eth.getBalance(addr)
		.then(function(res) {
			return res;
		}, function(err) {
			console.log(err);
		}
	);
};

program
	.version('0.1.0')
	.command('check [listfile]')
	.description('Ethereum balance checker')
	.option('-a, --all','Check and output all addresses')
	.action(checkBalances);

program.parse(process.argv);

// if program was called with no arguments, show help.
if (program.args.length === 0) program.help();