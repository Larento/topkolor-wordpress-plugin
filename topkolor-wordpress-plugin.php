<?php
/**
 * Plugin Name: TOPKOLOR Plugin
 * Author: Larik
 * Version: 0.0.6
 * GitHub Plugin URI: https://github.com/Larento/topkolor-wordpress-plugin
 * License: GNU General Public License v2 or later
 */

function tk_custom_taxonomy_product_style() {
  $labels = [
		'name'              => _x('Product Styles', 'taxonomy general name'),
    'singular_name'     => _x('Product Style', 'taxonomy singular name'),
    'search_items'      => __('Search Product Styles'),
    'all_items'         => __('All Product Styles'),
    'parent_item'       => __('Parent Product Style'),
    'parent_item_colon' => __('Parent Product Style:'),
    'edit_item'         => __('Edit Product Style'),
    'update_item'       => __('Update Product Style'),
    'add_new_item'      => __('Add New Product Style'),
    'new_item_name'     => __('New Product Style Name'),
    'menu_name'         => __('Product '),
  ];
  $args = [
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => [
      'slug'              => NULL,
      'with_front'        => false,
    ],
  ];
  register_taxonomy('product_styles', ['portfolio_item'], $args);
};
add_action('init', 'tk_custom_taxonomy_product_style');

function tk_custom_taxonomy_product_kind() {
  $labels = [
		'name'              => _x('Product Kinds', 'taxonomy general name'),
    'singular_name'     => _x('Product Kind', 'taxonomy singular name'),
    'search_items'      => __('Search Product Kinds'),
    'all_items'         => __('All Product Kinds'),
    'parent_item'       => __('Parent Product Kind'),
    'parent_item_colon' => __('Parent Product Kind:'),
    'edit_item'         => __('Edit Product Kind'),
    'update_item'       => __('Update Product Kind'),
    'add_new_item'      => __('Add New Product Kind'),
    'new_item_name'     => __('New Product Kind Name'),
    'menu_name'         => __('Product Kind'),
  ];
  $args = [
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => [
      'slug'              => NULL,
      'with_front'        => false,
    ],
  ];
  register_taxonomy('product_kinds', ['portfolio_item'], $args);
};
add_action('init', 'tk_custom_taxonomy_product_kind');

function tk_custom_post_type_portfolio_item() {
  $labels = [
    'name'              => _x('Portfolio Item', 'post type general name' ),
    'menu_name'         => 'Portfolio Items',
  ];
  $args = [
    'labels'            => $labels,
    'public'            => true,
    'menu_position'     => 5,
    'supports'          => ['title', 'editor', 'thumbnail', 'excerpt'],
    'has_archive'       => true,
    'taxonomies'        => ['post_tags', 'product_styles', 'product_kinds'],
    'rewrite'           => [
      'slug'              => '/portfolio/%product_styles%/%product_kinds%',
      'with_front'        => false,
    ],
  ];
  register_post_type( 'portfolio_item', $args ); 
};
add_action( 'init', 'tk_custom_post_type_portfolio_item' );
?>