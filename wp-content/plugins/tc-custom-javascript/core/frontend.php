<?php

class TCCJ_Core_Frontend {
	public static function print_script_in_footer() {
		//$tccj_content = sanitize_text_field( get_option( 'tccj_content', '' ) );
		$tccj_content = get_option( 'tccj_content', '' );
		$tccj_content = stripslashes( $tccj_content );
		$tccj_content = html_entity_decode( $tccj_content );

		if ( $tccj_content != '' ) {
			echo '<!-- TC Custom JavaScript -->';
			echo '<script type="text/javascript">' . $tccj_content . '</script>';
		}
	}
}
