<?php
namespace Cleaner;

class UrlFilter extends Filter {
  function apply( $content ) {
    return preg_replace_callback(
      '/url\(["\']?(?!\/\/|(?:http|data):)(?U:(.*))["\']?\)/',
      function($matches) {
        $url = $this->transform_url( $matches[1] );
        return "url($url)";
      },
      $content
    );
  }

  function transform_url( $url ) {
    if ( substr( $url, 0, 1 ) == '/' ) {
      return $url;
    }
    else {
      return $this->source->dirname() . '/' . $url;
    }
  }

}