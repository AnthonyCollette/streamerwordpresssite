/**
 * Theme Name:     <?php echo esc_html( $new_theme_title ), "\n"; ?>
 * Author:         <?php echo esc_html( $new_theme_author ), "\n"; ?>
 * Template:       <?php echo esc_html( $parent_theme_template ), "\n"; ?>
 * Text Domain:	   <?php echo sanitize_title( $new_theme_title ), "\n"; ?>
 * Description:    <?php echo esc_html( $new_theme_description ), "\n"; ?>
<?php if( isset( $new_theme_uri ) && ! empty( $new_theme_uri ) ): ?>
 * Theme URI:      <?php echo esc_url($new_theme_uri), "\n"; ?>
<?php 
endif;
if( isset( $new_author_uri ) && ! empty( $new_author_uri ) ):
?>
 * Author URI:     <?php echo esc_url( $new_author_uri ), "\n"; ?>
<?php 
endif;
if( isset( $new_version ) && ! empty( $new_version ) ):
?>
 * Version:        <?php echo esc_html( $new_version ), "\n"; ?>
<?php 
endif;
if( isset($new_license ) && ! empty( $new_license ) ):
?>
 * License:        <?php echo esc_html( $new_license ), "\n"; ?>
<?php 
endif;
if(isset($new_license_uri) && ! empty( $new_license_uri ) ):
?>
 * License URI:    <?php echo esc_url( $new_license_uri ), "\n"; ?>
<?php 
endif;
if(isset($new_tags) && ! empty( $new_tags ) ):
?>
 * Tags:           <?php echo esc_html($new_tags), "\n"; ?>
<?php 
endif;
?>
 */
