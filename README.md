# Cleaner
Contributors: lassebunk
Tags: assets, javascript, stylesheet, css, js, minify, uglify, uglifier
Requires at least: 4.0
Tested up to: 4.2.2
Stable tag: 0.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your site faster by automatically combining your JavaScript and CSS assets
into two single files.

## Description

Cleaner makes your WordPress site faster by combining your JavaScript and CSS
assets into two single files – one `.js` file and one `.css` file. These
files are then served like normal, but with a lot fewer HTTP requests.
This is all done automatically, without any setup or maintenance.

The plugin works by tapping into the URLs of stylesheets and JavaScripts added
by other plugins. If these are marked as "special", e.g. conditional (`gt IE 8`
etc.), they are left untouched. If not, then they are downloaded (for remote
files) or copied (for local files), and combined into two single files.

The combined files are named like this:

* `/wp-content/assets/695958a57ee90c6495158a09dd6ba972.css`
* `/wp-content/assets/79af08af99f9f6a29729a973c2682174.js`

Where the filename is a hash of the URLs that is contained by that file.
This also means that if a stylesheet or script is added or removed, the
combined asset will be rebuilt. This will only happen on the first request
– after that, the assets will be taken from the cache and served to the user.

## Installation

1. Download the plugin to `wp-content/plugins`.
2. Activate the plugin.

## Contributing

Contributions are appreciated and very welcome. You can contribute in the
plugin's [GitHub repository](https://github.com/lassebunk/wp-cleaner).