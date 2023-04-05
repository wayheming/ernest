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
	}
}
