<?php
/**
 * Plugin Name: TOPKOLOR Plugin
 * Author: Larik
 * Version: 0.0.6
 * GitHub Plugin URI: https://github.com/Larento/topkolor-wordpress-plugin
 * License: GNU General Public License v2 or later
 */

  function wporg_custom_post_type() {
      register_post_type('wporg_product',
        array(
          'labels' => array(
            'name'          => __('Products', 'textdomain'),
            'singular_name' => __('Product', 'textdomain'),
          ),
            'public'      => true,
            'has_archive' => true,
        ),
      );
    };

    add_action('init', 'wporg_custom_post_type');
?>