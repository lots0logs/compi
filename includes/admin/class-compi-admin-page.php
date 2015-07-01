<?php
/**
 * This outputs the admin pages for Compi.
 *
 * @since    1.0.0
 * @author wpdots
 * @category Admin
 * @package  Compi
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Compi_Admin_Page' ) ) {

	/**
	 * Compi_Admin_Page Class
	 *
	 * @since 1.0.0
	 */
	class Compi_Admin_Page {

		/**
		 * Handles output of Compi page in admin.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public static function output() {
			$view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : '';

			if ( false === ( $page_content = get_transient( 'compi_html_' . $view ) ) ) {
				$page_content = do_action('compi_html_content_' . $view);

				if ( $page_content ) {
					set_transient( 'compi_html_' . $view, wp_kses_post( $page_content ), 60*60*24*7 ); // Cached for a week.
				}
			}

			include_once( 'views/html-admin-page.php' );
		} // END output()

	} // END Compi_Admin_Page() class.

} // END if class exists.

return new Compi_Admin_Page();

?>
