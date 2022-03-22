<?php
defined('ABSPATH') or die('No script kiddies please!!');

function wctg_custom_child_theme_process() {

	if(isset($_POST['custom-child-create']) && wp_verify_nonce( $_POST['wp-easy-nonce'], 'custom-child-theme-creation' ) ){ 
			$result = wctg_handle_form_submission();
			if($result !== TRUE) {
				$msg = $result->get_error_message();
				$type = $result->get_error_codes();
				$go_url = $go_url = add_query_arg( array(
				    'msg'        => $msg,
				    'error_type' => $type[0],
				), admin_url( 'themes.php?page=custom-child-theme' ) );
				wp_redirect( $go_url );
			} else {

				$template              = sanitize_text_field( $_POST['parent-directory'] );

				$theme_root            = get_theme_root();
				$parent_theme_dir      = $theme_root. '/'. $template;


				$new_theme_title       = sanitize_text_field( $_POST['childtheme'] );

				$new_theme_author      = sanitize_text_field( $_POST['childauthor'] );

				$new_theme_description = sanitize_text_field( $_POST['description'] );

				$new_theme_name        = sanitize_title($new_theme_title);

				$new_theme_uri         = esc_url_raw($_POST['theme-uri'] );

				$new_author_uri        = esc_url_raw( $_POST['author-uri'] );

				$new_version           = sanitize_text_field( $_POST['version'] );

				$new_license           = sanitize_text_field( $_POST['license'] );

				$new_license_uri       = esc_url_raw( $_POST['license-uri'] );

				$new_tags              = sanitize_text_field( $_POST['tags'] );

				$new_theme_path        = $theme_root.'/'.$new_theme_name;
				$parent_theme_template = $template;

				mkdir( $new_theme_path );
				
				// Make style.css
				ob_start();
				require WCTG_BASE.'/templates/css.php';
				$css = ob_get_clean();
				file_put_contents( $new_theme_path.'/style.css', $css );

				// "Generate" functions.php 
				$function_name = $new_theme_name."_style";
				$function_name = str_replace("-", "_", $function_name);

				$function = "add_action( 'wp_enqueue_scripts', '" . $function_name . "' );
				function {$function_name}() {
					wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
					wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
				}";
				ob_start();

				require WCTG_BASE . '/templates/functions.php';
				$wp_function = ob_get_clean();
				file_put_contents( $new_theme_path . '/functions.php', $wp_function );
				if (isset($_POST['parent-screenshot']) && !empty($_POST['parent-screenshot'])){
					$screenshot = filter_var($_POST['parent-screenshot'], FILTER_SANITIZE_NUMBER_INT);
					}
					if( isset($screenshot) && $screenshot ==1 ){

						$screenshots = glob( $parent_theme_dir.'/screenshot.{png,jpg,jpeg,gif}', GLOB_BRACE );
						if($screenshots != false){
							$screenshot_path = basename($screenshots[0]);

							copy(
								$parent_theme_dir.'/'.$screenshot_path,
								$new_theme_path.'/'.$screenshot_path
							);
						}
					}
					else{
						if(isset($_FILES['fileUpload'])){
							$target_dir = $new_theme_path."/";
							$target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
							$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
							if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_dir."screenshot.".$imageFileType) ){

								$screenshot =TRUE;
		          			}
		          			else{


		          				$go_url = $go_url = add_query_arg( array(
							    'msg'=> __('Could-not-copy-screenshot','wp-child-theme-generator'),
							    'error_type' => 'empty'
							    
								),admin_url( 'themes.php?page=custom-child-theme' ));
								wp_redirect( $go_url );

		          			}


						}
					}
					
					if (isset($_POST['save-activate']) && !empty($_POST['save-activate'])){
					$save_activate = filter_var($_POST['save-activate'], FILTER_SANITIZE_NUMBER_INT);
					}

					if(isset($save_activate) && $save_activate == 1){
						switch_theme($new_theme_name);
						$go_url = admin_url( 'themes.php?activated=true' );
						wp_redirect( $go_url );
					}
					else{
						$go_url = $go_url = add_query_arg( array(
							    'msg'=> 'Child-theme-of-'.$template.'-created',
							    'error_type' => 'updated'
							    
							),admin_url( 'themes.php?page=custom-child-theme' ));
						wp_redirect( $go_url );
					}

				}
			}
		}
	
