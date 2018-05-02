<?php
/*
Plugin Name: Being Boss - BeingBoss.club
Plugin URI:  https://www.beingboss.club
Description: Custom PHP Functions for Being Boss
Version:     1.1.7
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


add_action( 'cmb2_admin_init', 'cmb2_bbpage_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbpage_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbpage_';

	/**
	 * Initiate the metabox
	 */
	$bbpage = new_cmb2_box( array(
		'id'            => 'bbpage_metabox',
		'title'         => __( 'Being Boss - Page Specific Options', 'cmb2' ),
		'object_types'  => array( 'page', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbpage->add_field( array(
    		'name'    => 'Header Image',
    		'desc'    => 'Upload an image or enter an URL.',
    		'id'      => $prefix . 'header_image',
    		'type'    => 'file',
    		// Optional:
    		'options' => array(
        		'url' => false, // Hide the text input for the url
    		),
    		'text'    => array(
        		'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
    		),
    		// query_args are passed to wp.media's library query.
	) );

	$bbpage->add_field( array(
    		'name' => 'Header Text Overlay',
    		'desc' => 'Text overlay for page header image (HTML-enabled)',
    		'default' => '',
    		'id' => $prefix . 'header_text',
    		'type' => 'textarea_small'
	) );

	$bbpage->add_field( array(
    		'name' => 'Hide Footer Subscribe Section',
    		'desc' => 'Check box to hide footer subscribe section',
    		'id'   => $prefix . 'hide_subscribe',
    		'type' => 'checkbox',
	) );

	$bbpage->add_field( array(
    		'name'    => 'Page Top Padding',
    		'desc'    => 'Optional padding for top of content area',
    		'default' => '0',
    		'id'      => $prefix . 'top_padding',
    		'type'    => 'text',
	) );

	$bbpage->add_field( array(
    		'name' => 'Page Specific CSS',
    		'desc' => 'Optional custom CSS for this individual page',
    		'default' => '',
    		'id' => $prefix . 'page_css',
    		'type' => 'textarea_small'
	) );

}






add_action( 'after_setup_theme', 'new_image_sizes' );
function new_image_sizes() {
    add_image_size( 'archive-thumb', 350 ); // 350 pixels wide (and unlimited height)
}




/**
 * Add User Profile Fields
 */

$extra_fields =  array( 
	array( 'bb_facebook', __( 'BB Facebook', 'rc_cucm' ), true ),
	array( 'bb_twitter', __( 'BB Twitter', 'rc_cucm' ), true ),
	array( 'bb_googleplus', __( 'BB Google+', 'rc_cucm' ), true ),
	array( 'bb_linkedin', __( 'BB LinkedIn', 'rc_cucm' ), false ),
	array( 'bb_pinterest', __( 'BB Pinterest', 'rc_cucm' ), false ),
	array( 'bb_instagram', __( 'BB Instagram', 'rc_cucm' ), false ),
	array( 'bb_youtube', __( 'BB Youtube', 'rc_cucm' ), false ),
	array( 'bb_website', __( 'BB Website', 'rc_cucm' ), false ),
	array( 'bb_customavatar', __( 'BB Custom Avatar', 'rc_cucm' ), false )
);

// Use the user_contactmethods to add new fields
add_filter( 'user_contactmethods', 'rc_add_user_contactmethods' );


/**
 * Add custom users custom contact methods
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function rc_add_user_contactmethods( $user_contactmethods ) {

	// Get fields
	global $extra_fields;
	
	// Display each fields
	foreach( $extra_fields as $field ) {
		if ( !isset( $contactmethods[ $field[0] ] ) )
    		$user_contactmethods[ $field[0] ] = $field[1];
	}

    // Returns the contact methods
    return $user_contactmethods;
}










/**
 * Add the field to the checkout
 **/
add_action( 'woocommerce_after_order_notes', 'wordimpress_custom_checkout_field' );
 
function wordimpress_custom_checkout_field( $checkout ) {
 
 //Check if Book in Cart (UPDATE WITH YOUR PRODUCT ID)
 $book_in_cart = wordimpress_is_conditional_product_in_cart( array(8540,9118) );
 
 //Book is in cart so show additional fields
 if ( $book_in_cart === true ) {
 echo '<div id="my_custom_checkout_field"><h3>' . __( 'New Orleans Terms & Info' ) . '</h3><p style="margin: 0 0 8px;">Do you agree to the <a href="https://www.dropbox.com/s/czc6dcpcb65y2ws/2017%20Being%20Boss%20NOLA%20Waiver%20and%20Indemnification%20Agreement.pdf?dl=0" target="_blank" style="color: #63ceca;">terms and conditions</a> for the NOLA vacation?</p>';
 
 woocommerce_form_field( 'inscription_checkbox', array(
 'required' => true,
 'type'  => 'checkbox',
 'class' => array( 'inscription-checkbox form-row-wide' ),
 'label' => __( 'Yes, I agree.' ),
 ), $checkout->get_value( 'inscription_checkbox' ) );

woocommerce_form_field( 'inscription_textbox', array(
'required' => true,
 'type'  => 'text',
 'class' => array( 'inscription-text form-row-wide' ),
 'label' => __( 'Do you have any food allergies? If so, please list them here. Otherwise, simply put no.' ),
 ), $checkout->get_value( 'inscription_textbox' ) );
 
 echo '</div>';
 }
 
}
 
/**
 * Check if Conditional Product is In cart
 *
 * @param $product_id
 *
 * @return bool
 */
function wordimpress_is_conditional_product_in_cart( $product_id ) {
 //Check to see if user has product in cart
 global $woocommerce;
 
 //flag no book in cart
 $book_in_cart = false;
 
 foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
 $_product = $values['data'];
 
 if ( in_array($_product->id, $product_id) ) {
 //book is in cart!
 $book_in_cart = true;
 
 }
 }
 
 return $book_in_cart;
 
}


/**
 * Update the order meta with field value
 **/
add_action( 'woocommerce_checkout_update_order_meta', 'wordimpress_custom_checkout_field_update_order_meta' );
 
function wordimpress_custom_checkout_field_update_order_meta( $order_id ) {
 
 //check if $_POST has our custom fields
 if ( $_POST['inscription_checkbox'] ) {
 //It does: update post meta for this order
 update_post_meta( $order_id, 'NOLA Terms', esc_attr( $_POST['inscription_checkbox'] ) );
 }
 if ( $_POST['inscription_textbox'] ) {
 update_post_meta( $order_id, 'Allergy Information', esc_attr( $_POST['inscription_textbox'] ) );
 }
}




/**
 * Add the field to order emails
 **/
add_filter( 'woocommerce_email_order_meta_keys', 'wordimpress_checkout_field_order_meta_keys' );
 
function wordimpress_checkout_field_order_meta_keys( $keys ) {
 
 $keys[] = 'inscription_checkbox';
 $keys[] = 'inscription_textbox';
 
 return $keys;
}





/**
* Process the checkout
*/

add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

function my_custom_checkout_field_process() {

   $nola_in_cart = wordimpress_is_conditional_product_in_cart( array(8540,9118) );

if ( $nola_in_cart === true ) {

   // Check if set, if its not set add an error.

   if ( ! $_POST['inscription_checkbox'] )
       wc_add_notice( __( 'Please read the terms and conditions and check the box.' ), 'error' );
   if ( ! $_POST['inscription_textbox'] )
       wc_add_notice( __( 'Please fill out the Food Allergies field. Put "none" if you have none.' ), 'error' );

}
}





/**
* Display custom post count in User dashboard list
*/
add_action('manage_users_columns','yoursite_manage_users_columns');
function yoursite_manage_users_columns($column_headers) {
    unset($column_headers['posts']);
    $column_headers['custom_posts'] = 'Assets';
    return $column_headers;
}

add_action('manage_users_custom_column','yoursite_manage_users_custom_column',10,3);
function yoursite_manage_users_custom_column($custom_column,$column_name,$user_id) {
    if ($column_name=='custom_posts') {
        $counts = _yoursite_get_author_post_type_counts();
        $custom_column = array();
        if (isset($counts[$user_id]) && is_array($counts[$user_id]))
            foreach($counts[$user_id] as $count) {
                $link = admin_url() . "edit.php?post_type=" . $count['type']. "&author=".$user_id;
                // admin_url() . "edit.php?author=" . $user->ID;
                $custom_column[] = "\t<tr><th><a href={$link}>{$count['label']}</a></th><td>{$count['count']}</td></tr>";
            }
        $custom_column = implode("\n",$custom_column);
        if (empty($custom_column))
            $custom_column = "<th>[none]</th>";
        $custom_column = "<table>\n{$custom_column}\n</table>";
    }
    return $custom_column;
}

function _yoursite_get_author_post_type_counts() {
    static $counts;
    if (!isset($counts)) {
        global $wpdb;
        global $wp_post_types;
        $sql = <<<SQL
        SELECT
        post_type,
        post_author,
        COUNT(*) AS post_count
        FROM
        {$wpdb->posts}
        WHERE 1=1
        AND post_type IN ('post','articles', 'events', 'library', 'optins', 'resources', 'sponsors', 'webinars', 'listing')
        AND post_status IN ('publish','pending', 'draft')
        GROUP BY
        post_type,
        post_author
SQL;
        $posts = $wpdb->get_results($sql);
        foreach($posts as $post) {
            $post_type_object = $wp_post_types[$post_type = $post->post_type];
            if (!empty($post_type_object->label))
                $label = $post_type_object->label;
            else if (!empty($post_type_object->labels->name))
                $label = $post_type_object->labels->name;
            else
                $label = ucfirst(str_replace(array('-','_'),' ',$post_type));
            if (!isset($counts[$post_author = $post->post_author]))
                $counts[$post_author] = array();
            $counts[$post_author][] = array(
                'label' => $label,
                'count' => $post->post_count,
                'type' => $post->post_type,
                );
        }
    }
    return $counts;
}












/**
* Include Custom Post Types on author pages
*/

function author_custom_post_types( $query ) {
  if( is_author() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'articles'
		));
	  return $query;
	}
}
add_filter( 'pre_get_posts', 'author_custom_post_types' );









function my_search_filter($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ($query->is_search) {
      $query->set('post_type', array( 'post', 'articles' ) );
    }
  }
}
add_action('pre_get_posts','my_search_filter');






add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );




/**
* Add the images to the special submenu -> the submenu items with the parent with 'pt-special-dropdown' class.
*
* @param array $items List of menu objects (WP_Post).
* @param array $args  Array of menu settings.
* @return array
*/
function add_images_to_special_submenu( $items ) {
	$special_menu_parent_ids = array();

	foreach ( $items as $item ) {
		if ( in_array( 'bb-special-dropdown', $item->classes, true ) && isset( $item->ID ) ) {
			$special_menu_parent_ids[] = $item->ID;
		}

		if ( in_array( $item->menu_item_parent, $special_menu_parent_ids ) && has_post_thumbnail( $item->object_id ) ) {
			$item->title = sprintf(
				'%1$s %2$s',
				get_the_post_thumbnail( $item->object_id, 'medium', array( 'alt' => esc_attr( $item->title ) ) ),
				$item->title
			);
		}
	}

	return $items;
}

add_filter( 'wp_nav_menu_objects', 'add_images_to_special_submenu' );







// Hook Gravity Forms user registration -> Map taxomomy

    function map_taxonomy($user_id, $config, $entry, $user_pass) {

        global $wpdb;

    // Get all taxonomies
        $taxs = get_taxonomies();

    // Get all user meta
        $all_meta_for_user = get_user_meta($user_id);

    // Loop through meta data and map to taxonomies with same name as user meta key
        foreach ($all_meta_for_user as $taxonomy => $value ) {

            if (in_array ($taxonomy, $taxs) ) {         // Check if there is a Taxonomy with the same name as the Custom user meta key

            // Get term id
                $term_id = get_user_meta($user_id, $taxonomy, true);
                If (is_numeric($term_id)) {             // Check if Custom user meta is an ID

                    Echo $taxonomy.'='.$term_id.'<br>';

                // Add user to taxomomy term
                    $term = get_term( $term_id, $taxonomy );
                    $termslug = $term->slug;
                    wp_set_object_terms( $user_id, array( $termslug ), $taxonomy, false);

                }
            }
        }

    }
    add_action("gform_user_registered", "map_taxonomy", 10, 4);






/** function wpse_custom_menu_order( $menu_ord ) {
    if ( !$menu_ord ) return true;

    return array(
        'index.php', // Dashboard
        'wpengine-common', // WPEngine
        'separator1', // First separator
        'edit.php', // Posts
        'upload.php', // Media
        'link-manager.php', // Links
        'edit-comments.php', // Comments
        'edit.php?post_type=page', // Pages
        'gf_edit_forms', // Gravity Forms
        'separator2', // Second separator
        'edit.php?post_type=articles', // Articles
        'edit.php?post_type=events', // Events
        'edit.php?post_type=library', // Library
        'edit.php?post_type=resources', // Resources
        'edit.php?post_type=optins', // Optins
        'edit.php?post_type=sponsors', // Sponsors
        'edit.php?post_type=webinar', // Webinar
        'edit.php?post_type=content_block', // Content Blocks
        'edit.php?post_type=listing', // Boss Directory
        'gravityflow-inbox', // Gravity Flow
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'options-general.php', // Settings
        'separator-last', // Last separator
    );
}
add_filter( 'custom_menu_order', 'wpse_custom_menu_order', 10, 1 );
add_filter( 'menu_order', 'wpse_custom_menu_order', 10, 1 );
*/













?>
