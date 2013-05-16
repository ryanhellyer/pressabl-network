<?php
/*

Plugin Name: Disable Grunion Admin link
Plugin URI: http://pixopoint.com/products/disable-grunion-admin-link/
Description: Disables the admin link in the Grunion Contact Form plugin
Author: PixoPoint Web Development / Ryan Hellyer
Version: 1.2
Author URI: http://pixopoint.com/

Copyright (c) 2009 PixoPoint Web Development

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

Code developed from http://teleogistic.net/2010/08/hiding-wordpress-custom-post-type-menu-items-without-disabling-edit-access/

*/


/**
 * Tell WordPress to expect a custom menu order
 * 
 * @since 1.0
 * @author Ryan Hellyer <ryan@pixopoint.com>
 */
 function pixopoint_toggle_menu_order(){
	return true;
}
add_filter( 'custom_menu_order', 'pixopoint_toggle_menu_order' );

/**
 * Erase menu items
 * 
 * @since 1.0
 * @author Ryan Hellyer <ryan@pixopoint.com>
 * @param string $menu_order
 */
function pixopoint_grunion_remove( $menu_order ){
	global $menu;

	foreach ( $menu as $mkey => $m ) {
		$key = array_search( 'edit.php?post_type=feedback', $m );

		if ( $key )
			unset( $menu[$mkey] );
	}

	return $menu_order;
}
add_filter( 'menu_order', 'pixopoint_grunion_remove' );

/**
 * Adds admin menu
 * 
 * @since 0.1
 * @author Ryan Hellyer <ryan@pixopoint.com>
 */
function pixopoint_grunion_options() {

	// Feedbacks - Grunion Contact Form
	$page = add_dashboard_page(
		'Form feedbacks',
		'Form feedbacks',
		'administrator',
		'pixopoint_grunion_page',
		'pixopoint_grunion_redirect'
	);
}
add_action( 'admin_menu', 'pixopoint_grunion_options' );

/**
 * Admin menu page redirect
 * 
 * @since 1.2
 * @author Ryan Hellyer <ryan@pixopoint.com>
 * @todo Change from meta redirect to 301 redirect - for some reason the 301 stopped working so temporarily switched to meta redirect to keep the punters happy
 */
function pixopoint_grunion_redirect() {
	echo '<meta http-equiv="refresh" content="0;url=' . admin_url() . 'edit.php?post_type=feedback' . '" />';
}
