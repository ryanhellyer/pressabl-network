<?php


if ( defined( 'PTC_DIR' ) )
	require_once( PTC_DIR . '/plugins.php' ); // Use functions specifically used by plugins (use require_once() to ensure that file isn't loaded twice by separate plugins)
else
	require_once( '../../themes/wppaintbrush/designer/plugins.php' ); // Use functions specifically used by plugins (use require_once() to ensure that file isn't loaded twice by separate plugins)



// Um, yeah, nasty way to suprress WP_DEBUG errors :P
ob_start();




// This file needs improved formatting and upgrade to "asides" which still uses legacy (pre version 0.8) formatting

	$style = "\n"; // bizarre requirement to prevent a glitch on using .= instead of just =


foreach ( $_POST as $option=>$value ) {
	$content_layout[$option] = $value;
}


/**
 * Convert a hexa decimal color code to its RGB equivalent
 * Code modified from http://php.net/manual/en/function.hexdec.php
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */
if ( !function_exists( 'ptc_hex2RGB' ) ) :
function ptc_hex2RGB( $hexStr, $returnAsString = true, $seperator = ',' ) {
	$hexStr = preg_replace( "/[^0-9A-Fa-f]/", '', $hexStr ); // Gets a proper hex string
	$rgbArray = array();
	if ( strlen( $hexStr ) == 6 ) { //If a proper hex code, convert using bitwise operation. No overhead... faster
		$colorVal = hexdec( $hexStr );
		$rgbArray['red'] = 0xFF & ( $colorVal >> 0x10 );
		$rgbArray['green'] = 0xFF & ( $colorVal >> 0x8 );
		$rgbArray['blue'] = 0xFF & $colorVal;
	}
	elseif ( strlen( $hexStr) == 3 ) { //if shorthand notation, need some string manipulations
		$rgbArray['red'] = hexdec( str_repeat( substr( $hexStr, 0, 1 ), 2 ) );
		$rgbArray['green'] = hexdec( str_repeat( substr( $hexStr, 1, 1 ), 2 ) );
		$rgbArray['blue'] = hexdec( str_repeat( substr( $hexStr, 2, 1 ), 2 ) );
	}
	else
		return false; //Invalid hex color code
	return $returnAsString ? implode( $seperator, $rgbArray ) : $rgbArray; // returns the rgb string or the associative array
}
endif;

// Dynamically convert fonts into longer/more useful string
function ptc_convert_fontfamily( $font ) {
	$font = str_replace( 'Helvetica Neue, Arial', "'Helvetica Neue', Arial, Helvetica, 'Nimbus Sans L', sans-serif", $font );
	$font = str_replace( 'Georgia, serif', "Georgia, 'Bitstream Charter', serif", $font );
	return $font;
}

function ptc_text_styling( $option, $content_layout ) {

	// Opacity - resetting to 1 due to not being support in current version
	$content_layout[$option . '_shadow_opacity'] = 1;

	// Setting some variables (to avoid WP_DEBUG errors)
	$vars_to_set = array(
		$option . '_fontfamily',
		$option . '_fontsize',
		$option . '_font_weight',
		$option . '_font_style',
		$option . '_line_height',
		$option . '_textdecoration',
		$option . '_text_transform',
		$option . '_small_caps',
		$option . '_shadow_x_coordinate',
		$option . '_shadow_y_coordinate',
		$option . '_shadow_blur_radius',
		$option . '_shadow_colour',
		$option . '_shadow_opacity',		
		$option . '_bordertop_width',
		$option . '_borderbottom_width',
		$option . '_bordertop_type',
		$option . '_borderbottom_type',
		$option . '_bordertop_colour',
		$option . '_borderbottom_colour',
		$option . '_margin_top',
		$option . '_margin_right',
		$option . '_margin_bottom',
		$option . '_margin_left',
		$option . '_padding_top',
		$option . '_padding_right',
		$option . '_padding_bottom',
		$option . '_padding_left',
	);
	foreach ( $vars_to_set as $var ) {			
		if ( empty( $content_layout[$var] ) )
			$content_layout[$var] = '';
	}

	$textstyling = "	font-family: " . ptc_convert_fontfamily( $content_layout[$option . '_fontfamily'] ) . ";
	font-size: " . $content_layout[$option . '_fontsize'] . "px;
	color: " . $content_layout[$option . '_textcolour'] . ";
	font-weight: " . $content_layout[$option . '_font_weight'] . ";
	font-style: " . $content_layout[$option . '_font_style'] . ";
	line-height: " . $content_layout[$option . '_line_height'] . "px;
	text-decoration: " . $content_layout[$option . '_textdecoration'] . ";
	text-transform: " . $content_layout[$option . '_text_transform'] . ";
	font-variant: " . $content_layout[$option . '_small_caps'] . ";
	text-shadow: " . $content_layout[$option . '_shadow_x_coordinate'] . "px " . $content_layout[$option . '_shadow_y_coordinate'] . "px " . $content_layout[$option . '_shadow_blur_radius'] . "px rgba(" . ptc_hex2RGB( $content_layout[$option . '_shadow_colour'] ) . ", " . $content_layout[$option . '_shadow_opacity'] . ");
	border-top: " . $content_layout[$option . '_bordertop_width'] . "px " . $content_layout[$option . '_bordertop_type'] . " " . $content_layout[$option . '_bordertop_colour'] . ";
	border-bottom: " . $content_layout[$option . '_borderbottom_width'] . "px " . $content_layout[$option . '_borderbottom_type'] . " " . $content_layout[$option . '_borderbottom_colour'] . ";
	margin: " . $content_layout[$option . '_margin_top'] . 'px ' . $content_layout[$option . '_margin_right'] . 'px ' . $content_layout[$option . '_margin_bottom'] . 'px ' . $content_layout[$option . '_margin_left'] . "px;
	padding: " . $content_layout[$option . '_padding_top'] . 'px ' . $content_layout[$option . '_padding_right'] . 'px ' . $content_layout[$option . '_padding_bottom'] . 'px ' . $content_layout[$option . '_padding_left'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, $option . '_background' ) . "
";
	return $textstyling;
}

// Correction of strings
$content_layout = str_replace( 'georgia, \&#039;Bitstream Charter\&#039;, serif', 'georgia, "Bitstream Charter", serif', $content_layout );
$content_layout = str_replace( 'georgia, \&#039;Bitstream Charter\&#039;, serif', 'georgia, "Bitstream Charter", serif', $content_layout );


// Crude hack to make legacy sidebar positions setup match the new inputs
$sidebar_positions = $content_layout['sidebar_positions'];
$sidebar_positions = str_replace( ',', '-', $sidebar_positions );
$sidebar_positions = str_replace( 'layout-', '', $sidebar_positions );
$sidebar_positions = str_replace( 'maincontent', 'content', $sidebar_positions );
$content_layout['asides'] = $sidebar_positions;
$check_if_string_in_haystack = strpos( $sidebar_positions, 'content' );
if ( $check_if_string_in_haystack === false )
	$style .= "#inner, #aside-1, #aside-2 {display: none;}\n\n";




// Load standard block wrapper CSS
function ptc_block_wrapper_css( $block, $content_layout ) {
	if ( !is_numeric( $content_layout[$block . '_border_top_width'] ) )
		$content_layout[$block . '_border_top_width'] = 0;
	if ( !is_numeric( $content_layout[$block . '_border_right_width'] ) )
		$content_layout[$block . '_border_right_width'] = 0;
	if ( !is_numeric( $content_layout[$block . '_border_bottom_width'] ) )
		$content_layout[$block . '_border_bottom_width'] = 0;
	if ( !is_numeric( $content_layout[$block . '_border_left_width'] ) )
		$content_layout[$block . '_border_left_width'] = 0;
	if ( !is_numeric( $content_layout[$block . '_bottom_margin'] ) )
		$content_layout[$block . '_bottom_margin'] = 0;
	if ( !is_numeric( $content_layout[$block . '_top_margin'] ) )
		$content_layout[$block . '_top_margin'] = 0;
	$html = "
	margin: " . $content_layout[$block . '_top_margin'] . "px auto " . $content_layout[$block . '_bottom_margin'] . "px auto;
	border-top: " . $content_layout[$block . '_border_top_width'] . "px " . $content_layout[$block . '_border_top_type'] . " " . $content_layout[$block . '_border_top_colour'] . ";
	border-right: " . $content_layout[$block . '_border_right_width'] . "px " . $content_layout[$block . '_border_right_type'] . " " . $content_layout[$block . '_border_right_colour'] . ";
	border-bottom: " . $content_layout[$block . '_border_bottom_width'] . "px " . $content_layout[$block . '_border_bottom_type'] . " " . $content_layout[$block . '_border_bottom_colour'] . ";
	border-left: " . $content_layout[$block . '_border_left_width'] . "px " . $content_layout[$block . '_border_left_type'] . " " . $content_layout[$block . '_border_left_colour'] . ";";
	return $html;
}

// Adding generated date info.
$style .= "/* Code generated " . date( 'l jS \of F Y h:i:s A' ) . " by WP Paintbrush CSS generator */";

// Beginning of main part of stylesheet
$style .= "
body,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,a,img,dl,dt,dd,ol,ul,li,fieldset,form,legend,table,tbody,tfoot,thead,tr,th,td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	list-style: none;
}
body {
	" . ptc_backgroundimage_creator( $content_layout, 'background', ' top center' ) . "
}

/* Header */
header {
	float: left;
	width: 100%;
	" . ptc_backgroundimage_creator( $content_layout, 'header_fullwidth_background' ) . "
}
header div.header {
	overflow: hidden;
	position: relative;
	display: block;
" . ptc_block_wrapper_css( 'header', $content_layout ) . "
	max-width: " . $content_layout['header_max_width'] . "px;
	min-width: " . $content_layout['header_min_width'] . "px;
	height: " . $content_layout['header_height'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, 'header_background' ) . "
}
* html header .wrapper {
	width: " . ( $content_layout['header_max_width'] + $content_layout['header_min_width'] ) / 2 . "px; /* Gives IE6 a fixed width (since it doesn't support max-width or min-width) */
}
header h1 {
	display: " . $content_layout['header_heading_display'] . ";
	position: absolute;
	left: 0;
	top: 0;
	text-align: " . $content_layout['header_heading_position_centered'] . ";
	width: 100%;
" . ptc_text_styling( 'header_heading', $content_layout ) . "
	margin-left: " . $content_layout['header_heading_position_x'] . "px;
	margin-top: " . $content_layout['header_heading_position_y'] . "px;
}
header h1 a {
	color: " . $content_layout['header_heading_textcolour'] . ";
	text-decoration: " . $content_layout['header_heading_textdecoration'] . ";
}
header #description {
	display: " . $content_layout['header_description_display'] . ";
	position: absolute;
	left: 0;
	top: 0;
	text-align: " . $content_layout['header_description_position_centered'] . ";
	width: 100%;
" . ptc_text_styling( 'header_description', $content_layout ) . "
	margin-left: " . $content_layout['header_description_position_x'] . "px;
	margin-top: " . $content_layout['header_description_position_y'] . "px;
}

header #logo {
	display: " . $content_layout['header_logo_display'] . ";
	position: absolute;
	left: 0;
	top: 0;
	text-indent: -999em;
	margin-left: " . $content_layout['header_logo_position_x'] . "px;
	margin-top: " . $content_layout['header_logo_position_y'] . "px;
	width: " . $content_layout['header_logo_width'] . "px;
	height: " . $content_layout['header_logo_height'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, 'header_logo_background' ) . "
}
header #search {
	display: " . $content_layout['header_searchbox_display'] . ";
	position: absolute;
	left: 0;
	top: 0;
	margin-left: " . $content_layout['header_searchbox_position_x'] . "px;
	margin-top: " . $content_layout['header_searchbox_position_y'] . "px;
	width: " . $content_layout['header_searchbox_width'] . "px;
	height: " . $content_layout['header_searchbox_height'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, 'header_searchbox_background' ) . "
}
header #search input {
	position: absolute;
	padding: 0;
	outline: none;
	border: none;
	background: none;
}
header #search input[type=text] {
	" . ptc_text_styling( 'searchtext', $content_layout ) . "
	position: relative;
	width: " . $content_layout['header_searchbox_text_width'] . "px;
	height: " . $content_layout['header_searchbox_text_height'] . "px;
	top: " . $content_layout['header_searchbox_text_position_y'] . "px;
	left: " . $content_layout['header_searchbox_text_position_x'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, 'header_searchbox_text_background' ) . "
}
header #search input[type=submit] {\n";
	if ( 'none' == $content_layout['header_searchsubmit_text_display'] )
		$style .= "	text-indent: -999em;\n";
	$style .= ptc_text_styling( 'searchsubmit', $content_layout ) . "
	position: absolute;
	width: " . $content_layout['header_searchsubmit_text_width'] . "px;
	height: " . $content_layout['header_searchsubmit_text_height'] . "px;
	top: " . $content_layout['header_searchsubmit_text_position_y'] . "px;
	left: " . $content_layout['header_searchsubmit_text_position_x'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, 'header_searchsubmit_text_background' ) . "
}
header #search input[type=submit]:hover {
	cursor: pointer;
}

/* Banner */
#banner {
	width: 100%;
	float: left;
}
#banner div.banner-image {
	max-width: " . $content_layout['banner_max_width'] . "px;
	min-width: " . $content_layout['banner_min_width'] . "px;
	height: " . $content_layout['banner_height'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, 'banner' ) . "
" . ptc_block_wrapper_css( 'banner', $content_layout ) . "
}

";

function ptc_menu_css( $content_layout, $number, $style ) {
	// Quick hack to cope with menu with ID of #nav (no number)
	if ( 1 == $number )
		$id = '';
	else
		$id = $number;




	// Setting some variables (to avoid WP_DEBUG errors)
	$vars_to_set = array(
		'menu' . $number . '_shadow_x_coordinate',
		'menu' . $number . '_shadow_y_coordinate',
		'menu' . $number . '_shadow_blur_radius',
		'menu' . $number . '_shadow_colour',
		'menu' . $number . '_shadow_opacity',
		'menu' . $number . '_bordertop_type',
		'menu' . $number . '_bordertop_width',
		'menu' . $number . '_bordertop_colour',
		'menu' . $number . '_borderbottom_type',
		'menu' . $number . '_borderbottom_width',
		'menu' . $number . '_borderbottom_colour',
		'menu' . $number . '_max_width',
	);
	foreach ( $vars_to_set as $var ) {			
		if ( empty( $content_layout[$var] ) )
			$content_layout[$var] = '';
	}



	$menu_item_height = ( 
		$content_layout['menu' . $number . '_padding_top'] + 
		$content_layout['menu' . $number . '_padding_bottom'] + 
		$content_layout['menu' . $number . '_line_height']
	);
	$menu_height = (
		$menu_item_height +
		$content_layout['menu' . $number . '_margin_top'] + 
		$content_layout['menu' . $number . '_margin_bottom']
	);
	$style .= "
/* Menu */
nav#nav" . $id . " {
	float: left;
	width: 100%;
	" . ptc_backgroundimage_creator( $content_layout, 'menu' . $number . '_fullwidth_background', 'top center' ) . "
}
nav#nav" . $id . " ul {
	" . ptc_backgroundimage_creator( $content_layout, 'menu' . $number . '_background', ' top center' ) . "
	max-width: " . $content_layout['menu' . $number . '_max_width'] . "px;
	min-width: " . $content_layout['menu' . $number . '_min_width'] . "px;
	height: " . $menu_height . "px;
	list-style: none;
	padding: 0;
" . ptc_block_wrapper_css( 'menu' . $number . '', $content_layout ) . "
}
* html nav#nav" . $id . " ul {
	width: " . ( $content_layout['menu' . $number . '_max_width'] + $content_layout['menu' . $number . '_min_width'] ) / 2 . "px;
}
nav#nav" . $id . " li {
	float: left;
	list-style: none;
	line-height: " . $content_layout['menu' . $number . '_line_height'] . "px;
	height: " . $menu_item_height . "px;
	font-family: " . $content_layout['menu' . $number . '_fontfamily'] . ";
	font-size: " . $content_layout['menu' . $number . '_fontsize'] . "px;
	color: " . $content_layout['menu' . $number . '_textcolour'] . ";
	font-weight: " . $content_layout['menu' . $number . '_font_weight'] . ";
	font-style: " . $content_layout['menu' . $number . '_font_style'] . ";
	text-transform: " . $content_layout['menu' . $number . '_text_transform'] . ";
	font-variant: " . $content_layout['menu' . $number . '_small_caps'] . ";
	text-shadow: " . $content_layout['menu' . $number . '_shadow_x_coordinate'] . "px " . $content_layout['menu' . $number . '_shadow_y_coordinate'] . "px " . $content_layout['menu' . $number . '_shadow_blur_radius'] . "px rgba(" . ptc_hex2RGB( $content_layout['menu' . $number . '_shadow_colour'] ) . ", " . $content_layout['menu' . $number . '_shadow_opacity'] . ");
	border-top: " . $content_layout['menu' . $number . '_bordertop_type'] . " " . $content_layout['menu' . $number . '_bordertop_width'] . "px " . $content_layout['menu' . $number . '_bordertop_colour'] . ";
	border-bottom: " . $content_layout['menu' . $number . '_borderbottom_type'] . " " . $content_layout['menu' . $number . '_borderbottom_width'] . "px " . $content_layout['menu' . $number . '_borderbottom_colour'] . ";
	margin: " . $content_layout['menu' . $number . '_margin_top'] . "px " . $content_layout['menu' . $number . '_margin_right'] . "px " . $content_layout['menu' . $number . '_margin_bottom'] . "px " . $content_layout['menu' . $number . '_margin_left'] . "px;
	padding: 0;
	" . ptc_backgroundimage_creator( $content_layout, 'menu' . $number . '_items_background', ' top right' ) . "
}";
	if ( $content_layout['menu' . $number . '_indent'] > 0 ) {
		$style .= "
nav#nav" . $id . " li:first-child {
	margin-left: " . $content_layout['menu' . $number . '_indent'] . "px;
}";
	}
	$style .= "
nav#nav" . $id . " li a {
	float: left;
	margin: 0;
	padding: " . $content_layout['menu' . $number . '_padding_top'] . 'px ' . $content_layout['menu' . $number . '_padding_right'] . 'px ' . $content_layout['menu' . $number . '_padding_bottom'] . 'px ' . $content_layout['menu' . $number . '_padding_left'] . "px;
	line-height: " . $content_layout['menu' . $number . '_line_height'] . "px;
	height: " . $content_layout['menu' . $number . '_line_height'] . "px;
	color: " . $content_layout['menu' . $number . '_textcolour'] . ";
	text-decoration: " . $content_layout['menu' . $number . '_textdecoration'] . ";
}
nav#nav" . $id . " li ul {
	display: none;
}
nav#nav" . $id . " li.current-menu-item a,
nav#nav" . $id . " li.current_page_item a,
nav#nav" . $id . " li:hover a {
	color: " . $content_layout['menu' . $number . '_hover_textcolour'] . ";
	font-weight: " . $content_layout['menu' . $number . '_hover_font_weight'] . ";
	font-style: " . $content_layout['menu' . $number . '_hover_font_style'] . ";
	text-decoration: " . $content_layout['menu' . $number . '_hover_textdecoration'] . ";
	" . ptc_backgroundimage_creator( $content_layout, 'menu' . $number . '_hover_background' ) . "
}
";
	return $style;
}

if ( isset( $content_layout['menu1_max_width'] ) ) {
	if ( is_numeric( $content_layout['menu1_max_width'] ) )
		$style = ptc_menu_css( $content_layout, '1', $style );
}
if ( isset( $content_layout['menu2_max_width'] ) ) {
	if ( is_numeric( $content_layout['menu2_max_width'] ) )
		$style = ptc_menu_css( $content_layout, '2', $style );
}

// Setting some variables (to avoid WP_DEBUG errors)
$vars_to_set = array(
	'maximum_width',
	'minimum_width',
	'paragraph_shadow_x_coordinate',
	'paragraph_shadow_y_coordinate',
	'paragraph_shadow_blur_radius',
	'paragraph_shadow_opacity',
	'changehome_top_columns',
	'pagination_shadow_x_coordinate',
	'pagination_shadow_y_coordinate',
	'pagination_shadow_blur_radius',
	'pagination_shadow_opacity',
);
foreach ( $vars_to_set as $var ) {			
	if ( empty( $content_layout[$var] ) )
		$content_layout[$var] = '';
}


$style .= "

/* Content */
.wrapper {
	margin: 0 auto;
	max-width: " . $content_layout['maincontent_maximum_width'] . "px;
	min-width: " . $content_layout['maincontent_minimum_width'] . "px;
}
* html .wrapper {
	width: " . ( $content_layout['maximum_width'] + $content_layout['minimum_width'] ) / 2 . "px; /* Gives IE6 a fixed width (since it doesn't support max-width or min-width) */
}
.wrapper #content {
	float: left;
	" . ptc_backgroundimage_creator( $content_layout, 'maincontent_background' ) . "
	background-repeat: " . $content_layout['maincontent_background_image_tiling'] . ";
}
#inner{
	clear: both;
	min-height: 0; /* IE7 */
	position: relative;
}
* html #inner{
	height: auto;
	overflow: visible;
}
aside {
	position: relative; /* IE7 and older need this to show float */
	overflow: hidden;
}
aside div.sidebar {
	" . ptc_backgroundimage_creator( $content_layout, 'sidebar_background' ) . "
	padding-right: " . $content_layout['aside_padding_right'] . "px;
	padding-left: " . $content_layout['aside_padding_left'] . "px;
	padding-top: " . $content_layout['aside_padding_top'] . "px;
}
aside div.widget {
	margin-bottom: " . $content_layout['aside_padding_bottom'] . "px;
}
.wrapper aside h3 {
" . ptc_text_styling( 'sidebar_heading', $content_layout ) . "
}
.wrapper aside ol,
.wrapper aside ul {
		margin: 0;
		padding: 0;
}
.wrapper aside ul li {
	list-style: square;
}
.wrapper aside li {
	list-style: square;
	margin-left: 20px !important;
" . ptc_text_styling( 'sidebar_list', $content_layout ) . "
}
.wrapper aside li a {
	color: " . $content_layout['sidebar_list_textcolour'] . ";
	font-weight: " . $content_layout['links_font_weight'] . ";
	font-style: " . $content_layout['links_font_style'] . ";
	text-decoration: " . $content_layout['links_textdecoration'] . ";
}
.wrapper aside,
.wrapper aside p {
" . ptc_text_styling( 'sidebar_paragraph', $content_layout ) . "
}

/* Extra information */
.wrapper .wp-caption,
.wrapper p.wp-caption-text {
	background: #eee;
	font-family: " . $content_layout['paragraph_fontfamily'] . ";
	font-size: " . 0.8 * $content_layout['paragraph_fontsize'] . "px;
	color: " . $content_layout['paragraph_textcolour'] . ";
	font-weight: " . $content_layout['paragraph_font_weight'] . ";
	font-style: " . $content_layout['paragraph_font_style'] . ";
	line-height: " . $content_layout['paragraph_line_height'] . "px;
	text-decoration: " . $content_layout['paragraph_textdecoration'] . ";
	text-transform: " . $content_layout['paragraph_text_transform'] . ";
	font-variant: " . $content_layout['paragraph_small_caps'] . ";
	text-shadow: " . $content_layout['paragraph_shadow_x_coordinate'] . "px " . $content_layout['paragraph_shadow_y_coordinate'] . "px " . $content_layout['paragraph_shadow_blur_radius'] . "px rgba(" . ptc_hex2RGB( $content_layout['paragraph_shadow_colour'] ) . ", " . $content_layout['paragraph_shadow_opacity'] . ");
	margin-bottom: 14px;
	padding: 5px 3px 10px;
	text-align: center;
	max-width: 96%;
}
.wp-caption img,
#maincontent .wp-caption img {
	margin: 2px 0 0 0;
	max-width: 98.5%;
	width: auto;
	height: auto;
}
.wp-caption .wp-caption-text {
	margin: 6px 0 0 0;
}

#aside-1 {
	width: " . $content_layout['aside_1_width'] . "px;
}
#aside-2 {
	width: " . $content_layout['aside_2_width'] . "px;
}

/* Main Content */
#maincontent {
	margin: 0;
	overflow:hidden;
	position: relative;
}
#maincontent .article-wrapper {
	margin: " . $content_layout['content_margin_top'] . "px " . $content_layout['content_margin_right'] . "px " . $content_layout['content_margin_bottom'] . "px " . $content_layout['content_margin_left'] . "px;
}
.wrapper p.post-info {
" . ptc_text_styling( 'postinfo', $content_layout ) . "
	display: " . $content_layout['postinfo_display'] . ";
}
";

if ( '' != $content_layout['changehome_top_columns'] ) :
	$style .= "
/* Magazine layout */
.magazine-top-post {
	overflow: hidden;
	float: left;
	width: ";
	if ( '' == $content_layout['changehome_top_columns'] )
		$content_layout['changehome_top_columns'] = 1;
	$style .= ( 100 / $content_layout['changehome_top_columns'] ) . "%;
	margin: 0;
	padding: 0;
}
.magazine-top-post div.post-inner {
	padding: " . $content_layout['changehome_box_paddings'] . ";
}
article.magazine-top-post div.post-outer {
	position: relative;
	height: " . $content_layout['changehome_box_height'] . "px;
	margin: " . $content_layout['changehome_box_margins'] . ";
	border: " . $content_layout['changehome_box_border_width'] . "px " . $content_layout['changehome_box_border_type'] . " " . $content_layout['changehome_box_border_colour'] . ";
}
.wrapper .magazine-top-post div.post-inner h2,
.wrapper .magazine-top-post div.post-inner h2 a {
" . ptc_text_styling( 'changehome_headings', $content_layout ) . "
}
.wrapper .magazine-top-post div.post-inner div.magazine-date {
" . ptc_text_styling( 'changehome_date', $content_layout ) . "
}
.wrapper .magazine-top-post div.post-inner p {
" . ptc_text_styling( 'changehome_posts', $content_layout ) . "
}
.wrapper .magazine-top-post div.post-outer div.magazine-post-info-outer {
	position: absolute;
	bottom: 0;
	width: 100%;
}
.wrapper .magazine-top-post div.post-outer div.magazine-post-info {
" . ptc_text_styling( 'changehome_postinfo', $content_layout ) . "
}
.wrapper .magazine-top-post a.post-thumbnail {
	float: right;
	border: 1px solid #ccc;
	padding: 3px;
	margin: 0 0 3px 3px;
}
.wrapper .magazine-top-post img {
	margin: 0 !important;
	padding: 0 !important;
	border: 1px solid red;
}
";
endif;

$style .= "
/* Pagination */
.wrapper #maincontent ul#numeric_pagination {
	float: left;
	width: 100%;
	margin: " . $content_layout['pagination_vertical_margin'] . "px 0 " . $content_layout['pagination_vertical_margin'] . "px 0;
}
.wrapper #maincontent ul#numeric_pagination li {
	float: left;
	list-style: none;
	font-family: " . $content_layout['pagination_fontfamily'] . ";
	font-size: " . $content_layout['pagination_fontsize'] . "px;
	color: " . $content_layout['pagination_textcolour'] . ";
	font-weight: " . $content_layout['pagination_font_weight'] . ";
	font-style: " . $content_layout['pagination_font_style'] . ";
	text-decoration: " . $content_layout['pagination_textdecoration'] . ";
	text-transform: " . $content_layout['pagination_text_transform'] . ";
	font-variant: " . $content_layout['pagination_small_caps'] . ";
	text-shadow: " . $content_layout['pagination_shadow_x_coordinate'] . "px " . $content_layout['pagination_shadow_y_coordinate'] . "px " . $content_layout['pagination_shadow_blur_radius'] . "px rgba(" . ptc_hex2RGB( $content_layout['pagination_shadow_colour'] ) . ", " . $content_layout['pagination_shadow_opacity'] . ");
	" . ptc_backgroundimage_creator( $content_layout, 'pagination_background_colour', '', '', '' ) . "
	padding: 0;
	text-align: center;
}
ul#numeric_pagination li a {
	float: left;
	height: " . $content_layout['pagination_fontsize'] . "px;
	line-height: " . $content_layout['pagination_fontsize'] . "px;
	margin: 0 " . $content_layout['pagination_horizontal_margin'] . "px 0 " . $content_layout['pagination_horizontal_margin'] . "px;
	padding: " . $content_layout['pagination_padding'] . 'px ' . $content_layout['pagination_padding'] . 'px ' . $content_layout['pagination_padding'] . 'px ' . $content_layout['pagination_padding'] . "px;
	color: " . $content_layout['pagination_textcolour'] . ";
	border: " . $content_layout['pagination_border_type'] . " " . $content_layout['pagination_border_width'] . "px " . $content_layout['pagination_border_colour'] . ";
}
ul#numeric_pagination li a:hover {
	";
 /*
	NAME DOESN"T WORK	
	ptc_backgroundimage_creator( $content_layout, 'pagination_background_hovercolour', '', '', '' ) . 
*/
$style .= "
	text-decoration: none;
	color: " . $content_layout['pagination_texthovercolour'] . ";
}

/* Comments */
#respond textarea {
	width: 98%;
}
#respond textarea,
#respond input {
	border: 1px solid #888;
	outline: none;
}
#respond input[type=text] {
	float: left;
	line-height: 25px;
	margin: 0 10px 0 0;
}
#respond p.comment-form-comment label {
	display: none;
}

/* Footer */
footer {
	width: 100%;
	float: left;
	overflow: auto;";
if ( 'block' == $content_layout['footer_fullwidth_background_display'] ) {

	$style .= "
	" . ptc_backgroundimage_creator( $content_layout, 'footer_fullwidth_background', ' top center' );
}
$style .= "
}
footer div.footer {
	overflow: hidden;
	position: relative;
	display: block;
	max-width: " . $content_layout['footer_max_width'] . "px;
	min-width: " . $content_layout['footer_min_width'] . "px;
	height: " . $content_layout['footer_height'] . "px;
	" . ptc_backgroundimage_creator( $content_layout, 'footer_background', ' top center' ) . "
" . ptc_block_wrapper_css( 'footer', $content_layout ) . "
}
footer p {
	text-align: " . $content_layout['footer_copyright_position_centered'] . ";
	" . ptc_text_styling( 'footer_copyright', $content_layout ) . "
	margin-left: " . $content_layout['footer_copyright_horizontalposition'] . "px;
	margin-top: " . $content_layout['footer_copyright_verticalposition'] . "px;
	width: 100%;
	display: " . $content_layout['footer_copyright_display'] . ";
}
footer a {
	color: " . $content_layout['links_textcolour'] . ";
	font-weight: " . $content_layout['links_font_weight'] . ";
	font-style: " . $content_layout['links_font_style'] . ";
	text-decoration: " . $content_layout['links_textdecoration'] . ";
}
footer a:hover {
	color: " . $content_layout['links_hover_textcolour'] . ";
	font-weight: " . $content_layout['links_hover_font_weight'] . ";
	font-style: " . $content_layout['links_hover_font_style'] . ";
	text-decoration: " . $content_layout['links_hover_textdecoration'] . ";
}
footer nav {";
if ( 'center' != $content_layout['footer_menu_alignment'] ) {
	$style .= "
	position: absolute;
	text-align: " . $content_layout['footer_menu_alignment'] . ";
	top: 0;";
}
else {
	$style .= "
	left: 0;";
}
$style .= "
	margin-top: " . $content_layout['footer_menu_verticalposition'] . "px;
	width: 100%;
	display: " . $content_layout['footer_menu_display'] . ";
}
footer nav ul {
";
if ( 'center' != $content_layout['footer_menu_alignment'] ) {
	$style .= "
	text-align: " . $content_layout['footer_menu_alignment'] . ";";
}
else {
	$style .= "
	text-align: center;";
}
$style .= "
	margin-" . $content_layout['footer_menu_alignment'] . ": " . $content_layout['footer_menu_horizontalposition'] . "px;
}
footer nav li {
	display: inline;
	margin: 0 " . $content_layout['footer_menu_gap'] . ";
}
footer nav li a {
" . ptc_text_styling( 'footer_menu', $content_layout ) . "
}
footer nav li ul {
	display: none;
}

/* Post contents */
.alignleft {
	float: left;
	margin: 0 10px 10px 0;
}
.aligncenter {
	display: block;
	margin: 0 auto;
}
.alignright {
	float: right;
	margin: 0 0 10px 10px;
}
";

/* Headings */
$heading = 1;
while( $heading < 7 ) {
	$style .= "
/* Heading " . $heading . " */
.wrapper h" . $heading . " {
" . ptc_text_styling( 'heading' . $heading, $content_layout ) . "
}
.wrapper h" . $heading . " a {
" . ptc_text_styling( 'heading' . $heading, $content_layout ) . "
}
.wrapper h" . $heading . " a:hover {
" . ptc_text_styling( 'heading' . $heading, $content_layout ) . "
}
";
	$heading++;
}

$style .= "
.wrapper p {
" . ptc_text_styling( 'paragraph', $content_layout ) . "
}
.wrapper #maincontent ul,
.wrapper #maincontent ol {
	margin: 0 0 0 35px;
	padding: 0;
}
.wrapper #maincontent li {
	margin: 0;
	padding: 0;
}
.wrapper #maincontent ul li {
	list-style: square;
}
.wrapper #maincontent ol li {
	list-style: decimal;
}
.wrapper #maincontent li {
" . ptc_text_styling( 'paragraph', $content_layout ) . "
}
.wrapper #maincontent li {
	line-height: auto;
	margin: 0;
	padding: 10px 0;
}
.wrapper blockquote {
" . ptc_text_styling( 'paragraph', $content_layout ) . "
}
.wrapper a {
	color: " . $content_layout['links_textcolour'] . ";
	font-weight: " . $content_layout['links_font_weight'] . ";
	font-style: " . $content_layout['links_font_style'] . ";
	text-decoration: " . $content_layout['links_textdecoration'] . ";
}
.wrapper a:hover {
	color: " . $content_layout['links_hover_textcolour'] . ";
	font-weight: " . $content_layout['links_hover_font_weight'] . ";
	font-style: " . $content_layout['links_hover_font_style'] . ";
	text-decoration: " . $content_layout['links_hover_textdecoration'] . ";
}

";

/* sidebar1-content-sidebar2 layout */
if ( $content_layout['asides'] == 'sidebar1-content-sidebar2' ) {
	$style .= "
#inner{
	margin: 0 " . $content_layout['aside_2_width'] . "px 0 " . $content_layout['aside_1_width'] . "px;
}
#aside-1 {
	float: left;
	margin: 0 0 0 -" . $content_layout['aside_1_width'] . "px;
}
#aside-2 {
	float: right;
	margin: 0 -" . $content_layout['aside_2_width'] . "px 0 0;
}
";
}

/* sidebar2-content-sidebar1 layout */
if ( $content_layout['asides'] == 'sidebar2-content-sidebar1' ) {
	$style .= "
#inner{
	margin: 0 " . $content_layout['aside_1_width'] . "px 0 " . $content_layout['aside_2_width'] . "px;
}
#aside-1 {
	float: right;
	margin: 0 -" . $content_layout['aside_1_width'] . "px 0 0;
}
#aside-2 {
	float: left;
	margin: 0 0 0 -" . $content_layout['aside_2_width'] . "px;
}
";
}

/* sidebar1-content layout */
if ( $content_layout['asides'] == 'sidebar1-content' ) {
	$style .= "
#aside-1 {
	float: left;
	margin: 0 0 0 -" . $content_layout['aside_1_width'] . "px;
}
#aside-2 {
	display: none;
}
#inner{
	margin: 0 0 0 " . $content_layout['aside_1_width'] . "px;
}
";
}

/* sidebar2-content layout */
if ( $content_layout['asides'] == 'sidebar2-content' ) {
	$style .= "
#aside-2 {
	float: left;
	margin: 0 0 0 -" . $content_layout['aside_2_width'] . "px;
}
#aside-1 {
	display: none;
}
#inner{
	margin: 0 0 0 " . $content_layout['aside_2_width'] . "px;
}
";
}

/* content-sidebar2 layout */
if ( $content_layout['asides'] == 'content-sidebar2' ) {
	$style .= "
#aside-1 {
	display: none;
}
#aside-2 {
	float: right;
	margin: 0 -" . $content_layout['aside_2_width'] . "px 0 0;
}
#inner{
	margin: 0 " . $content_layout['aside_2_width'] . "px 0 0;
	margin-left: 0;
}
";
}

/* content-sidebar1 layout */
if ( $content_layout['asides'] == 'content-sidebar1' ) {
	$style .= "
#aside-2 {
	display: none;
}
#aside-1 {
	float: right;
	margin: 0 -" . $content_layout['aside_1_width'] . "px 0 0;
}
#inner{
	margin: 0 " . $content_layout['aside_1_width'] . "px 0 0;
}
";
}

/* content layout */
if ( $content_layout['asides'] == 'content' ) {
	$style .= "
#aside-1,
#aside-2 {
	display: none;
}
#inner{
}
";
}

/* content-sidebar1-sidebar2 layout */
if ( $content_layout['asides'] == 'content-sidebar1-sidebar2' ) {
	$style .= "
#aside-1 {
	float: right;
	margin: 0 -" . $content_layout['aside_1_width'] . "px 0 0;
}
#aside-2 {
	float: right;
	margin: 0 -" . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px 0 0;
}
#inner{
	margin: 0 " . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px 0 0;
}
";
}

/* content-sidebar2-sidebar1 layout */
if ( $content_layout['asides'] == 'content-sidebar2-sidebar1' ) {
	$style .= "
#aside-1 {
	float: right;
	margin: 0 -" . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px 0 0;
}
#aside-2 {
	float: right;
	margin: 0 -" . $content_layout['aside_2_width'] . "px 0 0;
}
#inner{
	margin: 0 " . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px 0 0;
}
";
}

/* sidebar2-sidebar1-content layout */
if ( $content_layout['asides'] == 'sidebar2-sidebar1-content' ) {
	$style .= "
#aside-1 {
	float: left;
	margin: 0 0 0 -" . $content_layout['aside_1_width'] . "px;
}
#aside-2 {
	float: left;
	margin:  0 0 0-" . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px;
}
#inner{
	margin: 0 0 0 " . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px;
}
";
}

/* sidebar1-sidebar2-content layout */
if ( $content_layout['asides'] == 'sidebar1-sidebar2-content' ) {
	$style .= "
#aside-1 {
	float: left;
	margin: 0 0 0 -" . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px;
}
#aside-2 {
	float: left;
	margin: 0 0 0 -" . $content_layout['aside_2_width'] . "px;
}
#inner{
	margin: 0 0 0 " . ( $content_layout['aside_1_width'] + $content_layout['aside_2_width'] ) . "px;
}
";
}



/* Adding custom CSS */
if ( isset( $content_layout['add_custom_css'] ) ) {
	$style .= "

/* Custom CSS */
	" . $content_layout['add_custom_css'];
}

/* Adding attribution to CSS code */
$style .= "

/* CSS provided by WP Paintbrush CSS generator */";


ob_end_clean();
