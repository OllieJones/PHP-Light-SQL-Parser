<?php /** @noinspection SpellCheckingInspection */

namespace olliejones\mysqlstatus;

require_once( 'cumulative.php' );

class MySQLStatus {
	private $status;
	private $vars;
	private $timestamp;
	private $cumulatives;


	public function __construct() {
		$this->cumulatives = getCumulativeFilters();
	}

	public static function makeByArrays( $status, $variables, $timestamp ) {
		$result            = new MySQLStatus();
		$result->status    = $status;
		$result->vars      = $variables;
		$result->timestamp = $timestamp;

		return $result;
	}

	public function toString() {
		return json_encode( array(
			'timestamp' => $this->timestamp,
			'status'    => $this->status,
			'vars'      => $this->vars
		) );
	}

	public function show() {
		$result   = array();
		$mismatch = array();
		foreach ( $this->status as $key => $value ) {
			$found = false;
			if ( is_numeric( $value ) ) {
				foreach ( $this->cumulatives as $match ) {
					$re = '/^' . $match . '$/i';
					if ( preg_match( $re, $key ) === 1 ) {
						$result[ $key ] = $value;
						$found          = true;
						break;
					}
				}
			}
			if ( ! $found ) {
				$mismatch[ $key . '|mismatch' ] = $value;
			}
		}

		return array_merge( $result, $mismatch );
	}
}