<?php

class tk_products_custom_route extends WP_REST_Controller {
 
  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes() {
    $version = '1';
    $namespace = 'tk-wordpress-plugin/v' . $version;
    $base = 'functions';
    register_rest_route( $namespace, '/' . $base . '/get_request_form_params/(?P<post_id>\d+)', array(
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_request_form_params' ),
        'permission_callback' => array( $this, 'get_info_permissions_check' ),
        'args'                => [
          'post_id'            => [
              'default'         => 1,
              'required'        => true,
          ],
        ],
      ),
    ),
    );
  }
 
  /**
   * Get one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_request_form_params( $request ) {
    $params = $request->get_params();
    $item['id'] = $params['post_id'];
    $post = get_post($item['id']);
    if (tk_is_product($post) === true) {
      $item['style'] = tk_get_product_slug( tk_get_current_product($post) );
    } else {
      $item['style'] = 'none';
    };
    if (tk_is_product_kind($post) === true) {
      if ( is_post_type_archive($post) === true ) {
        $item['kind'] = 'none';
      } else {
        $item['kind'] = tk_get_product_kind_slug( tk_get_current_product_kind($post) );
      };
    } else {
      $item['kind'] = 'none';
    };
    $data = $this->prepare_item_for_response( $item, $request );
    return new WP_REST_Response( $data, 200 );
  }

  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_info_permissions_check( $request ) {
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