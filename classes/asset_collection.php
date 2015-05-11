<?php
namespace Cleaner;

class AssetCollection {
  var $sources = array();

  function add($url) {
    $this->sources[] = $url;
  }

  function url() {
    $filename = $this->filename();
    $path = $this->asset_path($filename);

    if (!file_exists($path)) {
      $this->build($path, $sources);
    }

    return $this->asset_url($filename);
  }

  function extension() {
    return '';
  }

  function transform($source, $relative_path) {
    return $source;
  }

  function filename() {
    return md5(serialize($this->sources)) . $this->extension();
  }

  function asset_path($file = '') {
    return WP_CONTENT_DIR . '/assets/' . $file;
  }

  function asset_url($file) {
    return content_url( 'assets/' . $file );
  }

  function cache_path($url = '') {
    $hash = '';

    if ($url) {
      $hash = md5($url);
    }

    return WP_CONTENT_DIR . '/assets/cache/' . $hash;
  }

  function cached_asset($url) {
    $path = $this->cache_path($url);
    if (file_exists($path)) {
      $content = file_get_contents($path);
    } else {
      $url = $this->optimized_url($url);
      $content = file_get_contents($url);
      file_put_contents($path, $content);
    }
    return $content;
  }

  function optimized_url($url) {
    if ( $this->is_local($url) ) {
      return ABSPATH . $this->relative_path( $url );
    } elseif ( substr( $url, 0, 2 ) == '//' ) {
      return 'http:' . $url;
    } else {
      return $url;
    }
  }

  function relative_path($url) {
    $path = str_replace( get_home_url() . '/', '', $url );
    $path = explode( '?', $path )[0];
    return $path;
  }

  function is_local($url) {
    return strpos( $url, get_home_url() ) !== false;
  }

  function build($file_path, $sources) {
    $content = array();
    foreach ($this->sources as $url) {
      $content[] = "/*********\n" .
                   " Source: $url\n" .
                   "**********/\n\n" .
                   $this->cached_asset($url);
    }
    file_put_contents($file_path, join("\n\n", $content));
  }
}