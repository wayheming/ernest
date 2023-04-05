<?php

namespace Ernest\Cli;

use Ernest\Api\Api;
use Exception;
use WP_CLI;
use WP_CLI\ExitException;

defined( 'ABSPATH' ) || exit;

/**
 * Class Cli.
 */
class Cli {
	/**
	 * Constructor.
	 * @throws Exception
	 */
	public function __construct() {
		if ( ! defined( 'WP_CLI' ) ) {
			return;
		}

		$this->register_command();
	}

	/**
	 * Register command.
	 * @throws Exception
	 */
	private function register_command(): void {
		WP_CLI::add_command( 'update_api_data', [ $this, 'update_api_data' ] );
	}

	/**
	 * Update API data.
	 * @throws ExitException
	 */
	public function update_api_data(): void {
		$data = ( new Api )->get_actual_data();

		if ( is_wp_error( $data ) ) {
			WP_CLI::error( $data );
		}

		if ( ! empty( $data ) ) {
			WP_CLI::success( esc_html__( 'Data from API successfully updated.', 'ernest' ) );
		} else {
			WP_CLI::error( esc_html__( 'Data from API not updated.', 'ernest' ) );
		}
	}
}
