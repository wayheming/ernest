<?php

namespace Ernest\Gutenberg;

defined( 'ABSPATH' ) || exit;

/**
 * Class Gutenberg.
 */
class Gutenberg {

	public function __construct() {
		$this->register_hooks();
	}

	private function register_hooks(): void {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts(): void {
		wp_enqueue_script(
			'ernest-table-block-scripts',
			ERNEST_PLUGIN_URL . 'assets/js/gutenberg/table-block.min.js',
			[ 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ],
			ERNEST_VERSION,
		);

		wp_localize_script(
			'ernest-table-block-scripts',
			'ernestGutenbergConfig',
			$this->get_localized_data()
		);
	}

	/**
	 * Get localized data.
	 *
	 * @return array Localized data.
	 */
	public function get_localized_data(): array {
		return [
			'adminAjaxUrl'             => admin_url( 'admin-ajax.php' ),
			'gutenbergUpdateDataNonce' => wp_create_nonce( 'ernest_gutenberg_update_table_nonce' ),
			'i18n'                     => [
				'title'       => esc_html__( 'Ernest API', 'ernest' ),
				'description' => esc_html__( 'Simple block to show API data.', 'ernest' ),
			],
		];
	}
}
