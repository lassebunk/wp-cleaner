<?php
namespace Cleaner;

require_once( dirname( __FILE__ ) . '/../../lib/JShrink/Minifier.php' );

class JShrinkFilter extends Filter {
  function apply( $content ) {
    return \JShrink\Minifier::minify( $content );
  }
}