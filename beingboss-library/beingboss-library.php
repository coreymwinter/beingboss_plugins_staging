<?php
/*
Plugin Name: Being Boss - Library Post Type
Plugin URI:  https://www.beingboss.club
Description: Custom Library post type for Being Boss
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
* Initializing the Library custom post type
*/
 
function library_post_type() {
 
// Set UI labels for Library post type
    $labels = array(
        'name'                => _x( 'Library', 'Post Type General Name' ),
        'singular_name'       => _x( 'Library', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Library' ),
        'parent_item_colon'   => __( 'Parent Library Item' ),
        'all_items'           => __( 'All Library Items' ),
        'view_item'           => __( 'View Library Item' ),
        'add_new_item'        => __( 'Add New Library Item' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Library Item' ),
        'update_item'         => __( 'Update Library Item' ),
        'search_items'        => __( 'Search Library Items' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Library post type
     
    $args = array(
        'label'               => __( 'library' ),
        'description'         => __( 'Being Boss Library' ),
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
    register_post_type( 'library', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'library_post_type', 0 );






add_action( 'cmb2_admin_init', 'cmb2_library_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_library_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bblibrary_';

	/**
	 * Initiate the metabox
	 */
	$bblibrary = new_cmb2_box( array(
		'id'            => 'bblibrary_metabox',
		'title'         => __( 'Library Item Details', 'cmb2' ),
		'object_types'  => array( 'library', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bblibrary->add_field( array(
    		'name'             => 'Library Item Type',
    		'desc'             => 'Select an option',
    		'id'               => 'wiki_test_select',
    		'type'             => 'select',
    		'show_option_none' => true,
    		'default'          => 'download',
    		'options'          => array(
        		'download'   => __( 'Download Link', 'cmb2' ),
			'email' => __( 'Email Sign-Up', 'cmb2' )
    		),
	) );


	$bblibrary->add_field( array(
    		'name' => __( 'Download/Email Link', 'cmb2' ),    		
		'desc' => 'Dropbox download link/email subscribe link',
    		'default' => '',
    		'id' => $prefix . 'download_link',
    		'type' => 'text_url'
	) );

	$bblibrary->add_field( array(
    		'name'    => 'Link Label',
    		'desc'    => 'field description (optional)',
    		'default' => '',
    		'id'      => $prefix . 'link_label',
    		'type'    => 'text',
	) );

	$bblibrary->add_field( array(
    		'name'    => 'ThriveLeads Opt-In',
    		'desc'    => 'ID only (Do NOT paste entire shortcode)',
    		'default' => '',
    		'id'      => $prefix . 'thrive_id',
    		'type'    => 'text',
	) );

}






//function resourcelibrary_query_args($query_args, $grid_name) {

    //if ($grid_name == 'Resource Library Bottom') {
        // all query parameters can be modified (https://codex.wordpress.org/Class_Reference/WP_Query)
        //$query_args['offset'] = 3;
   // }
    
   // return $query_args;
    
//}

//add_filter('tg_wp_query_args', 'resourcelibrary_query_args', 10, 2);





// Add new Library Type taxonomy
add_action( 'init', 'create_librarytype_hierarchical_taxonomy', 0 );
 
function create_librarytype_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Library Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Library Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'all_items' => __( 'All Library Types' ),
    'parent_item' => __( 'Parent Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Library Type' ), 
    'update_item' => __( 'Update Library Type' ),
    'add_new_item' => __( 'Add New Library Type' ),
    'new_item_name' => __( 'New Library Type Name' ),
    'menu_name' => __( 'Library Types' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('librarytype',array('library'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'librarytype' ),
  ));
 
}






?>