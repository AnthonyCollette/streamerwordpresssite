<?php
/**
 * Plugin Name:     Star Rating Block
 * Description:     The Star Rating block allows you to display author-assigned star ratings within your content.
 * Version:         1.0.0
 * Author:          Achal Jain
 * Author URI:  	https://achalj.github.io
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     ib-star-rating
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function ideabox_star_rating_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "ideabox/star-rating" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'ideabox-star-rating-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'ideabox-star-rating-block-editor', 'ib-star-rating' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'ideabox-star-rating-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'ideabox-star-rating-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'ideabox/star-rating', array(
		'editor_script' => 'ideabox-star-rating-block-editor',
		'editor_style'  => 'ideabox-star-rating-block-editor',
		'style'         => 'ideabox-star-rating-block',
	) );
}
add_action( 'init', 'ideabox_star_rating_block_init' );
