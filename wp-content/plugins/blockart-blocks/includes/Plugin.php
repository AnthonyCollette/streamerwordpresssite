<?php
/**
 * BlockArt plugin main class.
 *
 * @since 1.0.0
 * @package BlockArt
 */

namespace BlockArt;

defined( 'ABSPATH' ) || exit;

/**
 * BlockArt setup.
 *
 * Include and initialize necessary files and classes for the plugin.
 *
 * @since   1.0.0
 */
final class Plugin {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var Plugin
	 */
	protected static $instance = null;

	/**
	 * Instance.
	 *
	 * Ensures only instance of Plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @return Plugin - Main instance.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin Constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function __construct() {

		Activation::init();
		Deactivation::init();
		AdminMenu::init();
		Review::init();
		BlocksManager::init();
		RESTAPI::init();
		Hooks::init();
		EnqueueScripts::init();
		self::init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	private static function init_hooks() {
		add_action( 'init', array( __CLASS__, 'init' ), 0 );
		add_action( 'in_admin_header', array( __CLASS__, 'hide_admin_notices' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_redirects' ) );
	}

	/**
	 * Initialize BlockArt when WordPress initializes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {

		/**
		 * BlockArt before init.
		 *
		 * @since 1.0.0
		 */
		do_action( 'blockart_before_init' );

		self::update_plugin_version();
		self::load_text_domain();
		self::register_settings();

		/**
		 * BlockArt init.
		 *
		 * Fires after BlockArt has loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'blockart_init' );
	}

	/**
	 * Update the plugin version.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function update_plugin_version() {
		update_option( '_blockart_version', BLOCKART_VERSION );
	}

	/**
	 * Load plugin text domain.
	 */
	private static function load_text_domain() {
		load_plugin_textdomain( 'blockart', false, plugin_basename( dirname( BLOCKART_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Hide admin notices from BlockArt admin pages.
	 *
	 * @since 1.0.0
	 */
	public static function hide_admin_notices() {

		// Bail if we're not on a BlockArt screen or page.
		if ( empty( $_REQUEST['page'] ) || false === strpos( sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ), 'blockart' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		global $wp_filter;
		$ignore_notices = array( 'blockart_admin_notice' );

		foreach ( array( 'user_admin_notices', 'admin_notices', 'all_admin_notices' ) as $wp_notice ) {
			if ( empty( $wp_filter[ $wp_notice ] ) ) {
				continue;
			}

			$hook_callbacks = $wp_filter[ $wp_notice ]->callbacks;

			if ( empty( $hook_callbacks ) || ! is_array( $hook_callbacks ) ) {
				continue;
			}

			foreach ( $hook_callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $callback ) {
					if ( ! empty( $name ) && in_array( $name, $ignore_notices, true ) ) {
						continue;
					}
					if (
						! empty( $callback['function'] ) &&
						! is_a( $callback['function'], '\Closure' ) &&
						isset( $callback['function'][0], $callback['function'][1] ) &&
						is_object( $callback['function'][0] ) &&
						in_array( $callback['function'][1], $ignore_notices, true )
					) {
						continue;
					}
					unset( $wp_filter[ $wp_notice ]->callbacks[ $priority ][ $name ] );
				}
			}
		}
	}

	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 */
	private static function register_settings() {

		register_setting(
			'_blockart_settings',
			'_blockart_dynamic_css_print_method',
			array(
				'type'              => 'string',
				'description'       => __( 'Settings to determine dynamic CSS print method', 'blockart' ),
				'show_in_rest'      => true,
				'default'           => 'internal-css',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		register_setting(
			'_blockart_settings',
			'_blockart_widget_css',
			array(
				'type'         => 'string',
				'show_in_rest' => true,
				'default'      => '',
			)
		);

		register_setting(
			'_blockart_settings',
			'_blockart_admin_footer_text_rated',
			array(
				'type'              => 'boolean',
				'show_in_rest'      => true,
				'default'           => false,
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
	}

	/**
	 * Redirecting user to dashboard page.
	 */
	public static function admin_redirects() {
		if ( get_option( '_blockart_activation_redirect' ) && apply_filters( 'blockart_activation_redirect', true ) ) {
			update_option( '_blockart_activation_redirect', false );
			wp_safe_redirect( admin_url( 'index.php?page=blockart#/getting-started' ) );
			exit;
		}
	}
}
