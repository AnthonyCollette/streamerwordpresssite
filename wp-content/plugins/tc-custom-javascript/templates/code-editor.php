<div class="wrap">
	<h2><?php _e( 'Custom JavaScript', TCCJ_TEXTDOMAIN ); ?></h2>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="tccj-form" name="tccj-form" method="post">
		<?php wp_nonce_field( 'update_custom_javascript', '_wpnonce' ); ?>
		<input type="hidden" name="action" value="tc_custom_javascript">
		<div class="tccj-main-container">
			<div class="tccj-content-container">
				<!-- <p>
					<span class="glyphicon glyphicon-info-sign"></span>
					<?php _e( 'Do not use // comment style, instead you can use /* comment */.', TCCJ_TEXTDOMAIN ); ?>
				</p> -->
				<textarea id="tccj-content" name="tccj-content"><?php echo html_entity_decode( stripslashes( $tccj_content ) ); ?></textarea>
				<p class="submit">
					<input type="submit" name="tccj-update" id="tccj-update" class="button button-primary" value="Update" />
				</p>
			</div>
			<div class="tccj-sidebar">
				<div class="postbox">
					<div class="inside">
						<p>
							<?php _e( '<strong>TC Custom JavaScript</strong>\'s still in early stage. If you have any troubles when using it, or any ideas to improve its features to fit with your work, please do not hesitate to contact us.' , TCCJ_TEXTDOMAIN ); ?>
						</p>
						<p>
							<?php _e( 'Email', TCCJ_TEXTDOMAIN ); ?>: <a href="mailto:tinycodestudio@gmail.com">tinycodestudio@gmail.com</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
