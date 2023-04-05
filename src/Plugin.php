<?php

namespace Ernest;

use Ernest\Admin\Admin;
use Ernest\Cli\Cli;
use Ernest\Gutenberg\Table;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin.
 */
final class Plugin {
	/**
	 * Instance of this object.
	 */
	private static Plugin $instance;

	/**
	 * Get instance.
	 *
	 * @return Plugin Current object instance.
	 */
	public static function get_instance(): Plugin {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->loader();
	}

	/**
	 * Load other classes.
	 */
	private function loader(): void {
		new Admin();
		new Cli();
		new Table();
	}
}

