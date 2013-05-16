<?php

add_action('admin_init', 'dp_dashboard_admin_setup' );
add_action('admin_menu', 'dp_dashboard_admin_page');

/**
 * @since 0.1.0
 */
function dp_dashboard_admin_setup() {

	/**
	 * Register settings for 'DP Dashboard' screen under 'Settings' page.
	 */
	register_setting(
		'dp_dashboard_settings',
		'dp_dashboard',
		'dp_dashboard_settings_validate'
	);

}

/**
 * @since 0.1.0
 */
function dp_dashboard_admin_page() {

	// Add DP Dashboard menu page under Settings
	add_options_page( __( 'DP Dashboard Settings', 'dp-dashboard'), __( 'DP Dashboard', 'dp-dashboard') , 'manage_options', 'dp_dashboard_settings', 'dp_dashboard_settings_display' );

}

/**
 * @since 0.1.0
 */
function dp_dashboard_settings_display() {

do_action( "dp_dashboard_before_settings_page" ); ?>

<div class="wrap dp-wrap dp-dashboard-wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( 'DP Dashboard Settings', 'dp-dashboard' ); ?></h2>

	<?php do_action( "dp_dashboard_open_settings_page" ); ?>

	<form method="post" action="options.php">

		<?php settings_fields('dp_dashboard_settings'); ?>
		
		<?php

			$options = wp_parse_args( get_option( 'dp_dashboard' ), dp_dashboard_default_settings() );
			
		?>
		
		<div class="clear"></div>

		<table class="form-table">
			
			<!-- Admin bar -->
			<tr>
				<th><label for="dp-dashboard-adminbar"><?php _e( 'Admin Bar:', 'dp-dashboard' ); ?></label></th>
				<td>
					<input class="checkbox" type="checkbox" value="1" <?php checked( '1', $options['adminbar'], true ); ?> id="dp-dashboard-adminbar" name="dp_dashboard[adminbar]" />
					<label for="dp-dashboard-adminbar"><?php _e( 'Use custom admin bar everywhere', 'dp-dashboard' ); ?></label>
					
					<p class="description"><?php echo __( 'By default, the custom admin bar is used on WordPress dashboard related pages only.', 'dp-dashboard' ); ?></p>
				</td>
			</tr>
			
			<!-- Custom CSS -->
			<tr>
				<th><label for="dp-dashboard-custom-css"><?php _e( 'Custom CSS:', 'dp-dashboard' ); ?></label></th>
				<td>
					<input class="checkbox" type="checkbox" value="1" <?php checked( '1', $options['custom_css'], true ); ?> id="dp-dashboard-custom-css" name="dp_dashboard[custom_css]" />
					<label for="dp-dashboard-custom-css"><?php _e( 'Activate custom CSS', 'dp-dashboard' ); ?></label>
					
					<p class="description"><?php _e( 'Your custom CSS file is available at <code>/dp-dashboard/css/custom.css</code>, add your custom CSS there and mark this option to activate your custom CSS file.', 'dp-dashboard' ); ?></p>
				</td>
			</tr>
			
			<!-- Theme -->
			<tr>
				<th>
					<label><?php _e( 'Theme:', 'dp-dashboard' ); ?></label>
				</th>
				<td>
					<ul>
						<li>
							<label><input type="radio" name="dp_dashboard[theme]" value="default" <?php checked( 'default', $options['theme'] ); ?>> <?php echo __( 'Default', 'dp-dashboard'  ) ?></label>
						</li>
					</ul>
					<p class="description"><?php _e( 'Sorry, there\'s only one theme at the moment.', 'dp-dashboard' ); ?></p>

				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					<input type="submit" class="button-primary" value="<?php _e( 'Update Settings', 'dp-dashboard' ) ?>" />
				</td>
			</tr>
		
		</table><!-- .form-table -->

	</form>
	
	<p><?php _e( 'DevPress Dashboard is updated several times per year to add new themes and to stay up to date with the current WordPress version. This plugin is not hosted on the offical plugins repository, therefore, you will not get automatic updates. For updates, check <a href="http://devpress.com/plugins/dp-dashboard">devpress.com/plugins/dp-dashboard</a>', 'dp-dashboard' ); ?></p>

</div><!-- .wrap -->

<?php }

/**
 * @since 0.1.0
 */
function dp_dashboard_settings_validate( $input ) {

	$input['adminbar'] = ( isset($input['adminbar']) == 1 ? 1 : 0 );
	$input['custom_css'] = ( isset($input['custom_css']) == 1 ? 1 : 0 );
	$input['theme'] = strip_tags( $input['theme'] );

	return $input;
}

?>