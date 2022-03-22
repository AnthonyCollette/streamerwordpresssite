<?php

defined('ABSPATH') or die('No script kiddies please!!');

add_action( 'admin_bar_menu', 'wctg_add_navbar_items', 200 );

add_action( 'init','wctg_easy_child_theme' );