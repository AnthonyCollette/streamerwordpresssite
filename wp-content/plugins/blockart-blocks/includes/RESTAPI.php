<?php
/**
 * Register REST routes.
 *
 * @since 1.0.0
 * @package BlockArt
 */

namespace BlockArt;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * REST_API class.
 *
 * @since 1.0.0
 */
class RESTAPI {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private static $namespace = 'blockart/v1';

	/**
	 * REST API endpoint.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private static $api_endpoint = 'https://wpblockart.com/wp-json/blockart-library/v1';

	/**
	 * Init.
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * REST_API constructor.
	 *
	 * @since 1.0.0
	 */
	private static function init_hooks() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	/**
	 * Register REST routes.
	 *
	 * @since 1.0.0
	 */
	public static function register_routes() {
		$rest_routes = array(
			'/save_block_css/'          => 'save_block_css',
			'/save_reusable_block_css/' => 'append_block_css',
			'/get_sections/'            => 'get_sections',
			'/get_single_section/'      => 'get_single_section',
			'/get_templates/'           => 'get_templates',
			'/get_single_template/'     => 'get_single_template',
		);

		foreach ( $rest_routes as $route => $callback ) {
			register_rest_route(
				self::$namespace,
				$route,
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( __CLASS__, $callback ),
					'permission_callback' => array( __CLASS__, 'permission_check' ),
				)
			);
		}
	}

	/**
	 * Create files.
	 *
	 * @since 1.0.0
	 * @param string $filename Filename.
	 * @param string $content Content.
	 * @return void|WP_REST_Response
	 */
	private static function create_files( $filename = '', $content = '' ) {

		try {
			global $wp_filesystem;

			$upload_dir_url = wp_upload_dir();
			$dir            = trailingslashit( $upload_dir_url['basedir'] ) . 'blockart/';

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			WP_Filesystem( false, $upload_dir_url['basedir'], true );

			if ( ! $wp_filesystem->is_dir( $dir ) ) {
				$wp_filesystem->mkdir( $dir );
			}

			$wp_filesystem->put_contents( $dir . $filename, $content );
		} catch ( Exception $e ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Save block CSS in post meta and on a file.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request Data about the request.
	 * @return WP_REST_Response
	 */
	public static function save_block_css( $request ) {

		$params         = $request->get_params();
		$post_id        = (int) sanitize_text_field( $params['postId'] );
		$filename       = "blockart-css-$post_id.css";
		$upload_dir_url = wp_upload_dir();
		$dir            = trailingslashit( $upload_dir_url['basedir'] ) . 'blockart/';

		if ( $params['has_block'] ) {
			self::create_files( $filename, $params['blockCss'] );
			update_post_meta( $post_id, '_blockart_active', 'yes' );
			update_post_meta( $post_id, '_blockart_css', $params['blockCss'] );
		} else {
			delete_post_meta( $post_id, '_blockart_active' );

			if ( file_exists( $dir . $filename ) ) {
				unlink( $dir . $filename );
			}

			delete_post_meta( $post_id, '_blockart_css' );
		}

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Data retrieved successfully.', 'blockart' ),
			)
		);
	}

	/**
	 * Append/Create css of the reusable blocks.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request Data about the request.
	 * @return WP_REST_Response
	 */
	public static function append_block_css( $request ) {

		$post               = $request->get_params();
		$reusable_block_css = $post['css'];
		$post_id            = (int) sanitize_text_field( $post['postId'] );

		if ( $post_id ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			$filename = "blockart-css-$post_id.css";
			$dir      = trailingslashit( wp_upload_dir()['basedir'] ) . 'blockart/';

			if ( file_exists( $dir . $filename ) ) {

				$main_css = get_post_meta( $post_id, '_blockart_css', true );

				if ( false === strpos( $main_css, $reusable_block_css ) ) {
					self::create_files( $filename, $main_css . $reusable_block_css );
					update_post_meta( $post_id, '_blockart_css', $main_css . $reusable_block_css );
				}
			} else {
				self::create_files( $filename, $reusable_block_css );
				update_post_meta( $post_id, '_blockart_active', 'yes' );
				update_post_meta( $post_id, '_blockart_css', $reusable_block_css );
			}

			return new WP_REST_Response(
				array(
					'success' => true,
					'message' => __( 'Data retrieved successfully.', 'blockart' ),
				)
			);
		}

		return new WP_REST_Response(
			array(
				'success' => false,
				'message' => __( 'Failed to retrieve data!', 'blockart' ),
			)
		);
	}

	/**
	 * Get sections.
	 *
	 * @since 1.0.2
	 * @param WP_REST_Request $request Data about the request.
	 * @return WP_REST_Response
	 */
	public static function get_sections( $request ) {
		return self::get_designs( $request );
	}

	/**
	 * Get single section content.
	 *
	 * @since 1.0.2
	 * @param WP_REST_Request $request Data about the request.
	 * @return WP_REST_Response
	 */
	public static function get_single_section( $request ) {
		return self::get_content( $request );
	}

	/**
	 * Get templates.
	 *
	 * @since 1.0.2
	 * @param WP_REST_Request $request Data about the request.
	 * @return WP_REST_Response
	 */
	public static function get_templates( $request ) {
		return self::get_designs( $request, 'templates' );
	}

	/**
	 * Get single template content.
	 *
	 * @since 1.0.2
	 * @param WP_REST_Request $request Data about the request.
	 * @return WP_REST_Response
	 */
	public static function get_single_template( $request ) {
		return self::get_content( $request, 'single-template' );
	}

	/**
	 * Fetch pre-made designs using self-hosted api endpoint.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request Data about the request.
	 * @param string $type Type of data to fetch.
	 * @return WP_REST_Response
	 */
	private static function get_designs( $request, $type = 'sections' ) {

		$param = $request->get_params();

		$remote_data = wp_remote_post(
			self::$api_endpoint . "/$type",
			array(
				'method'  => 'POST',
				'timeout' => 120,
				'body'    => array(
					'starter_pack' => isset( $param['starter_pack'] ) && $param['starter_pack'],
				),
			)
		);

		if ( is_wp_error( $remote_data ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => __( 'Failed to retrieve data!', 'blockart' ),
				)
			);
		}

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Data retrieved successfully.', 'blockart' ),
				'data'    => $remote_data['body'],
			)
		);
	}

	/**
	 * Fetch content using self-hosted api endpoint.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request Data about the request.
	 * @param string $type Type of data to fetch.
	 * @return WP_REST_Response
	 */
	private static function get_content( $request, $type = 'single-section' ) {

		$params      = $request->get_params();
		$remote_data = wp_remote_post(
			self::$api_endpoint . "/{$type}",
			array(
				'method'  => 'POST',
				'timeout' => 120,
				'body'    => array(
					'id' => $params['id'],
				),
			)
		);

		if ( is_wp_error( $remote_data ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => __( 'Failed to retrieve data!', 'blockart' ),
				)
			);
		}

		$data              = json_decode( $remote_data['body'] );
		$processed_content = ImportHelper::process_content( $data->content );

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Data retrieved successfully.', 'blockart' ),
				'data'    => $processed_content,
			)
		);
	}

	/**
	 * Permission check callback.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function permission_check() {
		return current_user_can( 'edit_posts' );
	}
}
