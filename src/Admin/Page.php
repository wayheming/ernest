<?php

namespace Ernest\Admin;

use Ernest\Api\Api;
use Ernest\Helpers\Notice;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Class Page.
 */
class Page {
	/**
	 * Slug of the admin area page.
	 *
	 * @var string
	 */
	const SLUG = 'ernest';

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
		add_action( 'admin_menu', [ $this, 'add_admin_options_page' ] );
		add_action( 'in_admin_header', [ $this, 'display_admin_header' ] );
		add_action( 'wp_ajax_ernest_get_actual_table', [ $this, 'get_actual_table_action' ] );
	}

	/**
	 * Outputs the plugin admin header.
	 */
	public function display_admin_header(): void {
		if ( ! $this->is_admin_page() ) {
			return;
		}

		?>
		<div class="ernest-dashboard-header">
			<h1><?php esc_html_e( 'Ernest dashboard', 'ernest' ); ?></h1>
		</div>
		<?php
	}

	/**
	 * Check if we're on a plugin page.
	 *
	 * @return bool
	 */
	public function is_admin_page(): bool {
		$current_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';

		return is_admin() && $current_page === self::SLUG;
	}

	/**
	 * Add admin options page.
	 */
	public function add_admin_options_page(): void {
		add_menu_page(
			esc_html__( 'Ernest', 'ernest' ),
			esc_html__( 'Ernest', 'ernest' ),
			'manage_options',
			$this::SLUG,
			[ $this, 'display' ],
			'dashicons-chart-line',
			3
		);
	}

	/**
	 * Render admin options page.
	 */
	public function display(): void {
		?>
		<div class="ernest-dashboard">
			<?php $this->display_menu(); ?>

			<div class="ernest-dashboard__content">
				<?php $this->display_table( 'hourly' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get API data.
	 *
	 * @param string $data_type Data type.
	 *
	 * @return array|WP_Error
	 */
	public function get_api_data( string $data_type ): WP_Error|array {
		$data = '';

		switch ( $data_type ) {
			case 'hourly':
				$data = $this->api->get_hourly_data();
				break;
			case 'actual':
				$data = $this->api->get_actual_data();
				break;
		}

		return $data;
	}

	/**
	 * Display table.
	 *
	 * @param string $data_type Data type.
	 */
	public function display_table( string $data_type ): void {
		$data = $this->get_api_data( $data_type );

		if ( is_wp_error( $data ) ) {
			Notice::print_error( $data->get_error_message() );

			return;
		}

		if ( $data_type === 'actual' ) {
			Notice::print_success( esc_html__( 'Data from API successfully updated.', 'ernest' ) );
		}

		$table = new Table( $data );
		$table->prepare_items();
		$table->display();

		$this->display_update_data_btn();
	}

	/**
	 * Display the update data button.
	 */
	public function display_update_data_btn(): void {
		?>
		<button class="ernest-btn ernest-btn-orange" id="ernest-update-data-button">
			<?php esc_html_e( 'Update data', 'ernest' ); ?>
		</button>
		<?php
	}

	/**
	 * Display the admin menu.
	 */
	public function display_menu(): void {
		$page = $this::SLUG;
		?>
		<ul class="ernest-dashboard__menu">
			<li class="ernest-dashboard__menu-item active">
				<a class="ernest-dashboard__menu-link" href="<?php echo esc_url( admin_url( 'admin.php?page=' . $page . '&tab=general' ) ); ?>">
					<?php esc_html_e( 'General', 'ernest' ); ?>
				</a>
			</li>
		</ul>
		<?php
	}

	/**
	 * Update table.
	 */
	public function get_actual_table_action(): void {
		check_ajax_referer( 'ernest_admin_update_table_nonce', 'security' );

		ob_start();

		$this->display_table( 'actual' );

		wp_send_json_success( ob_get_clean() );
	}
}
