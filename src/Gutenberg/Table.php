<?php

namespace Ernest\Gutenberg;

use Ernest\Api\Api;
use Ernest\Helpers\Notice;

defined( 'ABSPATH' ) || exit;

/**
 * Class Table.
 */
class Table {
	/**
	 * Api.
	 *
	 * @var Api
	 */
	private Api $api;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->api = new Api();
		$this->register_hooks();
	}

	/**
	 * Register hooks.
	 */
	private function register_hooks(): void {
		add_action( 'wp_ajax_ernest_get_table', [ $this, 'get_table_action' ] );
		add_action( 'wp_ajax_nopriv_ernest_get_table', [ $this, 'get_table_action' ] );
		add_action( 'init', [ $this, 'register_block' ] );
	}

	public function register_block(): void {
		$this->register_scripts();
		$this->register_styles();

		register_block_type(
			'ernest/table',
			[
				'editor_script' => 'ernest-gutenberg-table-editor',
				'editor_style'  => 'ernest-gutenberg-table-editor',
				'script'        => 'ernest-gutenberg-table',
				'style'         => 'ernest-gutenberg-table',
				'attributes'    => [
					'id'    => [
						'type'    => 'boolean',
						'default' => true,
					],
					'fname' => [
						'type'    => 'boolean',
						'default' => true,
					],
					'lname' => [
						'type'    => 'boolean',
						'default' => true,
					],
					'email' => [
						'type'    => 'boolean',
						'default' => true,
					],
					'date'  => [
						'type'    => 'boolean',
						'default' => true,
					],
				],
			]
		);
	}

	/**
	 * Register scripts.
	 */
	private function register_scripts(): void {
		wp_register_script(
			'ernest-gutenberg-table-editor',
			ERNEST_PLUGIN_URL . 'assets/js/gutenberg/table/table-editor.min.js',
			[ 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ],
			ERNEST_VERSION,
		);

		wp_register_script(
			'ernest-gutenberg-table',
			ERNEST_PLUGIN_URL . 'assets/js/gutenberg/table/table.min.js',
			[ 'jquery' ],
			ERNEST_VERSION,
		);

		wp_localize_script(
			'ernest-gutenberg-table-editor',
			'ernestGutenbergTableConfig',
			[
				'i18n' => [
					'title'       => esc_html__( 'Ernest API', 'ernest' ),
					'description' => esc_html__( 'Simple block to show API data.', 'ernest' ),
					'loading'     => esc_html__( 'Loading...', 'ernest' ),
				],
			]
		);
	}

	/**
	 * Register styles.
	 */
	private function register_styles(): void {
		wp_register_style(
			'ernest-gutenberg-table',
			ERNEST_PLUGIN_URL . 'assets/css/gutenberg/table/table.min.css',
			[],
			ERNEST_VERSION,
		);

		wp_register_style(
			'ernest-gutenberg-table-editor',
			ERNEST_PLUGIN_URL . 'assets/css/gutenberg/table/table-editor.min.css',
			[],
			ERNEST_VERSION,
		);
	}

	/**
	 * Get table action.
	 */
	public function get_table_action(): void {
		$data = $this->api->get_hourly_data();

		if ( is_wp_error( $data ) ) {
			wp_send_json_success( $data->get_error_message() );
		}

		wp_send_json_success( $data );
	}
}
