<?php
namespace Cleaner;

class Source {
  var $url;
  var $path;
  var $filters;

  function __construct( $source_url, $filters = array() ) {
    $this->url = $source_url;
    $this->path = \Cleaner::cache_path( md5( $source_url ) );
    $this->filters = $filters;
  }

  function content( $filters = array() ) {
    if (file_exists($this->path)) {
      $content = file_get_contents($this->path);
    } else {
      $content = $this->render();
      file_put_contents($this->path, $content);
    }
    return $content;
  }

  function render() {
    $content = file_get_contents( $this->optimized_url() );
    $content = $this->apply_filters( $content );
    return $this->content_header() . $content;
  }

  function apply_filters( $content ) {
    foreach ( $this->filters as $filter ) {
      $filter = new $filter( $this );
      $content = $filter->apply( $content );
    }
    return $content;
  }

  function dirname() {
    return dirname( $this->url );
  }

  function optimized_url() {
    $url = $this->url;
    if ( $this->is_local() ) {
      return ABSPATH . $this->relative_path();
    } elseif ( substr( $url, 0, 2 ) == '//' ) {
      return 'http:' . $url;
    } else {
      return $url;
    }
  }

  function is_local() {
    return strpos( $this->url, get_home_url() ) !== false;
  }

  function relative_path() {
    $path = str_replace( get_home_url() . '/', '', $this->url );
    $path = explode( '?', $path )[0];
    return $path;
  }

  function content_header() {
    return "/*********\n" .
           "Source: " . $this->url . "\n" .
           "**********/\n\n";
  }
}