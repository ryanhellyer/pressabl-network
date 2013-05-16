<?php
/*

Plugin Name: Cloudfront Demo
Plugin URI: http://pixopoint.com/
Description: Demo to show how Cloudfront can be used to pull whole pages
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
 * Cloudfront Demo
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryan@pixopoint.com>
 * @since 1.9
 */
class Cloudfront_Demo {

	/**
	 * Class constructor
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@pixopoint.com>
	 */
	public function __construct() {

		// Bail out now if in admin panel or on login page
		if ( is_admin() || strstr( $_SERVER['REQUEST_URI'], 'wp-login.php' ) )
			return;

/*
		if ( isset($_COOKIE['wordpress_test_cookie'])) {

			header( "Location: http://cloudfront.ryanhellyer.net/comments/" );
			// wp_redirect( "Location: http://cloudfront.ryanhellyer.net/comments/", 302 );
			exit;

		}
*/

		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
	}

	/**
	 * Begins output buffering
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@pixopoint.com>
	 */
	public function template_redirect() {
		ob_start( array( $this, 'ob' ) );
	}

	/**
	 * Callback for output buffer
	 * Filters URLs
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@pixopoint.com>
	 * @return string
	 */
	public function ob( $content ) {

		// Rewrite uploads URLs
		$content = str_replace( 'http://clouddemo.ryanhellyer.net', 'http://cloudfront.ryanhellyer.net', $content );
		$content = str_replace( 'http://pressabl.hellyer.net.nz/clouddemo', 'http://cloudfront.ryanhellyer.net', $content );
		$content = str_replace( 'http://cloudfront.ryanhellyer.net/wp-content/themes/twentyeleven', 'http://prsb.co/wp-content/themes/twentyeleven', $content );
		$content = str_replace( 'http://prsb.co/wp-content/themes/twentyeleven/style.css', 'http://prsb.co/wp-content/themes/twentyeleven/style4.css', $content );

		// Send comment form to proper domain
		$content = str_replace(
			'<form action="http://cloudfront.ryanhellyer.net/wp-comments-post.php" method="post" id="commentform">',
			'<form action="http://clouddemo.ryanhellyer.net/wp-comments-post.php" method="post" id="commentform">',
			$content
		);

		$remove = "var _gaq = _gaq || [];";
		$content = str_replace( $remove, '', $content );
		$remove = "_gaq.push(['_setAccount', 'UA-2481610-5']);";
		$content = str_replace( $remove, '', $content );
		$remove = "_gaq.push(['_setDomainName', 'none']);";
		$content = str_replace( $remove, '', $content );
		$remove = "_gaq.push(['_trackPageview']);";
		$content = str_replace( $remove, '', $content );
		$remove = "(function() {";
		$content = str_replace( $remove, '', $content );
		$remove = "var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;";
		$content = str_replace( $remove, '', $content );
		$remove = "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
		$content = str_replace( $remove, '', $content );
		$remove = "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);";
		$content = str_replace( $remove, '', $content );
		$remove = "})();";
		$content = str_replace( $remove, '', $content );
//		$content = $remove . $content;


		return $content;
	}

}

new Cloudfront_Demo;

