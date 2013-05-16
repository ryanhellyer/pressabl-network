<?php
/*

Plugin Name: Spam Destroyer
Plugin URI: http://geek.ryanhellyer.net/products/spam-destroyer/
Description: Kills spam dead in it's tracks
Author: Ryan Hellyer
Version: 1.4.5 alpha
Author URI: http://geek.ryanhellyer.net/

Copyright (c) 2013 Ryan Hellyer



The plugin was heavily inspired by the work of
Donncha O Caoimh (http://ocaoimh.ie/cookies-for-comments/)
and Elliot Back (http://wordpress-plugins.feifei.us/hashcash/)


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
license.txt file included with this plugin for more information.

*/


define( 'SPAM_DESTROYER_VERSION', '1.4.4' ); // version number
define( 'SPAM_DESTROYER_LOGGING', 'true' ); // log statistics
define( 'SPAM_DESTROYER_AUTODELETE', false ); // automatically delete spam messages?
define( 'SPAM_DESTROYER_URL', plugins_url( '/',  __FILE__ ) ); // URL for the plugins folder
define( 'SPAM_DESTROYER_STATS_BLOCK_SIZE', 2 ); // number of comments to track before phoning home
define( 'SPAM_DESTROYER_API', 'http://geek.ryanhellyer.net/' ); // Location for phoning home to
//define( 'SPAM_DESTROYER_API', 'http://localhost/wordpress/' ); // Location for phoning home to

require( 'inc/class-spam-destroyer.php' );
$spam_destroyer = new Spam_Destroyer();

require( 'inc/class-spam-destroyer-statistics.php' );
new Spam_Destroyer_Statistics;

require( 'inc/class-spam-destroyer-tracking.php' );
new Spam_Destroyer_Tracking;
