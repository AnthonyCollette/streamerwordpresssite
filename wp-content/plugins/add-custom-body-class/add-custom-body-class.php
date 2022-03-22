<?php
/**
 * Plugin Name: Add Custom Body Class
 * Author: Anil Ankola
 * Version: 1.2
 * Description: Use this plugin to add a custom class in the HTML body tag.
 * Text Domain: add-custom-body-class
*/
if(!defined('ABSPATH')) exit; // Prevent Direct Browsing

// Add Custom meta box
function add_custom_body_class_post_meta_boxes() {
	$screens = get_post_types();
	foreach ( $screens as $screen ) {
		add_meta_box('add_custom_body_class_box', 'Add Custom Body Class', 'add_custom_body_class_box', $screen, 'side', 'default');
	}
}
add_action( "admin_init", "add_custom_body_class_post_meta_boxes" );

function save_custom_body_class_post_meta_boxes(){
    global $post;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( get_post_status( $post->ID ) === 'auto-draft' ) {
        return;
    }
    update_post_meta( $post->ID, "add_custom_body_class", sanitize_text_field( $_POST[ "add_custom_body_class" ] ) );
}
add_action( 'save_post', 'save_custom_body_class_post_meta_boxes' );

function add_custom_body_class_box(){
	global $post;
   	$get_class_value = get_post_custom( $post->ID );
    $add_custom_body_class = $get_class_value[ "add_custom_body_class" ][ 0 ];?>
    <input type="text" id="add_custom_body_class" name="add_custom_body_class" value="<?php echo $add_custom_body_class; ?>">
    <?php    
}

// dispaly body class function
add_filter('body_class','add_custom_field_body_class');
function add_custom_field_body_class( $classes ) {
	$show_body_class = get_post_meta(get_the_ID(),'add_custom_body_class', true);
	if($show_body_class){		
		$classes[] = $show_body_class;		
	}	
	// return the $classes array
	return $classes;
}
?>