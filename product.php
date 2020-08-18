<?php
//Creation functions
  function tk_custom_post_type_product($menu_name, $name, $slug) {
    $labels = [
      'name'              => _x("$name Products", 'post type general name' ),
      'singular_name'     => _x( "$name Product", 'post type singular name' ),
      'add_new'           => _x( 'Add New', 'product' ),
      'add_new_item'      => __( 'Add New Product' ),
      'edit_item'         => __( 'Edit Product' ),
      'new_item'          => __( 'New Product' ),
      'all_items'         => __( $menu_name ),
      'view_item'         => __( 'View Product' ),
      'search_items'      => __( 'Search Products' ), 
      'menu_name'         => __("$name Products"),
    ];
    $args = [
      'labels'            => $labels,
      'public'            => true,
      'description'       => 'Product',
      'menu_position'     => 1,
      'supports'          => ['title', 'editor', 'thumbnail', 'excerpt'],
      'has_archive'       => $slug,
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
      'description'       => 'Product Kind',
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

  function tk_register_product_type($menu_name, $name, $slug, $kinds) {
    tk_custom_post_type_product($menu_name, $name, $slug);
    tk_custom_taxonomy_product_kind($name, $slug);
    tk_custom_taxonomy_add_terms($slug, $kinds);
  };

//Access functions
  function tk_post_type_name($slug) {
    return substr($slug, 0, 3) . "_product";
  };

  function tk_taxonomy_name($slug, $post_name = false) {
    if ($post_name !== false) {
      return $post_name . "_kind";
    } else {
      return tk_post_type_name($slug) . "_kind";
    };
  };

  function tk_get_products() {
    return get_post_types( ['description'  => 'Product',], 'objects' );
  };

  function tk_get_current_product() {
    $products_array = get_post_types(['name' => get_post_type(), 'description'  => 'Product',], 'objects');
    return ( $products_array !== array() ) ? reset($products_array) : 'not_product';
  };

  function tk_is_product() {
    return ( tk_get_current_product() === 'not_product' ) ? false : true; 
  };

  function tk_get_product_slug($product) {
    return $product->name;
  };

  function tk_get_product_label($product) {
    return $product->labels->all_items;
  };

  function tk_get_product_kinds($product) {
    return get_terms([
      'taxonomy'    => tk_taxonomy_name('', tk_get_product_slug($product)),
      'hide_empty'  => false,
      'order'       => 'ID',
      'orderby'     => 'DESC',
    ]);
  };

  function tk_get_current_product_kind() {
    if ( tk_is_product() === true ) {
      global $post;
      $product = tk_get_current_product();
      $product_kinds_array = get_the_terms( $post, tk_taxonomy_name('', tk_get_product_slug($product)) );
      return ( $product_kinds_array !== array() ) ? reset($product_kinds_array) : 'not_product_kind';
    } else {
      return 'not_product_kind';
    };
  };

  function tk_is_product_kind() {
    return ( tk_get_current_product_kind() === 'not_product_kind' ) ? false : true; 
  };

  function tk_get_product_kind_slug($product_kind) {
    return $product_kind->slug;
  };

  function tk_get_product_kind_label($product_kind) {
    return $product_kind->name;
  };
?>

