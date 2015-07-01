<?php
/**
 * Runs on Uninstall of [Compi]
 *
 * This file will remove any options and tables you
 * have created for this plugin.
 *
 * @since     1.0.0
 * @author wpdots
 * @category 	Core
 * @package 	Compi
 * @license   GPL-2.0+
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

global $wpdb, $wp_roles;

// For a single site
if ( ! is_multisite() ) {

	$status_options = get_option( 'compi_status_options', array() );

	if ( ! empty( $status_options['uninstall_data'] ) ) {

		// Delete options
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'compi_%';");

		// Roles + caps
		$installer = include( 'includes/admin/class-compi-install.php' );
		$installer->remove_roles();

		// Pages
		$get_pages = $installer->compi_pages();
		foreach( $get_pages as $key => $page ) {
			wp_trash_post( get_option( 'compi_' . $key . '_page_id' ) );
		}

		/*

		 */

	}

}
// For a multisite network
else {
	$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );

		/*

		 */
		delete_site_option( 'option_name' );
	}

	switch_to_blog( $original_blog_id ); // Return to original blog.
}
?>
