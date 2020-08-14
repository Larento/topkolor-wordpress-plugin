<?php
function tk_post_type_name($slug) {
  return substr($slug, 0, 3) . "_product";
}

function tk_taxonomy_name($slug) {
  return tk_post_type_name($slug) . "_kind";
}

function tk_custom_post_type_product($name, $slug) {
  $labels = [
    'name'              => _x("$name Products", 'post type general name' ),
    'singular_name'     => _x( "$name Products", 'post type singular name' ),
    'add_new'           => _x( 'Add New', 'product' ),
    'add_new_item'      => __( 'Add New Product' ),
    'edit_item'         => __( 'Edit Product' ),
    'new_item'          => __( 'New Product' ),
    'all_items'         => __( 'All Products' ),
    'view_item'         => __( 'View Product' ),
    'search_items'      => __( 'Search Products' ), 
    'menu_name'         => __("$name Products"),
  ];
  $args = [
    'labels'            => $labels,
    'public'            => true,
    'menu_position'     => 1,
    'supports'          => ['title', 'editor', 'thumbnail', 'excerpt'],
    'has_archive'       => true,
    'rewrite'           => [
      'slug'              => $slug . "/%" . tk_taxonomy_name($slug) . "%",
      'with_front'        => false,
    ],
  ];
  register_post_type( tk_post_type_name($slug), $args ); 
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
    'menu_name'         => __("$name Product Kinds"),
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
  register_taxonomy( tk_taxonomy_name($slug), tk_post_type_name($slug), $args );
};

function tk_custom_taxonomy_add_terms($slug, $kinds) {
  foreach ($kinds as $key => $value) {
    wp_insert_term( $key, tk_taxonomy_name($slug), [
      'description' => '',
      'parent'      => 0,
      'slug'        => $value,
    ]);
  };
};

function tk_custom_post_type_permalinks($post_link, $post, $leavename, $sample, $slug) {
  $taxonomy_name = tk_taxonomy_name($slug);
  if ( strpos($post_link, "%" . $taxonomy_name . "%") !== false ) {
    $taxonomy_terms = get_the_terms($post->ID, $taxonomy_name);
    if ( empty($taxonomy_terms) !== true ) {
      $post_link = str_replace("%" . $taxonomy_name . "%", array_pop($taxonomy_terms)->slug, $post_link);
    } else {
      $post_link = str_replace("%" . $taxonomy_name . "%", 'uncategorized', $post_link);
    };
  };
  return $post_link;
};

function tk_register_product_type($name, $slug, $kinds) {
  tk_custom_post_type_product($name, $slug);
  tk_custom_taxonomy_product_kind($name, $slug);
  tk_custom_taxonomy_add_terms($slug, $kinds);
};
?>

