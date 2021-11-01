<?php

use Palicao\PhpRebloom\BloomFilter;
use Palicao\PhpRebloom\RedisClient;
use Palicao\PhpRebloom\RedisConnectionParams;

require __DIR__ . '/vendor/autoload.php';

$bloomFilter = new BloomFilter(
	new RedisClient(
		new Redis(),
		new RedisConnectionParams('127.0.0.1', 6379)
	)
);

$candidates = [
	'password1234', // pwned
	'troyhuntsucks', // not pwned
	'hunter1', // pwned
	'scotthelmerules', // not pwned
	'chucknorris', // pwned
];

foreach ($candidates as $candidate) {
	$start = microtime(true);
	$exists = $bloomFilter->exists('pwned-bloom', strtoupper(sha1($candidate)));
	$end = microtime(true);
	echo $candidate . ' ' . ($exists ? 'pwned' : 'not pwned') . ' in ' . ($end - $start) . "ms.\n";
}