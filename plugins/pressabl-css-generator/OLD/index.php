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
 * @since 0.1
 */
if ( !defined( 'ABSPATH' ) ) {
	if ( empty( $_GET['wppb_css_generator'] ) )
		$_GET['wppb_css_generator'] = '';
	if ( 'process' == $_GET['wppb_css_generator'] ) {
		require( 'processor.php' );
		echo $style;
		die;
	}
}

/* Add internal CSS
 * Bypasses external API to process CSS
 * @since 0.1
 */
function wppaintbrush_css_generator() {
	global $stylesheet, $content_layout;
//$_POST = $content_layout;

	require( 'processor.php' );
	//echo $style;

	if ( !isset( $stylesheet ) )
		$stylesheet = '';
	$stylesheet .= $style;
}
return;
add_action( 'wppb_add_css', 'wppaintbrush_css_generator' );

/*
if ( isset( $_GET['generator-css'] ) ) {
	if ( '' != $_GET['generator-css'] )
		add_action( 'init', 'wppaintbrush_css_generator' );
}
*/

/* Add internal CSS
 * Bypasses external API to process CSS
 * @since 1.0
function wppb_add_internal_css() {
	global $content_layout;

//	$_POST = $content_layout;
//	require( WP_PLUGIN_DIR . '/pressabl-css-generator/processor.php' );

	wppaintbrush_css_generator();	
	
	if ( !isset( $stylesheet ) )
		$stylesheet = '';
	$stylesheet .= $style;
}
 */

function wppb_add_internal_css_init() {
	remove_action( 'wppb_add_css', 'wppb_add_external_css' );
}
add_action( 'init', 'wppb_add_internal_css_init', 1 );

