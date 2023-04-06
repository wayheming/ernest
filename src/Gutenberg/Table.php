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

		wp_localize_script(
			'ernest-gutenberg-table',
			'ernestGutenbergTableConfig',
			[
				'adminAjaxUrl' => admin_url( 'admin-ajax.php' ),
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
	}

	/**
	 * Get table action.
	 */
	public function get_table_action(): void {
		$data = $this->api->get_hourly_data();

		if ( is_wp_error( $data ) ) {
			wp_send_json_success( $data->get_error_message() );
		}

		if ( ! isset( $_POST['attributes'] ) ) {
			wp_send_json_success( esc_html__( 'No attributes found.', 'ernest' ) );
		}

		$sanitized_attributes = array_map( 'sanitize_text_field', $_POST['attributes'] );

		wp_send_json_success( $this->display_table( $data, $sanitized_attributes ) );
	}

	/**
	 * Display table.
	 *
	 * @param array $data Data.
	 * @param array $attributes Attributes.
	 *
	 * @return string
	 */
	private function display_table( array $data, array $attributes ): string {
		ob_start();

		?>
		<div class="wp-block-table">
			<table>
				<thead>
				<tr>
					<?php foreach ( $data['headers'] as $key => $header ) : ?>
						<?php
						if ( ! isset( $attributes[ $key ] ) || $attributes[ $key ] === 'false' ) {
							continue;
						}
						?>
						<th><?php echo esc_html( $header ); ?></th>
					<?php endforeach; ?>
				</tr
				</thead>

				<tbody>
				<?php foreach ( $data['rows'] as $row ) : ?>
					<tr>
						<?php foreach ( $row as $key => $value ) : ?>
							<?php
							if ( ! isset( $attributes[ $key ] ) || $attributes[ $key ] === 'false' ) {
								continue;
							}
							?>
							<td><?php echo esc_html( $value ); ?></td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php

		return ob_get_clean();
	}
}
