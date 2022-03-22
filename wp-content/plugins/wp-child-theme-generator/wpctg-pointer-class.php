<?php
 /**
   * Main function for pointers.
   *
   * @since    1.0.0
   */
     function wpctg_pointer_load( $hook_suffix ) {
     
        // Don't run on WP < 3.3
        if ( get_bloginfo( 'version' ) < '3.3' )
            return;
     
        $screen = get_current_screen();
        $screen_id = $screen->id;
        // Get pointers for this screen
        $pointers = apply_filters( 'wpctg_admin_pointers-'.$screen_id, array() );
     
        if ( ! $pointers || ! is_array( $pointers ) )
            return;
     
        // Get dismissed pointers
        $dismissed = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
        $dismissed = explode( ',', $dismissed );

        $valid_pointers =array();
     
        // Check pointers and remove dismissed ones.
        foreach ( $pointers as $pointer_id => $pointer ) {
     
            // Sanity check
            if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
                continue;
     
            $pointer['pointer_id'] = $pointer_id;
     
            // Add the pointer to $valid_pointers array
            $valid_pointers['pointers'][] =  $pointer;

        }
        // No valid pointers? Stop here.
        if ( empty( $valid_pointers ) )
            return;
     
        // Add pointers style to queue.
        wp_enqueue_style( 'wp-pointer' );
     
        // Add pointers script to queue. Add custom script.
        wp_enqueue_script( 'wpctg-pointer', plugins_url( 'assets/js/wpctg-pointer.js', __FILE__ ), array( 'wp-pointer' ) );
     
        // Add pointer options to script.
        wp_localize_script( 'wpctg-pointer', 'wpctgPointer', $valid_pointers );
    }
        

 /**
   * Pointer for easy child theme
   *
   * @since    1.0.0
   */
    function wpctg_register_pointer_child_theme( $p ) {
        
        $p['id3'] = array(
            'target' => '#custom-select',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'Child theme with custom options' ,'wp-child-theme-generator'),
                    __( 'To create the child theme with custom options, choose theme from the drop-down.','wp-child-theme-generator')
                ),
                'position' => array( 'edge' => 'top', 'align' => 'left' )
            )
        );
        return $p;
    }

 /**
   * Pointer for Appearance on plugin activation.
   *
   * @since    1.0.0
   */
    function wpctg_register_pointer_appearance( $q ) {
        
        $q['app1'] = array(
            'target' => '#adminmenuwrap #menu-appearance',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'WP Child Theme Custom Options', 'wp-child-theme-generator'),
                    __( 'Go to <b>Child Theme Gen</b> to create child theme with custom options.','wp-child-theme-generator')
                ),
                'position' => array( 'edge' => 'left', 'align' => 'center' )
            )
        );
        $q['id5'] = array(
            'target' => '#wp-admin-bar-child-theme',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'Quickly create child theme' ,'wp-child-theme-generator'),
                    __( 'To quickly create the child theme in one click.','wp-child-theme-generator')
                ),
                'position' => array( 'edge' => 'top', 'align' => 'left' )
            )
        );

        return $q;
    }