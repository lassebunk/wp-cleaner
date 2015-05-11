<?php
namespace Cleaner;

class Asset {
  var $sources = array();

  function add( $url ) {
    $this->sources[] = new Source($url, $this->filters());
  }

  function url() {
    $filename = $this->filename();
    $path = \Cleaner::asset_path($filename);

    if (!file_exists($path)) {
      $content = $this->build();
      file_put_contents( $path, $content );
    }

    return \Cleaner::asset_url($filename);
  }

  function extension() {
    return '';
  }

  function filename() {
    return md5(serialize($this->sources)) . $this->extension();
  }

  function filters() {
    return array();
  }

  function build() {
    $content = array_map(
      function($source) {
        return $source->content();
      },
      $this->sources
    );
    return join( "\n\n", $content );
  }
}