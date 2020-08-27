<?php
namespace tk\functions;

function folder_media( $path ) {
  $folders = \wp_rml_objects();
  $picture_folder = \wp_rml_get_object_by_id( \_wp_rml_root() );
  foreach ( $folders as $folder ) {
    if ( \is_rml_folder( $folder ) === true ) {
      $folder_path = urldecode( $folder->getPath() );
      if ( $folder_path == $path ) {
        $picture_folder = $folder;
        break;
      };
    };
  };
  return \wp_rml_get_attachments( $picture_folder->getId() );
}

function post_media( $parentURL ) {
  return folder_media( $parentURL . '/' . get_the_title() );
}