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
    'menu_name'         => __('Product Styles'),
  ];
  $args = [
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => [
      'slug'              => 'product_style',
      'with_front'        => false,
    ],
  ];
  register_taxonomy('product_style', 'portfolio_item', $args);
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
      'slug'              => 'product_kind',
      'with_front'        => false,
    ],
  ];
  register_taxonomy('product_kind', 'portfolio_item', $args);
};

add_action('init', 'tk_custom_taxonomy_product_kind');

function tk_custom_post_type_portfolio_item() {
  $labels = [
    'name'              => _x('Portfolio Items', 'post type general name' ),
    'singular_name'     => _x( 'Portfolio Item', 'post type singular name' ),
    'add_new'           => _x( 'Add New', 'portfolio' ),
    'add_new_item'      => __( 'Add New Item' ),
    'edit_item'         => __( 'Edit Item' ),
    'new_item'          => __( 'New Item' ),
    'all_items'         => __( 'All Items' ),
    'view_item'         => __( 'View Item' ),
    'search_items'      => __( 'Search Items' ), 
    'parent_item_colon' => '',
    'menu_name'         => 'Portfolio Items'
  ];
  $args = [
    'labels'            => $labels,
    'public'            => true,
    'menu_position'     => 5,
    'supports'          => ['title', 'editor', 'thumbnail', 'excerpt'],
    'rewrite'           => [
      'slug'              => 'portfolio/%product_style%/', // 
      'with_front'        => false,
    ],
    'has_archive'       => 'portfolio',
  ];
  register_post_type( 'portfolio_item', $args ); 
};

add_action( 'init', 'tk_custom_post_type_portfolio_item' );

class taxonomy_member {
  public $taxonomy;
  public $name;
  public $slug;
};

add_filter('post_type_link', 'product_style_and_kind_permalink_structure', 10, 4);

function product_style_and_kind_permalink_structure($post_link, $post, $leavename, $sample) {
  $taxonomies = ['product_style', 'product_kind'];
  foreach ($taxonomies as $taxonomy) {
    if ( strpos($post_link, "%$taxonomy%") !== false ) {
      $taxonomy_terms = get_the_terms($post->ID, $taxonomy);
      if ( empty($taxonomy_terms) === false ) {
        $post_link = str_replace("%$taxonomy%", array_pop($taxonomy_terms)->slug, $post_link);
      } else {
        $post_link = str_replace("%$taxonomy%", 'uncategorized', $post_link);
      };
    };
  };
  return $post_link;
};
?>