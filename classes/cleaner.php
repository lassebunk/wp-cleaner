<?php
class Cleaner {
  var $styles;
  var $scripts;

  function __construct() {
    $this->styles = new Cleaner\Styles;
    $this->scripts = new Cleaner\Scripts;

    add_filter( 'style_loader_src', array( $this, 'style_src' ), 10000, 2 );
    add_filter( 'script_loader_src', array( $this, 'script_src' ) );
    add_action( 'wp_head', array( $this, 'head' ) );
  }

  function activate() {
    if ( !file_exists( asset_path() ) )
      mkdir( asset_path(), 0755, true );
    if ( !file_exists( cache_path() ) )
      mkdir( cache_path(), 0755, true );
  }

  function head($content) {
    global $scripts, $styles;

    $assets = array(
      '<link rel="stylesheet" type="text/css" href="' . $this->styles->url() . '" />',
      '<script type="text/javascript" src="' . $this->scripts->url() . '"></script>'
    );

    echo join("\n", $assets) . "\n";
  }

  function style_src($url, $handle) {
    global $cleaner;
    if ( $cleaner->styles->should_keep( $handle ) ) {
      return $url;
    } else {
      $cleaner->styles->add( $url );
    }
  }

  function script_src($url) {
    global $cleaner;
    $cleaner->scripts->add( $url );
  }
}