<?php /** @noinspection SpellCheckingInspection */
require_once ("../src/LightSQLParser.php");
$parser = new \olliejones\sqlparser\LightSQLParser();
$testfileName = realpath('queries.txt');
$foo = getcwd();
$count = 0;
$stream = fopen($testfileName, 'r');
$accumulator = array();
while($line = chop(fgets($stream))) {
	$parser->setQuery($line);
	$result = $parser->getFingerprint();
	$hash = substr(hash('md5', $result), 0, 16);
	$item = (object)[];
	$item->q = $result;
	$item->recent = $line;
	$item->count = 1;
	if (array_key_exists($hash, $accumulator)) {
		$accumulator[$hash]->count += 1;
		$accumulator[$hash]->recent = $line;
	}
	else {
		$accumulator[$hash] = $item;
	}
	$count++;
}

/* get queries in descending order of elapsed time */
usort( $accumulator, function ( $a, $b ) {
	if ( $a->count == $b->count ) {
		return 0;
	}
	$d = $a->count - $b->count;

	return $d < 0.0 ? 1 : - 1;
} );

foreach ($accumulator as $hash => $item) {
	echo $item->count . " " .  $item->q . "\n";
}
