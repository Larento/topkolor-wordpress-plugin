<?php
/**
 * Plugin Name: TOPKOLOR Plugin
 * Author: Larik
 * Version: 0.0.7
 * GitHub Plugin URI: https://github.com/Larento/topkolor-wordpress-plugin
 * License: GNU General Public License v2 or later
 */

include_once( plugin_dir_path( __FILE__ ) . '/product.php' );
include_once( plugin_dir_path( __FILE__ ) . '/tk_REST_Controller.php' );

$product_types = [
  'Терраццо'            => [
    'name'                => 'Terrazzo',
    'slug'                => 'terrazzo',
    'kinds'               => [
      'Полы'                => 'floors',
      'Столешницы'          => 'countertops',
      'Подоконники'         => 'window-sills',
      'Раковины'            => 'sinks',
      'Панели'              => 'panels',
    ],
  ],

  'Декоративный бетон'  => [
    'name'                => 'Decorative Concrete',
    'slug'                => 'decorative-concrete',
    'kinds'               => [
      'Полы'                => 'floors',
      'Столешницы'          => 'countertops',
      'Подоконники'         => 'window-sills',
      'Раковины'            => 'sinks',
      'Панели'              => 'panels',
    ],
  ],

  'Микроцемент'         => [
    'name'                => 'Micro Concrete',
    'slug'                => 'micro-concrete',
    'kinds'               => [
      'Напольные покрытия'  => 'floor-coverings',
      'Настенные покрытия'  => 'wall-coverings',
      'Элементы интерьера'  => 'interior-elements',
    ],
  ],
];

$tk_register = [];
$tk_permalinks_filter = [];

foreach ($product_types as $key => $type) {
  $name = $type['name'];
  $slug = $type['slug'];
  $kinds = $type['kinds'];
  $menu_name = $key;
  $tk_register[$slug] = function() use ($menu_name, $name, $slug, $kinds) {
    tk_register_product_type($menu_name, $name, $slug, $kinds);
  };
  $tk_permalinks_filter[$slug] = function($post_link, $post, $leavename, $sample) use ($slug) {
    return tk_custom_post_type_permalinks($post_link, $post, $leavename, $sample, $slug);
  };
}

foreach ($tk_register as $register_func) {
  add_action( 'init', $register_func );
}

foreach ($tk_permalinks_filter as $filter_func) {
  add_filter('post_type_link', $filter_func, 10, 4);
}

add_action( 'rest_api_init', 'prefix_register_my_rest_routes' );
function prefix_register_my_rest_routes() {
	$controller = new tk_products_custom_route();
	$controller->register_routes();
}
?>