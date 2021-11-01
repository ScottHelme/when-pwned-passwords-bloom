<?php

use Palicao\PhpRebloom\BloomFilter;
use Palicao\PhpRebloom\RedisClient;
use Palicao\PhpRebloom\RedisConnectionParams;

require __DIR__ . '/vendor/autoload.php';

$n = 613584246;
$p = 0.00000001;

$bloomFilter = new BloomFilter(
	new RedisClient(
		new Redis(),
		new RedisConnectionParams('127.0.0.1', 6379)
	)
);

$start = microtime(true);
$before = memory_get_usage();
$bloomFilter->reserve('pwned-bloom', $p, $n);
$after = memory_get_usage();
echo ($after - $before) . "\n";

$count = 0;
foreach (importData() as $hash) {
	$bloomFilter->insert('pwned-bloom', $hash);
	$count++;
	echo $hash . ' ' . $count . "\n";
}

$end = microtime(true);
echo 'Execution time: ' . ($end - $start) / 60 . 'm';

function importData() {
	$fh = fopen('input.txt', 'r');

	while(!feof($fh)) {
		yield strtok(trim(fgets($fh)), ':');
	}

	fclose($fh);
}