<?php
/*
Plugin Name: Being Boss - Events Post Type
Plugin URI:  https://www.beingboss.club
Description: Custom Events post type for Being Boss
Version:     1
Author:      Corey Winter
Author URI:  https://coreymwinter.com
*/

/**
 * Get the bootstrap!
 */
if ( file_exists( __DIR__ . '/cmb2/init.php' ) ) {
  require_once __DIR__ . '/cmb2/init.php';
} elseif ( file_exists(  __DIR__ . '/CMB2/init.php' ) ) {
  require_once __DIR__ . '/CMB2/init.php';
}






/*
* Initializing the Events custom post type
*/
 
function events_post_type() {
 
// Set UI labels for Events post type
    $labels = array(
        'name'                => _x( 'Events', 'Post Type General Name' ),
        'singular_name'       => _x( 'Event', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Events' ),
        'parent_item_colon'   => __( 'Parent Event Item' ),
        'all_items'           => __( 'All Event Items' ),
        'view_item'           => __( 'View Event Item' ),
        'add_new_item'        => __( 'Add New Event Item' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Event Item' ),
        'update_item'         => __( 'Update Event Item' ),
        'search_items'        => __( 'Search Event Items' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Event post type
     
    $args = array(
        'label'               => __( 'events' ),
        'description'         => __( 'Being Boss Events' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'events', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'events_post_type', 0 );






add_action( 'cmb2_admin_init', 'cmb2_events_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_events_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbevents_';

	/**
	 * Initiate the metabox
	 */
	$bbevents = new_cmb2_box( array(
		'id'            => 'bbevents_metabox',
		'title'         => __( 'Event Details', 'cmb2' ),
		'object_types'  => array( 'events', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );
	
	$bbevents->add_field( array(
    		'name'    => 'Event Details',
    		'desc'    => 'field description (optional)',
    		'default' => '',
    		'id'      => $prefix . 'event_details',
    		'type'    => 'textarea_small',
	) );

	$bbevents->add_field( array(
    		'name'    => 'Event Link',
    		'desc'    => 'field description (optional)',
    		'default' => '',
    		'id'      => $prefix . 'event_link',
    		'type'    => 'text',
	) );

    	$bbevents->add_field( array(
            	'name'    => 'Event Link Label',
            	'desc'    => 'field description (optional)',
            	'default' => '',
            	'id'      => $prefix . 'event_label',
            	'type'    => 'text',
    	) );
	
	$bbevents->add_field( array(
		'name'	=> 'Vacation Video Link',
		'desc'	=> 'Popup Video',
		'default'	=> '',
		'id'		=> $prefix . 'vacation_video',
		'type'		=> 'text',
	) );

}





// Add new Event Type taxonomy
add_action( 'init', 'create_eventtype_hierarchical_taxonomy', 0 );
 
function create_eventtype_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Event Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Event Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'all_items' => __( 'All Event Types' ),
    'parent_item' => __( 'Parent Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Event Type' ), 
    'update_item' => __( 'Update Event Type' ),
    'add_new_item' => __( 'Add New Event Type' ),
    'new_item_name' => __( 'New Event Type Name' ),
    'menu_name' => __( 'Event Types' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('eventtype',array('events'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'eventtype' ),
  ));
 
}









?>