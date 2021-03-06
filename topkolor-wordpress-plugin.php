<?php
/**
 * Plugin Name: TOPKOLOR Plugin
 * Author: Larik
 * Version: 0.2.0
 * GitHub Plugin URI: https://github.com/Larento/topkolor-wordpress-plugin
 * License: GNU General Public License v2 or later
 */
use \tk\functions as tk;

include_once( plugin_dir_path(__FILE__) . '/functions.php' );
include_once( plugin_dir_path(__FILE__) . '/product/product_classes.php' );
include_once( plugin_dir_path(__FILE__) . '/product/product_functions.php' );
include_once( plugin_dir_path(__FILE__) . '/product/product_rest_controller.php' );

$tk_products = tk\register_products( plugin_dir_path(__FILE__) . '/product/product_types.json' );

add_action( 'init', tk\get_handle('register_decorations_folder') );
add_action( 'init', tk\get_handle('action_init') );
add_action( 'init', tk\get_handle('products_init') );

add_action( 'rest_api_init', 'prefix_register_my_rest_routes' );
function prefix_register_my_rest_routes() {
  $controller = new \tk\classes\product_rest_controller();
  $controller->register_routes();
}
