<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */
?>
<div id="sidebar" role="complementary"><?php

	if ( ! dynamic_sidebar( 'sidebar' ) ) { ?>

	<aside>
		<p><?php _e( 'Please add widgets to the sidebar.', 'hellish' ); ?></p>
	</aside><?php
	}
	?>

</div><!-- #sidebar -->
