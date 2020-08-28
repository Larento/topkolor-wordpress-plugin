<?php
namespace tk\functions;

function get_handle($handle) {
  return __NAMESPACE__ . '\\' . $handle;
}

function get_current_post( ?\WP_Post $current_post = null ) {
  global $post;
  $current_post = $current_post ?? $post;
  return $current_post;
}

function create_rml_folder( string $name, string $parentID ) {
  $ID = \wp_rml_create_or_return_exisiting_id( $name, $parentID, 0 );
  \wp_rml_structure_reset();
  return $ID;
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

function post_media(string $parentURL ) {
  return folder_media( $parentURL . '/' . get_the_title() );
}
