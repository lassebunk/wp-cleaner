<?php
/**
 * Plugin Name: Cleaner
 * Plugin URI: https://github.com/lassebunk/wp-cleaner
 * Description: Joins assets into a single CSS and JS file.
 * Version: 1.0.0
 * Author: Lasse Bunk
 * Author URI: https://github.com/lassebunk
 * License: GPLv2 or later
 */

$cleaner_scripts = array();
$cleaner_styles = array();

function cleaner_asset_url($file) {
  return content_url( 'assets/' . $file );
}

function cleaner_asset_path($file = '') {
  return WP_CONTENT_DIR . '/assets/' . $file;
}

function cleaner_cache_path($url = '') {
  $hash = '';

  if ($url) {
    $hash = md5($url);
  }

  return WP_CONTENT_DIR . '/assets/cache/' . $hash;
}

function cleaner_activate() {
  if ( !file_exists( cleaner_asset_path() ) )
    mkdir( cleaner_asset_path(), 0755, true );
  if ( !file_exists( cleaner_cache_path() ) )
    mkdir( cleaner_cache_path(), 0755, true );
}

function cleaner_enqueue_script($url) {
  global $cleaner_scripts;
  $cleaner_scripts[] = $url;
}

function cleaner_enqueue_style($url) {
  global $cleaner_styles;
  $cleaner_styles[] = $url;
}

function cleaner_get_cached_asset($url) {
  $path = cleaner_cache_path($url);
  if (file_exists($path)) {
    $content = file_get_contents($path);
  } else {
    $url = cleaner_optimize_url($url);
    $content = file_get_contents($url);
    file_put_contents($path, $content);
  }
  return $content;
}

function cleaner_optimize_url($url) {
  if ( cleaner_is_local($url) ) {
    return ABSPATH . cleaner_get_relative_path( $url );
  } elseif ( substr( $url, 0, 2 ) == '//' ) {
    return 'http:' . $url;
  } else {
    return $url;
  }
}

function cleaner_style_filter($url, $handle) {
  if ( cleaner_should_keep($handle) ) {
    return $url;
  } else {
    cleaner_enqueue_style($url);
  }
}

function cleaner_script_filter($url) {
  cleaner_enqueue_script($url);
}

function cleaner_get_relative_path($url) {
  $path = str_replace( get_home_url() . '/', '', $url );
  $path = explode( '?', $path )[0];
  return $path;
}

function cleaner_is_local($url) {
  return strpos( $url, get_home_url() ) !== false;
}

function cleaner_should_keep($handle) {
  global $wp_styles;

  $options = $wp_styles->registered[$handle]->extra;

  return isset($options['conditional']);
}

function cleaner_build_asset($file_path, $sources) {
  $content = array();
  foreach ($sources as $url) {
    $content[] = "/*********\n" .
                 " Source: $url\n" .
                 "**********/\n\n" .
                 cleaner_get_cached_asset($url);
  }
  file_put_contents($file_path, join("\n\n", $content));
}

function cleaner_head($content) {
  global $cleaner_scripts, $cleaner_styles;

  $script_file = md5(serialize($cleaner_scripts)) . '.js';
  $style_file = md5(serialize($cleaner_styles)) . '.css';
  $script_path = cleaner_asset_path($script_file);
  $style_path = cleaner_asset_path($style_file);

  if (!file_exists($script_path)) {
    cleaner_build_asset($script_path, $cleaner_scripts);
  }

  if (!file_exists($style_path)) {
    cleaner_build_asset($style_path, $cleaner_styles);
  }

  $assets = array(
    '<script type="text/javascript" src="' . cleaner_asset_url($script_file) . '"></script>',
    '<link rel="stylesheet" type="text/css" href="' . cleaner_asset_url($style_file) . '" />'
  );

  echo join("\n", $assets) . "\n";
}

register_activation_hook( __FILE__, 'cleaner_activate' );
add_filter( 'style_loader_src', 'cleaner_style_filter', 10000, 2 );
add_filter( 'script_loader_src', 'cleaner_script_filter' );
add_action( 'wp_head', 'cleaner_head' );
