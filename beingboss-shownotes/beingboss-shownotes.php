<?php
/*
Plugin Name: Being Boss - Shownotes
Plugin URI:  https://www.beingboss.club
Description: Custom Shownote Fields for Being Boss
Version:     1.1.2
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






add_action( 'cmb2_admin_init', 'cmb2_bbshownotes_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbshownotes_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbshownotes_';

	/**
	 * Initiate the metabox
	 */
	$bbshownotes = new_cmb2_box( array(
		'id'            => 'bbshownotes_metabox',
		'title'         => __( 'Being Boss - Shownote Details', 'cmb2' ),
		'object_types'  => array( 'post', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Soundcloud Player',
    		'desc' => 'Paste the entire Soundcloud player embed code',
    		'default' => '',
    		'id' => $prefix . 'soundcloud',
    		'type' => 'textarea_code'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Top Quote',
    		'desc' => 'Main quote for the top of the page',
    		'default' => '',
    		'id' => $prefix . 'top_quote',
    		'type' => 'textarea_small'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Top Quote Author',
    		'desc' => 'Top Quote Author',
    		'id'   => $prefix . 'quote_author',
    		'type' => 'text'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Top Quote Author Twitter Username',
    		'desc' => 'Top Quote Author Twitter Username (username only, no @ symble or full address)',
    		'id'   => $prefix . 'quote_author_twitter',
    		'type' => 'text'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Topics',
    		'desc' => 'Topics discussed in this episode',
    		'id'   => $prefix . 'topics',
    		'type' => 'wysiwyg'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Resources',
    		'desc' => 'Resources discussed in this episode',
    		'id'   => $prefix . 'resources',
    		'type' => 'wysiwyg'
	) );

	$bbshownoteguest = $bbshownotes->add_field( array(
    		'id'          => $prefix . 'morefrom',
    		'type'        => 'group',
    		'description' => __( 'The More From Guest section. Click Add Another Guest for multiple guests.', 'cmb2' ),
    		'repeatable'  => true, // use false if you want non-repeatable group
    		'options'     => array(
        		'group_title'   => __( 'Guest {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        		'add_button'    => __( 'Add Another Guest', 'cmb2' ),
        		'remove_button' => __( 'Remove Entry', 'cmb2' ),
        		'sortable'      => true, // beta
        		// 'closed'     => true, // true to have the groups closed by default
    		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$bbshownotes->add_group_field( $bbshownoteguest, array(
    		'name' => 'Guest Name',
    		'id'   => $prefix . 'guestname',
    		'type' => 'text',
	) );

	$bbshownotes->add_group_field( $bbshownoteguest, array(
    		'name' => 'Content',
    		'description' => 'The content for the More From Guest section',
    		'id'   => $prefix . 'guestinfo',
    		'type' => 'wysiwyg',
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Pinterest Images',
    		'desc' => '',
    		'id'   => $prefix . 'pinitimages',
    		'type' => 'file_list',
    		// 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
        		// 'query_args' => array( 'type' => 'image' ), // Only images attachment
    		// Optional, override default text strings
	) );

	$bbshownotes->add_field( array(
    		'name'       => __( 'Episode sponsors', 'cmb2' ),
    		'desc'       => __( 'Select all episode sponsors', 'cmb2' ),
    		'id'         => $prefix . 'sponsor_select',
    		'type'       => 'multicheck',
    		'options_cb' => 'cmb2_get_sponsors_list',
	) );

	$bbshownotes->add_field( array(
    		'name'       => __( 'Episode Optin', 'cmb2' ),
    		'desc'       => __( 'Select a fullwidth optin', 'cmb2' ),
    		'id'         => $prefix . 'optin_select',
    		'type'       => 'select',
		'show_option_none' => true,
    		'options_cb' => 'cmb2_get_optins_list',
	) );

}






/**
 *
 * @param  string  $bbshownotes_pinitimages
 * @param  string  $img_size
 */
function cmb2_bbshownotes_pinterest_images( $file_list_meta_key, $img_size ) {

    // Get the list of files
    $files = get_post_meta( get_the_ID(), $file_list_meta_key, 1 );

    echo '<div class="file-list-wrap">';
    // Loop through them and output an image
    foreach ( (array) $files as $attachment_id => $attachment_url ) {
        echo '<div class="file-list-image" style="display: inline-block;">';
        echo wp_get_attachment_image( $attachment_id, $img_size, "", ["class" => "pinterestimage"] );
        echo '</div>';
    }
    echo '</div>';
}












/**
 * Gets a number of sponsor posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'sponsors',
        'numberposts' => 100,
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
function cmb2_get_sponsors_list() {
    return cmb2_get_post_options( array( 'post_type' => 'sponsors', 'numberposts' => 100 ) );
}










/*
* Initializing the Opt-Ins custom post type
*/
 
function shownote_optins_post_type() {
 
// Set UI labels for Optins post type
    $labels = array(
        'name'                => _x( 'Optins', 'Post Type General Name' ),
        'singular_name'       => _x( 'Optin', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Optins' ),
        'parent_item_colon'   => __( 'Parent Optin' ),
        'all_items'           => __( 'All Optins' ),
        'view_item'           => __( 'View Optin' ),
        'add_new_item'        => __( 'Add New Optin' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Optin' ),
        'update_item'         => __( 'Update Optin' ),
        'search_items'        => __( 'Search Optins' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Optins post type
     
    $args = array(
        'label'               => __( 'optins' ),
        'description'         => __( 'Being Boss Optins' ),
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
    register_post_type( 'optins', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'shownote_optins_post_type', 0 );



// Add new Optins Category taxonomy
add_action( 'init', 'create_optincat_hierarchical_taxonomy', 0 );
 
function create_optincat_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Display Style', 'taxonomy general name' ),
    'singular_name' => _x( 'Display Style', 'taxonomy singular name' ),
    'search_items' =>  __( 'Display Styles' ),
    'all_items' => __( 'All Display Styles' ),
    'parent_item' => __( 'Parent Style' ),
    'parent_item_colon' => __( 'Parent Style:' ),
    'edit_item' => __( 'Edit Display Style' ), 
    'update_item' => __( 'Update Display Style' ),
    'add_new_item' => __( 'Add New Display Style' ),
    'new_item_name' => __( 'New Display Style Name' ),
    'menu_name' => __( 'Display Styles' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('displaystyle',array('optins'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'displaystyle' ),
  ));
 
}





/**
 * Gets a number of optin posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_optins_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'optins',
        'numberposts' => 100,
	'tax_query' => array(
		array(
			'taxonomy' => 'displaystyle',
			'field'    => 'slug',
			'terms'    => 'fullwidth',
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
function cmb2_get_optins_list() {
    return cmb2_get_optins_post_options( array( 'post_type' => 'optins', 'numberposts' => 100 ) );
}





/**
 * Creates Resources Taxonomy
 */
function custom_resources_init(){

  //set some options for our new custom taxonomy
  $args = array(
    'label' => __( 'Related Resources' ),
    'hierarchical' => true,
    'capabilities' => array(
      // allow anyone editing posts to assign terms
      'assign_terms' => 'edit_posts',
      /* 
      * but you probably don't want anyone except 
      * admins messing with what gets auto-generated! 
      */
      'edit_terms' => 'administrator'
    )
  );

  /* 
  * create the custom taxonomy and attach it to
  * custom post type A 
  */
  register_taxonomy( 'related-resources', 'post', $args);
}

add_action( 'init', 'custom_resources_init' );




/**
 * Populates Resources Taxonomy with Resources Custom Post Type
 */
function update_resources_terms($post_id) {

  // only update terms if it's a post-type-B post
  if ( 'resources' != get_post_type($post_id)) {
    return;
  }

  // don't create or update terms for system generated posts
  if (get_post_status($post_id) == 'auto-draft') {
    return;
  }
    
  /*
  * Grab the post title and slug to use as the new 
  * or updated term name and slug
  */
  $term_title = get_the_title($post_id);
  $term_slug = get_post( $post_id )->post_name;

  /*
  * Check if a corresponding term already exists by comparing 
  * the post ID to all existing term descriptions. 
  */
  $existing_terms = get_terms('related-resources', array(
    'hide_empty' => false
    )
  );

  foreach($existing_terms as $term) {
    if ($term->description == $post_id) {
      //term already exists, so update it and we're done
      wp_update_term($term->term_id, 'related-resources', array(
        'name' => $term_title,
        'slug' => $term_slug
        )
      );
      return;
    }
  }

  /* 
  * If we didn't find a match above, this is a new post, 
  * so create a new term.
  */
  wp_insert_term($term_title, 'related-resources', array(
    'slug' => $term_slug,
    'description' => $post_id
    )
  );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_resources_terms');




?>