<?php
namespace Cleaner;

class Filter {
  var $source;

  function __construct( $source ) {
    $this->source = $source;
  }

  function apply( $content ) {
    return $content;
  }
}