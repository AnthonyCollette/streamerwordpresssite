<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );

function wctg_showErrorNotice() {
	if ( basename( $_SERVER['PHP_SELF'] ) == 'themes.php' && ! empty( $_REQUEST['msg'] ) ) {
		if ( isset( $_GET['error_type'] ) ) {

			switch ( $_GET['error_type'] ) {
				case 'empty':
					$type = 'warning';
					$msg  = esc_html( $_GET['msg'] );
					$msg  = str_replace( '-', ' ', $msg );

					break;
				case 'updated':
					$type = 'success';
					$msg  = esc_html( $_GET['msg'] );
					$msg  = str_replace( '-', ' ', $msg );
					break;

				case 'exists':
					$type = 'error';
					$msg  = esc_html( $_GET['msg'] );
					$msg  = str_replace( '-', ' ', $msg );

					break;

				case 'scripts':
					$type = 'error';
					$msg  = esc_html( $_GET['msg'] );
					$msg  = str_replace( '-', ' ', $msg );

					break;

				default:
					$type = 'error';
					$msg  = __( 'oops something went wrong', 'wp-child-theme-generator' );
					break;

			}

			printf(
				'<div class=" notice-bar is-dismissible notice notice-%s"><p>%s</p></div>',
				$type,
				$msg
			);
		}
	}
}

function wctg_handle_form_submission() {

	$error = new WP_Error();

	$new_theme_title = sanitize_text_field( $_POST['childtheme'] );

	$new_theme_author = sanitize_text_field( $_POST['childauthor'] );

	$new_theme_description = sanitize_text_field( $_POST['description'] );

	if ( ! isset( $_POST['wp-easy-nonce'] ) || ! wp_verify_nonce( $_POST['wp-easy-nonce'], 'custom-child-theme-creation' ) ) {
			$error->add( 'exists', __( 'You-are-not-verified-sorry.', 'wp-child-theme-generator' ) );
	}

	if ( empty( $_POST['childtheme'] ) ) {
		$error->add( 'empty', __( 'Child-theme-name-is-required', 'wp-child-theme-generator' ) );
	}

	if ( empty( $_POST['childauthor'] ) ) {
		$error->add( 'empty', __( 'Child-author-name-is-required', 'wp-child-theme-generator' ) );
	}

	if ( empty( $_POST['description'] ) ) {
		$error->add( 'empty', __( 'Description-is-required', 'wp-child-theme-generator' ) );
	}

	$new_theme_name = sanitize_title( $new_theme_title );
	$theme_root     = get_theme_root();

	$new_theme_path = $theme_root . '/' . $new_theme_name;

	if ( file_exists( $new_theme_path ) ) {
		$error->add( 'scripts', __( 'Child-theme-directory-already-exisits', 'wp-child-theme-generator' ) );
	}

	if ( empty( $new_theme_title ) ) {
		$error->add( 'scripts', __( 'No-scripts-please', 'wp-child-theme-generator' ) );
	}

	if ( empty( $new_theme_author ) ) {
		$error->add( 'scripts', __( 'No-scripts-please', 'wp-child-theme-generator' ) );
	}
	if ( empty( $new_theme_description ) ) {
		$error->add( 'scripts', __( 'No-scripts-please', 'wp-child-theme-generator' ) );
	}

	$total_error = $error->get_error_codes();
	if ( ! empty( $total_error ) ) {
		return $error;
	} else {
		return true;
	}

}
