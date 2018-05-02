<?php
/*
Plugin Name: Being Boss - Articles Post Type
Plugin URI:  https://www.beingboss.club
Description: Custom Articles post type for Being Boss
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
* Initializing the Articles custom post type
*/
 
function article_post_type() {
 
// Set UI labels for Articles post type
    $labels = array(
        'name'                => _x( 'Articles', 'Post Type General Name' ),
        'singular_name'       => _x( 'Article', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Articles' ),
        'parent_item_colon'   => __( 'Parent Article Item' ),
        'all_items'           => __( 'All Article Items' ),
        'view_item'           => __( 'View Article Item' ),
        'add_new_item'        => __( 'Add New Article Item' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Article Item' ),
        'update_item'         => __( 'Update Article Item' ),
        'search_items'        => __( 'Search Article Items' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Article post type
     
    $args = array(
        'label'               => __( 'article' ),
        'description'         => __( 'Being Boss Articles' ),
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
    register_post_type( 'articles', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'article_post_type', 0 );










// Add new Article category taxonomy
add_action( 'init', 'create_article_hierarchical_taxonomy', 0 );
 
function create_article_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Article Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Article Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Article Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Article Category' ), 
    'update_item' => __( 'Update Article Category' ),
    'add_new_item' => __( 'Add New Article Category' ),
    'new_item_name' => __( 'New Article Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('articlecategories',array('articles'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'articlecategories' ),
  ));
 
}











/**
 * Creates Resources Taxonomy
 */
function custom_resources_articles_init(){

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
  register_taxonomy( 'related-resources-articles', 'articles', $args);
}

add_action( 'init', 'custom_resources_articles_init' );




/**
 * Populates Resources Taxonomy with Resources Custom Post Type
 */
function update_resources_articles_terms($post_id) {

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
  $existing_terms = get_terms('related-resources-articles', array(
    'hide_empty' => false
    )
  );

  foreach($existing_terms as $term) {
    if ($term->description == $post_id) {
      //term already exists, so update it and we're done
      wp_update_term($term->term_id, 'related-resources-articles', array(
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
  wp_insert_term($term_title, 'related-resources-articles', array(
    'slug' => $term_slug,
    'description' => $post_id
    )
  );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_resources_articles_terms');








?>