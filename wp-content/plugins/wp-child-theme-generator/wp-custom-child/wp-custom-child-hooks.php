<?php

defined('ABSPATH') or die('No script kiddies please!!');

add_action( 'admin_post_child_theme','wctg_custom_child_theme_process' );

add_action( 'admin_post_nopriv_child_theme', 'wctg_custom_child_theme_process' );

add_action( 'admin_menu',  'wctg_custom_child_theme_menu', 0  );

add_action( 'admin_notices', 'wctg_showErrorNotice');

add_filter( 'wpctg_admin_pointers-appearance_page_custom-child-theme', 'wpctg_register_pointer_child_theme' );
add_filter( 'wpctg_admin_pointers-plugins', 'wpctg_register_pointer_appearance' );
add_action( 'admin_enqueue_scripts', 'wpctg_pointer_load' );


add_action( 'admin_enqueue_scripts', 'wctg_plugin_scripts' );