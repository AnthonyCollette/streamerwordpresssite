<?php
/**
 * Activation class.
 *
 * @package BlockArt
 * @since 1.0.0
 */

namespace BlockArt;

defined( 'ABSPATH' ) || exit;

/**
 * Activation class.
 */
class Activation {

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		register_activation_hook( BLOCKART_PLUGIN_FILE, array( __CLASS__, 'on_activate' ) );
		add_action( 'init', array( __CLASS__, 'check_version' ), 0 );
	}

	/**
	 * Callback for plugin activation hook.
	 */
	public static function on_activate() {
		self::maybe_set_activation_redirect();
		self::maybe_set_activation_time();
	}

	/**
	 * Set initial activation redirect flag.
	 *
	 * @return void
	 */
	private static function maybe_set_activation_redirect() {
		$blockart_version = get_option( '_blockart_version' );

		if ( empty( $blockart_version ) ) {
			update_option( '_blockart_activation_redirect', true );
		}
	}

	/**
	 * Set initial activation time.
	 *
	 * @return void
	 */
	private static function maybe_set_activation_time() {
		$activation_time = get_option( '_blockart_activation_time', '' );

		if ( empty( $activation_time ) ) {
			update_option( '_blockart_activation_time', time() );
		}
	}

	/**
	 * Check version on init.
	 *
	 * @return void
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( '_blockart_version' ), BLOCKART_VERSION, '<' ) ) {
			self::maybe_set_activation_time();
		}
	}
}
