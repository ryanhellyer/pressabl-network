<?php
/*

Plugin Name: Spam Destroyer Tracking
Plugin URI: http://geek.ryanhellyer.net/
Description: Stats tracking for the Spam Destroyer
Author: Ryan Hellyer
Version: 1.0
Author URI: http://geek.ryanhellyer.net/

Copyright (c) 2013 Ryan Hellyer


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
license.txt file included with this plugin for more information.

*/




// TODO: Store the plugins list in a more sane way


/**
 * Tracking of users
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Spam_Destroyer_API {
	public $post_meta;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->post_meta = array(
			'previous_spam'  => 'Previous spam',
			'previous_ham'   => 'Previous ham',
			'previous_false' => 'Previous false',
			'previous_start' => 'Previous start',
			'previous_end'   => 'Previous end',
			'total_spam'     => 'Total spam',
			'total_ham'      => 'Total ham',
			'total_false'    => 'Total false',
			'total_start'    => 'Total start',
			'plugins'        => 'Plugin list',
		);
		
		add_action( 'init',           array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'admin_init',     array( $this, 'meta_boxes_save' ) );

		// Check for a couple of potential post variables before processing the domain
		if ( isset( $_POST['domain'] ) && isset( $_POST['total_spam'] ) ) {
			add_action( 'init', array( $this, 'process_domain' ) );
		}
	}

	/**
	 * Add admin metabox for thumbnail chooser
	 */
	public function add_metabox() {
		add_meta_box(
			'stats', // ID
			'Stats', // Title
			array(
				$this,
				'meta_box', // Callback to method to display HTML
			),
			'spam-stats', // Post type
			'normal', // Context, choose between 'normal', 'advanced', or 'side'
			'core'  // Position, choose between 'high', 'core', 'default' or 'low'
		);
	}
	
	/**
	 * Output the thumbnail meta box
	 */
	public function meta_box() {
		global $post;
		
		// 
		if ( isset( $_GET['post'] ) )
			$post_ID = (int) $_GET['post'];
		else
			$post_ID = '';

		foreach( $this->post_meta as $key => $value ) {
			if (
				'previous_start' == $key ||
				'previous_end' == $key ||
				'total_start' == $key ||
				'total_end' == $key
			) {
				$time_value = get_post_meta( $post_ID, '_' . $key, true );
				$time = date( 'h:m d M Y', $time_value );
			} else {
				$time = '';
			}

			echo '
			<p>
				<label for="_' . $key . '">' . $value . '</label>
				<br />
				<input type="text" name="_' . $key . '" id="_' . $key . '" value="' . get_post_meta( $post_ID, '_' . $key, true ) . '" />
				<br />
				<small>' . $time . '</small>
			</p>';
			unset( $time );
		}
	}
	
	/**
	 * Save opening times meta box data
	 */
	function meta_boxes_save() {
		
		// Only process if the form has actually been submitted
		if (
			isset( $_POST['_wpnonce'] ) &&
			isset( $_POST['post_ID'] )
		) {
			
			// Do nonce security check
			wp_verify_nonce( '_wpnonce', $_POST['_wpnonce'] );
			
			// Grab post ID
			$post_ID = (int) $_POST['post_ID'];
			
			// Iterate through each possible piece of meta data
			foreach( $this->post_meta as $key => $value ) {
				if ( isset( $_POST['_' . $key] ) ) {
					$data = esc_html( $_POST['_' . $key] ); // Sanitise data input
					update_post_meta( $post_ID, '_' . $key, $data ); // Store the data
				}
			}
			
		}
		
	}

	/*
	 * Process the domain
	 */
	public function process_domain() {
		$url = esc_url( $_POST['domain'] );
		$post = array(
			'post_title'  => $url,
			'post_status' => 'publish',
			'post_type'   => 'spam-stats',
		);
		
		$spam_post = get_page_by_title( $url, '', 'spam-stats' );
		if ( isset( $spam_post->ID ) ) {
			$post_id = $spam_post->ID;
		} else {
			$post_id = wp_insert_post( $post );
		}

		if ( isset( $_POST['previous_spam'] ) ) {
			$previous_spam  = (int) $_POST['previous_spam'];
			update_post_meta( $post_id, '_previous_spam', $previous_spam );
		}
		if ( isset( $_POST['previous_ham'] ) ) {
			$previous_ham   = (int) $_POST['previous_ham'];
			update_post_meta( $post_id, '_previous_ham', $previous_ham );
		}
		if ( isset( $_POST['previous_false'] ) ) {
			$previous_false = (int) $_POST['previous_false'];
			update_post_meta( $post_id, '_previous_false', $previous_false );
		}
		if ( isset( $_POST['previous_start'] ) ) {
			$previous_start = (int) $_POST['previous_start'];
			update_post_meta( $post_id, '_previous_start', $previous_start );
		}
		if ( isset( $_POST['previous_end'] ) ) {
			$previous_end   = (int) $_POST['previous_end'];
			update_post_meta( $post_id, '_previous_end', $previous_end );
		}
		if ( isset( $_POST['total_spam'] ) ) {
			$total_spam     = (int) $_POST['total_spam'];
			update_post_meta( $post_id, '_total_spam', $total_spam );
		}
		if ( isset( $_POST['total_ham'] ) ) {
			$total_ham      = (int) $_POST['total_ham'];
			update_post_meta( $post_id, '_total_ham', $total_ham );
		}
		if ( isset( $_POST['total_false'] ) ) {
			$total_false    = (int) $_POST['total_false'];
			update_post_meta( $post_id, '_total_false', $total_false );
		}
		if ( isset( $_POST['total_start'] ) ) {
			$total_start    = (int) $_POST['total_start'];
			update_post_meta( $post_id, '_total_start', $total_start );
		}
		if ( isset( $_POST['plugins'] ) ) {
			$plugins        = esc_html( $_POST['plugins'] );
			update_post_meta( $post_id, '_plugins', $plugins );
		}
		if ( isset( $_POST['ver'] ) ) {
			$version        = esc_html( $_POST['ver'] );
			update_post_meta( $post_id, '_ver', $version );
		}

		wp_die( 'Thanks for storing with us :)' );
	}
	
	/**
	 * Add post type
	 */
	public function register_post_type() {
		$args = array(
			'public'               => false,
			'labels'        => array(
				'name'          => 'Spam Stats',
				'singular_name' => 'Spam Stat',
			),
			'show_ui'              => true,
			'menu_position'        => 3,
			'supports'             => array(
				'title',
			),
		);
		register_post_type( 'spam-stats', $args );

	}

}
new Spam_Destroyer_API;
