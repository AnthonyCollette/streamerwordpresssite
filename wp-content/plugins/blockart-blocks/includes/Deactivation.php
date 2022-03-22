<?php
/**
 * Deactivation class.
 *
 * @package BlockArt
 * @since 1.0.0
 */

namespace BlockArt;

defined( 'ABSPATH' ) || exit;

/**
 * Deactivation class.
 */
class Deactivation {

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		register_deactivation_hook( BLOCKART_PLUGIN_FILE, array( __CLASS__, 'on_deactivate' ) );
	}

	/**
	 * Callback for plugin deactivation hook.
	 *
	 * @since 1.0.0
	 */
	public static function on_deactivate() {}
}
