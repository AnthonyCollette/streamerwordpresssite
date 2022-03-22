<?php

defined('ABSPATH') or die('No script kiddies please!!');

function wctg_add_navbar_items( $admin_bar ) {

	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$current_theme = wp_get_theme();
	$all_themes    = wp_get_themes();
	$admin_bar->add_menu( array(
			'id'    => 'child-theme',
			'title' => __('Make Child Theme','wp-child-theme-generator'),
		));

	$admin_bar->add_menu( array(
		'id'     => 'easy-child-theme',
		'parent' => 'child-theme',
		'title'  => __('Create & Activate','wp-child-theme-generator'),
	));
	$admin_bar->add_menu( array(
		'id'     => 'custom-child-theme',
		'parent' => 'child-theme',
		'title'  => __('Custom Options','wp-child-theme-generator'),
		'href'   => admin_url( 'themes.php?page=custom-child-theme' ),
	));

	$redirect_to = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ;

	if ( ! empty( $all_themes ) ) {
		foreach ( $all_themes as $key =>$theme) {
		$temp = $theme->get('Template');
			if( empty( $temp ) ) {
					// Add menu items.
				$admin_bar->add_menu( array(
					'id'     => 'easy-child-theme-' . $key,
					'parent' => 'easy-child-theme',
					'title'  => $theme->get('Name'),

					'href'  => add_query_arg( array(
						'easy-child-name' =>$theme->get('Name'),
						'easy-child-switch' => $key,
						'easy-child-td' => $theme->get('TextDomain'),
						'easy-child-author' => $theme->get('Author'),
						'easy-child-description' => $theme->get('Description'),
						'easy-child-go'     => esc_url( $redirect_to ),
					), admin_url() ),

				));
			}
					
		}

	}

}

function wctg_easy_child_theme() {

	if ( isset( $_GET['easy-child-switch'] ) && ! empty( $_GET['easy-child-switch'] ) ) {

		$parent_theme_title = sanitize_text_field( $_GET['easy-child-name'] );
		
		$parent_theme_name = sanitize_text_field ( $_GET['easy-child-switch'] );


		$parent_theme_template = $parent_theme_name;

		$theme_root = get_theme_root();
		$parent_theme_dir = $theme_root. '/'. $parent_theme_name;


		// Turn a theme name into a directory name
		$new_theme_name = $parent_theme_name.'-child';
		$new_theme_title = $parent_theme_title. ' Child';
		$new_theme_author = sanitize_text_field ( $_GET['easy-child-author'] );
		$new_theme_description = sanitize_text_field( $_GET['easy-child-description'] );

		// Validate theme name
		$new_theme_path = $theme_root.'/'.$new_theme_name;
		if ( file_exists( $new_theme_path ) ) {
			$go_url = add_query_arg( array(
							    'msg'=> __('Child-theme-already-exists.-Please-try-using-custom-child-theme-feature.','wp-child-theme-generator'),
							    'error_type' => 'exists'
							    
								),admin_url( 'themes.php' ));
								wp_redirect( $go_url );
			return new WP_Error( 'exists', 'Theme directory already exists!' );
		}

		mkdir( $new_theme_path );

		// Make style.css
		ob_start();
		require WCTG_BASE . '/templates/css.php';
		$css = ob_get_clean();
		file_put_contents( $new_theme_path . '/style.css', $css );	

		$function_name = $new_theme_name . "_style";
		$function_name = str_replace( "-", "_", $function_name );
		$function_name = preg_replace("/[^A-Za-z0-9?!_]/",'',$function_name);

		$function = " add_action( 'wp_enqueue_scripts', '". $function_name . "' );
  function ".$function_name."() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',array('parent-style'));
}";
		ob_start();
		require WCTG_BASE.'/templates/functions.php';
		$wp_function = ob_get_clean();
		file_put_contents( $new_theme_path . '/functions.php', $wp_function );

		// "Generate" functions.php 
		
		$screenshots = glob( $parent_theme_dir.'/screenshot.{png,jpg,jpeg,gif}', GLOB_BRACE );
		if($screenshots != false){
			$screenshot_path = basename($screenshots[0]);

			copy(
				$parent_theme_dir.'/'.$screenshot_path,
				$new_theme_path.'/'.$screenshot_path
			);
		}
		
		$theme = wp_get_theme( sanitize_text_field( $_GET['easy-child-switch'] ) );
		switch_theme($new_theme_name);

		$go_url = admin_url( 'themes.php?activated=true' );

		wp_redirect( $go_url );

	exit;
	}
}
