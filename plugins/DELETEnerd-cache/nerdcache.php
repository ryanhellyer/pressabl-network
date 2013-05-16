<?php
/*
Plugin name: Nerd Cache
Plugin URI: http://pixopoint.com/products/nerd-cache/
Description: Advanced caching plugin for WordPress. Intended only for power users.
Author: Ryan Hellyer
Author URI: http://ryanhellyer.net/
Version: 1.0

Based heavily on nananananananananananananananana BATCACHE!!! by Andy Skelton / Automattic

*/



/**
 * Do not load if advanced-cache.php isn't loaded yet
 *
 * @todo Create admin panel notice
 * @since 1.0
 * @author Ryan Hellyer <ryan@metronet.no>
 */
if ( isset( $nerdcache ) ) {
	if ( ! is_object( $nerdcache ) || ! method_exists( $wp_object_cache, 'incr' ) ) {
		die( 'Oops! Something is wrong with your object cache. nerd-cache is not operational.' );
	}
}
else {
//	die( 'You need to move advanced-cache.php from the nerd-cache folder, into your wp-content folder before the nerd-cache plugin is operational' );
}
if ( ! defined( 'WP_CACHE' ) ) {
	die( 'You forgot to define "WP_CACHE" in your wp-config.php file!' );
}


/**
 * Main Nerd Cache plugin class
 * Handles cache flushing
 * 
 * @copyright Copyright (c), PixoPoint
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryan@pixopoint.com>
 * @since 1.0
 */
class Nerd_Cache {

	/**
	 * Class constructor
	 * 
	 * @author Ryan Hellyer <ryan@pixopoint.com>
	 * @since 1.0
	 */
	public function __construct( $args = array() ) {
		global $nerdcache;
		$nerdcache->configure_groups();
		add_action( 'clean_post_cache', 'regenerate_post' ); // Regenerate home and permalink on posts and pages
	}

	/**
	 * Regenerate the posts
	 * Used for flushing the cache
	 * 
	 * @author Ryan Hellyer <ryan@pixopoint.com>
	 * @since 1.0
	 */
	public function regenerate_post( $post_id ) {
		global $nerdcache;

		$post = get_post( $post_id );
		if ( $post->post_type == 'revision' || get_post_status( $post_id ) != 'publish' )
			return;
	
		$this->clear_url( get_option( 'home' ) );
		$this->clear_url( trailingslashit( get_option( 'home' ) ) );
		$this->clear_url( get_permalink( $post_id ) );
	}

	/**
	 * Flushes the cache for a single URL
	 * 
	 * @author Ryan Hellyer <ryan@pixopoint.com>
	 * @since 1.0
	 */
	public function clear_url( $url ) {
		global $nerdcache;
		if ( empty( $url ) )
			return false;
		$url_key = md5( $url );
		wp_cache_add(
			"{$url_key}_version",
			0,
			$nerdcache->group
		);
		return wp_cache_incr("{$url_key}_version", 1, $nerdcache->group);
	}

}
new Nerd_Cache();
