<?php

namespace Ernest\Api;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Class Api.
 */
class Api {
	/**
	 * URL.
	 *
	 * @var string
	 */
	private string $url = 'https://miusage.com/v1/challenge/1/';

	/**
	 * Transient lifetime.
	 *
	 * @var int
	 */
	private int $transient_lifetime;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->transient_lifetime = apply_filters( 'ernest_api_transient_lifetime', 60 * 60 );
	}

	/**
	 * Get hourly data.
	 *
	 * @return WP_Error|array
	 */
	public function get_hourly_data(): WP_Error|array {
		$cached_data = get_transient( 'ernest_api_data' );

		if ( $cached_data ) {
			return $cached_data;
		}

		$response = $this->request( $this->url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		set_transient( 'ernest_api_data', $response, $this->transient_lifetime );

		return $response;
	}

	/**
	 * Get actual data.
	 *
	 * @return WP_Error|array
	 */
	public function get_actual_data(): WP_Error|array {
		$response = $this->request( $this->url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		set_transient( 'ernest_api_data', $response, $this->transient_lifetime );

		return $response;
	}

	/**
	 * Request.
	 *
	 * @param string $url URL to request.
	 *
	 * @return WP_Error|array
	 */
	private function request( string $url ): WP_Error|array {
		return ( new Request() )->request( $url );
	}
}
