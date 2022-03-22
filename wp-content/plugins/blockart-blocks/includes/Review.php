<?php
/**
 * Class Review.
 *
 * @package BlockArt
 * @since 1.0.3
 */

namespace BlockArt;

defined( 'ABSPATH' ) || exit;

/**
 * Class Review.
 */
class Review {

	/**
	 * Init.
	 *
	 * @since 1.0.3
	 * @return void
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.0.3
	 * @return void
	 */
	private static function init_hooks() {
		add_action( 'admin_head', array( __CLASS__, 'review_notice_scripts' ) );
		add_action( 'admin_notices', array( __CLASS__, 'review_notice' ) );
		add_action( 'wp_ajax_blockart_review_notice_dismiss', array( __CLASS__, 'review_notice_dismiss' ) );
	}

	/**
	 * Review notice markup.
	 *
	 * @since 1.0.3
	 * @return void
	 */
	public static function review_notice() {
		if ( ! self::maybe_show_review_notice() ) {
			return;
		}
		?>
		<div class="notice blockart-notice blockart-review-notice">
			<div class="blockart-notice-logo">
				<svg width="120" height="120" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M22 22H2V2h20zM3 21h18V3H3z" fill="#0369fc" />
					<path d="M13.46 10l-1.39-5-1.39 5zm.92 3H9.77l-1 4.46V19h6.4v-1.52z" fill="#005cff" fill-rule="evenodd" />
				</svg>
			</div>
			<div class="blockart-notice-content">
				<h3 class="blockart-notice-title"><?php esc_html_e( 'HAKUNA MATATA!', 'blockart' ); ?></h3>
				<p class="blockart-notice-description">
					<?php
					printf(
						/* Translators: 1: Plugin name, 2: Benefit, 3: Break tag, 4: Smile icon */
						esc_html__(
							'Hope you are having nice experience with %1$s plugin. Please provide this plugin a nice review. %3$s %2$s Basically, it would encourage us to release updates regularly with new features & bug fixes so that you can keep on using the plugin without any issues and also to provide free support like we have been doing. %4$s',
							'blockart'
						),
						'<strong>BlockArt Blocks</strong>',
						'<strong>What benefit would you have?</strong> <br>',
						'<br>',
						'<span class="dashicons dashicons-smiley smile-icon"></span>'
					)
					?>
				</p>
				<p class="blockart-notice-actions">
					<a href="https://wordpress.org/support/plugin/blockart-blocks/reviews?rate=5#new-post" target="_blank" rel="noopener noreferrer" class="button button-primary blockart-leave-review">
						<span class="dashicons dashicons-external"></span>
						<?php esc_html_e( 'Sure, I\'d love to!', 'blockart' ); ?>
					</a>
					<a href="#" class="button button-secondary blockart-remind-me-later"><span  class="dashicons dashicons-smiley"></span><?php esc_html_e( 'Remind me later', 'blockart' ); ?></a>
					<a href="#" class="button button-secondary blockart-reviewed-already"><span class="dashicons dashicons-dismiss"></span><?php esc_html_e( 'I already did', 'blockart' ); ?></a>
					<a href="https://wpblockart.com/contact/" class="button button-secondary blockart-have-query" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-testimonial"></span><?php esc_html_e( 'I have a query', 'blockart' ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Maybe show review notice.
	 *
	 * @since 1.0.3
	 * @return bool True or false.
	 */
	private static function maybe_show_review_notice() {
		$user_id         = get_current_user_id();
		$activation_time = get_option( '_blockart_activation_time' );
		$review          = get_user_meta( $user_id, '_blockart_review', true );

		if (
			$activation_time > strtotime( '-14 day' ) ||
			( isset( $review['partial_dismiss'] ) && ( $review['partial_dismiss'] > strtotime( '-14 day' ) ) ) ||
			( isset( $review['dismiss'] ) && $review['dismiss'] )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Review notice scripts.
	 *
	 * @return void
	 */
	public static function review_notice_scripts() {
		if ( ! self::maybe_show_review_notice() ) {
			return;
		}
		?>
		<style type="text/css">
			.blockart-notice{padding:16px;display:flex;align-items:center;border-left-color:#005cff!important}.blockart-notice .blockart-notice-logo{margin-right:20px;display:flex;align-items:center;justify-content:center}.blockart-notice .smile-icon{background:#e7e94b;padding:2px;font-size:18px;border-radius:50%}.blockart-notice .blockart-notice-content h3{margin:0;font-size:20px;line-height:1.5}.blockart-notice .blockart-notice-content p{margin-top:4px;padding:0}.blockart-notice .blockart-notice-content .blockart-notice-actions{margin-top:9px;margin-bottom:0}.blockart-notice .blockart-notice-content .button{margin-right:5px}.blockart-notice .blockart-notice-content .button .dashicons{margin:3px 4px 0 0}.blockart-notice .blockart-notice-content .button-secondary{color:#005cff;border-color:#005cff}.blockart-notice .blockart-notice-content .button-primary{background-color:#005cff}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function(t){t(document).on("click",".blockart-notice .button:not(.blockart-have-query)",function(e){t(this).hasClass("blockart-leave-review")||e.preventDefault();var a={action:"blockart_review_notice_dismiss",security:"<?php echo esc_js( wp_create_nonce( 'blockart_review_notice_dismiss_nonce' ) ); ?>",type:"dismiss"};t(this).hasClass("blockart-remind-me-later")&&(a.type="partial_dismiss"),t.post(ajaxurl,a),t(".blockart-notice").remove()})});
		</script>
		<?php
	}

	/**
	 * Dismiss review notice.
	 *
	 * @since 1.0.3
	 * @return void
	 */
	public static function review_notice_dismiss() {
		check_ajax_referer( 'blockart_review_notice_dismiss_nonce', 'security' );

		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$data = array();

		if ( 'dismiss' === $type ) {
			$data['dismiss'] = true;
		}

		if ( 'partial_dismiss' === $type ) {
			$data['partial_dismiss'] = time();
		}

		update_user_meta( get_current_user_id(), '_blockart_review', $data );
	}

}
