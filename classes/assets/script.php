<?php
namespace Cleaner;
class Script extends Asset {
  function extension() {
    return '.js';
  }

  function render_html() {
    return '<script type="text/javascript" src="' . $this->url() . '"></script>';
  }
}