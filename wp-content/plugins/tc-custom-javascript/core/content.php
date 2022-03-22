<?php

class TCCJ_Core_Content {
	public static function update() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_custom_javascript' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		}
		if ( current_user_can( 'manage_options' ) && self::has_update_request() ) {
			if ( function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() )
				$tccj_content = stripslashes( $_POST['tccj-content'] );
			else
				$tccj_content = $_POST['tccj-content'];

			// Sanitizing data before insert to database
			$tccj_content = wp_check_invalid_utf8( $tccj_content, true );
			$tccj_content = htmlentities( $tccj_content );

			// if ( ! get_magic_quotes_runtime() )
				// $tccj_content = addslashes( $tccj_content );

			update_option( 'tccj_content', $tccj_content );

			wp_redirect( admin_url('themes.php?page=tc-custom-javascript') );
		}
	}

	private static function has_update_request() {
		if ( is_admin() && isset( $_POST['tccj-update'] ) && ( $_POST['tccj-update'] == 'Update' ) )
			return true;
		else
			return false;
	}
}
