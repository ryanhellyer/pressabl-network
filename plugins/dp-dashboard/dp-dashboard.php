<?php
/**
 * Plugin Name: DP Dashboard
 * Plugin URI: http://devpress.com/plugins/dp-dashboard/
 * Description: A plugin for customizing WordPress admin pages.
 * Version: 0.1
 * Author: Tung Do
 * Author URI: http://devpress.com
 *
 * @package   dp-dashboard
 * @version   0.1.0
 * @author    Tung Do <tung@devpress.com>
 * @copyright Copyright (c) 2013, Tung Do
 * @link      http://devpress.com
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class DP_Dashboard {

	/**
	 * PHP5 constructor method.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 3 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 4 );

	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 0.1.0
	 */
	public function constants() {

		/* Set constant path to the plugin directory. */
		define( 'DP_DASHBOARD_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the admin directory. */
		define( 'DP_DASHBOARD_ADMIN', DP_DASHBOARD_DIR . trailingslashit( 'admin' ) );
		
		/* Set the constant path to the includes directory. */
		define( 'DP_DASHBOARD_INCLUDES', DP_DASHBOARD_DIR . trailingslashit( 'includes' ) );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since 0.1.0
	 */
	public function i18n() {

		/* Load the translation of the plugin. */
		if( is_admin() ) {
			load_plugin_textdomain( 'dp-dashboard', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since 0.1.0
	 */
	public function admin() {

		/* Only load files if in the WordPress admin. */
		if ( is_admin() ) {
			require_once( DP_DASHBOARD_ADMIN . 'admin.php' );
		}
	}
	
	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since 0.1.0
	 */
	public function includes() {

		require_once( DP_DASHBOARD_INCLUDES . 'functions.php' );
		require_once( DP_DASHBOARD_INCLUDES . 'defaults.php' );

	}

}

new DP_Dashboard();

?>