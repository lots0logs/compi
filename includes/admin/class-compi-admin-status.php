<?php
/**
 * Debug Compi / Status page
 *
 * @since    1.0.0
 * @author wpdots
 * @category Admin
 * @package  Compi
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Compi_Admin_Status' ) ) {

/**
 * Compi_Admin_Status Class
 *
 * @since 1.0.0
 */
class Compi_Admin_Status {

	/**
	 * Handles output of the reports page in admin.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function output() {
		include_once( 'views/html-admin-page-status.php' );
	} // END output()

	/**
	 * Handles output of report.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function status_report() {
		include_once( 'views/html-admin-page-status-report.php' );
	} // END status_report()

	/**
	 * Handles output of import / export
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function status_port( $port ) {
		//global $compi, $wpdb;

		include_once( 'views/html-admin-page-status-import-export.php' );
	} // END status_port()

	/**
	 * Handles output of tools.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function status_tools() {
		$tools = self::get_tools();

		if ( ! empty( $_GET['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'debug_action' ) ) {

			include( 'class-compi-install.php' );
			$installer = new Compi_Install();

			switch ( $_GET['action'] ) {

				case "install_pages" :
					// Install any missing pages Compi requires.
					$installer->create_pages();
					echo '<div class="updated compi-message"><p>' . sprintf( __( 'All missing %s pages was installed successfully.', COMPI_TEXT_DOMAIN ), Compi()->name ) . '</p></div>';
				break;

				case "reset_roles" :
					// Remove then re-add caps and roles.
					$installer->remove_user_roles();
					$installer->create_user_roles();

					echo '<div class="updated compi-message"><p>' . __( 'Roles successfully reset', COMPI_TEXT_DOMAIN ) . '</p></div>';
				break;

				case "restart" :
					// Remove capabilities and user roles, then delete plugin options.
					$installer->remove_user_roles();
					$installer->delete_options();

					do_action( 'compi_system_restart' );

					// Install default plugin options and then re-add capabilities and user roles.
					$installer->create_options();
					$installer->create_user_roles();

					echo '<div class="updated compi-message"><p>' . __( 'All previous data has been removed and re-installed the defaults.', COMPI_TEXT_DOMAIN ) . '</p></div>';
				break;

				default:
					$action = esc_attr( $_GET['action'] );
					if ( isset( $tools[ $action ]['callback'] ) ) {
						$callback = $tools[ $action ]['callback'];
						$return = call_user_func( $callback );

						if ( $return === false ) {
							if ( is_array( $callback ) ) {
								echo '<div class="error"><p>' . sprintf( __( 'There was an error calling %s::%s', COMPI_TEXT_DOMAIN ), get_class( $callback[0] ), $callback[1] ) . '</p></div>';
							} else {
								echo '<div class="error"><p>' . sprintf( __( 'There was an error calling %s', COMPI_TEXT_DOMAIN ), $callback ) . '</p></div>';
							}
						}
					}
				break;
			}
		}

		// Display message if settings settings have been saved
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			echo '<div class="updated"><p>' . __( 'Your changes have been saved.', COMPI_TEXT_DOMAIN ) . '</p></div>';
		}

		include_once( 'views/html-admin-page-status-tools.php' );
	} // END status_tools()

	/**
	 * Get all the tools Compi supports for administrators.
	 *
	 * @since  1.0.0
	 * @access public
	 * @filter compi_debug_tools
	 * @return Array
	 */
	public static function get_tools() {
		return apply_filters( 'compi_debug_tools', array(

			'install_pages' => array(
				'name'   => sprintf( __( 'Install %s Pages', COMPI_TEXT_DOMAIN ), Compi()->name ),
				'button' => __( 'Install pages', COMPI_TEXT_DOMAIN ),
				'desc'   => sprintf( __( '<strong class="red">Note:</strong> This tool will install all the missing %s pages. Pages already defined and set up will not be replaced.', COMPI_TEXT_DOMAIN ), Compi()->name ),
			),

			'reset_roles' => array(
				'name'   => __( 'Capabilities', COMPI_TEXT_DOMAIN ),
				'button' => __( 'Reset capabilities', COMPI_TEXT_DOMAIN ),
				'desc'   => sprintf( __( 'This tool will reset the admin roles to default. Use this if your users cannot access all of the %s admin pages.', COMPI_TEXT_DOMAIN ), Compi()->name ),
			),

			'restart' => array(
				'name'   => __( 'Start Over', COMPI_TEXT_DOMAIN ),
				'button' => __( 'Restart', COMPI_TEXT_DOMAIN ),
				'desc'   => sprintf( __( 'This tool will erase all settings, database tables (if any) and re-install "<em>%s</em>". Use this if you are really sure. All current data will be lost and will not be recoverable.', COMPI_TEXT_DOMAIN ), Compi()->name ),
			),

		) );
	} // END get_tools()

} // END class

} // END if class exists

return new Compi_Admin_Status();

?>
