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

function current_product( ?\WP_Post $current_post = null ) {
  global $post;
  $current_post = $current_post ?? $post;
  $products = get_products();
  foreach ( $products as $product ) {
    if ( get_post_type($current_post) === $product->slug ) {
      return $product;
    }
  }
  return 'not_product';
}

function is_product( ?\WP_Post $current_post = null ) {
  return ( current_product($current_post) === 'not_product' ) ? false : true; 
}

function current_product_kind( ?\WP_Post $current_post = null ) {
  global $post;
  $current_post = $current_post ?? $post;
  if ( is_product($current_post) ) {
    $product = current_product($current_post);
    $kind = get_the_terms( $current_post, $product->taxonomy->slug );
    if ( is_array($kind) ) {
      $kind = reset($kind);
    } else {
      return 'no_product_kind';
    }
    foreach ( $product->taxonomy->kinds as $this_kind ) {
      if ( $kind === $this_kind->wp_object ) {
        return $this_kind;
      }
    }
  } else {
    return 'no_product_kind';
  };
}

function is_product_kind( ?\WP_Post $current_post = null ) {
  return ( current_product_kind($current_post) === 'no_product_kind' ) ? false : true; 
}

function product_media() {
  if ( is_product() && is_product_kind() ){
    $parentURL = current_product()->archive_name . "/" . current_product_kind()->label;
    return post_media($parentURL);
  } else {
    return 'Error! Post is not a product or does not have a valid kind.';
  };
}