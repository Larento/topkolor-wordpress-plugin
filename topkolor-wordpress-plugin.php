<?php
/**
 * Plugin Name: TOPKOLOR Plugin
 * Author: Larik
 * Version: 0.0.6
 * GitHub Plugin URI: https://github.com/Larento/topkolor-wordpress-plugin
 * License: GNU General Public License v2 or later
 */

function tk_custom_taxonomy_style() {
  $labels = [
		'name'              => _x('Styles', 'taxonomy general name'),
    'singular_name'     => _x('Style', 'taxonomy singular name'),
    'search_items'      => __('Search Styles'),
    'all_items'         => __('All Styles'),
    'parent_item'       => __('Parent Style'),
    'parent_item_colon' => __('Parent Style:'),
    'edit_item'         => __('Edit Style'),
    'update_item'       => __('Update Style'),
    'add_new_item'      => __('Add New Style'),
    'new_item_name'     => __('New Style Name'),
    'menu_name'         => __('Style'),
  ];
  $args = [
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => ['slug' => 'style'],
  ];
  register_taxonomy('style', ['portfolio_item'], $args);
}
add_action('init', 'tk_custom_taxonomy_style');

function tk_custom_post_type_portfolio_item() {
  $labels = [
    'name'               => _x( 'Portfolio Item', 'post type general name' ),
    'menu_name'          => 'Portfolio Items',
  ];
  $args = [
    'labels'        => $labels,
    'public'        => true,
    'menu_position' => 5,
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
    'has_archive'   => true,
  ];
  register_post_type( 'portfolio_item', $args ); 
}
add_action( 'init', 'tk_custom_post_type_portfolio_item' );
?>