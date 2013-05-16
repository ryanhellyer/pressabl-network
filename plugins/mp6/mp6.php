<?php
/*
Plugin Name: MP6
Plugin URI: http://wordpress.org/extend/plugins/mp6/
Description: This is a plugin to break the wp-admin UI, and is not recommended for non-savvy users.
Version: 0.2
Author:
Author URI: http://wordpress.org
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'admin_init', 'mp6_register_admin_color_schemes', 1);
function mp6_register_admin_color_schemes() {
	wp_admin_css_color( 'mp6', _x( 'MP6', 'admin color scheme' ), plugins_url( 'css/colors-mp6.css', __FILE__ ),
		array( '#333', '#444', '#0074a2', '#2ea2cc' ) );
	
	// hack to adjust the version, because the enqueue system has no nice way to modify script data once it's already added elsewhere
	$modtime = filemtime( plugin_dir_path( __FILE__ ) .'css/colors-mp6.css' );
	global $wp_styles;
	$wp_styles->registered['colors']->ver = $modtime;
}

add_filter( 'get_user_option_admin_color', 'mp6_force_admin_color' );
function mp6_force_admin_color( $color_scheme ) {
	return 'mp6';
}
