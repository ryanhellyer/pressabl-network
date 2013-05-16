<?php
/*
Plugin Name: WP Paintbrush CSS Generator
Plugin URI: http://wppaintbrush.com/
Description: CSS generator for WP Paintbrush theme and some plugins
Author: Ryan Hellyer / Dan Milward
Version: 0.1
Author URI: http://wppaintbrush.com/
*/



/* Accepting requests from external websites
 * Checks ABSPATH to ensure that WordPress hasn't been loaded
 * This code will only be used for the external API and is ignored when used internally (note the use of die;)
 * @since 0.1
 */
if ( !defined( 'ABSPATH' ) ) {
	if ( empty( $_GET['wppb_css_generator'] ) )
		$_GET['wppb_css_generator'] = '';
	if ( 'process' == $_GET['wppb_css_generator'] ) {
		require( 'processor.php' );
		echo $style;
	}
	die;
}




/* Add internal CSS
 * Bypasses external API to process CSS
 * @since 0.1
 */
function wppaintbrush_css_generator() {
	global $css;

	require( 'processor.php' );

	if ( !isset( $css ) )
		$css = '';
	$css .= $style;
	//$css = 'scsc';
}
add_action( 'wppb_add_css', 'wppaintbrush_css_generator' );

/* Turning off external API
 * Prevents both internal and external API's being used at once 
 * @since 0.1
 */
function wppb_add_internal_css_init() {
	remove_action( 'wppb_add_css', 'wppb_add_external_css' );
}
add_action( 'init', 'wppb_add_internal_css_init', 1 );

