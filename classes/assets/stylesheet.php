<?php
namespace Cleaner;

class Stylesheet extends Asset {
  function filters() {
    return array(
      'Cleaner\UrlFilter'
    );
  }

  function should_keep($style_handle) {
    global $wp_styles;

    $options = $wp_styles->registered[$style_handle]->extra;

    return isset($options['conditional']);
  }

  function extension() {
    return '.css';
  }

  function render_html() {
    return '<link rel="stylesheet" type="text/css" href="' . $this->url() . '" />';
  }
}