<?php
/**
 * Register and enqueue scripts for plugin.
 *
 * @since 1.0.0
 * @package BlockArt
 */

namespace BlockArt;

/**
 * Register and enqueue scripts for plugin.
 *
 * @since 1.0.0
 */
class EnqueueScripts {

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	private static function init_hooks() {

		add_action( 'init', array( __CLASS__, 'register_scripts' ) );

		if ( 'external-css-file' === get_option( '_blockart_dynamic_css_print_method' ) ) {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_block_styles' ) );
		} else {
			add_action( 'wp_head', array( __CLASS__, 'add_inline_styles' ), PHP_INT_MAX );
		}

		add_action( 'wp_head', array( __CLASS__, 'add_inline_widget_styles' ), PHP_INT_MAX );

		if ( is_admin() ) {
			add_action( 'delete_post', array( __CLASS__, 'remove_css_file' ) );
		}
	}

	/**
	 * Register scripts and styles for plugin.
	 *
	 * @since 1.0.0
	 */
	public static function register_scripts() {
		self::register_admin_scripts();
		self::register_admin_styles();
		self::register_blocks_scripts();
		self::register_blocks_styles();
	}

	/**
	 * Register admin scripts.
	 *
	 * @since 1.0.0
	 */
	private static function register_admin_scripts() {

		$asset = blockart_get_asset_file( 'admin' );

		wp_register_script(
			'blockart-admin',
			plugins_url( '/dist/admin.js', BLOCKART_PLUGIN_FILE ),
			$asset['dependencies'],
			BLOCKART_VERSION,
			true
		);

		wp_localize_script(
			'blockart-admin',
			'_BLOCKART_ADMIN_',
			array(
				'cssPrintMethod' => get_option( '_blockart_dynamic_css_print_method' ),
				'version'        => BLOCKART_VERSION,
				'adminURL'       => admin_url(),
			)
		);
	}

	/**
	 * Register admin styles.
	 *
	 * @since 1.0.0
	 */
	private static function register_admin_styles() {
		wp_register_style(
			'blockart-admin',
			plugins_url( 'dist/admin.css', BLOCKART_PLUGIN_FILE ),
			array( 'wp-components' ),
			BLOCKART_VERSION
		);
	}

	/**
	 * Register script for blocks.
	 *
	 * @since 1.0.0
	 */
	private static function register_blocks_scripts() {

		global $pagenow;
		$asset = blockart_get_asset_file( 'blocks' );

		wp_register_script(
			'blockart-blocks',
			plugins_url( '/dist/blocks.js', BLOCKART_PLUGIN_FILE ),
			$asset['dependencies'],
			BLOCKART_VERSION,
			true
		);

		wp_set_script_translations( 'blockart-blocks', 'blockart', BLOCKART_LANGUAGES );

		wp_localize_script(
			'blockart-blocks',
			'_BLOCKART_',
			array(
				'isNotPostEditor'  => 'widgets.php' === $pagenow || 'customize.php' === $pagenow,
				'placeholderImage' => BLOCKART_ASSETS_DIR_URL . '/images/placeholder.png',
				'isWP59OrAbove'    => is_wp_version_compatible( '5.9' ),
			)
		);
	}

	/**
	 * Register all the block styles.
	 *
	 * @since 1.0.0
	 */
	private static function register_blocks_styles() {

		wp_register_style(
			'blockart-blocks',
			plugins_url( 'dist/style-blocks.css', BLOCKART_PLUGIN_FILE ),
			is_admin() ? array( 'wp-editor' ) : null,
			BLOCKART_VERSION
		);

		wp_register_style(
			'blockart-blocks-editor',
			plugins_url( 'dist/blocks.css', BLOCKART_PLUGIN_FILE ),
			array( 'wp-edit-blocks' ),
			BLOCKART_VERSION
		);
	}

	/**
	 * Add frontend internal style.
	 *
	 * @since 1.0.0
	 */
	public static function add_inline_styles() {

		$post_id = get_the_ID();
		$css     = get_post_meta( $post_id, '_blockart_css', true );

		self::enqueue_google_fonts();

		if ( ! empty( $css ) ) {
			printf( "<style id=\"blockart-post-{$post_id}-css\">\n%s\n</style>\n", $css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Enqueue blocks frontend css.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue_block_styles() {

		$post_id                 = get_the_ID();
		$upload_dir_url          = wp_get_upload_dir();
		$blockart_upload_dir_url = trailingslashit( $upload_dir_url['basedir'] );

		if ( $post_id ) {
			$blockart_css_path = $blockart_upload_dir_url . "blockart/blockart-css-{$post_id}.css";

			if ( file_exists( $blockart_css_path ) ) {
				$blockart_css_dir_url = trailingslashit( $upload_dir_url['baseurl'] );

				if ( is_ssl() ) {
					$blockart_css_dir_url = str_replace( 'http://', 'https://', $blockart_css_dir_url );
				}

				$css_url = $blockart_css_dir_url . "blockart/blockart-css-{$post_id}.css";

				wp_enqueue_style( "blockart-post-{$post_id}", $css_url, array(), filemtime( $blockart_css_path ) );
			} else {
				$css = get_post_meta( $post_id, '_blockart_css', true );

				if ( ! empty( $css ) ) {
					wp_add_inline_style( 'blockart-blocks', $css );
				}
			}
		}

		self::enqueue_google_fonts();
	}

	/**
	 * Enqueue Google fonts.
	 *
	 * @since 1.0.0
	 */
	private static function enqueue_google_fonts() {

		global $post;

		if ( ! is_object( $post ) ) {
			return;
		}

		$content         = apply_filters( 'blockart_block_content', $post->post_content );
		$blocks          = parse_blocks( $content );
		$font_attributes = self::get_font_attributes( $blocks );
		$google_fonts    = '';

		if ( count( $font_attributes ) > 0 ) {
			foreach ( $font_attributes as $family => $weight ) {
				if ( ! empty( $family ) && 'Default' !== $family ) {
					$google_fonts .= str_replace( ' ', '+', trim( $family ) ) . ':' . join( ',', array_unique( $weight ) ) . '|';
				}
			}

			if ( ! empty( $google_fonts ) ) {
				wp_register_style(
					'blockart-google-fonts',
					add_query_arg(
						array(
							'family'  => $google_fonts,
							'display' => 'swap',
						),
						'//fonts.googleapis.com/css'
					),
					array(),
					BLOCKART_VERSION
				);

				wp_enqueue_style( 'blockart-google-fonts' );
			}
		}
	}

	/**
	 * Get font attributes used in BlockArt blocks.
	 *
	 * @since 1.0.0
	 * @param array $blocks All blocks in a content.
	 * @param array $fonts Used Google fonts.
	 * @return array Font attributes.
	 */
	private static function get_font_attributes( $blocks, $fonts = array() ) {

		foreach ( $blocks as $block ) {
			if ( false !== strpos( $block['blockName'], 'blockart' ) ) {
				foreach ( $block['attrs'] as $key => $attr ) {
					if ( isset( $attr['typography'] ) && isset( $attr['family'] ) ) {
						$weight             = $block['attrs'][ $key ]['weight'] ? (string) $block['attrs'][ $key ]['weight'] : '400';
						$family             = $block['attrs'][ $key ]['family'];
						$fonts[ $family ][] = $weight;
					}
				}

				if ( isset( $block['innerBlocks'] ) && count( $block['innerBlocks'] ) > 0 ) {
					$inner_block_fonts = self::get_font_attributes( $block['innerBlocks'], $fonts );

					if ( count( $inner_block_fonts ) > 0 ) {
						$fonts = array_merge( $fonts, $inner_block_fonts );
					}
				}
			}
		}

		return $fonts;
	}

	/**
	 * Remove CSS file if post is deleted.
	 *
	 * @since 1.0.0
	 * @param int $id Post ID.
	 */
	public static function remove_css_file( $id ) {

		$upload_dir_url = wp_upload_dir();
		$dir            = trailingslashit( $upload_dir_url['basedir'] ) . 'blockart/';
		$filename       = "blockart-css-$id.css";

		if ( file_exists( $dir . $filename ) ) {
			try {
				unlink( $dir . $filename );
			} catch ( \Exception $e ) {
				echo wp_kses_post( __( 'Failed to delete CSS file: ', 'blockart' ) . $e->getMessage() );
			}
		}
	}

	/**
	 * Add inline widget styles.
	 *
	 * @return void
	 */
	public static function add_inline_widget_styles() {
		$widget_css = get_option( '_blockart_widget_css' );

		if ( ! empty( $widget_css ) ) {
			printf( "<style id='blockart-widget-css'>\n%s\n</style>\n", $widget_css ); // phpcs:ignore
		}
	}
}
