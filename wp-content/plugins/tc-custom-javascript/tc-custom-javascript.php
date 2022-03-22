<?php
/*
Plugin Name: TC Custom JavaScript
Description: Add custom JavaScript to your site from a professional editor in the WordPress admin (Admin Menu &gt; Appearance &gt; <a href="themes.php?page=tc-custom-javascript">Custom JavaScript</a>).
Version: 1.2.3
Author: Tiny Code
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'TCCJ_MAIN_FILE', __FILE__ );
define( 'TCCJ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TCCJ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TCCJ_TEXTDOMAIN', 'tc-custom-javascript' );

require_once TCCJ_PLUGIN_DIR . 'core/plugin.php';

TCCJ_Core_Plugin::init();
