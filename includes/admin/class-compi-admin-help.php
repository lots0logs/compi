<?php
/**
 * Help is provided for this plugin on Compi pages.
 *
 * @since    1.0.0
 * @author wpdots
 * @category Admin
 * @package  Compi
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Compi_Admin_Help' ) ) {

/**
 * Class - Compi_Admin_Help
 *
 * @since 1.0.0
 */
class Compi_Admin_Help {

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'current_screen', array( $this, 'add_help_tabs' ), 50 );
	} // END __construct()

	/**
	 * Adds help tabs to Compi pages.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_help_tabs() {
		$screen = get_current_screen();

		if ( ! in_array( $screen->id, compi_get_screen_ids() ) )
			return;

		$screen->add_help_tab( array(
			'id'      => 'compi_docs_tab',
			'title'   => __( 'Documentation', COMPI_TEXT_DOMAIN ),
			'content' =>
				'<p>' . sprintf( __( 'Thank you for using <strong>%s</strong> :) Should you need help using or extending %s please read the documentation.', COMPI_TEXT_DOMAIN ), Compi()->name, Compi()->name ) . '</p>' .

				'<p><a href="' . Compi()->doc_url . '" class="button button-primary">' . sprintf( __( '%s Documentation', COMPI_TEXT_DOMAIN ), Compi()->name ) . '</a> <!--a href="#" class="button">' . __( 'Restart Tour', COMPI_TEXT_DOMAIN ) . '</a--></p>'
		) );

		$screen->add_help_tab( array(
			'id'      => 'compi_support_tab',
			'title'   => __( 'Support', COMPI_TEXT_DOMAIN ),
			'content' =>
				'<p>' . sprintf( __( 'After <a href="%s">reading the documentation</a>, for further assistance you can use the <a href="%s">community forum</a>.', COMPI_TEXT_DOMAIN ), Compi()->doc_url, Compi()->wp_plugin_support_url, __( 'Company Name' , COMPI_TEXT_DOMAIN ) ) . '</p>' .

				'<p>' . __( 'Before asking for help we recommend checking the status page to identify any problems with your configuration.', COMPI_TEXT_DOMAIN ) . '</p>' .

				'<p><a href="' . admin_url( 'admin.php?page=' . COMPI_PAGE . '-status' ) . '" class="button button-primary">' . __( 'System Status', COMPI_TEXT_DOMAIN ) . '</a> <a href="' . Compi()->wp_plugin_support_url . '" class="button">' . __( 'Community Support', COMPI_TEXT_DOMAIN ) . '</a>'
		) );

		$screen->add_help_tab( array(
			'id'      => 'compi_bugs_tab',
			'title'   => __( 'Found a bug?', COMPI_TEXT_DOMAIN ),
			'content' =>
				'<p>' . sprintf( __( 'If you find a bug within <strong>%s</strong> you can create a ticket via <a href="%s">Github issues</a>. Ensure you read the <a href="%s">contribution guide</a> prior to submitting your report. Be as descriptive as possible and please include your <a href="%s">system status report</a>.', COMPI_TEXT_DOMAIN ), Compi()->name, COMPI_GITHUB_REPO_URI . 'issues?state=open', COMPI_GITHUB_REPO_URI . 'blob/master/CONTRIBUTING.md', admin_url( 'admin.php?page=' . COMPI_PAGE . '-status' ) ) . '</p>' .

				'<p><a href="' . COMPI_GITHUB_REPO_URI . 'issues?state=open" class="button button-primary">' . __( 'Report a bug', COMPI_TEXT_DOMAIN ) . '</a> <a href="' . admin_url( 'admin.php?page=' . COMPI_PAGE . '-status' ) . '" class="button">' . __( 'System Status', COMPI_TEXT_DOMAIN ) . '</a></p>'
		) );

		if ( !empty( Compi()->web_url ) || !empty( Compi()->wp_plugin_url ) || defined( COMPI_GITHUB_REPO_URI ) ) {
			$screen->set_help_sidebar(
				'<p><strong>' . __( 'For more information:', COMPI_TEXT_DOMAIN ) . '</strong></p>' .
				'<p><a href="' . Compi()->web_url . '" target="_blank">' . sprintf( __( 'About %s', COMPI_TEXT_DOMAIN ), Compi()->name ) . '</a></p>' .
				'<p><a href="' . Compi()->wp_plugin_url . '" target="_blank">' . __( 'Project on WordPress.org', COMPI_TEXT_DOMAIN ) . '</a></p>' .
				'<p><a href="' . COMPI_GITHUB_REPO_URI . '" target="_blank">' . __( 'Project on Github', COMPI_TEXT_DOMAIN ) . '</a></p>'
			);
		}

	} // END add_help_tabs()

} // END Compi_Admin_Help class.

} // END if class exists.

return new Compi_Admin_Help();

?>
