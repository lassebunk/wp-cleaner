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

require_once( dirname(__FILE__) . '/classes/asset_collection.php' );
require_once( dirname(__FILE__) . '/classes/cleaner.php' );
require_once( dirname(__FILE__) . '/classes/scripts.php' );
require_once( dirname(__FILE__) . '/classes/styles.php' );

$cleaner = new Cleaner;

register_activation_hook( __FILE__, array( $cleaner, 'activate' ) );