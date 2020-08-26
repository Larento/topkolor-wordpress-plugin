<?php

/**
 * Plugin Name: TOPKOLOR Plugin
 * Author: Larik
 * Version: 0.0.8
 * GitHub Plugin URI: https://github.com/Larento/topkolor-wordpress-plugin
 * License: GNU General Public License v2 or later
 */

include_once(plugin_dir_path(__FILE__) . '/product.php');
include_once(plugin_dir_path(__FILE__) . '/tk_REST_Controller.php');
include_once(plugin_dir_path(__FILE__) . '/func.php');

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

foreach ( $product_types as $key => $type ) {
  $label = $type['name'];
  $url_slug = $type['slug'];
  $kinds = $type['kinds'];
  $archive_name = $key;
  $tk_product_types[$key] = New tk\product( $label, $url_slug, $archive_name, $kinds );
  $tk_product_types[$key]->wp_add();  
}

add_action( 'rest_api_init', 'prefix_register_my_rest_routes' );
function prefix_register_my_rest_routes() {
	$controller = new tk_products_custom_route();
	$controller->register_routes();
}

function get_profgbfgbfgs() {
  global $tk_product_types;
  return $tk_product_types;
}

// function get_form_params($post_id) {
//   $item['id'] = $post_id;
//   $post = get_post($item['id']);
//   if (tk_is_product($post) === true) {
//     $item['style'] = tk_get_product_slug(tk_get_current_product($post));
//   } else {
//     $item['style'] = 'none';
//   }
//   if (tk_is_product_kind($post) === true) {
//     if (is_post_type_archive($post) === true) {
//       $item['kind'] = 'none';
//     } else {
//       $item['kind'] = tk_get_product_kind_slug(tk_get_current_product_kind($post));
//     }
//   } else {
//     $item['kind'] = 'none';
//   }
//   if ($post !== NULL) {
//     wp_send_json_success($item);
//   } else {
//     wp_send_json_error('Post is not a product or does not exist!');
//   }
// }

// add_action('template_redirect', function () {
//   global $wp_query;
//   $post_id = $wp_query->get('request_form_post_id');

//   if (!empty($post_id)) {
//     get_form_params($post_id);
//   }
// });
?>