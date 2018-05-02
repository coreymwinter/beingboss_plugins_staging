<?php
/*
Plugin Name: Being Boss - Resources Post Type
Plugin URI:  https://www.beingboss.club
Description: Custom Resources Post Type for Being Boss
Version:     1.1.1
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
* Initializing the Resources custom post type
*/
 
function resources_post_type() {
 
// Set UI labels for Resources post type
    $labels = array(
        'name'                => _x( 'Resources', 'Post Type General Name' ),
        'singular_name'       => _x( 'Resource', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Resources' ),
        'parent_item_colon'   => __( 'Parent Resource' ),
        'all_items'           => __( 'All Resources' ),
        'view_item'           => __( 'View Resource' ),
        'add_new_item'        => __( 'Add New Resource' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Resource' ),
        'update_item'         => __( 'Update Resource' ),
        'search_items'        => __( 'Search Resources' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Resources post type
     
    $args = array(
        'label'               => __( 'resources' ),
        'description'         => __( 'Being Boss Resources' ),
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
    register_post_type( 'resources', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'resources_post_type', 0 );





add_action( 'cmb2_admin_init', 'cmb2_resources_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_resources_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbresources_';

	/**
	 * Initiate the metabox
	 */
	$bbresources = new_cmb2_box( array(
		'id'            => 'bbresources_metabox',
		'title'         => __( 'Resource Details', 'cmb2' ),
		'object_types'  => array( 'resources', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbresources->add_field( array(
    		'name'    => 'Resource Quote',
    		'desc'    => 'Do not include quotation marks',
    		'default' => '',
    		'id'      => $prefix . 'quote',
    		'type'    => 'text',
	) );

    	$bbresources->add_field( array(
            	'name'    => 'Resource Quote Author',
            	'desc'    => '',
            	'default' => '',
            	'id'      => $prefix . 'quote_author',
            	'type'    => 'text',
    	) );

	$bbresources->add_field( array(
    		'name'       => __( 'Sidebar Optin', 'cmb2' ),
    		'desc'       => __( 'Select a sidebar optin', 'cmb2' ),
    		'id'         => $prefix . 'optin_select',
    		'type'       => 'select',
		'show_option_none' => true,
    		'options_cb' => 'cmb2_get_optins_resource_list',
	) );

}



// Add new Resource Category taxonomy
add_action( 'init', 'create_resourcecat_hierarchical_taxonomy', 0 );
 
function create_resourcecat_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Resource Category', 'taxonomy general name' ),
    'singular_name' => _x( 'Resource Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Resource Categories' ),
    'all_items' => __( 'All Resource Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Resource Category' ), 
    'update_item' => __( 'Update Resource Category' ),
    'add_new_item' => __( 'Add New Resource Category' ),
    'new_item_name' => __( 'New Resource Category Name' ),
    'menu_name' => __( 'Resource Categories' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('resourcecategory',array('resources'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'resourcecategory' ),
  ));
 
}




/**
 * Gets a number of optin posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_optins_resource_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'optins',
        'numberposts' => 100,
	'tax_query' => array(
		array(
			'taxonomy' => 'displaystyle',
			'field'    => 'slug',
			'terms'    => 'sidebar',
		),
	),

    ) );

    $posts = get_posts( $args );

    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    }

    return $post_options;
}

/**
 * Gets 100 posts for your_post_type and displays them as options
 * @return array An array of options that matches the CMB2 options array
 */
function cmb2_get_optins_resource_list() {
    return cmb2_get_optins_resource_options( array( 'post_type' => 'optins', 'numberposts' => 100 ) );
}






?>
