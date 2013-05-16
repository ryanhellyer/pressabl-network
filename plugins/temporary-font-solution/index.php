<?php
/*

Plugin Name: Temporary Fonts
Plugin URI: http://pixopoint.com/
Description: Temporary fonts plugin for ryanhellyer.net. Should be improved for public release at some point
Author: Ryan Hellyer
Version: 1.0
Author URI: http://pixopoint.com/

Copyright (c) 2012 Ryan Hellyer

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
license.txt file included with this plugin for more information.

*/


/**
 * Define constants
 * 
 * @since 1.0
 * @author Ryan Hellyer <ryan@pixopoint.com>
 */
define( 'TEMPFONTS_DIR', dirname( __FILE__ ) . '/' ); // Plugin folder DIR
define( 'TEMPFONTS_URL', WP_PLUGIN_URL . '/' . basename( TEMPFONTS_DIR ) ); // Plugin folder URL

/*
 * Adds CSS to front end of site
 * 
 * @since 1.0
 * @author Ryan Hellyer <ryan@pixopoint.com>
 */
function temporary_fonts() {
	wp_enqueue_style( 'font-squirrel-fonts', TEMPFONTS_URL . '/fonts.css', false, '', 'screen' );
}
add_action( 'wp_enqueue_scripts', 'temporary_fonts' );


