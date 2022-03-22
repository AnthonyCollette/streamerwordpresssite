<?php 

defined('ABSPATH') or die('No script kiddies please!!');

if (!function_exists( 'wctg_custom_child_theme_form' ) ) :

	function wctg_custom_child_theme_form() {

		$all_themes    = wp_get_themes();
	
	
	?>
	<div class = "childtheme-container">
		<div id= "custom-child-theme-settings" class= "boxed">
		<h1 class="child-head"> <?php _e('WP Child Theme Generator',  'wp-child-theme-generator'); ?></h1>
			<div id="postbox-child" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable"><div id="wp_doc_tab_metabox" class="postbox  ui-sortable">
				
					<h2 class="child-theme-header">
						<span><?php _e('Child Theme Options','wp-child-theme-generator');?></span>
					</h2>
					<br>
				<div class ="centered">
				<table class="form-table">
					<tbody>
				<tr>
				    <th scope="row">
				    <?php 
				    // $screen = get_current_screen();
				    // echo $screen->id;
				    ?>
				        <label for="child-theme" class="my-text-field"><?php _e('Select a theme', 'wp-child-theme-generator');?></label>
				    </th>

		    		<td>

						<select id='custom-select'>
							<?php 
							printf( '<option selected disabled hidden>%s</option>', __('Choose A Theme','wp-child-theme-generator') );
							foreach ($all_themes as $key => $value) {
								$temp = $value->get('Template');
								if( empty($temp)){
						  			echo("<option value='".$key."'data-name ='".$value->get('Name')."' data-description ='".$value->get('Description')."'data-author ='".$value->get('Author')."'>".$value->get('Name')."</option>");
									}
								
								}
							?>
						</select>
					</td>
				</tr>

				
				</tbody>
				</table>
			</div>
			<div id = 'theme-info' class= 'theme-select-info'>
					<p> <?php _e ('Please select a theme first.', 'wp-child-theme-generator'); ?> </p>
				</div>
					<form id="custom-child" method ="post" action ="<?php echo esc_url( admin_url('admin-post.php') ); ?>" enctype="multipart/form-data">
						<table class="form-table child-table">
    		 				 <tbody>
								<tr>
								    <th scope="row">
								        <label for="my-text-field"><?php _e('Child Theme Name','wp-child-theme-generator'); ?> </label>
								    </th>
								 
								    <td>
										<input type="text" id= "child-name" name="childtheme" value = "">
									</td>
								</tr>
								<tr>
								    <th scope="row">
								        <label for="my-text-field"><?php _e('Author Name', 'wp-child-theme-generator'); ?> </label>
								    </th>
								 
								    <td>
								 	 <input type="text" id="child-author" name="childauthor"> 
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="my-text-field"><?php _e('Child Theme Description','wp-child-theme-generator');?></label>
									</th>

									<td>
								  		<textarea  id="child-description" class="wctg-styled" name="description"> </textarea><br>
									</td>
								</tr>

								  <tr>
				    				<th scope="row"><?php _e('Child Theme Options','wp-child-theme-generator'); ?></th>
				    					<td>
				        					<fieldset>
				        						<legend class="screen-reader-text">
				        							<span><?php _e('Child Theme Screenshot & Activate Options', 'wp-child-theme-generator');?></span>
				        						</legend>
				           						<label for="save-activate">
													<input type="checkbox" id="save-activate" value="1" name="save-activate"> <?php _e('Create & Activate','wp-child-theme-generator');?>
												</label>
				            					<br>
				            					<label for="child-screenshot">
				             						<input type="checkbox" id="child-screenshot" name="parent-screenshot" value="1" checked > <?php _e("Use parent theme's screenshot", "wp-child-theme-generator" ); ?>
				           						</label>
				       						 </fieldset>
				       					</td>
				       				</tr>
								  	<tr id = "custom-screenshot">
					    				<th scope="row"><?php _e ('Choose custom screenshot' , 'wp-child-theme-generator');?></th>
					    				<td>
											  <div id= "custom-screenshot">
												  <input id="fileUpload" name="fileUpload" type="file"/>
												  	<br>
												  	<span class="description"> <?php printf( __('WordPress recommends screenshot of size 1200x900 px and in .png format.<br> Click %1$s for more details ', 'wp-child-theme-generator'),'<a href="https://codex.wordpress.org/Theme_Development" target= "_blank" rel="designer">here</a>' ); ?>
												  	</span>
													   <div id="image-holder">
													   </div>
											  </div>
										</td>
									</tr>					
								    <tr>
				    				<th scope="row"><?php _e('Optional Information', 'wp-child-theme-generator'); ?></th>
				    					<td>
				        					<fieldset>
				        						<legend class="screen-reader-text">
				        							<span>Optional Information</span>
				        						</legend>
				           						<label for="theme_uri">
													<input type="text" id="theme-uri" name="theme-uri" placeholder = "<?php _e('Theme URI','wp-child-theme-generator'); ?>">
												</label>
				            					<br>
				            					<label for="author-uri">
				             						<input type="text" id="author-uri" name="author-uri" placeholder = "<?php _e('Author URI', 'wp-child-theme-generator');?>"> 
				           						</label>	
				           						<br>
				           						<label for="version">
				             						<input type="text" id="version" name="version" placeholder = "<?php _e('Version','wp-child-theme-generator' ); ?>"> 
				           						</label>
				           						<br>
				           						<label for="license">
				             						<input type="text" id="license" name="license" placeholder ="<?php _e('License','wp-child-theme-generator');?>"> 
				           						</label>
				           						<br>
				           						<label for="new_license_uri">
				             						<input type="text" id="license-uri" name="license-uri" placeholder ="<?php _e('license URI','wp-child-theme-generator');?>"> 
				           						</label>
				           						<br>
				           						<label for="tags">

				             						<input type="text" id="tags" name="tags" placeholder ="<?php _e('Tags', 'wp-child-theme-generator');?>"> 
				           						</label>

				       						 </fieldset>
				       					</td>
				       				</tr>
				  				

				  					<input type ="hidden" id="parent" name="parent-directory" value ="">
					

					 				<?php wp_nonce_field('custom-child-theme-creation', 'wp-easy-nonce'); ?>

					 				<input type="hidden" name="action" value="child_theme">

									<tr>
										<td class = "child-button">
									 
									 		<?php submit_button(__('Create Child Theme', 'wp-child-theme-generator'), 'primary' ,'custom-child-create'); ?>

										</td>
									</tr>

								</tbody>
							</table>
						</form>

					</div>
				</div>
			</div>
		</div>
		<div class = "childtheme-sidebar">			
				<?php 
				$advert = array(
							'our-plugins' => array(
									'title' => __( 'Our Plugins', 'wp-child-theme-generator' ),
									'image'=> 'plugin.png',
									'button_text' => __( 'Our Plugins', 'wp-child-theme-generator' ),
									'link' => 'http://wensolutions.com/plugins',
									'message' => __( 'Checkout our other cools plugins for WordPress.', 'wp-child-theme-generator' )
							),
							'doc' => array(
								'title' => __( 'Documentation', 'wp-child-theme-generator' ),
								'image'=> 'docico.png',
								'button_text' => __( 'Get documentation here', 'wp-child-theme-generator' ),
								'link' => 'http://wensolutions.com/',
								'message' => __( 'Click below to view full documentation of the plugin.', 'wp-child-theme-generator' )
								),
							'supp' => array(
									'title' => __( 'Support', 'wp-child-theme-generator' ),
									'image'=> 'help.png',
									'button_text' => __( 'Get support here', 'wp-child-theme-generator' ),
									'link' => 'http://wensolutions.com/',
									'message' => __( 'If you need further assistance, please feel free to visit our support team.', 'wp-child-theme-generator' )
							)							
						);
				 foreach ($advert as $key => $value) { ?>
			<div id="wp_doc_documentation" class="postbox ">
				<h2 class="child-title"><span><?php _e($value['title'] , 'wp-child-theme-generator' ); ?></span></h2>
				<div class="inside">
					<div class="thumbnail">
					    <a href="<?php echo esc_url( $value['link'] ) ;?>" target="_blank" class="">
					    	<img src="<?php echo WCTG_BASE_URL."/assets/images/". $value['image'];?>" style="max-width:100%">
					    </a>
					    <p class="text-justify"><?php _e( $value['message'], 'wp-child-theme-generator' ); ?> </p>
					    <p class="text-center"><a href="<?php echo esc_url( $value['link'] ); ?>" target="_blank" class="button button-primary"><?php echo $value['button_text']; ?></a></p>
				    </div>     
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<?php
	

}

endif;