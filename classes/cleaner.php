<?php
class Cleaner {
  var $stylesheet;
  var $script;

  static function asset_path($filename = '') {
    return WP_CONTENT_DIR . '/assets/' . $filename;
  }

  static function asset_url($filename) {
    return content_url( 'assets/' . $filename );
  }

  static function cache_path($filename = '') {
    return self::asset_path('cache/' . $filename);
  }

  function __construct() {
    $this->stylesheet = new Cleaner\Stylesheet;
    $this->script = new Cleaner\Script;

    add_filter( 'style_loader_src', array( $this, 'style_src' ), 10000, 2 );
    add_filter( 'script_loader_src', array( $this, 'script_src' ) );
    add_action( 'wp_head', array( $this, 'head' ) );
  }

  function activate() {
    if ( !file_exists( $this->assets_path ) )
      mkdir( $this->assets_path, 0755, true );
    if ( !file_exists( $this->cache_path ) )
      mkdir( $this->cache_path, 0755, true );
  }

  function clear() {
    exec( 'rm ' . self::cache_path('*') );
    exec( 'rm ' . self::asset_path('*') );
  }

  function head($content) {
    $assets = array(
      '<link rel="stylesheet" type="text/css" href="' . $this->stylesheet->url() . '" />',
      '<script type="text/javascript" src="' . $this->script->url() . '"></script>'
    );

    echo join("\n", $assets) . "\n";
  }

  function style_src( $url, $handle ) {
    if ( $this->stylesheet->should_keep( $handle ) ) {
      return $url;
    } else {
      $this->stylesheet->add( $url );
    }
  }

  function script_src( $url ) {
    $this->script->add( $url );
  }
}