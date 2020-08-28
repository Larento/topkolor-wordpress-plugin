<?php
namespace tk\functions;
use \tk\classes\product as product;

function register_products( string $json_path ) {
  $product_types = json_decode( file_get_contents($json_path), true );
  foreach ( $product_types as $type ) {
    $registered_types[] = new product( $type['label'], $type['slug'], $type['name'], $type['kinds'] ); 
  }
  return $registered_types;
}

function get_products() {
  global $tk_products;
  return $tk_products;
}

function current_product( ?\WP_Post $post = null ) {
  $post = get_current_post($post);
  $products = get_products();
  foreach ( $products as $product ) {
    if ( get_post_type($post) === $product->slug ) {
      return $product;
    }
  }
  return 'not_product';
}

function is_product( ?\WP_Post $post = null ) {
  return ( current_product($post) === 'not_product' ) ? false : true; 
}

function current_product_kind( ?\WP_Post $post = null ) {
  $post = get_current_post($post);
  if ( is_product($post) ) {
    $product = current_product($post);
    $kind = get_the_terms( $post, $product->taxonomy->slug );
    if ( is_array($kind) ) {
      $kind = reset($kind);
    } else {
      return 'no_product_kind';
    }
    foreach ( $product->taxonomy->kinds as $this_kind ) {
      if ( $kind == $this_kind->wp_object ) {
        return $this_kind;
      }
    }
  } else {
    return 'no_product_kind';
  }
}

function is_product_kind( ?\WP_Post $post = null ) {
  return ( current_product_kind($post) === 'no_product_kind' ) ? false : true; 
}

function products_init() {
  $products = get_products();
  foreach ( $products as $product ) {
    $product_types[] = $product->slug;
  }
  $args = [ 'post_type' => $product_types ];
  $products_query = new \WP_Query($args);
  if ( $products_query->have_posts() ) {
    while ( $products_query->have_posts() ) {
      $products_query->the_post();
      do_action( get_handle('products_init_loop'), get_post() );
    }
  } else {
    return false;
  }
  wp_reset_postdata();
  return true;
}

function action_init() {
  add_action( get_handle('products_init_loop'), get_handle('set_product_folder') );
  add_action( get_handle('products_init_loop'), get_handle('set_product_thumbnail') );
}

function set_product_folder( \WP_Post $post ) {
  $kind = current_product_kind($post);
  create_rml_folder( get_the_title($post), $kind->folderID );
}

function set_product_thumbnail( \WP_Post $post ) {
  $attachments = product_media($post);
  $new_thumbnail_ID = reset($attachments);
  if ( has_post_thumbnail($post) ) {
    $thumbnail_ID = get_post_thumbnail_id($post);
    if ( $new_thumbnail_ID !== $thumbnail_ID ) {
        delete_post_thumbnail($post);
        set_post_thumbnail( $post, $new_thumbnail_ID );
    }
  } else {
      set_post_thumbnail( $post, $new_thumbnail_ID );
  }
}

function product_media( ?\WP_Post $post = null ) {
  $post = get_current_post($post);
  $parentURL = current_product($post)->archive_name . "/" . current_product_kind($post)->label;
  return post_media( $parentURL, $post );
}