<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Subpage Header Code
require_once('subpage-header.php');

//* Set Localization (do not remove)
load_child_theme_textdomain( 'parallax', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'parallax' ) );

//* Add Image upload to WordPress Theme Customizer
add_action( 'customize_register', 'parallax_customizer' );
function parallax_customizer(){
	require_once( get_stylesheet_directory() . '/lib/customize.php' );
}

//* Include Section Image CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

global $blogurl;
$blogurl = get_stylesheet_directory_uri();
register_nav_menu('shop-for-caster',__( 'Shop for casters' ));
//Dequeue Styles
function project_dequeue_unnecessary_styles() {
	if (is_front_page()) {
		if (!is_user_logged_in()) {
		    wp_dequeue_style( 'megamenu-fontawesome' );
		    wp_deregister_style( 'megamenu-fontawesome' );
		    wp_dequeue_style( 'megamenu-fontawesome5' );
		    wp_deregister_style( 'megamenu-fontawesome5' );
		    wp_dequeue_style( 'font-awesome' );
		    wp_deregister_style( 'font-awesome' );
		    wp_dequeue_style( 'wc-block-style' );
		    wp_deregister_style( 'wc-block-style' );
		    wp_dequeue_style( 'jquery-magnificpopup' );
		    wp_deregister_style( 'jquery-magnificpopup' );
		    wp_dequeue_style( 'wclinks2p_style' );
		    wp_deregister_style( 'wclinks2p_style' );
		    wp_dequeue_style( 'pac-styles' );
		    wp_deregister_style( 'pac-styles' );
		    wp_dequeue_style( 'pac-layout-styles' );
		    wp_deregister_style( 'pac-layout-styles' );
		    wp_dequeue_style( 'quote-request-style' );
		    wp_deregister_style( 'quote-request-style' );
		    wp_dequeue_style( 'wpsl-styles' );
		    wp_deregister_style( 'wpsl-styles' );
		    // wp_dequeue_style( 'dashicons' );
		    // wp_deregister_style( 'dashicons' );
		    wp_dequeue_style( 'prdctfltr-main-css' );
		    wp_deregister_style( 'prdctfltr-main-css' );
		    wp_dequeue_style( 'prdctfltr-scrollbar-css' );
		    wp_deregister_style( 'prdctfltr-scrollbar-css' );
		    wp_dequeue_style( 'jetpack_css' );
		    wp_deregister_style( 'jetpack_css' );
		    // wp_dequeue_style( 'thrive-framework' );
		    // wp_deregister_style( 'thrive-framework' );
		}	
	}
}
add_action( 'wp_print_styles', 'project_dequeue_unnecessary_styles' );

// Dequeue JavaScripts
function project_dequeue_unnecessary_scripts() {
    wp_dequeue_script( 'modernizr-js' );
        wp_deregister_script( 'modernizr-js' );
    // wp_dequeue_script( 'project-js' );
    //     wp_deregister_script( 'project-js' );
}
add_action( 'wp_print_scripts', 'project_dequeue_unnecessary_scripts' );


//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles' );
function parallax_enqueue_scripts_styles() {
	// Styles
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/css/allstyles.css', array() );
	wp_enqueue_style( 'hs-custom', get_stylesheet_directory_uri() . '/css/cstm-style.css?'.time() );

	if(is_front_page()){
		wp_enqueue_style( 'owl', get_stylesheet_directory_uri() . '/css/owl.carousel.min.css' );
	}

	// Scripts
	wp_enqueue_script( 'owl', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array('jquery') );
	
}

// Removes Query Strings from scripts and styles
function remove_script_version( $src ){
  if ( strpos( $src, 'uploads/bb-plugin' ) !== false || strpos( $src, 'uploads/bb-theme' ) !== false ) {
    return $src;
  }
  else {
    $parts = explode( '?ver', $src );
    return $parts[0];
  }
}
//add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
//add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );


//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Reposition the primary navigation menu
//remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Add Search to Primary Nav
//add_filter( 'genesis_header', 'genesis_search_primary_nav_menu', 10 );
function genesis_search_primary_nav_menu( $menu ){
    locate_template( array( 'searchform-header.php' ), true );
}

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'breadcrumb',
	'footer-widgets',
	'footer',
) );

// Add Read More Link to Excerpts
add_filter('excerpt_more', 'get_read_more_link');
add_filter( 'the_content_more_link', 'get_read_more_link' );
function get_read_more_link() {
   return '...&nbsp;<a class="readmore" href="' . get_permalink() . '">Read&nbsp;More &raquo;</a>';
}

//* Add support for 4-column footer widgets
add_theme_support( 'genesis-footer-widgets', 0 );

//* Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
	$post_info = '[post_date] [post_comments] [post_edit]';
	return $post_info;
}

//* Custom Breadcrumb Hook 
function breadcrumb_hook() {
	do_action('breadcrumb_hook');
}

//* Remove breadcrumbs and reposition them
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'breadcrumb_hook', 'genesis_do_breadcrumbs', 12 );

// Modify Breadcrumbs Args
add_filter( 'genesis_breadcrumb_args', 'malcolm_breadcrumb_args' );
function malcolm_breadcrumb_args( $args ) {
	$args['prefix'] = '<div class="breadcrumbs"><div class="wrap">';
	$args['suffix'] = '</div></div>';
	$args['sep'] = ' <span class="bread-sep">></span> ';
	$args['heirarchial_attachments'] = true;
	$args['heirarchial_categories'] = true;
	$args['display'] = true;
	$args['labels']['prefix'] = '';
    return $args;
}

// Blog Widgets
genesis_register_sidebar( array(
	'id'			=> 'blog-sidebar',
	'name'			=> __( 'Blog Widgets', 'thrive' ),
	'description'	=> __( 'This is latest news widget', 'thrive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'compare-sidebar',
	'name'			=> __( 'Compare Widgets', 'thrive' ),
	'description'	=> __( 'This is compare widget', 'thrive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'filter-sidebar',
	'name'			=> __( 'Filter Widgets', 'thrive' ),
	'description'	=> __( 'This is filter widget', 'thrive' ),
) );

// Add Header Links Widget to Header
//add_action( 'genesis_before', 'header_widget', 1 );
	function header_widget() {
	if (is_active_sidebar( 'header-links' ) ) {
 	genesis_widget_area( 'header-links', array(
		'before' => '<div class="header-links">',
		'after'  => '</div>',
	) );
}}

// Unregister unused sidebar
//unregister_sidebar( 'header-right' );

// Previous / Next Post Navigation Filter For Genesis Pagination
add_filter( 'genesis_prev_link_text', 'gt_review_prev_link_text' );
function gt_review_prev_link_text() {
        $prevlink = '&laquo;';
        return $prevlink;
}
add_filter( 'genesis_next_link_text', 'gt_review_next_link_text' );
function gt_review_next_link_text() {
        $nextlink = '&raquo;';
        return $nextlink;
}

/* Subpage Header Backgrounds - Utilizes: Featured Images & Advanced Custom Fields Repeater Fields */

// AFC Repeater Setup - NOTE: Set Image Return Value to ID
// Row Field Name:
$rows = '';
$rows = get_field('subpage_header_backgrounds', 5);
// Counts the rows and selects a random row


$row_count = count((array)$rows);

$i = rand(0, $row_count - 1);
// Set Image size to be returned
$image_size = 'subpage-header';
// Get Image ID from the random row
$image_id = $rows[ $i ]['background_image'];
// Use Image ID to get Image Array
$image_array = wp_get_attachment_image_src($image_id, $image_size);
// Set "Default BG" to first value of the Image Array. $image_array[0] = URL;
$default_bg = $image_array[0]; 


// Custom function for getting background images
function custom_background_image($postID = "") {
	// Variables
	global $default_bg;
	global $postID;
	global $blog_slug;
	
	$currentID = get_the_ID();
	$blogID = get_option( 'page_for_posts');
	$parentID = wp_get_post_parent_id( $currentID );

	// is_home detects if you're on the blog page- must be set in admin area
	if( is_home() ) {
		$currentID = $blogID;
	} 
	// Else if post page, set ID to BlogID.
	elseif( is_home() || is_single() || is_archive() || is_search() ) {
		$currentID = $blogID;
	}

	// Try to get custom background based on current page/post
	$currentBackground = wp_get_attachment_image_src(get_post_thumbnail_id($currentID), 'subpage-header');
	//Current page/post has no custom background loaded
	if(!$currentBackground) {
		// Find blog ID
		$blog_page = get_page_by_path($blog_slug, OBJECT, 'page');
		if ($blog_page) {
			$blogID = $blogID;
			$currentID = $blogID;
		}
		// Else if post page, set ID to BlogID.
		elseif(is_single() || is_archive()) {
			$currentID = $blogID; 
		}

		// Current page has a parent
		if($parentID) {
			// Try to get parents custom background
			$parent_background = wp_get_attachment_image_src(get_post_thumbnail_id($parentID), 'subpage-header');
			// Set parent background if it exists
			if($parent_background) {
				$background_image = $parent_background[0];
			}
			// Set default background
			else {
				$background_image = $default_bg;
			}
		}
		// NO parent or no parent background: set default bg.
		else {
			$background_image = $default_bg;
		}
	}
	// Current Page has a custom background: use that
	else {
		$background_image = $currentBackground[0];
	}
	return $background_image;
}

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav', 12 );

// Add Additional Image Sizes
add_image_size( 'genesis-post-thumbnail', 163, 108, true );
add_image_size( 'subpage-header', 1600, 162, true );
add_image_size( 'news-thumb', 260, 150, false );
add_image_size( 'news-full', 800, 300, false );
add_image_size( 'sidebar-thumb', 200, 150, false );
add_image_size( 'mailchimp', 564, 9999, false );
add_image_size( 'amp', 600, 9999, false  );


// Gravity Forms confirmation anchor on all forms
add_filter( 'gform_confirmation_anchor', '__return_true' );


// Button Shortcode
// Usage: [button url="https://www.google.com"] Button Shortcode [/button]
function button_shortcode($atts, $content = null) {
  extract( shortcode_atts( array(
	  'url' => '#',
	  'target' => '_self',
	  'onclick' => '',

  ), $atts ) 
);
return '<a target="' . $target . '" href="' . $url . '" class="button" onClick="' . $onclick . '"><span>' . do_shortcode($content) . '</span></a>';
}
add_shortcode('button', 'button_shortcode');

// Link Shortcode
// Usage: [link url=”tel:1-817-447-9194″ onClick=”onClick=”ga(‘send’, ‘event’, { eventCategory: ‘Click to Call’, eventAction: ‘Clicked Phone Number’, eventLabel: ‘Header Number’});”]
function link_shortcode($atts, $content = null) {
  extract( shortcode_atts( array(
	  'url' => '#',
	  'target' => '_self',
	  'onclick' => '',
  ), $atts ) 
);
return '<a target="' . $target . '" href="' . $url . '" onClick="' . $onclick . '">' . do_shortcode($content) . '</a>';
}
add_shortcode('link', 'link_shortcode');

//* Declare WooCommerce support
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

// Advance Custom field for Scheme Markups will be output under wphead tag
add_action('wp_head', 'add_scripts_to_wphead');
function add_scripts_to_wphead() {
	if( get_field('custom_javascript') ):	
		echo get_field('custom_javascript', 5);
	endif;
}

// Run shortcodes in Text Widgets
add_filter('widget_text', 'do_shortcode');


//Removing unused Default Wordpress Emoji Script - Performance Enhancer
function disable_emoji_dequeue_script() {
    wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'disable_emoji_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Removes Emoji Scripts 
add_action('init', 'remheadlink');
function remheadlink() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

// Add "nav-primary" class to Main Menu as this gets removed when we reposition the menu inside header/widget area
add_filter( 'genesis_attr_nav-header', 'thrive_custom_nav_id' );
function thrive_custom_nav_id( $attributes ) {
 	$attributes['class'] = 'nav-primary';
 	return $attributes;
}

//Sets the number of revisions for all post types
add_filter( 'wp_revisions_to_keep', 'revisions_count', 10, 2 );
function revisions_count( $num, $post ) {
	$num = 3;
    return $num;
}

// Enable Featured Images in RSS Feed and apply Custom image size so it doesn't generate large images in emails
function featuredtoRSS($content) {
global $post;
if ( has_post_thumbnail( $post->ID ) ){
$content = '<div>' . get_the_post_thumbnail( $post->ID, 'mailchimp', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
}
return $content;
}
 
add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

/* 
 * Dequeue Gutenberg-hooked CSS file `wp-block-library.css` file from `wp_head()`
 *
 * @author Thrive Agency
 * @since  12182018
 * @uses   wp_dequeue_style
 */
add_action( 'wp_enqueue_scripts', function() {
  wp_dequeue_style( 'wp-block-library' );
});


remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


function insertAfterShopProductTitle()
{
    global $product;
	$max_length = 50;
	$i=0;
    $product_instance = wc_get_product($product->id);
    $product_short_description = $product_instance->get_short_description();
    if (strlen($product_short_description) > $max_length)
	{
	    $offset = ($max_length - 3) - strlen($product_short_description);
	    $product_short_description = substr($product_short_description, 0, strrpos($product_short_description, ' ', $offset));
	}

	$productAttr = array();

	if (get_the_category_by_ID($product->category_ids[0]) === 'Casters') {
		$productAttr = array(
			'Caster Type'=>'pa_caster-type',
			'Tread Width'=>'pa_wheel-tread-width',
			'Wheel Diameter'=>'pa_wheel-diameter',
			'Wheel Type'=>'pa_wheel-type',
			'Capacity'=>'pa_load-capacity',
			'Finish'=>'pa_caster-finish'
		);
	}
	if (get_the_category_by_ID($product->category_ids[0]) === 'Wheels') {
		$productAttr = array(
			'Wheel Color'=>'pa_wheel-color',
			'Tread Width'=>'pa_wheel-tread-width',
			'Wheel Diameter'=>'pa_wheel-diameter',
			'Wheel Type'=>'pa_wheel-type',
			'Capacity'=>'pa_load-capacity',
			'Bearing Type'=>'pa_bearing-type'
		);
	}
	echo '<div class="'.(is_front_page() ? 'home-featured-product' : 'product-list-withattr' ).'"><div class="short-des">'.$product_short_description.'</div> <ul>';
	foreach ($productAttr as $productAttrKey => $productAttrValue){
		if($i==3 && is_front_page()) break;

			if ($productAttrValue == 'pa_wheel-type'){
				$productAttrValue2 = (empty($product->get_attribute('pa_wheel-type')) ? $product->get_attribute('pa_wheel-material') : $product->get_attribute('pa_wheel-type'));
			}

			if ($productAttrValue != 'pa_wheel-type')
				{
					$productAttrValue2 = ( empty($product->get_attribute($productAttrValue)) ? '-' : $product->get_attribute($productAttrValue) );
				}
			$productAttrValue2 = ( empty($productAttrValue2) ? '-' : $productAttrValue2 );
			echo '<li> <strong>'.$productAttrKey.'</strong><span>'.$productAttrValue2.'</span></li>';
			$i++;
     }
     echo '</ul><div class="p-bottom-btns"><a></a> <a href="'.get_permalink($product->id).'" class="p-detail-btn">View Detail</a></div>';
    echo '</div>';
 }
add_action('woocommerce_shop_loop_item_title', 'insertAfterShopProductTitle', 15);
 // sorting
 remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
 add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 25 );
 remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
 add_action( 'woo_custom_catalog_ordering', 'woocommerce_catalog_ordering', 30 ); 
 remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);


add_action( 'woocommerce_single_product_summary', 'storefront_post_header_categories', 20 );
function storefront_post_header_categories() {
	global $product;
	$productAttr = array();

	if (get_the_category_by_ID($product->category_ids[0]) === 'Casters') {
		$productAttr = array(
			'Caster Type'=>'pa_caster-type',
			'Tread Width'=>'pa_wheel-tread-width',
			'Wheel Diameter'=>'pa_wheel-diameter',
			'Wheel Type'=>'pa_wheel-type',
			'Capacity'=>'pa_load-capacity',
			'Finish'=>'pa_caster-finish'
		);
	}
	if (get_the_category_by_ID($product->category_ids[0]) === 'Wheels') {
		$productAttr = array(
			'Wheel Color'=>'pa_wheel-color',
			'Tread Width'=>'pa_wheel-tread-width',
			'Wheel Diameter'=>'pa_wheel-diameter',
			'Wheel Type'=>'pa_wheel-type',
			'Capacity'=>'pa_load-capacity',
			'Bearing Type'=>'pa_bearing-type'
		);
	}
	echo '<div class="product-list-withattr"><ul>';
	foreach ($productAttr as $productAttrKey => $productAttrValue){
		if($i==3 && is_front_page()) break;

		if ($productAttrValue == 'pa_wheel-type'){
			$productAttrValue2 = (empty($product->get_attribute('pa_wheel-type')) ? $product->get_attribute('pa_wheel-material') : $product->get_attribute('pa_wheel-type'));
		}

		if ($productAttrValue != 'pa_wheel-type')
			{
				$productAttrValue2 = ( empty($product->get_attribute($productAttrValue)) ? '-' : $product->get_attribute($productAttrValue) );
			}

			$productAttrValue2 = ( empty($productAttrValue2) ? '-' : $productAttrValue2 );
			echo '<li> <strong>'.$productAttrKey.':</strong><span>'.$productAttrValue2.'</span></li>';
			$i++;
     }
     echo '</ul>';
    echo '</div>';
}

add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
	unset( $tabs['description'] );
    $tabs['additional_information']['title'] = __( 'Technical Data' );
    $tabs['additional_information']['priority'] = 5; 
    $tabs['applications']['priority'] = 10;
    $tabs['options']['priority'] = 50;
    return $tabs;
}

register_sidebar( array ( 'name' => __( 'Product Page contact Panel', '' ),
'id' => 'ppcp', 'description' => __( 'Custom Sidebar', '' ), 'before_widget'
=> '<div class="widget-content">', 'after_widget' => "</div>", 'before_title'
=> '<h3 class="widget-title">', 'after_title' => '</h3>', ) );

register_sidebar( array ( 'name' => __( 'Recommended Products', '' ),
'id' => 'rp', 'description' => __( 'Custom Sidebar', '' ), 'before_widget'
=> '<div class="widget-content">', 'after_widget' => "</div>", 'before_title'
=> '<h3 class="widget-title">', 'after_title' => '</h3>', ) );
/**
 * Change number of related products output
 */ 
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
  function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 4 related products
	$args['columns'] = 3; // arranged in 2 columns
	return $args;
}

	// register_nav_menu( 'caster-menu' ,__( 'All Casters' ));    
	// wp_nav_menu( array( 'theme_location' => 'caster-menu' ) );
/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 20;
  return $cols;
}
add_action( 'woocommerce_before_shop_loop', 'ps_selectbox', 25 );
function ps_selectbox() {
    $per_page = filter_input(INPUT_GET, 'products_per_page', FILTER_SANITIZE_NUMBER_INT);     
    echo '<div class="woocommerce-ordering">';
    echo '<select class="sortby"  onchange="if (this.value) window.location.href=this.value">';   
    $orderby_options = array(
        '20' => '20 per page',
        '30' => '30 per page',
        '40' => '40 per page',
        '50' => '50 per page',
        '60' => '60 per page',
        '-1' => 'All'
    );
    foreach( $orderby_options as $value => $label ) {
        echo "<option ".selected( $per_page, $value )." value='?products_per_page=$value'>$label</option>";
    }
    echo '</select>';
    echo '</div>';
}

add_action( 'pre_get_posts', 'ps_pre_get_products_query' );
function ps_pre_get_products_query( $query ) {
   $per_page = filter_input(INPUT_GET, 'products_per_page', FILTER_SANITIZE_NUMBER_INT);
   if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'product' ) ){
        $query->set( 'products_per_page', $per_page );
    }
}
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
add_action( 'before_delete_post', 'delete_product_images', 10, 1 );

function delete_product_images( $post_id )
{
    $product = wc_get_product( $post_id );

    if ( !$product ) {
        return;
    }

    $featured_image_id = $product->get_image_id();
    $image_galleries_id = $product->get_gallery_image_ids();

    if( !empty( $featured_image_id ) ) {
        wp_delete_post( $featured_image_id );
    }

    if( !empty( $image_galleries_id ) ) {
        foreach( $image_galleries_id as $single_image_id ) {
            wp_delete_post( $single_image_id );
        }
    }
}

/**
 * Hide specific attributes from the Additional Information tab on single
 * WooCommerce product pages.
 *
 * @param WC_Product_Attribute[] $attributes Array of WC_Product_Attribute objects keyed with attribute slugs.
 * @param WC_Product $product
 *
 * @return WC_Product_Attribute[]
 */
function mycode_hide_attributes_from_additional_info_tabs( $attributes, $product ) {

	/**
	 * Array of attributes to hide from the Additional Information
	 * tab on single WooCommerce product pages.
	 */
	$hidden_attributes = [
		'pa_wheel-diameter-hidden',
		'pa_load-capacity-hidden',
	];

	foreach ( $hidden_attributes as $hidden_attribute ) {

		if ( ! isset( $attributes[ $hidden_attribute ] ) ) {
			continue;
		}

		$attribute = $attributes[ $hidden_attribute ];

		$attribute->set_visible( false );
	}

	return $attributes;
}

add_filter( 'woocommerce_product_get_attributes', 'mycode_hide_attributes_from_additional_info_tabs', 20, 2 );

add_action( 'woocommerce_no_products_found', function(){
    remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 );

    // HERE change your message below
    $message = __( 'Can’t find what you need? Let us know. If P&H doesn’t manufacture a product that will work for you, lets talk about designing one.', 'woocommerce' );

    echo '<p class="woocommerce-info">' . $message .'</p>';
    echo do_shortcode('[gravityform id="10" title="false" description="false" ajax="true"]');
}, 9 );

add_filter('woocommerce_is_purchasable', '__return_TRUE');

add_action('wp_ajax_remove_all_quotes', 'remove_all_quotes');
add_action('wp_ajax_nopriv_remove_all_quotes', 'remove_all_quotes');
function remove_all_quotes()
{
	$quotes = WC()->session->get('quotes');
	unset($quotes);
	WC()->session->set('quotes', $quotes);

	echo 'Quote successfully deleted!';

	die();
}

add_action('addify_before_quote', function () {
	if (!is_user_logged_in()) {
		echo '<div class="woocommerce-info non-loggedin-user">
		Returning customer? <a href="#" class="showlogin">Click here to login</a>	
		</div>';
		// echo '<div class="woocommerce-form-login-toggle">';
		// wc_print_notice(apply_filters('woocommerce_checkout_login_message', esc_html__('Returning customer?', 'woocommerce')) . ' <a href="#" class="showlogin">' . esc_html__('Click here to login', 'woocommerce') . '</a>', 'notice');
		// echo '</div>';
		echo '<div id="loginwrapper" style="display: none; border: 1px solid #cfc8d8;padding: 20px;margin: 2em 0;text-align: left;border-radius: 5px;">';
		woocommerce_login_form(
			array(
				"message"  => esc_html__("If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.", "woocommerce"),
				"redirect" => wc_get_checkout_url(),
				"hidden"   => true,
			)
		);
		echo '</div>';
	}
});

add_action('addify_after_quote', function () {
	if (is_user_logged_in()) {
		echo '<style>
		table.quote-fields tbody tr:nth-child(1).addify-option-field {display: none; !important;}
		table.quote-fields tbody tr:nth-child(2) {
			width: 50%;
			float: left;
			padding-left: 0;
		}
		</style>';
		echo '<div style="display: flex;">
		<div class="woocommerce-info loggedin-user" style="margin-top: 25px;width: 50%;margin-right: 10px;">
		Want to change billing information? <a href="#showbillingfields" class="showbillingfields" id="showbillingfields">Click here to show billing information</a>
		</div>
		<div style="flex-grow: 1;">
		<div>Leave a Comment</div>
		<textarea placeholder="" id="leaveACommentTemp"></textarea>
		</div>
		</div>
		<h3 class="addify-fields-form-title" style="display: none;">Details</h3>';

		echo '<script>
		jQuery(function(){
			jQuery(document).on("keyup", "#leaveACommentTemp", function(){
				jQuery("textarea[name=afrfq_field_22853]").val(jQuery(this).val());
			});
		});
		</script>';
	} else {
		echo '<style>
		table.quote-fields tbody tr:nth-child(1).addify-option-field {display: none; !important;}
		</style>';

		echo '
		<div style="display: flex;">
		<div style="width: 50%;"><h3>Details</h3><div style="display: block;">Create an account by entering the information below. If you are a returning customer please login at the top of the page.</div></div>
		<div style="flex-grow: 1;">
		<div>Leave a Comment</div>
		<textarea placeholder="" id="leaveACommentTemp"></textarea>
		</div>
		</div>';

		echo '<script>
		jQuery(function(){
			jQuery(document).on("keyup", "#leaveACommentTemp", function(){
				jQuery("textarea[name=afrfq_field_22853]").val(jQuery(this).val());
			});

			jQuery(document).on("click", ".showlogin", function(e){
				e.preventDefault();
				e.stopPropagation();

				jQuery("#loginwrapper form.login").show();
				jQuery("#loginwrapper").toggle("slow");
			});
		});
		</script>';
	}
});