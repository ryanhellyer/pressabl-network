<?php
/*

Plugin Name: Pull CDN Pages
Plugin URI: http://geek.ryanhellyer.net/
Description: Allows you to use a pull CDN to power your pages
Author: Ryan Hellyer
Version: 2.0
Author URI: http://geek.ryanhellyer.net/

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
 * Pull CDN Setup
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 2.0
 */
class Pull_CDN {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_action( 'admin_redirect',    array( $this, 'template_redirect' ) );
	}

	/**
	 * Begins output buffering
	 */
	public function template_redirect() {
		ob_start( array( $this, 'ob' ) );
	}

	/**
	 * Callback for output buffer
	 * Filters URLs
	 * 
	 * @param string $content
	 * @return string
	 */
	public function ob( $content ) {

		$content = $this->filter_content_urls( $content );
		$content = $this->filter_includes_urls( $content );
		$content = $this->filter_uploads_urls( $content );

		return $content;
	}

	/**
	 * Filter for uploads URLs
	 * Replaces original URLs with CDN URLs
	 * Maps blogs.dir structure for multisite
	 * 
	 * @param string $content
	 * @return string
	 */
	private function filter_uploads_urls( $content ) {

		// Grab current uploads URL
		$upload_dir = wp_upload_dir();
		$upload_url = $upload_dir['baseurl'] . '/';

		// Set uploads folder
		if ( defined( 'PULL_CDN_UPLOADS' ) ) {
			$new_uploads_url = PULL_CDN_UPLOADS;
		}
		$new_uploads_url = PULL_CDN_DOMAIN . '/';

		// Map upload file structure to that of blogs.dir for multisite
		if ( is_multisite() ) {
			global $blog_id; // Blog ID for multi-site
			$new_uploads_url .= $blog_id . '/files/';

			// Catering for mapped multi-site domains
			$mapped_uploads_url = str_replace( site_url(), get_blog_option( $blog_id, 'siteurl' ), $upload_url );
			$content = str_replace( $mapped_uploads_url, $new_uploads_url, $content );

			
			$content = str_replace( home_url() . '/files/', $new_uploads_url, $content );
		}
		// Rewrite uploads URLs
		$content = str_replace( $upload_url, $new_uploads_url, $content );

		return $content;
	}

	/**
	 * Content URL filter
	 *
	 * @param string $content
	 * @return string
	 */
	private function filter_content_urls( $content ) {

		if ( defined( 'PULL_CDN_WP_CONTENT' ) ) {
			$content = str_replace( content_url() . '/', PULL_CDN_WP_CONTENT, $content );

			$wp_content = str_replace( get_blog_option( $blog_id, 'siteurl' ), site_url(), WP_CONTENT_URL ) . '/';
			$content = str_replace( $wp_content . '/', PULL_CDN_WP_CONTENT, $content );

			// Catering for mapped multi-site domains
			$wp_content = home_url( 'wp-content/' );
			$content = str_replace( $wp_content, PULL_CDN_WP_CONTENT, $content );
			
		}

		return $content;
	}

	/**
	 * Includes URL filter
	 * 
	 * @param string $content
	 * @return string
	 */
	private function filter_includes_urls( $content ) {

		if ( defined( 'PULL_CDN_WP_INCLUDES' ) ) {
			$content = str_replace( includes_url(), PULL_CDN_WP_INCLUDES, $content );
		}

		return $content;
	}

}

new Pull_CDN;

