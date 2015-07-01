<?php
/**
 * Compi Admin.
 *
 * @since    1.0.0
 * @author wpdots
 * @category Admin
 * @package  Compi
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Compi_Admin' ) ) {

class Compi_Admin {

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		// Actions
		add_action( 'init',              array( $this, 'includes' ) );
		add_action( 'admin_init',        array( $this, 'prevent_admin_access' ) );
		add_action( 'current_screen',    array( $this, 'tour' ) );
		add_action( 'current_screen',    array( $this, 'conditional_includes' ) );
		add_action( 'admin_footer', 'compi_print_js', 25 );
		// Filters
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
		add_filter( 'update_footer',     array( $this, 'update_footer' ), 15 );
	} // END __construct()

	/**
	 * Include any classes we need within admin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @filter compi_enable_admin_help_tab
	 */
	public function includes() {
		// Functions
		include( 'compi-admin-functions.php' );

		// Use this action to register custom post types, user roles and anything else
		do_action( 'compi_admin_include' );

		// Classes we only need if the ajax is not-ajax
		if ( ! is_ajax() ) {
			// Transifex Stats
			include( 'class-compi-transifex-api.php' );
			include( 'class-compi-transifex-stats.php' );

			// Main Plugin
			include( 'class-compi-admin-menus.php' );
			include( 'class-compi-admin-welcome.php' );
			include( 'class-compi-admin-notices.php' );

			// Plugin Help
			if ( apply_filters( 'compi_enable_admin_help_tab', true ) ) {
				include( 'class-compi-admin-help.php' );
			}
		}
	} // END includes()

	/**
	 * This includes Compi tour.
	 *
	 * @since  1.0.*
	 * @access public
	 */
	public function tour() {
		// Plugin Tour
		$ignore_tour = get_option( 'compi_ignore_tour' );

		if ( !isset( $ignore_tour ) || !$ignore_tour ) {
			//include( 'class-compi-admin-pointers.php' );
		}
	} // END tour()

	/**
	 * Include admin files conditionally.
	 *

	 *         specifically for other WordPress pages.
	 * @since  1.0.0
	 * @access public
	 */
	public function conditional_includes() {
		$screen = get_current_screen();

		switch ( $screen->id ) {
			case 'dashboard' :
				// Include a file to load only for the dashboard.
			break;
			case 'users' :
			case 'user' :
			case 'profile' :
			case 'user-edit' :
				// Include a file to load only for the user pages.
			break;
		}
	} // END conditional_includes()

	/**
	 * Prevent any user who cannot 'edit_posts'
	 * (subscribers etc) from accessing admin.
	 *

	 *         page you want to redirect the user to.
	 * @since  1.0.0
	 * @access public
	 * @filter compi_prevent_admin_access
	 */
	public function prevent_admin_access() {
		$prevent_access = false;

		if ( 'yes' == get_option( 'compi_lock_down_admin' ) && ! is_ajax() && ! ( current_user_can( 'edit_posts' ) || current_user_can( Compi()->manage_plugin ) ) && basename( $_SERVER["SCRIPT_FILENAME"] ) !== 'admin-post.php' ) {
			$prevent_access = true;
		}

		$prevent_access = apply_filters( 'compi_prevent_admin_access', $prevent_access );

		if ( $prevent_access ) {
			wp_safe_redirect( get_permalink( compi_get_page_id( 'page-slug' ) ) );
			exit;
		}
	} // END prevent_admin_access()

	/**
	 * Filters the admin footer text by placing links
	 * for Compi including a simply thank you to
	 * review Compi on WordPress.org.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  $text
	 * @filter compi_admin_footer_review_text
	 * @return string
	 */
	public function admin_footer_text( $text ) {
		$screen = get_current_screen();

		if ( in_array( $screen->id, compi_get_screen_ids() ) ) {

			$links = apply_filters( 'compi_admin_footer_text_links', array(
				Compi()->web_url . '?utm_source=wpadmin&utm_campaign=footer' => __( 'Website', COMPI_TEXT_DOMAIN ),
				Compi()->doc_url . '?utm_source=wpadmin&utm_campaign=footer' => __( 'Documentation', COMPI_TEXT_DOMAIN ),
			) );

			$text    = '';
			$counter = 0;

			foreach ( $links as $key => $value ) {
				$text .= '<a target="_blank" href="' . $key . '">' . $value . '</a>';

				if( count( $links ) > 1 && count( $links ) != $counter ) {
					$text .= ' | ';
					$counter++;
				}
			}

			// Rating and Review added since 1.0.2
			if ( apply_filters( 'compi_admin_footer_review_text', true ) ) {
				$text .= sprintf( __( 'If you like <strong>%1$s</strong> please leave a <a href="%2$s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating on <a href="%2$s" target="_blank">WordPress.org</a>. A huge thank you in advance!', COMPI_TEXT_DOMAIN ), Compi()->name, Compi()->wp_plugin_review_url );
			}

			return $text;
		}

		return $text;
	} // END admin_footer_text()

	/**
	 * Filters the update footer by placing details
	 * of Compi and links to contribute or
	 * report issues with Compi when viewing any
	 * of Compi pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  $text
	 * @filter compi_update_footer_links
	 * @return string $text
	 */
	public function update_footer( $text ) {
		$screen = get_current_screen();

		if ( in_array( $screen->id, compi_get_screen_ids() ) ) {
			$version_link = esc_attr( admin_url( 'index.php?page=' . COMPI_PAGE . '-about' ) );

			$text = '<span class="wrap">';

			$links = apply_filters( 'compi_update_footer_links', array(
				COMPI_GITHUB_REPO_URI . 'blob/master/CONTRIBUTING.md?utm_source=wpadmin&utm_campaign=footer' => __( 'Contribute', COMPI_TEXT_DOMAIN ),
				COMPI_GITHUB_REPO_URI . 'issues?state=open&utm_source=wpadmin&utm_campaign=footer' => __( 'Report Bugs', COMPI_TEXT_DOMAIN ),
				COMPI_TRANSIFEX_PROJECT_URI . '?utm_source=wpadmin&utm_campaign=footer' => __( 'Translate', COMPI_TEXT_DOMAIN ),
			) );

			foreach( $links as $key => $value ) {
				$text .= '<a target="_blank" class="add-new-h2" href="' . $key . '">' . $value . '</a>';
			}

			$text .= '</span>' . '</p>'.
			'<p class="alignright">'.
			sprintf( __( '%s Version', COMPI_TEXT_DOMAIN ), Compi()->name ).
			' : <a href="' . $version_link . '">'.
			esc_attr( Compi()->version ).
			'</a>';

			return $text;
		}

		return $text;
	} // END update_footer()

} // END class

} // END if class exists

return new Compi_Admin();
?>
