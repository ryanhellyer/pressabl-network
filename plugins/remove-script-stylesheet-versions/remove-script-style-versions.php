<?php
/*
Plugin Name: Remove Script & Stylesheet Versions
Description: Removes the version string (?ver=) from scripts and stylesheets.
Author: Kawauso
Version: 1.0
*/

function rssv_scripts() {
	global $wp_scripts;
	if ( !is_a( $wp_scripts, 'WP_Scripts' ) )
		return;
	foreach ( $wp_scripts->registered as $handle => $script )
		$wp_scripts->registered[$handle]->ver = null;
}

function rssv_styles() {
	global $wp_styles;
	if ( !is_a( $wp_styles, 'WP_Styles' ) )
		return;
	foreach ( $wp_styles->registered as $handle => $style )
		$wp_styles->registered[$handle]->ver = null;
}

add_action( 'wp_print_scripts', 'rssv_scripts', 100 );
add_action( 'wp_print_footer_scripts', 'rssv_scripts', 100 );

add_action( 'admin_print_styles', 'rssv_styles', 100 );
add_action( 'wp_print_styles', 'rssv_styles', 100 );