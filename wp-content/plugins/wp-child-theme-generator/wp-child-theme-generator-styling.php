<?php
defined('ABSPATH') or die('No script kiddies please!!');


function wctg_custom_child_theme_menu() {
	add_submenu_page(
			'themes.php',
		__( 'Child Theme Gen', 'wp-child-theme-generator' ),
		__('Child Theme Gen','wp-child-theme-generator'),
		'manage_options',
		'custom-child-theme',
		'wctg_custom_child_theme_form'
	);
}


function wctg_plugin_scripts(){
	wp_enqueue_script( 'wctg_script', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js' , array('jquery'));
	wp_register_style( 'wctg_style',plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css', false, '1.0.0' );
	wp_enqueue_style( 'wctg_style' );
}