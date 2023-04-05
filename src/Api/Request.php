<?php

namespace Ernest\Api;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Class Request.
 */
class Request {
	/**
	 * Request.
	 *
	 * @param string $url URL to request.
	 *
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function request( string $url ): WP_Error|array {
		$request = wp_remote_request(
			$url,
			apply_filters( 'ernest_request_args',
				[
					'method' => 'GET',
				]
			)
		);

		return $this->remote_retrieve_body( $request );
	}

	/**
	 * Retrieve the body of the response.
	 *
	 * @param WP_Error|array $response HTTP response.
	 *
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	private function remote_retrieve_body( WP_Error|array $response ): WP_Error|array {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $response_code === 200 && is_array( $response_body ) ) {
			return $response_body['data'] ?? $response_body['error'];
		}

		if ( ! empty( $response_body['message'] ) ) {
			return new WP_Error( $response_code, esc_html( $response_body['message'] ) );
		}

		if ( ! empty( $response_body['error'] ) ) {
			return new WP_Error( $response_code, esc_html( $response_body['error'] ) );
		}

		return new WP_Error( $response_code, esc_html__( 'API not working.', 'ernest' ) );
	}
}
