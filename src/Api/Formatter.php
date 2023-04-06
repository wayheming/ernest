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

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function get_data(): array {
		$this->format_headers();

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
}
