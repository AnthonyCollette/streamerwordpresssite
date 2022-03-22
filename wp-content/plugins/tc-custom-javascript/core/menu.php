<?php

class TCCJ_Core_Menu {
	public static function add() {
		if ( current_user_can( 'manage_options' ) ) {
			add_theme_page( 'Custom JavaScript', 'Custom JavaScript', 'edit_theme_options', 'tc-custom-javascript', array( __CLASS__, 'render_theme_menu_page' ) );
		}
	}

	public static function render_theme_menu_page() {
		$tccj_content = get_option( 'tccj_content', '' );
		include TCCJ_PLUGIN_DIR . 'templates/code-editor.php';
	}
}
