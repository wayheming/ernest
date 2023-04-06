<?php

namespace Ernest\Api;

defined( 'ABSPATH' ) || exit;

/**
 * Class Formatter.
 */
class Formatter {
	/**
	 * Data.
	 *
	 * @var array
	 */
	private array $data;

	/**
	 * Constructor.
	 *
	 * @param array $data Data.
	 */
	public function __construct( array $data ) {
		$this->data = $data;
	}

	/**
	 * Get data.
	 *
	 * @return array
	 */
	public function get_data(): array {
		$this->format_headers();
		$this->format_date();

		return $this->data;
	}

	private function format_headers(): void {
		$headers = [];

		foreach ( $this->data['headers'] as $header ) {
			switch ( $header ) {
				case 'First Name':
					$headers['fname'] = $header;
					break;
				case 'Last Name':
					$headers['lname'] = $header;
					break;
				default:
					$headers[ strtolower( $header ) ] = $header;
					break;
			}
		}

		$this->data['headers'] = $headers;
	}

	/**
	 * Format date.
	 */
	private function format_date(): void {
		foreach ( $this->data['rows'] as $key => $row ) {
			$this->data['rows'][ $key ]['date'] = date( 'Y/m/d H:i:s', $row['date'] );
		}
	}
}
