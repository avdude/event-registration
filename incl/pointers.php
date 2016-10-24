<?php

/**
 * @author David Fleming
 * @copyright 2013
 */

add_action( 'admin_enqueue_scripts', 'evr_pointer_load', 1000 );
 
function evr_pointer_load( $hook_suffix ) {
 
    // Don't run on WP < 3.3
    if ( get_bloginfo( 'version' ) < '3.3' )
        return;
 
    $screen = get_current_screen();
    $screen_id = $screen->id;
 
    // Get pointers for this screen
    $pointers = apply_filters( 'evr_admin_pointers-' . $screen_id, array() );
 
    if ( ! $pointers || ! is_array( $pointers ) )
        return;
 
    // Get dismissed pointers
    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
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
    wp_enqueue_script( 'evr-pointer', plugins_url( 'js/evr-pointer.js', __FILE__ ), array( 'wp-pointer' ) );
 
    // Add pointer options to script.
    wp_localize_script( 'evr-pointer', 'evrPointer', $valid_pointers );
}

/*
evr-pointer.js

jQuery(document).ready( function($) {
    evr_open_pointer(0);
    function evr_open_pointer(i) {
        pointer = evrPointer.pointers[i];
        options = $.extend( pointer.options, {
            close: function() {
                $.post( ajaxurl, {
                    pointer: pointer.pointer_id,
                    action: 'dismiss-wp-pointer'
                });
            }
        });
 
        $(pointer.target).pointer( options ).pointer('open');
    }
});
*/

/*
Action Code
add_filter( 'evr_admin_pointers-post', 'evr_register_pointer_testing' );
function evr_register_pointer_testing( $p ) {
    $p['xyz140'] = array(
        'target' => '#change-permalinks',
        'options' => array(
            'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                __( 'Title' ,'plugindomain'),
                __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','plugindomain')
            ),
            'position' => array( 'edge' => 'top', 'align' => 'middle' )
        )
    );
    return $p;
}

*/



?>