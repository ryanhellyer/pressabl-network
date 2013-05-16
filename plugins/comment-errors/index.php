<?php
/*
Plugin Name: Comment Errors
Plugin URI: http://geek.ryanhellyer.net/products/comment-errors/
Description: The <a href="http://geek.ryanhellyer.net/products/comment-errors/">Comment Errors Plugin</a> prevents your site visitors from ever seeing the ugly default error page in WordPress and instead redirects them back to where they were attempting to post to, and adds an appropriate error message to the comments section.

Author: Ryan Hellyer / Metronet
Version: 1.1
Author URI: http://metronet.no/

Copyright 2012 Metronet

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/


function comments_error_admin_notice() {
	if ( ! isset( $_GET['s'] ) )
		return;

	// Shows as an error message. You could add a link to the right page if you wanted.
	echo( '
		<style>
		#comment-errors {
			background: #ffebe8;
			border: 1px solid #c00;
		}
		</style>
		<div id="message" class="error">
			<h3>Comment errors</h3>
			<p>
				Sorry, but the Comment Errors plugin has a bug which is
				preventing it from doing it\'s job. You should deactivate
				it to avoid it causing problems with your comments area.
			</p>
		</div>' );
}
add_action( 'admin_notices', 'comments_error_admin_notice' );

function turn_comment_errors_off() {
	if ( ! is_admin() )
		return;

	if ( ! isset( $_GET['s'] ) ) {
		if ( false === ( $transient = get_transient( 'comment_errors_transient' ) ) ) {
//				deactivate_plugins( __FILE__ );
			wp_redirect( admin_url( '/plugins.php?s=comment-errors' ), 302 );
			set_transient( 'comment_errors_transient', $transient, ( 60 * 24 * 7 ) );
			exit;
		}
	}

}
add_action( 'admin_init', 'turn_comment_errors_off' );
