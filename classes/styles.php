<?php
namespace Cleaner;

class Styles extends AssetCollection {
  function should_keep($style_handle) {
    global $wp_styles;

    $options = $wp_styles->registered[$style_handle]->extra;

    return isset($options['conditional']);
  }

  function extension() {
    return '.css';
  }

  function transform($source, $relative_path) {
    return preg_replace_callback(
      '/url\(["\']?(?!\/\/|(?:http|data):)(?U:(.*))["\']?\)/',
      function($matches) use ( $relative_path ) {
        $url = $this->transform_url($matches[1], $relative_path);
        return "url($url)";
      },
      $source
    );
  }

  function transform_url($url, $relative_path) {
    if ( substr($url, 0, 1) == '/' ) {
      return $url;
    }
    else {
      return $relative_path . '/' . $url;
    }
  }
}