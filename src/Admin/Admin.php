<?php

namespace Ernest\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Class Admin.
 */
class Admin {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_hooks();
		new Page();
	}

	/**
	 * Register hooks.
	 */
	private function register_hooks(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue styles for the admin area.
	 *
	 * @param string $hook Hook.
	 */
	public function enqueue_scripts( string $hook ): void {
		if ( ! str_contains( $hook, Page::SLUG ) ) {
			return;
		}

		wp_enqueue_script(
			'ernest-admin-scripts',
			ERNEST_PLUGIN_URL . 'assets/js/admin/admin.min.js',
			[ 'jquery' ],
			ERNEST_VERSION,
			true
		);

		wp_localize_script(
			'ernest-admin-scripts',
			'ernestConfig',
			$this->get_localized_data()
		);
	}

	/**
	 * Enqueue styles for the admin area.
	 *
	 * @param string $hook Hook.
	 */
	public function enqueue_styles( string $hook ): void {
		if ( ! str_contains( $hook, Page::SLUG ) ) {
			return;
		}

		wp_enqueue_style(
			'ernest-admin-styles',
			ERNEST_PLUGIN_URL . 'assets/css/admin/styles.min.css',
			[],
			ERNEST_VERSION,
		);
	}

	/**
	 * Get localized data.
	 *
	 * @return array Localized data.
	 */
	public function get_localized_data(): array {
		return [
			'adminAjaxUrl'         => admin_url( 'admin-ajax.php' ),
			'adminUpdateDataNonce' => wp_create_nonce( 'ernest_admin_update_table_nonce' ),
		];
	}
}
