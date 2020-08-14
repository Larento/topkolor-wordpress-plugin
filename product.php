<?php
function tk_custom_post_type_product($name, $slug) {
  $labels = [
    'name'              => _x("$name Products", 'post type general name' ),
    'singular_name'     => _x( "$name Products", 'post type singular name' ),
    'add_new'           => _x( 'Add New', 'product' ),
    'add_new_item'      => __( 'Add New Product' ),
    'edit_item'         => __( 'Edit Product' ),
    'new_item'          => __( 'New Product' ),
    'all_items'         => __( 'All Product' ),
    'view_item'         => __( 'View Product' ),
    'search_items'      => __( 'Search Product' ), 
    'menu_name'         => __("$name Products"),
  ];
  $args = [
    'labels'            => $labels,
    'public'            => true,
    'menu_position'     => 0,
    'supports'          => ['title', 'editor', 'thumbnail', 'excerpt'],
    'has_archive'       => true,
    'rewrite'           => [
      'slug'              => $slug."/%$slug"."_product_kind%",
      'with_front'        => false,
    ],
  ];
  register_post_type( "$slug"."_product", $args ); 
};

function tk_custom_taxonomy_product_kind($name, $slug) {
  $labels = [
    'name'              => _x("$name Product Kinds", 'taxonomy general name'),
    'singular_name'     => _x("$name Product Kind", 'taxonomy singular name'),
    'search_items'      => __('Search Product Kinds'),
    'all_items'         => __('All Product Kinds'),
    'parent_item'       => __('Parent Product Kind'),
    'parent_item_colon' => __('Parent Product Kind:'),
    'edit_item'         => __('Edit Product Kind'),
    'update_item'       => __('Update Product Kind'),
    'add_new_item'      => __('Add New Product Kind'),
    'new_item_name'     => __('New Product Kind Name'),
    'menu_name'         => __("$name Product Kind"),
  ];
  $args = [
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => [
      'slug'              => $slug,
      'with_front'        => false,
    ],
  ];
  register_taxonomy("$slug"."_product_kind", "$slug"."_product", $args);
};

function tk_custom_taxonomy_add_terms($slug, $kinds) {
  foreach ($kinds as $key => $value) {
    wp_insert_term( $key, "$slug"."_product_kind", [
      'description' => '',
      'parent'      => 0,
      'slug'        => $value,
    ]);
  };
};

function tk_register_product_type($name, $slug, $kinds) {
  tk_custom_post_type_product($name, $slug);
  tk_custom_taxonomy_product_kind($name, $slug);
  tk_custom_taxonomy_add_terms($slug, $kinds);
};
?>

