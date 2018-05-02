<?php
/*
Plugin Name: Being Boss - Sponsors Post Type
Plugin URI:  https://www.beingboss.club
Description: Custom Sponsors Post Type for Being Boss
Version:     1.0.1
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
* Initializing the Sponsors custom post type
*/
 
function sponsors_post_type() {
 
// Set UI labels for Sponsors post type
    $labels = array(
        'name'                => _x( 'Sponsors', 'Post Type General Name' ),
        'singular_name'       => _x( 'Sponsor', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Sponsors' ),
        'parent_item_colon'   => __( 'Parent Sponsor' ),
        'all_items'           => __( 'All Sponsors' ),
        'view_item'           => __( 'View Sponsor' ),
        'add_new_item'        => __( 'Add New Sponsor' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Sponsor' ),
        'update_item'         => __( 'Update Sponsor' ),
        'search_items'        => __( 'Search Sponsors' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Sponsors post type
     
    $args = array(
        'label'               => __( 'sponsors' ),
        'description'         => __( 'Being Boss Corporate Sponsors' ),
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
    register_post_type( 'sponsors', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'sponsors_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_sponsors_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_sponsors_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbsponsors_';

	/**
	 * Initiate the metabox
	 */
	$bbsponsors = new_cmb2_box( array(
		'id'            => 'bbsponsors_metabox',
		'title'         => __( 'Sponsor Item Details', 'cmb2' ),
		'object_types'  => array( 'sponsors', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbsponsors->add_field( array(
    		'name'    => 'Sponsor Link',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'link',
    		'type'    => 'text',
	) );


}






?>
