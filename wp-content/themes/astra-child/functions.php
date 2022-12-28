<?php
/**
 * TAURUS SPORTS AG Theme Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package TAURUS SPORTS AG Theme
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_TAURUS_SPORTS_AG_THEME_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'taurus-sports-ag-theme-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_TAURUS_SPORTS_AG_THEME_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );




/* Custom Post Type Start */
function create_posttype() {
register_post_type( 'Jobs',
// CPT Options
array(
  'labels' => array(
   'name' => __( 'jobs' ),
   'singular_name' => __( 'Jobs' )
  ),
  'public' => true,
  'has_archive' => false,
  'rewrite' => array('slug' => 'jobs'),
 )
);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );
/* Custom Post Type End */
/*Custom Post type start*/
function cw_post_type_news() {
$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
);
$labels = array(
'name' => _x('jobs', 'plural'),
'singular_name' => _x('jobs', 'singular'),
'menu_name' => _x('Jobs', 'admin menu'),
'name_admin_bar' => _x('jobs', 'admin bar'),
'add_new' => _x('Add New', 'add new'),
'add_new_item' => __('Add New jobs'),
'new_item' => __('New jobs'),
'edit_item' => __('Edit jobs'),
'view_item' => __('View jobs'),
'all_items' => __('All jobs'),
'search_items' => __('Search jobs'),
'not_found' => __('No jobs found.'),
);
$args = array(
'supports' => $supports,
'labels' => $labels,

'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'jobs'),
'has_archive' => true,
'hierarchical' => true,
);
register_post_type('jobs', $args);
}



add_action('init', 'cw_post_type_news');
/*Custom Post type end*/



/* Custom Post Type Start */
function create_posttype2() {
register_post_type( 'Team',
// CPT Options
array(
  'labels' => array(
   'name' => __( 'team' ),
   'singular_name' => __( 'Team' )
  ),
  'public' => true,
  'has_archive' => false,
  'rewrite' => array('slug' => 'team'),
 )
);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype2' );
/* Custom Post Type End */
/*Custom Post type start*/
function cw_post_type_news2() {
$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
);

$labels = array(
'name' => _x('team', 'plural'),
'singular_name' => _x('team', 'singular'),
'menu_name' => _x('Team', 'admin menu'),
'name_admin_bar' => _x('team', 'admin bar'),
'add_new' => _x('Add New', 'add new'),
'add_new_item' => __('Add New team'),
'new_item' => __('New team'),
'edit_item' => __('Edit team'),
'view_item' => __('View team'),
'all_items' => __('All team'),
'search_items' => __('Search team'),
'not_found' => __('No team found.'),
);
$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'team'),
'has_archive' => true,
'hierarchical' => true,
);
register_post_type('team', $args);
register_taxonomy( 'categories', array('team'), array(
        'hierarchical' => true, 
        'label' => 'Categories', 
        'singular_label' => 'Category', 
        'rewrite' => array( 'slug' => 'categories', 'with_front'=> false )
        )
    );
}
add_action('init', 'cw_post_type_news2');
/*Custom Post type end*/

