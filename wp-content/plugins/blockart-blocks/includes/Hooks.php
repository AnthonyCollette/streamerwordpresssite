<?php
/**
 * Hooks.
 *
 * Extend WordPress or plugin functionality.
 *
 * @package BlockArt
 * @since 1.0.0
 */

namespace BlockArt;

defined( 'ABSPATH' ) || exit;

/**
 * Initialize hooks.
 *
 * @since 1.0.0
 */
class Hooks {

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.0.0
	 */
	private static function init_hooks() {
		add_filter( 'blockart_block_content', array( __CLASS__, 'widget_content' ) );
	}

	/**
	 * Append widget block content.
	 *
	 * @since 1.0.0
	 * @param string $content Post content.
	 * @return string Content.
	 */
	public static function widget_content( $content ) {

		$widget_blocks = get_option( 'widget_block' );

		if ( empty( $widget_blocks ) ) {
			return $content;
		}

		foreach ( (array) $widget_blocks as $block ) {
			if (
				isset( $block['content'] ) &&
				false !== strpos( $block['content'], 'blockart/' )
			) {
				$content .= $block['content'];
			}
		}

		return $content;
	}
}
