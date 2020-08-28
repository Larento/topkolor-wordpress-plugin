<?php
namespace tk\classes;

class product_kind {
  public ?\WP_Term $wp_object;
  public string $label;
  public string $slug;
  public ?int $folderID;

  public function __construct( string $label, string $slug, string $taxonomy_slug ) {
    $this->label = $label;
    $this->slug = $slug;
    $this->wp_object = $this->add_kind( $this->label, $this->slug, $taxonomy_slug );
  }

  private function add_kind( string $label, string $slug, string $taxonomy_slug ) {
    $id = wp_insert_term( $label, $taxonomy_slug, [
      'parent'      => 0,
      'slug'        => $slug,
    ]);
    if ( is_wp_error($id) ) {
      return get_term_by( 'slug', $slug, $taxonomy_slug ); 
    }
    return get_term_by( 'term_taxonomy_id', $id[1] );
  }
}

class product_taxonomy {
  public ?\WP_Taxonomy $wp_object;
  public string $slug;
  public string $url_slug;
  public ?array $kinds;

  public function __construct( string $label, string $slug, string $url_slug, array $kinds ) {
    $this->slug = $slug;
    $this->url_slug = $url_slug;
    $this->wp_object = $this->register( $label, $this->slug, $this->url_slug );
    foreach ($kinds as $kind_label => $kind_slug) {
      $kind = new product_kind( $kind_label, $kind_slug, $slug );
      $this->kinds[] = $kind;
    }
  }

  private function register( string $label, string $slug, string $url_slug ) {
    $labels = [
      'name'              => _x("$label Product Kinds", 'taxonomy general name'),
      'singular_name'     => _x("$label Product Kind", 'taxonomy singular name'),
      'search_items'      => __('Search Product Kinds'),
      'all_items'         => __('All Product Kinds'),
      'parent_item'       => __('Parent Product Kind'),
      'parent_item_colon' => __('Parent Product Kind:'),
      'edit_item'         => __('Edit Product Kind'),
      'update_item'       => __('Update Product Kind'),
      'add_new_item'      => __('Add New Product Kind'),
      'new_item_name'     => __('New Product Kind Name'),
      'menu_name'         => __("$label Product Kinds"),
    ];
    $args = [
      'description'       => 'Product Kind',
      'hierarchical'      => false,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => [
        'slug'              => $url_slug,
        'with_front'        => false,
      ],
    ];
    return register_taxonomy( $slug, substr( $slug, 0, (strpos( $slug, 'kind' ) - 1) ), $args );
  }
}

class product {
  public ?\WP_Post_Type $wp_object;
  public string $label;
  public string $slug;
  public string $url_slug;
  public string $archive_name;
  public product_taxonomy $taxonomy;
  private array $kinds;
  public ?int $folderID;

  public function __construct( string $label, string $url_slug, string $archive_name, array $kinds ) {
    $this->label = $label;
    $this->url_slug = $url_slug;
    $this->archive_name = $archive_name;
    $this->kinds = $kinds;
    $this->slug = substr( $this->url_slug, 0, 3 ) . "_product";
    $this->wp_add();
  }

  private function register ( string $label, string $slug, string $url_slug, string $taxonomy_slug, string $archive_name) {
    $labels = [
      'name'              => _x("$label Products", 'post type general name' ),
      'singular_name'     => _x( "$label Product", 'post type singular name' ),
      'add_new'           => _x( 'Add New', 'product' ),
      'add_new_item'      => __( 'Add New Product' ),
      'edit_item'         => __( 'Edit Product' ),
      'new_item'          => __( 'New Product' ),
      'all_items'         => __( $archive_name ),
      'view_item'         => __( 'View Product' ),
      'search_items'      => __( 'Search Products' ), 
      'menu_name'         => __("$label Products"),
    ];
    $args = [
      'labels'            => $labels,
      'public'            => true,
      'description'       => 'Product',
      'menu_position'     => 1,
      'supports'          => ['title', 'editor', 'thumbnail', 'excerpt'],
      'has_archive'       => $url_slug,
      'rewrite'           => [
        'slug'              => $url_slug . "/%" . $taxonomy_slug . "%",
        'with_front'        => false,
      ],
    ];
    return register_post_type( $slug, $args ); 
  }

  public function add_register() {
    $taxonomy_slug = $this->slug . "_kind";
    $this->wp_object = $this->register( $this->label, $this->slug, $this->url_slug, $taxonomy_slug, $this->archive_name );
    $this->taxonomy = new product_taxonomy( $this->label, $taxonomy_slug, $this->url_slug, $this->kinds );
  }

  public function add_permalink_filter($post_link, $post, $leavename, $sample) {
    $taxonomy_slug = $this->slug . "_kind";
    if ( strpos($post_link, "%" . $taxonomy_slug . "%") !== false ) {
      $taxonomy_terms = get_the_terms($post->ID, $taxonomy_slug);
      if ( !empty($taxonomy_terms) ) {
        $post_link = str_replace("%" . $taxonomy_slug . "%", array_pop($taxonomy_terms)->slug, $post_link);
      } else {
        $post_link = str_replace("%" . $taxonomy_slug . "%", 'uncategorized', $post_link);
      };
    };
    return $post_link;
  }

  private function wp_add() {
    add_action('init', array($this, 'add_register'));
    add_filter('post_type_link', array($this, 'add_permalink_filter'), 10, 4);
    add_action('init', array($this, 'set_folders'), 99);
  }

  public function wp_remove() {
    remove_action('init', array($this, 'add_register'));
    remove_filter('post_type_link', array($this, 'add_permalink_filter'), 10, 4);
  }

  private function set_folders() {
    $this->folderID = \tk\functions\create_rml_folder( $this->archive_name, \_wp_rml_root() );
    foreach ( $this->taxonomy->kinds as $kind ) {
      $kind->folderID = \tk\functions\create_rml_folder( $kind->label, $this->folderID );
    }
  }
}



