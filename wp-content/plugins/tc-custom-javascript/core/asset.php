<?php

class TCCJ_Core_Asset {
	public static function enqueque() {
		global $pagenow;
		if ( $pagenow == 'themes.php' && isset( $_GET['page'] ) && $_GET['page'] == 'tc-custom-javascript' ) {
			wp_enqueue_style( 'tccj-bootstrap-css', TCCJ_PLUGIN_URL . 'assets/bootstrap/css/bootstrap.min.css' );
			wp_enqueue_style( 'tccj-bootstrap-theme-css', TCCJ_PLUGIN_URL . 'assets/bootstrap/css/bootstrap-theme.min.css' );
			wp_enqueue_script( 'tccj-bootstrap-js', TCCJ_PLUGIN_URL . 'assets/bootstrap/js/bootstrap.min.js' );
			wp_enqueue_style( 'tccj-codemirror-css', TCCJ_PLUGIN_URL . 'assets/codemirror/lib/codemirror.css' );
			wp_enqueue_script( 'tccj-codemirror-js', TCCJ_PLUGIN_URL . 'assets/codemirror/lib/codemirror.js' );
			wp_enqueue_script( 'tccj-codemirror-mode-javascript-js', TCCJ_PLUGIN_URL . 'assets/codemirror/mode/javascript/javascript.js' );
			wp_enqueue_style( 'tccj-general-css', TCCJ_PLUGIN_URL . 'assets/tiny-code/general.css' );
			wp_enqueue_script( 'tccj-code-editor-js', TCCJ_PLUGIN_URL . 'assets/tiny-code/code-editor.js' );
		}
	}
}
