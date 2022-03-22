<?php
/**
 * Plugin Name: BlockArt Blocks
 * Description: Craft your website beautifully using Gutenberg blocks like section/column, heading, button, etc. Unlimited possibilities of design with features like colors, backgrounds, typography, layouts, spacing, etc.
 * Author: WPBlockArt
 * Author URI: https://wpblockart.com/
 * Version: 1.0.3
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * Text Domain: blockart
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package BlockArt
 */

use BlockArt\Plugin;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'BLOCKART_VERSION' ) ) {
	define( 'BLOCKART_VERSION', '1.0.3' );
}

if ( ! defined( 'BLOCKART_PLUGIN_FILE' ) ) {
	define( 'BLOCKART_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'BLOCKART_PLUGIN_DIR' ) ) {
	define( 'BLOCKART_PLUGIN_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'BLOCKART_PLUGIN_DIR_URL' ) ) {
	define( 'BLOCKART_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'BLOCKART_ASSETS' ) ) {
	define( 'BLOCKART_ASSETS', dirname( __FILE__ ) . '/assets' );
}

if ( ! defined( 'BLOCKART_ASSETS_DIR_URL' ) ) {
	define( 'BLOCKART_ASSETS_DIR_URL', BLOCKART_PLUGIN_DIR_URL . 'assets' );
}

if ( ! defined( 'BLOCKART_DIST_DIR_URL' ) ) {
	define( 'BLOCKART_DIST_DIR_URL', BLOCKART_PLUGIN_DIR_URL . 'dist' );
}

if ( ! defined( 'BLOCKART_LANGUAGES' ) ) {
	define( 'BLOCKART_LANGUAGES', dirname( __FILE__ ) . '/languages' );
}

// Check whether assets are built or not.
if (
	! file_exists( dirname( __FILE__ ) . '/dist/blocks.js' ) ||
	! file_exists( dirname( __FILE__ ) . '/dist/blocks.css' ) ||
	! file_exists( dirname( __FILE__ ) . '/dist/style-blocks.css' ) ||
	! file_exists( dirname( __FILE__ ) . '/dist/blocks.asset.php' )
) {
	add_action(
		'admin_notices',
		function() {
			printf(
				'<div class="notice notice-error is-dismissible"><p><strong>%s </strong>%s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">%s</span></button></div>',
				esc_html( 'BlockArt:' ),
				wp_kses_post( __( 'Assets are not built. Run <code>npm install && npm run build</code> from the wp-content/plugins/blockart directory.', 'blockart' ) ),
				esc_html__( 'Dismiss this notice.', 'blockart' )
			);
		}
	);

	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( BLOCKART_PLUGIN_FILE ) );

			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			// phpcs:enable WordPress.Security.NonceVerification.Recommended
		}
	);

	return;
}

// Load the autoloader.
require_once __DIR__ . '/vendor/autoload.php';

if ( ! function_exists( 'blockart' ) ) {
	function blockart() {
		return Plugin::instance();
	}
}

blockart();
