<?php
/**
 * Plugin Name: Ernest
 * Plugin URI: https://github.com/wayheming/ernest
 * Description: API Based Plugin.
 * Author: Ernest Behinov
 * Version: 0.0.1
 * Author URI: https://github.com/wayheming'
 * Text Domain: ernest
 * Domain Path: assets/languages
 */

defined( 'ABSPATH' ) || exit;

/**
 * Path to the plugin root directory.
 */
if ( ! defined( 'ERNEST_PLUGIN_PATH' ) ) {
	define( 'ERNEST_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Url to the plugin root directory.
 */
if ( ! defined( 'ERNEST_PLUGIN_URL' ) ) {
	define( 'ERNEST_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Plugin version.
 */
if ( ! defined( 'ERNEST_VERSION' ) ) {
	define( 'ERNEST_VERSION', '0.0.1' );
}

require_once ERNEST_PLUGIN_PATH . 'vendor/autoload.php';

Ernest\Plugin::get_instance();
