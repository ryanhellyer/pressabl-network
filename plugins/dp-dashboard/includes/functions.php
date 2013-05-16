<?php

/* Load scripts for admin pages */
add_action( 'admin_enqueue_scripts', 'dp_dashboard_scripts' );

/* Load "dp-dashboard" stylesheet at "admin_footer" instead of "admin_enqueue_scripts" due some WordPress stylesheets loaded mid-page, in which "!important" exists. */
add_action( 'admin_footer', 'dp_dashboard_css' );

/* Load login css */
add_action( 'login_enqueue_scripts', 'dp_dashboard_login_css' );

/* Filter Visual Editor CSS */
add_filter( 'mce_css', 'dp_dashboard_editor_css' );

/* Load additional styles for popular plugins. Use "admin_enqueue_scripts" instead of "admin_footer" because they're all dependent on the initial "dp-dashboard" stylesheet, which already loads at "admin_footer". */
add_action( 'admin_enqueue_scripts', 'dp_dashboard_plugin_css' );

/* Use custom admin bar on frontend pages if admin bar setting is true */
add_action( 'wp_enqueue_scripts', 'dp_dashboard_enqueue_admin_bar' );
add_action( 'wp_head', 'dp_dashboard_admin_bar_bump', 11 ); // Priority of 11 to execute after '_admin_bar_bump_cb'

/**
 * Enqueue scripts.
 *
 * @since 0.1
 */
function dp_dashboard_scripts() {

	/* Check plugin settings */
	$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );

	/* Register script based on selected theme */
	wp_register_script( 'dp-dashboard-scripts', plugins_url( '/js/' . $options['theme'] . '/scripts.js', dirname( __FILE__ ) ), array( 'jquery' ), false, true );
	
	/* Load register script(s) */
	wp_enqueue_script( 'dp-dashboard-scripts' );

}

/**
 * Enqueue styles.
 *
 * @since 0.1
 */
function dp_dashboard_css() {

	/* Check plugin settings */
	$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );
	
	/* Register Font Awesome */
	wp_register_style( 'dp-dashboard-font-awesome', plugins_url( '/css/font-awesome.css', dirname( __FILE__ ) ), array( 'colors' ) );
	
	/* Register main stylesheet based on plugin settings */
	wp_register_style( 'dp-dashboard', plugins_url( '/css/' . $options['theme'] . '/style.css', dirname( __FILE__ ) ), array( 'colors' ) );
	
	/* Load registered stylesheets */
	wp_enqueue_style( 'dp-dashboard-font-awesome' );
	wp_enqueue_style( 'dp-dashboard' );
	
	/* Set admin-bar.css file path under selected theme directory */
	$admin_bar_file = DP_DASHBOARD_DIR . 'css/' . $options['theme'] . '/admin-bar.css';
	
	/* if selected dashboard theme has admin-bar.css file then register and load it */
	if( file_exists( $admin_bar_file ) ) {
	
		/* Register admin bar stylesheet based on plugin settings */
		wp_register_style( 'dp-dashboard-admin-bar', plugins_url( '/css/' . $options['theme'] . '/admin-bar.css', dirname( __FILE__ ) ), array( 'admin-bar' ) );
		
		wp_enqueue_style( 'dp-dashboard-admin-bar' );
	
	}
	
	/* Load Custom CSS if custom css is true in admin settings */
	if ( $options['custom_css'] ) {
	
		wp_register_style( 'dp-dashboard-custom', plugins_url( '/css/custom.css', dirname( __FILE__ ) ), array( 'dp-dashboard' ) );
		
		wp_enqueue_style( 'dp-dashboard-custom' );
	
	}
	
}

/**
 * Login CSS
 * 
 * @since 0.1.0
 */
function dp_dashboard_login_css( ) {

	/* Check plugin settings */
	$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );

	/* Register main stylesheet based on plugin settings */
	wp_register_style( 'dp-dashboard', plugins_url( '/css/' . $options['theme'] . '/style.css', dirname( __FILE__ ) ), array( 'colors-fresh' ) );
	
	/* Load registered stylesheets */
	wp_enqueue_style( 'dp-dashboard' );

}

/**
 * Add Editor Style
 * 
 * @since 0.1.0
 */
function dp_dashboard_editor_css( $mce_css ) {

	/* Check plugin settings */
	$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );
	
	/* Set editor-style.css file path under selected theme directory */
	$editor_style_file = DP_DASHBOARD_DIR . 'css/' . $options['theme'] . '/editor-style.css';
	
	/* If active theme doesn't support editor-style and selected dashboard theme has editor-style.css */
	if( !current_theme_supports( 'editor-style' ) && file_exists( $editor_style_file ) ) {

		$mce_css .= ', ' . plugins_url( '/css/' . $options['theme'] . '/editor-style.css', dirname( __FILE__ ) );
		
	}

    return $mce_css;
}

/**
 * Load additional styles for popular plugins
 *
 * @since 0.1.0
 */
function dp_dashboard_plugin_css( $hook_suffix ) {

	/* Check plugin settings */
	$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );

	/* if selected theme is 'default' */
	if ( $options['theme'] == 'default' ) {

		/* Enqueue Gravity Forms Stylesheet */
		if (
			$hook_suffix == 'toplevel_page_gf_edit_forms' ||
			$hook_suffix == 'forms_page_gf_new_form' ||
			$hook_suffix == 'forms_page_gf_entries' ||
			$hook_suffix == 'forms_page_gf_settings' ||
			$hook_suffix == 'forms_page_gf_export' ||
			$hook_suffix == 'forms_page_gf_update' ||
			$hook_suffix == 'forms_page_gf_addons' ||
			$hook_suffix == 'forms_page_gf_help' ) {

			wp_register_style( 'dp-dashboard-gravity-forms', plugins_url( '/css/' . $options['theme'] . '/gravity-forms.css', dirname( __FILE__ ) ), array( 'dp-dashboard' ) );

			wp_enqueue_style( 'dp-dashboard-gravity-forms' );
		}
		
		/* Enqueue Theme Check Stylesheet */
		if ( $hook_suffix == 'appearance_page_themecheck' ) {

			wp_register_style( 'dp-dashboard-theme-check', plugins_url( '/css/' . $options['theme'] . '/theme-check.css', dirname( __FILE__ ) ), array( 'dp-dashboard' ) );
			
			wp_enqueue_style( 'dp-dashboard-theme-check' );
		}

	}

}

/**
 * Enqueue admin bar on frontend if admin bar is used globally
 *
 * @since 0.1
 */
function dp_dashboard_enqueue_admin_bar() {

	/* Check plugin settings */
	$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );
	
	/* Set admin-bar.css file path under selected theme directory */
	$admin_bar_file = DP_DASHBOARD_DIR . 'css/' . $options['theme'] . '/admin-bar.css';

	if ( is_admin_bar_showing() && $options['adminbar'] && file_exists( $admin_bar_file ) ) {
	
		/* Register Font Awesome */
		wp_register_style( 'dp-dashboard-font-awesome', plugins_url( '/css/font-awesome.css', dirname( __FILE__ ) ), false );

		/* Register admin-bar.css file of the chosen theme */
		wp_register_style( 'dp-dashboard-admin-bar', plugins_url( '/css/' . $options['theme'] . '/admin-bar.css', dirname( __FILE__ ) ), array( 'admin-bar' ) );
		
		/* Enqueue style(s) */
		wp_enqueue_style( 'dp-dashboard-font-awesome' );
		wp_enqueue_style( 'dp-dashboard-admin-bar' );

	}
	
}

/**
 * Load custom admin bar adjustment CSS
 *
 * @since 0.1
 */
function dp_dashboard_admin_bar_bump() {

	/* Check plugin settings */
	$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );

	/* If admin bar is showing and custom admin bar is used globally then load custom CSS */
	if ( is_admin_bar_showing() && $options['adminbar'] ) :
	
		if ( $options['theme'] == 'default' ) : ?>

		<style type="text/css" media="screen">
			/* DP Dashboard admin bar adjustment */
			html { margin-top: 48px !important; }
			* html body { margin-top: 48px !important; }
		</style>

		<?php endif;

	endif;
	
}

?>