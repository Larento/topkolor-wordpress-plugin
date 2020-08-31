<?php
namespace tk\classes;
use \tk\functions as tk;

use function tk\functions\is_product;

class product_rest_controller extends \WP_REST_Controller {
 
  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes() {
    $version = '1';
    $namespace = "tk-wordpress-plugin/v$version";
    $base = 'functions';
    register_rest_route( $namespace, "/$base/get_products",
      [
        [
          'methods'             => \WP_REST_Server::READABLE,
          'callback'            => [ $this, 'get_products' ],
          'permission_callback' => [ $this, 'get_info_permissions_check' ],
        ],
      ],
    );

    register_rest_route( $namespace, "/$base/get_request_form_params/(?P<post_id>\d+)",
      [
        [
          'methods'             => \WP_REST_Server::READABLE,
          'callback'            => [ $this, 'get_request_form_params' ],
          'permission_callback' => [ $this, 'get_info_permissions_check' ],
          'args'                => [
            'post_id' => [
                'default'         => 1,
                'required'        => true,
            ],
          ],
        ],
      ],
    );
  }

  /**
   * Get one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_products($request) {
    $item = tk\get_products();
    $data = $this->prepare_item_for_response( $item, $request );
    if ( isset($data) ) {
      return new \WP_REST_Response( $data, 200 );
    } else {
      return new \WP_Error( 'Error!', __( 'Post does not exist.', 'text-domain' ) );
    }
  }

  /**
   * Get one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_request_form_params($request) {
    $params = $request->get_params();
    $item['id'] = $params['post_id'];
    $post = get_post($item['id']);
    $item['style'] = 'none';
    $item['kind'] = 'none';
    if ( tk\is_product($post) ) {
      $item['style'] = tk\current_product($post)->slug;
    }
    if ( tk\is_product_kind($post) && !is_post_type_archive($post)) {
      $item['kind'] = tk\current_product_kind($post)->slug;
    }
    $data = $this->prepare_item_for_response( $item, $request );
    if ( $post !== null ) {
      return new \WP_REST_Response( $data, 200 );
    } else {
      return new \WP_Error( 'Error!', __( 'Post does not exist.', 'text-domain' ) );
    }
  }

  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_info_permissions_check($request) {
    return true;
    //return current_user_can( 'edit_something' );
  }
 
  /**
   * Prepare the item for the REST response
   *
   * @param mixed $item WordPress representation of the item.
   * @param WP_REST_Request $request Request object.
   * @return mixed
   */
  public function prepare_item_for_response( $item, $request ) {
    return $item;
  }
}

?>