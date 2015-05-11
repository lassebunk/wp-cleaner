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

require_once( dirname(__FILE__) . '/classes/cleaner.php' );
require_once( dirname(__FILE__) . '/classes/asset.php' );
require_once( dirname(__FILE__) . '/classes/source.php' );
require_once( dirname(__FILE__) . '/classes/script.php' );
require_once( dirname(__FILE__) . '/classes/stylesheet.php' );
require_once( dirname(__FILE__) . '/classes/filter.php' );
require_once( dirname(__FILE__) . '/classes/filters/url_filter.php' );

$cleaner = new Cleaner;
$cleaner->clear();

register_activation_hook( __FILE__, array( $cleaner, 'activate' ) );