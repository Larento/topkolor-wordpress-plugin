<?php
/**
 * Plugin Name: TOPKOLOR Plugin
 * Author: Larik
 * Version: 0.0.6
 * GitHub Plugin URI: https://github.com/Larento/topkolor-wordpress-plugin
 * License: GNU General Public License v2 or later
 */

function tk_portfolio_item() {
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
add_action( 'init', 'tk_portfolio_item' );
?>