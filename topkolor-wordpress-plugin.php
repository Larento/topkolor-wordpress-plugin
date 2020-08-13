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
    'name'              => _x('Style', 'taxonomy general name'),
    'singular_name'     => _x('Style', 'taxonomy singular name'),
    'menu_name'         => __('Style'),
  ];
  $args = [
    'hierarchical'      => false, // make it hierarchical (like categories)
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => ['slug' => 'course'],
  ];
  register_taxonomy('style', ['portfolio_item'], $args);
}
add_action('init', 'tk_custom_taxonomy_style');

function tk_custom_post_type_portfolio_item() {
  $labels = array(
    'name'               => _x( 'Portfolio Item', 'post type general name' ),
    'menu_name'          => 'Portfolio Items',
  );
  $args = array(
    'labels'        => $labels,
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt',),
    'has_archive'   => true,
  );
  register_post_type( 'portfolio_item', $args ); 
}
add_action( 'init', 'tk_custom_post_type_portfolio_item' );
?>