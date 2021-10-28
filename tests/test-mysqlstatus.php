<?php /** @noinspection SpellCheckingInspection */


require_once( "../src/mysqlstatus.php" );

$cums        = array();
$states      = array();
$cumulatives = getCumulativeFilters();

$testfileName = realpath( 'imfsstats.json' );
$stream       = fopen( $testfileName, 'r' );
$allData      = json_decode( fread( $stream, filesize( $testfileName ) ), true );
fclose( $stream );

foreach ( $allData as $item ) {
	$data      = $item['data'];
	$vars      = $data['variables'];
	$status    = $data['globalStatus'];
	$timestamp = $item['t'];

	if (is_array($status)){
		foreach ( $status as $key => $value ) {
			$found = false;
			if ( is_numeric( $value ) ) {
				foreach ( $cumulatives as $match ) {
					$re = '/^' . $match . '$/i';
					if ( preg_match( $re, $key ) === 1 ) {
						$found = true;
						break;
					}
				}
			}
			if ( $found ) {
				$a =  &$cums;
			} else {
				$a = &$states;
			}
			if ( ! array_key_exists( $key, $a ) ) {
				$a[ $key ] = 0;
			}
			$a[ $key ] = $a [ $key ] + 1;

		}	}
}
ksort($cums);
ksort($states);


if ( false ) {
	foreach ( $allData as $item ) {
		$data      = $item['data'];
		$vars      = $data['variables'];
		$status    = $data['globalStatus'];
		$timestamp = $item['t'];
		$a         = \olliejones\mysqlstatus\MySQLStatus::makeByArrays( $status, $vars, $timestamp );
		$s         = $a->show();
		print_r( $s );
	}
}