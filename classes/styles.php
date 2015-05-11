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
}