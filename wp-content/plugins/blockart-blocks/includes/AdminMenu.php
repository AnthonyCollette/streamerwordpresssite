<?php
/**
 * AdminMenu class.
 *
 * @package BlockArt
 * @since 1.0.0
 */

namespace BlockArt;

defined( 'ABSPATH' ) || exit;

/**
 * AdminMenu class.
 */
class AdminMenu {

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.0.0
	 */
	private static function init_hooks() {
		add_action( 'admin_menu', array( __CLASS__, 'init_menus' ) );
		add_filter( 'admin_footer_text', array( __CLASS__, 'admin_footer_text' ), 1 );
		add_filter( 'update_footer', array( __CLASS__, 'admin_footer_version' ), 11 );
	}

	/**
	 * Init menus.
	 *
	 * @since 1.0.0
	 */
	public static function init_menus() {

		$blockart_page = add_menu_page(
			esc_html__( 'BlockArt', 'blockart' ),
			esc_html__( 'BlockArt', 'blockart' ),
			'manage_options',
			'blockart',
			array( __CLASS__, 'markup' ),
			'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 22H2V2h20zM3 21h18V3H3z" fill="#fff"/><path d="M13.46 10l-1.39-5-1.39 5zm.92 3H9.77l-1 4.46V19h6.4v-1.52z" fill="#fff" fill-rule="evenodd"/></svg>' ) // phpcs:ignore
		);

		add_submenu_page(
			'blockart',
			esc_html__( 'Getting Started', 'blockart' ),
			esc_html__( 'Getting Started', 'blockart' ),
			'manage_options',
			'blockart#/getting-started',
			array( __CLASS__, 'markup' )
		);

		add_submenu_page(
			'blockart',
			esc_html__( 'Settings', 'blockart' ),
			esc_html__( 'Settings', 'blockart' ),
			'manage_options',
			'blockart#/settings',
			array( __CLASS__, 'markup' )
		);

		add_action( "admin_print_scripts-$blockart_page", array( __CLASS__, 'enqueue' ) );

		remove_submenu_page( 'blockart', 'blockart' );
	}

	/**
	 * Markup.
	 *
	 * @since 1.0.0
	 */
	public static function markup() {
		echo '<div id="blockart"></div>';
	}

	/**
	 * Enqueue.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {
		wp_enqueue_script( 'blockart-admin' );
		wp_enqueue_style( 'blockart-admin' );
	}

	/**
	 * Change admin footer text on BlockArt page.
	 *
	 * @param string $text Admin footer text.
	 * @return mixed|string Admin footer text.
	 */
	public static function admin_footer_text( $text ) {

		if ( 'toplevel_page_blockart' !== get_current_screen()->id ) {
			return $text;
		}

		if ( ! get_option( '_blockart_admin_footer_text_rated' ) ) {
			$text = sprintf(
				/* translators: 1: BlockArt 2:: Five stars */
				esc_html__( 'Enjoyed %1$s? please leave us a %2$s rating. We really appreciate your support!', 'blockart' ),
				sprintf( '<strong>%s</strong>', esc_html__( 'BlockArt', 'blockart' ) ),
				'<a href="https://wordpress.org/support/plugin/blockart-blocks/reviews?rate=5#new-post" target="_blank" class="blockart-rating-link">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		} else {
			$text = esc_html__( 'Thank you for creating with BlockArt.', 'blockart' );
		}

		return $text;
	}

	/**
	 * Override WordPress version with plugin version.
	 *
	 * @param string $version Version text.
	 * @return string Version text.
	 */
	public static function admin_footer_version( $version ) {

		if ( 'toplevel_page_blockart' !== get_current_screen()->id ) {
			return $version;
		}

		return __( 'Version ', 'blockart' ) . BLOCKART_VERSION;
	}
}
