<?php
class Cleaner {
  var $stylesheet;
  var $header_script;
  var $footer_script;

  static function asset_path($filename = '') {
    return wp_upload_dir()['basedir'] . '/cleaner-assets/' . $filename;
  }

  static function asset_url($filename) {
    return wp_upload_dir()['baseurl'] . '/cleaner-assets/' . $filename;
  }

  static function cache_path($filename = '') {
    return self::asset_path('cache/' . $filename);
  }

  function __construct() {
    $this->stylesheet = new Cleaner\Stylesheet;
    $this->header_script = new Cleaner\Script( array( 'position' => 'head' ) );
    $this->footer_script = new Cleaner\Script( array( 'position' => 'footer' ) );

    if ( !is_admin() ) {
      add_filter( 'style_loader_src',        array( $this, 'style_src' ), 10000, 2 );
      add_filter( 'script_loader_src',       array( $this, 'script_src' ), 10000, 2 );
      add_action( 'wp_head',                 array( $this, 'head' ), 9 );
      add_action( 'wp_print_footer_scripts', array( $this, 'footer' ) );
    }
  }

  function activate() {
    $assets_path = self::asset_path();
    if ( !file_exists( $assets_path ) )
      mkdir( $assets_path, 0755, true );

    $cache_path = self::cache_path();
    if ( !file_exists( $cache_path ) )
      mkdir( $cache_path, 0755, true );
  }

  function clear() {
    exec( 'rm ' . self::cache_path('*') );
    exec( 'rm ' . self::asset_path('*') );
  }

  function head($content) {
    $this->print_assets( array(
      $this->header_script,
      $this->stylesheet
    ));
  }

  function footer($content) {
    $this->print_assets( array(
      $this->footer_script
    ));
  }

  function print_assets( $assets ) {
    $lines = array_map( function($asset) {
      return $asset->html();
    }, $assets );
    $html = join("\n", array_filter( $lines ) );
    if ( $html ) echo $html . "\n";
  }

  function style_src( $url, $handle ) {
    if ( $this->stylesheet->should_keep( $handle ) ) {
      return $url;
    } else {
      $this->stylesheet->add( $url );
    }
  }

  function script_src( $url, $handle ) {
    global $wp_scripts;

    if ( isset( $wp_scripts->registered[$handle] ) ) {
      $options = $wp_scripts->registered[$handle]->extra;
      if ( !isset( $options['group'] ) || $options['group'] == 0 ) {
        $this->header_script->add( $url );
      } elseif ( $options['group'] == 1 ) {
        $this->footer_script->add( $url );
      }
    } else {
      return $url;
    }
  }
}