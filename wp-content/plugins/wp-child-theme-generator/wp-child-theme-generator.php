<?php
/**
 * Plugin Name:     WP Child Theme Generator
 * Description:     A simple plugin for easy child theme making, easy but very effective. Enjoy!
 * Plugin URI:      http://wensolutions.com/plugins/wp-child-theme-generator
 * Author:          WEN Solutions
 * Author URI: 		http://wensolutions.com
 * Version:           1.0.6
 * License:         GPL2
 * Text Domain:     wp-child-theme-generator
 * Domain Path:     /languages
 */

defined('ABSPATH') or die('No script kiddies please!!');

add_action( 'plugins_loaded', array( 'WP_Child_Theme_Generator', 'get_instance' ) );

class WP_Child_Theme_Generator {

	private static $instance = null;

	public static function get_instance() {
		if ( ! isset( self::$instance ) )
			self::$instance = new self;
		return self::$instance;
	}

	private function __construct() {
		// Define constants.
		define( 'WCTG_BASE', dirname( __FILE__ ) );
		define( 'WCTG_BASE_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

		// Get required files.
		require plugin_dir_path( __FILE__ ).'wp-easy-child/wp-easy-child-hooks.php';
		require plugin_dir_path( __FILE__ ).'wp-custom-child/wp-custom-child-hooks.php';
		require plugin_dir_path( __FILE__ ).'wp-easy-child/wp-easy-child.php';
		require plugin_dir_path( __FILE__ ).'wp-custom-child/wp-custom-child-form-handling.php';
		require plugin_dir_path( __FILE__ ).'wp-custom-child/wp-custom-child-form.php';
		require plugin_dir_path( __FILE__ ).'wp-custom-child/wp-custom-child.php';
		require plugin_dir_path( __FILE__ ).'wpctg-pointer-class.php';
		require plugin_dir_path( __FILE__ ).'wp-child-theme-generator-styling.php';
	}
}
