<?php
namespace tk\functions;

function get_handle($handle) {
  return __NAMESPACE__ . "\\$handle";
}

function get_current_post( ?\WP_Post $current_post = null ) {
  $post = get_post();
  $current_post = $current_post ?? $post;
  return $current_post;
}

function create_rml_folder( string $name, ?int $parentID ) {
  return \wp_rml_create_or_return_existing_id( $name, $parentID, 0 );
}

function folder_media( string $path ) {
  $folders = \wp_rml_objects();
  $picture_folder = \wp_rml_get_object_by_id( \_wp_rml_root() );
  foreach ( $folders as $folder ) {
    if ( \is_rml_folder( $folder ) === true ) {
      $folder_path = urldecode( $folder->getPath() );
      if ( $folder_path == $path ) {
        $picture_folder = $folder;
        break;
      }
    }
  }
  return \wp_rml_get_attachments( $picture_folder->getId() );
}

function post_media(string $parentURL, ?\WP_Post $post = null ) {
  $post = get_current_post($post);
  $title = get_the_title($post);
  return folder_media( "$parentURL/$title" );
}

function register_decorations_folder( ) {
  $parent_id = create_rml_folder( 'Оформление', \_wp_rml_root() );
  create_rml_folder( get_the_title( get_option('page_on_front') ), $parent_id );
  create_rml_folder( 'Логотипы и иконки', $parent_id );
  create_rml_folder( 'Фоны', $parent_id );
}