<?php

namespace Ernest\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Class Notice.
 */
class Notice {
	/**
	 * Print notice.
	 *
	 * @param string $message Message to display.
	 * @param string $type Type of notice.
	 */
	private static function print_notice( string $message, string $type ): void {
		?>
		<div class="ernest-notice <?php echo esc_attr( $type ); ?>">
			<p><?php echo esc_html( $message ); ?></p>
		</div>
		<?php
	}

	/**
	 * Print success notice.
	 *
	 * @param string $message Message to display.
	 */
	public static function print_success( string $message ): void {
		self::print_notice( $message, 'updated' );
	}

	/**
	 * Print error notice.
	 *
	 * @param string $message Message to display.
	 */
	public static function print_error( string $message ): void {
		self::print_notice( $message, 'error' );
	}
}

