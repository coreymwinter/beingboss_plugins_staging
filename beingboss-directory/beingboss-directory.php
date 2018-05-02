<?php
/*
Plugin Name: Being Boss - Directory Post Type
Plugin URI:  https://www.beingboss.club
Description: Custom Certified Boss Directory post type for Being Boss
Version:     1.0.0
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


class Boss_Directory_Plugin {
    public function __construct() {
		// Hook into the admin menu
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
	}
	
	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = 'Boss Directory Settings';
		$menu_title = 'Settings';
		$capability = 'manage_options';
		$slug = 'boss_directory';
		$callback = array( $this, 'plugin_settings_page_content' );
		$icon = 'dashicons-admin-plugins';
		$position = 99;

		add_submenu_page( 'edit.php?post_type=listing', $page_title, $menu_title, $capability, $slug, $callback );
	}
	
	public function plugin_settings_page_content() {
		echo 'Hello World!';
	}
}
new Boss_Directory_Plugin();






/*
* Initializing the Directory custom post type
*/
 
function directory_post_type() {
 
// Set UI labels for Directory post type
    $labels = array(
        'name'                => _x( 'Directory Listings', 'Post Type General Name' ),
        'singular_name'       => _x( 'Listing', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Boss Directory' ),
        'parent_item_colon'   => __( 'Parent Listing' ),
        'all_items'           => __( 'All Listings' ),
        'view_item'           => __( 'View Listing' ),
        'add_new_item'        => __( 'Add New Listing' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Listing' ),
        'update_item'         => __( 'Update Listing' ),
        'search_items'        => __( 'Search Listings' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Directory post type
     
    $args = array(
        'label'               => __( 'listing' ),
        'description'         => __( 'Being Boss Directory Listings' ),
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
        'menu_position'       => 55,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
		'menu_icon'			  => 'https://beingboss.club/wp-content/themes/beingboss2018/img/Icon_Resource_Blue.png'
    );
     
    // Registering your Custom Post Type
    register_post_type( 'listing', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'directory_post_type', 0 );





add_action( 'admin_init', 'add_admin_menu_separator' );
function add_admin_menu_separator( $position ) {

	global $menu;

	$menu[ $position ] = array(
		0	=>	'',
		1	=>	'read',
		2	=>	'separator' . $position,
		3	=>	'',
		4	=>	'wp-menu-separator'
	);

}

add_action( 'admin_menu', 'set_admin_menu_separator' );
function set_admin_menu_separator() {
	do_action( 'admin_init', 52 );
} // end set_admin_menu_separator





add_action( 'cmb2_init', 'cmb2_bbdirectory_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbdirectory_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbdirectory_';

	/**
	 * Initiate the metabox
	 */
	$bbd_details = new_cmb2_box( array(
		'id'            => 'bbd_details_metabox',
		'title'         => __( 'Listing - Business Details', 'cmb2' ),
		'object_types'  => array( 'listing', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


		$bbd_details->add_field( array(
	    		'name' => 'Short Business Description',
	    		'desc' => '',
	    		'default' => '',
	    		'id' => $prefix . 'short_description',
	    		'type' => 'textarea_small'
		) );

		$bbd_details->add_field( array(
	    		'name'    => 'Dream Customer',
	    		'desc'    => '',
	    		'default' => '',
	    		'id'      => $prefix . 'dream_customer',
	    		'type'    => 'textarea_small',
		) );

		$bbd_details->add_field( array(
	    		'name'    => 'Positioning Statement',
	    		'desc'    => '',
	    		'default' => '',
	    		'id'      => $prefix . 'positioning_statement',
	    		'type'    => 'textarea_small',
		) );



	$bbd_media = new_cmb2_box( array(
		'id'            => 'bbd_media_metabox',
		'title'         => __( 'Listing - Media', 'cmb2' ),
		'object_types'  => array( 'listing', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


		$bbd_media->add_field( array(
	    		'name' => 'Business Logo',
	    		'desc' => '',
	    		'default' => '',
	    		'id' => $prefix . 'business_logo',
	    		'type' => 'file',
		) );



	$bbd_questions = new_cmb2_box( array(
		'id'            => 'bbd_questions_metabox',
		'title'         => __( 'Listing - Questions', 'cmb2' ),
		'object_types'  => array( 'listing', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


		$bbd_questions->add_field( array(
	    		'name'    => 'What Makes Your Feel Most Boss?',
	    		'desc'    => '',
	    		'default' => '',
	    		'id'      => $prefix . 'feel_boss',
	    		'type'    => 'textarea_small',
		) );

		$bbd_questions->add_field( array(
	    		'name'    => 'What is the story about how you started your business?',
	    		'desc'    => '',
	    		'default' => '',
	    		'id'      => $prefix . 'your_story',
	    		'type'    => 'textarea_small',
		) );

		$bbd_questions->add_field( array(
	    		'name'    => 'Who are some of your Hot Shit 200?',
	    		'desc'    => '',
	    		'default' => '',
	    		'id'      => $prefix . 'hot_shit',
	    		'type'    => 'textarea_small',
		) );

		$bbd_questions->add_field( array(
    		'name'       => __( 'What is your favorite Being Boss episode?', 'cmb2' ),
    		'desc'       => __( '', 'cmb2' ),
    		'id'         => $prefix . 'episode_select',
    		'type'       => 'select',
			'show_option_none' => true,
    		'options_cb' => 'cmb2_get_shownote_list',
		) );

		$bbd_questions->add_field( array(
	    		'name'    => 'Why is that episode your favorite?',
	    		'desc'    => '',
	    		'default' => '',
	    		'id'      => $prefix . 'favorite_episode_explaination',
	    		'type'    => 'textarea_small',
		) );
}












add_action( 'cmb2_init', 'cmb2_bbdirectory_private_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbdirectory_private_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbdp_';

	/**
	 * Initiate the metabox
	 */
	$bbdp = new_cmb2_box( array(
		'id'            => 'bbdirectory_private_mb',
		'title'         => __( 'Private Listing Details', 'cmb2' ),
		'object_types'  => array( 'listing', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


	$bbdp->add_field( array(
    		'name' => 'Header Text Overlay',
    		'desc' => 'Text overlay for page header image (HTML-enabled)',
    		'default' => '',
    		'id' => $prefix . 'header_text',
    		'type' => 'textarea_small'
	) );


	$bbdp->add_field( array(
    		'name'    => 'Webinar Month',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'month',
    		'type'    => 'text',
	) );


}













// Add new Directory category taxonomy
add_action( 'init', 'create_directory_hierarchical_taxonomy', 0 );
 
function create_directory_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Directory Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Directory Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Directory Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Directory Category' ), 
    'update_item' => __( 'Update Directory Category' ),
    'add_new_item' => __( 'Add New Directory Category' ),
    'new_item_name' => __( 'New Directory Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('directorycategories',array('listing'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'directorycategories' ),
  ));
 
}


// Add new Directory tier taxonomy
add_action( 'init', 'create_directory_tier_taxonomy', 0 );
 
function create_directory_tier_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Directory Tiers', 'taxonomy general name' ),
    'singular_name' => _x( 'Directory Tier', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Tiers' ),
    'all_items' => __( 'All Directory Tiers' ),
    'parent_item' => __( 'Parent Tier' ),
    'parent_item_colon' => __( 'Parent Tier:' ),
    'edit_item' => __( 'Edit Directory Tier' ), 
    'update_item' => __( 'Update Directory Tier' ),
    'add_new_item' => __( 'Add New Directory Tier' ),
    'new_item_name' => __( 'New Directory Tier Name' ),
    'menu_name' => __( 'Tiers' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('directorytiers',array('listing'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'directorytiers' ),
  ));
 
}








add_shortcode( 'cmb-form', 'cmb2_do_frontend_form_shortcode' );
/**
 * Shortcode to display a CMB2 form for a post ID.
 * @param  array  $atts Shortcode attributes
 * @return string       Form HTML markup
 */
function cmb2_do_frontend_form_shortcode( $atts = array() ) {
	global $post;

	/**
	 * Depending on your setup, check if the user has permissions to edit_posts
	 */
	if ( ! current_user_can( 'edit_posts' ) ) {
		return __( 'You do not have permissions to edit this post.', 'lang_domain' );
	}

	/**
	 * Make sure a WordPress post ID is set.
	 * We'll default to the current post/page
	 */
	if ( ! isset( $atts['post_id'] ) ) {
		$atts['post_id'] = $post->ID;
	}

	// If no metabox id is set, yell about it
	if ( empty( $atts['id'] ) ) {
		return __( "Please add an 'id' attribute to specify the CMB2 form to display.", 'lang_domain' );
	}

	$metabox_id = esc_attr( $atts['id'] );
	$object_id = absint( $atts['post_id'] );
	// Get our form
	$form = cmb2_get_metabox_form( $metabox_id, $object_id );

	return $form;
}




add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
      show_admin_bar(false);
    }
}











add_action( 'cmb2_init', 'cmb2_bbuser_directory_metaboxes' );
function cmb2_bbuser_directory_metaboxes() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bbuser_directory_';

    /**
     * Initiate the metabox
     */
    $bbuser_directory = new_cmb2_box( array(
        'id'            => 'bbuser_directory_metabox',
        'title'         => __( 'Being Boss - Directory Details', 'cmb2' ),
        'object_types'  => array( 'user', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
        'new_user_section' => 'add-new-user'
    ) );

	$bbuser_directory->add_field( array(
	    'name'    => 'Directory - User Level',
	    'desc'    => '',
	    'id'      => $prefix . 'level',
	    'type'    => 'select',
	    'options' => array(
	    	'registered' => 'Registered',
	    	'applied' => 'Applied',
	    	'inactive' => 'Inactive',
	        'low' => 'Low',
	        'mid' => 'Mid',
	        'high' => 'High',
	    ),
	) );

}









/**
 * Gets a number of shownote posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_shownote_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'post',
        'numberposts' => 500,
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
 * Gets for your_post_type and displays them as options
 * @return array An array of options that matches the CMB2 options array
 */
function cmb2_get_shownote_list() {
    return cmb2_get_shownote_options( array( 'post_type' => 'post', 'numberposts' => 500 ) );
}












function directory_update_post_tier( $user_id ) {

        $info = get_user_meta($user_id, 'bbuser_directory_level', true);

        //get all items of that user
        $args = array(
                'author' => $user_id,
                'post_type' => 'listing',
                'post_status' => array('publish', 'draft')
        );

        $items = get_posts($args); 

        foreach ($items as $item) {

        	$item_status = get_post_status($item->ID);

        	if ($info == 'low') {
	        	wp_set_post_terms($item->ID, array( 219 ), 'directorytiers');
	        	if ($item_status == 'draft') {
	        		wp_publish_post( $item->ID );
	        	}
	        }

	        if ($info == 'mid') {
	        	wp_set_post_terms($item->ID, array( 220 ), 'directorytiers');
	        	if ($item_status == 'draft') {
	        		wp_publish_post( $item->ID );
	        	}
	        }

	        if ($info == 'high') {
	        	wp_set_post_terms($item->ID, array( 221 ), 'directorytiers');
	        	if ($item_status == 'draft') {
	        		wp_publish_post( $item->ID );
	        	}
	        }

	        if ($info == 'inactive') {
	        	wp_set_post_terms($item->ID, array( 222 ), 'directorytiers');
	        	wp_update_post(array(
			        'ID'    =>  $item->ID,
			        'post_status'   =>  'draft'
			    ));
	        }
        }
}
// add_action( 'edit_user_profile_update', 'directory_update_post_tier' );
// add_action( 'personal_options_update', 'directory_update_post_tier' );
add_action( 'profile_update', 'directory_update_post_tier' );










?>