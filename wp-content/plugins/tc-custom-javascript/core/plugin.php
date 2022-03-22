<?php

require_once TCCJ_PLUGIN_DIR . 'core/asset.php';
require_once TCCJ_PLUGIN_DIR . 'core/menu.php';
require_once TCCJ_PLUGIN_DIR . 'core/content.php';
require_once TCCJ_PLUGIN_DIR . 'core/frontend.php';

class TCCJ_Core_Plugin {
	public static function init() {
		register_activation_hook( TCCJ_MAIN_FILE, array( 'TCCJ_Core_Plugin', 'on_activate' ) );
		add_action( 'admin_init', array( __CLASS__, 'do_activation_redirect' ) );

		add_action( 'admin_menu', array( 'TCCJ_Core_Menu', 'add' ) );
		add_action( 'admin_enqueue_scripts', array( 'TCCJ_Core_Asset', 'enqueque' ) );

		add_action( 'admin_post_tc_custom_javascript', array( 'TCCJ_Core_Content', 'update' ) );
		
		add_action( 'wp_print_footer_scripts', array( 'TCCJ_Core_Frontend', 'print_script_in_footer') );
	}

	public static function on_activate() {
		update_option( 'tccj_do_activation_redirect', 'Yes' );
	}

	public static function do_activation_redirect() {
		if ( get_option( 'tccj_do_activation_redirect', 'No' ) === 'Yes' ) {
			update_option( 'tccj_do_activation_redirect', 'No' );
			wp_redirect( admin_url('themes.php?page=tc-custom-javascript') );
			exit;
		}
	}
}
